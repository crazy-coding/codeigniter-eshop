<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promote_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        //check auth
        if (!auth_check()) {
            redirect(lang_base_url());
        }
    }

    /**
     * Pricing
     */
    public function pricing($product_id)
    {
        if ($this->promoted_products_enabled != 1) {
            redirect(lang_base_url());
        }

        $data["product"] = $this->product_model->get_product_by_id($product_id);
        if (empty($data["product"])) {
            redirect(lang_base_url());
        }
        //check product user
        if ($data["product"]->user_id != user()->id) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("promote_your_product");
        $data['description'] = trans("promote_your_product") . " - " . $this->app_name;
        $data['keywords'] = trans("promote_your_product") . "," . $this->app_name;
        $data["type"] = $this->input->get('type');
        if ($data["type"] != "new" && $data["type"] != "exist") {
            redirect($this->agent->referrer());
        } else {
            $this->session->set_userdata('mds_promote_product_type', $data["type"]);
        }

        $completed = $this->input->get('completed');
        if ($completed == 1) {
            $this->cart_model->unset_sess_cart_payment_method();
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('promote/pricing', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Pricing Post
     */
    public function pricing_post()
    {
        $plan_type = $this->input->post('plan_type', true);
        $price_per_day = price_format_decimal($this->payment_settings->price_per_day);
        $price_per_month = price_format_decimal($this->payment_settings->price_per_month);

        $day_count = $this->input->post('day_count', true);
        $month_count = $this->input->post('month_count', true);
        $total_amount = 0;
        if ($plan_type == "daily") {
            $total_amount = number_format($day_count * $price_per_day, 2, ".", "") * 100;
            $purchased_plan = trans("daily_plan") . " (" . $day_count . " " . trans("days") . ")";
        }
        if ($plan_type == "monthly") {
            $day_count = $month_count * 30;
            $total_amount = number_format($month_count * $price_per_month, 2, ".", "") * 100;
            $purchased_plan = trans("monthly_plan") . " (" . $day_count . " " . trans("days") . ")";
        }
        $data = new stdClass();
        $data->plan_type = $this->input->post('plan_type', true);
        $data->product_id = $this->input->post('product_id', true);
        $data->day_count = $day_count;
        $data->month_count = $month_count;
        $data->total_amount = $total_amount;
        $data->purchased_plan = $purchased_plan;

        $this->session->set_userdata('modesy_selected_promoted_plan', $data);
        redirect(lang_base_url() . "promote-product/payment-method");
    }

    /**
     * Payment Method
     */
    public function payment_method()
    {
        if ($this->promoted_products_enabled != 1) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("promote_your_product");
        $data['description'] = trans("promote_your_product") . " - " . $this->app_name;
        $data['keywords'] = trans("promote_your_product") . "," . $this->app_name;
        $data['promoted_plan'] = $this->session->userdata('modesy_selected_promoted_plan');
        if (empty($data['promoted_plan'])) {
            redirect(lang_base_url());
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('promote/payment_method', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Payment Method Post
     */
    public function payment_method_post()
    {
        $this->cart_model->set_sess_cart_payment_method();
        $transaction_number = 'bank-' . generate_transaction_number();
        $this->session->set_userdata('mds_promote_bank_transaction_number', $transaction_number);
        redirect(lang_base_url() . "promote-product/payment");
    }

    /**
     * Payment
     */
    public function payment()
    {
        $data['title'] = trans("promote_your_product");
        $data['description'] = trans("promote_your_product") . " - " . $this->app_name;
        $data['keywords'] = trans("promote_your_product") . "," . $this->app_name;
        $data['promoted_plan'] = $this->session->userdata('modesy_selected_promoted_plan');
        if (empty($data['promoted_plan'])) {
            redirect(lang_base_url());
        }
        $data['cart_payment_method'] = $this->cart_model->get_sess_cart_payment_method();
        if (empty($data['cart_payment_method'])) {
            redirect($this->agent->referrer());
        }
        $data['transaction_number'] = $this->session->userdata('mds_promote_bank_transaction_number');

        $this->load->view('partials/_header', $data);
        $this->load->view('promote/payment', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Payment with Paypal
     */
    public function paypal_payment_post()
    {
        $promoted_plan = $this->session->userdata('modesy_selected_promoted_plan');
        if (!empty($promoted_plan)) {
            //execute payment
            $this->promote_model->execute_promote_payment_paypal($promoted_plan);
            //add to promoted products
            $this->promote_model->add_to_promoted_products($promoted_plan);

            //reset cache
            reset_cache_data_on_change();
            reset_user_cache_data(user()->id);

            $data = array(
                'result' => 1,
                'redirect' => lang_base_url() . "promote-product/completed?method=paypal&product_id=" . $promoted_plan->product_id
            );
            echo json_encode($data);
        } else {
            $this->session->set_flashdata('error', trans("msg_payment_database_error"));
            $data = array(
                'status' => 0,
                'redirect' => lang_base_url() . "promote-product/pricing/" . $promoted_plan->product_id
            );
            echo json_encode($data);
        }
    }


    /**
     * Stripe with Paypal
     */
    public function stripe_payment_post()
    {
        $promoted_plan = $this->session->userdata('modesy_selected_promoted_plan');
        require_once(APPPATH . 'third_party/stripe/vendor/autoload.php');
        try {
            $token = $this->input->post('payment_id', true);
            $email = $this->input->post('email', true);
            $payment_amount = $this->input->post('payment_amount', true);
            $currency = $this->input->post('currency', true);
            //Init stripe
            $stripe = array(
                "secret_key" => $this->payment_settings->stripe_secret_key,
                "publishable_key" => $this->payment_settings->stripe_publishable_key,
            );
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            //customer
            $customer = \Stripe\Customer::create(array(
                'email' => $email,
                'source' => $token
            ));
            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => $payment_amount,
                'currency' => $currency,
                'description' => @$promoted_plan->purchased_plan
            ));

            //add payment to database
            if (!empty($promoted_plan)) {
                //execute payment
                $this->promote_model->execute_promote_payment_stripe($promoted_plan);
                //add to promoted products
                $this->promote_model->add_to_promoted_products($promoted_plan);

                $this->session->set_flashdata('success', trans("msg_payment_success"));

                //reset cache
                reset_cache_data_on_change();
                reset_user_cache_data(user()->id);

                $data = array(
                    'result' => 1,
                    'redirect' => lang_base_url() . "promote-product/completed?method=stripe&product_id=" . $promoted_plan->product_id
                );
                echo json_encode($data);
            } else {
                $this->session->set_flashdata('error', trans("msg_payment_database_error"));
                $data = array(
                    'status' => 0,
                    'redirect' => lang_base_url() . "promote-product/pricing/" . $promoted_plan->product_id
                );
                echo json_encode($data);
            }
            //add payment to database end


        } catch (\Stripe\Error\Base $e) {
            $this->session->set_flashdata('error', trans("msg_error"));
            $data = array(
                'status' => 0,
                'redirect' => lang_base_url() . "promote-product/pricing/" . $promoted_plan->product_id
            );
            echo json_encode($data);
        } catch (Exception $e) {
            $this->session->set_flashdata('error', trans("msg_error"));
            $data = array(
                'status' => 0,
                'redirect' => lang_base_url() . "promote-product/pricing/" . $promoted_plan->product_id
            );
            echo json_encode($data);
        }
    }


    /**
     * Payment with Stripe
     */
    public function stripe_payment_post1()
    {
        $promoted_plan = $this->session->userdata('modesy_selected_promoted_plan');
        if (!empty($promoted_plan)) {
            //execute payment
            $this->promote_model->execute_promote_payment_stripe($promoted_plan);
            //add to promoted products
            $this->promote_model->add_to_promoted_products($promoted_plan);

            $this->session->set_flashdata('success', trans("msg_payment_success"));

            //reset cache
            reset_cache_data_on_change();
            reset_user_cache_data(user()->id);

            $data = array(
                'result' => 1,
                'redirect' => lang_base_url() . "promote-product/completed?method=stripe&product_id=" . $promoted_plan->product_id
            );
            echo json_encode($data);
        } else {
            $this->session->set_flashdata('error', trans("msg_payment_database_error"));
            $data = array(
                'status' => 0,
                'redirect' => lang_base_url() . "promote-product/pricing/" . $promoted_plan->product_id
            );
            echo json_encode($data);
        }
    }

    /**
     * Payment with Iyzico
     */
    public function iyzico_payment_post()
    {
        $token = $this->input->post('token', true);
        $lang_base_url = $this->input->get('lang_base_url', true);

        $options = $this->initialize_iyzico();
        $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId("123456");
        $request->setToken($token);
        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $options);

        if ($checkoutForm->getPaymentStatus() == "SUCCESS") {
            $currency = $checkoutForm->getCurrency();
            $price = $checkoutForm->getPrice();
            $promoted_plan = $this->session->userdata('modesy_selected_promoted_plan');
            if (!empty($promoted_plan)) {
                //execute payment
                $this->promote_model->execute_promote_payment_iyzico($promoted_plan, $token, $currency, $price);
                //add to promoted products
                $this->promote_model->add_to_promoted_products($promoted_plan);

                //reset cache
                reset_cache_data_on_change();
                reset_user_cache_data(user()->id);

                redirect($lang_base_url . "promote-product/completed?method=iyzico&product_id=" . $promoted_plan->product_id);
            }
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($lang_base_url . "/promote-product/payment");
        }
    }

    /**
     * Payment with Bank Transfer
     */
    public function bank_transfer_payment_post()
    {
        $promoted_plan = $this->session->userdata('modesy_selected_promoted_plan');
        if (!empty($promoted_plan)) {
            //execute payment
            $this->promote_model->execute_promote_payment_bank($promoted_plan);

            $type = $this->session->userdata('mds_promote_product_type');

            if (empty($type)) {
                $type = "new";
            }
            $transaction_number = $this->session->userdata('mds_promote_bank_transaction_number');
            redirect(lang_base_url() . "promote-product/completed?method=bank_transfer&transaction_number=" . $transaction_number . "&product_id=" . $promoted_plan->product_id);
        }
        $this->session->set_flashdata('error', trans("msg_error"));
        redirect(lang_base_url() . "/promote-product/payment");
    }

    /**
     * Completed
     */
    public function completed()
    {
        $data['title'] = trans("msg_payment_completed");
        $data['description'] = trans("msg_payment_completed") . " - " . $this->app_name;
        $data['keywords'] = trans("payment") . "," . $this->app_name;
        $data['promoted_plan'] = $this->session->userdata('modesy_selected_promoted_plan');
        if (empty($data['promoted_plan'])) {
            redirect(lang_base_url());
        }

        $data["method"] = $this->input->get('method');
        $data["transaction_number"] = $this->input->get('transaction_number');

        $this->load->view('partials/_header', $data);
        $this->load->view('promote/payment_completed', $data);
        $this->load->view('partials/_footer');
    }

}