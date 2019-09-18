<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "paypal"): ?>
    <div class="row">
        <div class="col-12">
            <!-- include message block -->
            <?php $this->load->view('product/_messages'); ?>
        </div>
    </div>
    <div id="payment-button-container" class=paypal-button-cnt">
        <p class="p-complete-payment"><?php echo trans("msg_complete_payment"); ?></p>
        <div id="paypal-button"></div>
    </div>

    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script>
        paypal.Button.render({
            // Configure environment
            env: paypal_mode,
            client: {
                sandbox: paypal_client_id,
                production: paypal_client_id
            },
            // Customize button (optional)
            locale: 'en_US',
            style: {
                size: 'medium',
                color: 'black',
                shape: 'rect',
            },
            // Enable Pay Now checkout flow (optional)
            commit: true,
            // Set up a payment
            payment: function (data, actions) {
                return actions.payment.create({
                    transactions: [{
                        amount: {
                            total: total_amount,
                            currency: currency
                        }
                    }]
                });
            },
            // Execute the payment
            onAuthorize: function (data, actions) {
                return actions.payment.execute().then(function () {
                    var data_array = {
                        'payment_id': data.paymentID,
                        'currency': currency,
                        'payment_amount': total_amount,
                        'payment_status': 'succeeded',
                        'lang_folder': lang_folder,
                        'form_lang_base_url': '<?php echo lang_base_url(); ?>'
                    };
                    data_array[csfr_token_name] = $.cookie(csfr_cookie_name);
                    $.ajax({
                        type: "POST",
                        url: base_url + "promote_controller/paypal_payment_post",
                        data: data_array,
                        success: function (response) {
                            var obj = JSON.parse(response);
                            if (obj.result == 1) {
                                window.location.href = obj.redirect;
                            } else {
                                location.reload();
                            }
                        }
                    });
                });
            }
        }, '#paypal-button');
    </script>
<?php endif; ?>
