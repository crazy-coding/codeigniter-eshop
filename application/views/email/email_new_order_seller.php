<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('email/_header', ['title' => trans("you_have_new_order")]); ?>
    <!-- START CENTERED WHITE CONTAINER -->
    <table role="presentation" class="main">
        <!-- START MAIN CONTENT AREA -->
        <?php if (!empty($order)): ?>
            <tr>
                <td class="wrapper">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <h1 style="text-decoration: none; font-size: 24px;line-height: 28px;font-weight: bold"><?php echo trans("you_have_new_order"); ?></h1>
                                <div class="mailcontent" style="line-height: 26px;font-size: 14px;">
                                    <h2 style="margin-bottom: 10px; font-size: 16px;font-weight: 600;"><?php echo trans("order_information"); ?></h2>
                                    <p style="color: #555;">
                                        <?php echo trans("order"); ?>:&nbsp;#<?php echo $order->order_number; ?><br>
                                        <?php echo trans("payment_status"); ?>:&nbsp;<?php echo trans($order->payment_status); ?><br>
                                        <?php echo trans("payment_method"); ?>:&nbsp;<?php
                                        if ($order->payment_method == "Bank Transfer") {
                                            echo trans("bank_transfer");
                                        } else {
                                            echo $order->payment_method;
                                        } ?><br>
                                        <?php echo trans("date"); ?>:&nbsp;<?php echo date("Y-m-d / h:i", strtotime($order->created_at)); ?><br>
                                    </p>
                                </div>

                                <h3 style="margin-bottom: 10px; font-size: 16px;font-weight: 600;border-bottom: 1px solid #d1d1d1;padding-bottom: 5px; margin-top: 30px;"><?php echo trans("products"); ?></h3>
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="text-align: left" class="table-products">
                                    <tr>
                                        <th style=""><?php echo trans("product"); ?></th>
                                        <th style=""><?php echo trans("unit_price"); ?></th>
                                        <th><?php echo trans("quantity"); ?></th>
                                        <th><?php echo trans("shipping"); ?></th>
                                        <th><?php echo trans("price"); ?></th>
                                    </tr>
                                    <?php foreach ($order_products as $item): ?>
                                        <tr>
                                            <td><?php echo $item->product_title; ?></td>
                                            <td><?php echo print_price($item->product_unit_price, $item->product_currency); ?></td>
                                            <td><?php echo $item->product_quantity; ?></td>
                                            <td><?php echo print_price($item->product_shipping_cost, $item->product_currency); ?></td>
                                            <td><?php echo print_price($item->product_total_price, $item->product_currency); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                                <p style='text-align: center;margin-top: 40px;'>
                                    <a href="<?php echo lang_base_url() . 'sale/' . $order->order_number; ?>" style='font-size: 14px;text-decoration: none;padding: 14px 40px;background-color: #09b1ba;color: #ffffff !important; border-radius: 3px;'>
                                        <?php echo trans("see_order_details"); ?>
                                    </a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php endif; ?>
        <!-- END MAIN CONTENT AREA -->
    </table>
<?php $this->load->view('email/_footer'); ?>