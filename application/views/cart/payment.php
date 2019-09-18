<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>
    var total_amount = '<?php echo price_format_decimal($cart_total->total); ?>';
    var currency = '<?php echo $payment_settings->default_product_currency; ?>';
    var paypal_mode = '<?php echo $payment_settings->paypal_mode; ?>';
    var paypal_client_id = '<?php echo $payment_settings->paypal_client_id; ?>';
    var stripe_key = '<?php echo $payment_settings->stripe_publishable_key; ?>';
    $(window).bind("load", function () {
        $("#payment-button-container").css("visibility", "visible");
    });
</script>

<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="shopping-cart shopping-cart-shipping">
                    <div class="row">
                        <div class="col-sm-12 col-lg-7">
                            <div class="left">
                                <h1 class="cart-section-title"><?php echo trans("checkout"); ?></h1>
                                <?php if (!auth_check()): ?>
                                    <div class="row m-b-15">
                                        <div class="col-12 col-md-6">
                                            <p><?php echo trans("checking_out_as_guest"); ?></p>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <p class="text-right"><?php echo trans("have_account"); ?>&nbsp;<a href="javascript:void(0)" class="link-underlined" data-toggle="modal" data-target="#loginModal"><?php echo trans("login"); ?></a></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($cart_has_physical_product == true): ?>
                                    <div class="tab-checkout tab-checkout-closed">
                                        <a href="<?php echo lang_base_url(); ?>cart/shipping"><h2 class=" title">1.&nbsp;&nbsp;<?php echo trans("shipping_information"); ?></h2></a>
                                        <a href="<?php echo lang_base_url(); ?>cart/shipping" class="link-underlined"><?php echo trans("edit"); ?></a>
                                    </div>
                                <?php endif; ?>

                                <div class="tab-checkout tab-checkout-closed">
                                    <a href="<?php echo lang_base_url(); ?>cart/payment-method"><h2 class=" title">
                                            <?php if ($cart_has_physical_product == true) {
                                                echo '2.';
                                            } else {
                                                echo '1.';
                                            } ?>
                                            &nbsp;<?php echo trans("payment_method"); ?></h2></a>
                                    <a href="<?php echo lang_base_url(); ?>cart/payment-method" class="link-underlined"><?php echo trans("edit"); ?></a>
                                </div>

                                <div class="tab-checkout tab-checkout-open">
                                    <h2 class="title">
                                        <?php if ($cart_has_physical_product == true) {
                                            echo '3.';
                                        } else {
                                            echo '2.';
                                        } ?>&nbsp;
                                        <?php echo trans("payment"); ?></h2>
                                    <div class="row">
                                        <div class="col-12">
                                            <?php
                                            $this->load->view("cart/payment_methods/_paypal");
                                            $this->load->view("cart/payment_methods/_stripe");
                                            $this->load->view("cart/payment_methods/_iyzico");
                                            $this->load->view("cart/payment_methods/_bank_transfer");
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-12 col-lg-5 order-summary-container">
                            <h2 class="cart-section-title"><?php echo trans("order_summary"); ?> (<?php echo get_cart_product_count(); ?>)</h2>
                            <div class="right">
                                <?php $this->load->view("cart/_order_summary"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->


