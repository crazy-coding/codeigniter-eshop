<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "stripe"): ?>
    <div id="payment-button-container" class=paypal-button-cnt">
        <div class="row">
            <div class="col-12">
                <!-- include message block -->
                <?php $this->load->view('product/_messages'); ?>
            </div>
        </div>
        <p class="p-complete-payment"><?php echo trans("msg_complete_payment"); ?></p>
        <button type="button" id="btn_stripe_checkout" class="btn btn-lg custom-stripe-button"><?php echo trans("stripe_checkout") ?></button>
    </div>

    <script src="https://checkout.stripe.com/v2/checkout.js"></script>
    <script>
        var handler = StripeCheckout.configure({
            key: stripe_key,
            image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
            locale: 'auto',
            currency: currency,
            token: function (token) {
                var data = {
                    'payment_id': token.id,
                    'email': token.email,
                    'currency': currency,
                    'payment_amount': '<?php echo $promoted_plan->total_amount; ?>',
                    'payment_status': 'succeeded',
                    'lang_folder': lang_folder,
                    'form_lang_base_url': '<?php echo lang_base_url(); ?>'
                };
                data[csfr_token_name] = $.cookie(csfr_cookie_name);
                $.ajax({
                    type: "POST",
                    url: base_url + "promote_controller/stripe_payment_post",
                    data: data,
                    success: function (response) {
                        var obj = JSON.parse(response);
                        if (obj.result == 1) {
                            window.location.href = obj.redirect;
                        } else {
                            location.reload();
                        }
                    }
                });
            }
        });
        document.getElementById('btn_stripe_checkout').addEventListener('click', function (e) {
            handler.open({
                name: '<?php echo html_escape($general_settings->application_name); ?>',
                description: '<?php echo trans("stripe_checkout"); ?>',
                amount: '<?php echo $promoted_plan->total_amount; ?>'
            });
            e.preventDefault();
        });
        // Close Checkout on page navigation:
        window.addEventListener('popstate', function () {
            handler.close();
        });
    </script>
<?php endif; ?>
