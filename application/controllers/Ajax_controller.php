<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->review_limit = 6;
    }

    /*
    *------------------------------------------------------------------------------------------
    * SEARCH LOCATION
    *------------------------------------------------------------------------------------------
    */

    //search location
    public function search_location()
    {
        $input_value = $this->input->post('input_value', true);
        $input_value = remove_special_characters($input_value);
        $data = array(
            'result' => 0,
            'response' => ''
        );

        if (!empty($input_value)) {
            $response = '<ul>';
            $countries = $this->location_model->search_countries($input_value);
            if (!empty($countries)) {
                $data['result'] = 1;
                foreach ($countries as $country) {
                    $response .= '<li><a href="javascript:void(0)" data-country="' . $country->id . '"><i class="icon-map-marker"></i>' . $country->name . '</a></li>';
                }
            }
            $states = $this->location_model->search_states($input_value);
            if (!empty($states)) {
                $data['result'] = 1;
                foreach ($states as $state) {
                    $response .= '<li><a href="javascript:void(0)" data-country="' . $state->country_id . '" data-state="' . $state->id . '"><i class="icon-map-marker"></i>' . $state->name . ', ' . $state->country_name . '</a></li>';
                }
            }
            $cities = $this->location_model->search_cities($input_value);
            if (!empty($cities)) {
                $data['result'] = 1;
                foreach ($cities as $city) {
                    $response .= '<li><a href="javascript:void(0)" data-country="' . $city->country_id . '" data-state="' . $city->state_id . '" data-city="' . $city->id . '"><i class="icon-map-marker"></i>' . $city->name . ', ' . $city->state_name . ', ' . $city->country_name . '</a></li>';
                }
            }
            $response .= '</ul>';
            $data['response'] = $response;
        }
        echo json_encode($data);
    }

    /*
    *------------------------------------------------------------------------------------------
    * AJAX SEARCH
    *------------------------------------------------------------------------------------------
    */

    //ajax search
    public function ajax_search()
    {
        $lang_base_url = $this->input->post('lang_base_url', true);
        $search_type = $this->input->post('search_type', true);
        $input_value = $this->input->post('input_value', true);
        $input_value = remove_special_characters($input_value);

        $data = array(
            'result' => 0,
            'response' => ''
        );

        if (!empty($search_type) && !empty($input_value)) {
            if ($search_type == 'member') {
                $data['result'] = 1;
                $response = '<ul>';
                $members = $this->profile_model->search_members_limited($input_value);
                if (!empty($members)) {
                    foreach ($members as $member) {
                        $response .= '<li><a href="' . $lang_base_url . 'profile' . '/' . $member->slug . '">' . $member->username . '</a></li>';
                    }
                } else {
                    $response .= '<li><a href="' . $lang_base_url . 'members?search=' . $input_value . '">' . $input_value . '</a></li>';
                }
                $response .= '</ul>';
                $data['response'] = $response;
            } else {
                $data['result'] = 1;
                $response = '<ul>';
                $products = $this->product_model->search_products($input_value);
                if (!empty($products)) {
                    foreach ($products as $product) {
                        $response .= '<li><a href="' . $lang_base_url . $product->slug . '">' . $product->title . '</a></li>';
                    }
                } else {
                    $response .= '<li><a href="' . $lang_base_url . 'products?search=' . $input_value . '">' . $input_value . '</a></li>';
                }
                $response .= '</ul>';
                $data['response'] = $response;
            }
        }
        echo json_encode($data);
    }

    /*
    *------------------------------------------------------------------------------------------
    * USER REVIEW FUNCTIONS
    *------------------------------------------------------------------------------------------
    */

    //add user review
    public function add_user_review()
    {
        if ($this->general_settings->user_reviews != 1) {
            exit();
        }
        $seller_id = $this->input->post('seller_id', true);
        $review = $this->user_review_model->get_review_by_user($seller_id, user()->id);
        if (!empty($review)) {
            echo "voted_error";
        } else {
            $this->user_review_model->add_review();
        }
    }

    //load more review
    public function load_more_user_review()
    {
        $seller_id = $this->input->post('seller_id', true);
        $limit = $this->input->post('limit', true);
        $new_limit = $limit + $this->review_limit;
        $data["user"] = $this->auth_model->get_user($seller_id);
        $data["reviews"] = $this->user_review_model->get_limited_reviews($seller_id, $new_limit);
        $data['review_count'] = $this->user_review_model->get_review_count($seller_id);
        $data['review_limit'] = $new_limit;

        $this->load->view('profile/_user_reviews', $data);
    }

    //delete user review
    public function delete_user_review()
    {
        $id = $this->input->post('review_id', true);
        $this->user_review_model->delete_review($id);
    }

    /*
    *------------------------------------------------------------------------------------------
    * EMAIL FUNCTIONS
    *------------------------------------------------------------------------------------------
    */

    //send email order summary to user
    public function send_email_order_summary()
    {
        $order_id = $this->input->post('order_id', true);
        //send email
        if ($this->general_settings->send_email_buyer_purchase == 1) {
            $this->load->model("email_model");
            $this->email_model->send_email_new_order($order_id);
        }
        reset_flash_data();
    }

    //send email new product
    public function send_email_new_product()
    {
        $product_link = $this->input->post('product_link', true);
        //send email
        if ($this->general_settings->send_email_new_product == 1) {
            $this->load->model('email_model');
            $this->email_model->send_email_new_product($product_link);
        }
        reset_flash_data();
    }

    //send email new message
    public function send_email_new_message()
    {
        $receiver_id = $this->input->post('receiver_id', true);
        $message_subject = $this->input->post('message_subject', true);
        $message_text = $this->input->post('message_text', true);

        $user = get_user($receiver_id);
        if (!empty($user)) {
            //send email
            if ($user->send_email_new_message == 1) {
                $this->load->model('email_model');
                $this->email_model->send_email_new_message($user, $message_subject, $message_text);
            }
        }
        reset_flash_data();
    }

    //send email order shipped
    public function send_email_order_shipped()
    {
        $order_product_id = $this->input->post('order_product_id', true);
        $order_product = $this->order_model->get_order_product($order_product_id);
        if (!empty($order_product)) {
            if ($this->general_settings->send_email_order_shipped == 1) {
                $this->load->model('email_model');
                $this->email_model->send_email_order_shipped($order_product);
            }
        }
        reset_flash_data();
    }
}
