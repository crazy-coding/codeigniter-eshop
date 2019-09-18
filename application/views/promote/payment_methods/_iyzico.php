<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "iyzico"): ?>
    <div class="row">
        <div class="col-12">
            <!-- include message block -->
            <?php $this->load->view('product/_messages'); ?>
        </div>
    </div>
    <?php
    $ci =& get_instance();
    $options = $ci->initialize_iyzico();
    $user = user();
    $ip = $this->input->ip_address();
    if (empty($ip)) {
        $ip = "85.34.78.112";
    }
    # create request class
    $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
    $request->setLocale(\Iyzipay\Model\Locale::TR);
    $request->setConversationId("123456");
    $request->setPrice(price_format_decimal($promoted_plan->total_amount));
    $request->setPaidPrice(price_format_decimal($promoted_plan->total_amount));
    $request->setCurrency(\Iyzipay\Model\Currency::TL);
    $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
    $request->setCallbackUrl(base_url() . "promote_controller/iyzico_payment_post?lang_base_url=" . lang_base_url());
    $request->setEnabledInstallments(array(2, 3, 6, 9));

    $buyer = new \Iyzipay\Model\Buyer();
    $buyer->setId($user->id);
    $buyer->setName($user->username);
    $buyer->setSurname($user->username);
    $buyer->setGsmNumber($user->phone_number);
    $buyer->setEmail($user->email);
    $buyer->setIdentityNumber("11111111111");
    $buyer->setRegistrationAddress("not_set");
    $buyer->setIp($ip);
    $buyer->setCity("not_set");
    $buyer->setCountry("not_set");
    $buyer->setZipCode("not_set");
    $request->setBuyer($buyer);

    $shippingAddress = new \Iyzipay\Model\Address();
    $shippingAddress->setContactName("not_set");
    $shippingAddress->setCity("not_set");
    $shippingAddress->setCountry("not_set");
    $shippingAddress->setAddress("not_set");
    $shippingAddress->setZipCode("");
    $request->setShippingAddress($shippingAddress);

    $billingAddress = new \Iyzipay\Model\Address();
    $billingAddress->setContactName("not_set");
    $billingAddress->setCity("not_set");
    $billingAddress->setCountry("not_set");
    $billingAddress->setAddress("not_set");
    $billingAddress->setZipCode("");
    $request->setBillingAddress($billingAddress);

    $basketItems = array();
    //shipping
    $BasketItem = new \Iyzipay\Model\BasketItem();
    $BasketItem->setId("0");
    $BasketItem->setName($promoted_plan->purchased_plan);
    $BasketItem->setCategory1(trans("promote_plan"));
    $BasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
    $BasketItem->setPrice(price_format_decimal($promoted_plan->total_amount));
    $basketItems[0] = $BasketItem;
    //cart items
    $request->setBasketItems($basketItems);
    # make request
    $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
    echo $checkoutFormInitialize->getcheckoutFormContent(); ?>
    <div id="iyzipay-checkout-form" class="responsive"></div>
<?php endif; ?>
