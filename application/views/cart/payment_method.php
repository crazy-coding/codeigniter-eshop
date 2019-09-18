<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                        <a href="<?php echo lang_base_url(); ?>cart/shipping"><h2 class="title">1.&nbsp;&nbsp;<?php echo trans("shipping_information"); ?></h2></a>
                                        <a href="<?php echo lang_base_url(); ?>cart/shipping" class="link-underlined edit-link"><?php echo trans("edit"); ?></a>
                                    </div>
                                <?php endif; ?>

                                <div class="tab-checkout tab-checkout-open">
                                    <h2 class="title">
                                        <?php if ($cart_has_physical_product == true) {
                                            echo '2.';
                                        } else {
                                            echo '1.';
                                        } ?>
                                        &nbsp;<?php echo trans("payment_method"); ?></h2>
                                    <?php echo form_open('cart_controller/payment_method_post', ['id' => 'form_validate', 'class' => 'validate_terms']); ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <ul class="payment-options-list">
                                                    <?php if ($payment_settings->paypal_enabled): ?>
                                                        <li>
                                                            <div class="list-left">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="option_paypal" name="payment_option" value="paypal" checked required>
                                                                    <label class="custom-control-label label-payment-option" for="option_paypal"><?php echo trans("paypal"); ?></label>
                                                                </div>
                                                            </div>
                                                            <div class="list-right">
                                                                <label for="option_paypal"><img src="<?php echo base_url(); ?>assets/img/paypal-icons.png" alt="paypal"></label>
                                                            </div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($payment_settings->stripe_enabled): ?>
                                                        <li>
                                                            <div class="list-left">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="option_stripe" name="payment_option" value="stripe" required <?php echo ($payment_settings->paypal_enabled != 1) ? 'checked' : ''; ?>>
                                                                    <label class="custom-control-label label-payment-option" for="option_stripe"><?php echo trans("stripe"); ?></label>
                                                                </div>
                                                            </div>
                                                            <div class="list-right">
                                                                <label for="option_stripe"><img src="<?php echo base_url(); ?>assets/img/stripe-icons.png" alt="stripe"></label>
                                                            </div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($payment_settings->iyzico_enabled): ?>
                                                        <li>
                                                            <div class="list-left">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="option_iyzico" name="payment_option" value="iyzico" required <?php echo ($payment_settings->paypal_enabled != 1 && $payment_settings->stripe_enabled != 1) ? 'checked' : ''; ?>>
                                                                    <label class="custom-control-label label-payment-option" for="option_iyzico"><?php echo trans("iyzico"); ?></label>
                                                                </div>
                                                            </div>
                                                            <div class="list-right">
                                                                <label for="option_iyzico"><img src="<?php echo base_url(); ?>assets/img/iyzico-icons.png" alt="iyzico"></label>
                                                            </div>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($payment_settings->bank_transfer_enabled): ?>
                                                        <li>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input" id="option_bank" name="payment_option" value="bank_transfer" required <?php echo ($payment_settings->paypal_enabled != 1 && $payment_settings->stripe_enabled != 1 && $payment_settings->iyzico_enabled != 1) ? 'checked' : ''; ?>>
                                                                <label class="custom-control-label label-payment-option" for="option_bank"><?php echo trans("bank_transfer"); ?>&nbsp;<small>(<?php echo trans("offline_payment"); ?>)</small>
                                                                </label>
                                                            </div>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox custom-control-validate-input">
                                                    <input type="checkbox" class="custom-control-input" name="terms" id="checkbox_terms" required>
                                                    <label for="checkbox_terms" class="custom-control-label"><?php echo trans("terms_conditions_exp"); ?>&nbsp;<a href="<?php echo lang_base_url(); ?>terms-conditions" class="link-terms" target="_blank"><strong><?php echo trans("terms_conditions"); ?></strong></a></label>
                                                </div>
                                            </div>

                                            <div class="form-group m-t-15">
                                                <a href="<?php echo lang_base_url(); ?>cart" class="link-underlined link-return-cart"><&nbsp;<?php echo trans("return_to_cart"); ?></a>
                                                <button type="submit" name="submit" value="update" class="btn btn-lg btn-custom float-right"><?php echo trans("continue_to_payment") ?></button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>

                                <div class="tab-checkout tab-checkout-closed-bordered">
                                    <h2 class="title">
                                        <?php if ($cart_has_physical_product == true) {
                                            echo '3.';
                                        } else {
                                            echo '2.';
                                        } ?>
                                        &nbsp;<?php echo trans("payment"); ?></h2>
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