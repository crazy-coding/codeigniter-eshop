<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($product->product_type == 'digital'):
    if (!empty($digital_sale)): ?>
        <div class="row-custom m-t-10">
            <?php echo form_open('file_controller/download_purchased_digital_file'); ?>
            <input type="hidden" name="sale_id" value="<?php echo $digital_sale->id; ?>">
            <button class="btn btn-instant-download"><i class="icon-download-solid"></i><?php echo trans("download") ?></button>
            <?php echo form_close(); ?>
        </div>
    <?php else: ?>
        <label class="label-instant-download"><i class="icon-download-solid"></i><?php echo trans("instant_download"); ?></label>
    <?php endif;
endif; ?>
    <h1 class="product-title"><?php echo html_escape($product->title); ?></h1>
<?php if ($product->status == 0): ?>
    <label class="badge badge-warning badge-product-status"><?php echo trans("pending"); ?></label>
<?php elseif ($product->visibility == 0): ?>
    <label class="badge badge-danger badge-product-status"><?php echo trans("hidden"); ?></label>
<?php endif; ?>
    <div class="row-custom meta">
        <?php echo trans("by"); ?>&nbsp;<a href="<?php echo lang_base_url() . 'profile' . '/' . $product->user_slug; ?>"><?php echo character_limiter(get_shop_name_product($product), 30, '..'); ?></a>
        <?php if ($general_settings->product_reviews == 1): ?>
            <span><i class="icon-comment"></i><?php echo html_escape($comment_count); ?></span>
        <?php endif; ?>
        <span><i class="icon-heart"></i><?php echo get_product_favorited_count($product->id); ?></span>
        <span><i class="icon-eye"></i><?php echo html_escape($product->hit); ?></span>
    </div>
    <div class="row-custom price">
        <?php if ($product->is_sold == 1): ?>
            <strong class="lbl-price" style="color: #9a9a9a;"><?php echo print_price($product->price, $product->currency); ?><span class="price-line"></span></strong>
            <strong class="lbl-sold"><?php echo trans("sold"); ?></strong>
        <?php else: ?>
            <strong class="lbl-price"><?php echo print_price($product->price, $product->currency); ?></strong>
        <?php endif; ?>
        <?php if (auth_check()): ?>
            <button class="btn btn-contact-seller" data-toggle="modal" data-target="#messageModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
        <?php else: ?>
            <button class="btn btn-contact-seller" data-toggle="modal" data-target="#loginModal"><i class="icon-envelope"></i> <?php echo trans("ask_question") ?></button>
        <?php endif; ?>
    </div>

    <div class="row-custom details">
        <?php if (!empty($product->product_condition)): ?>
            <div class="item-details">
                <div class="left">
                    <label><?php echo trans("condition"); ?></label>
                </div>
                <div class="right">
                    <?php $product_condition = get_product_condition_by_key($product->product_condition, $selected_lang->id);
                    if (!empty($product_condition)):?>
                        <span><?php echo html_escape($product_condition->option_label); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($product->product_type == 'digital'): ?>
            <div class="item-details">
                <div class="left">
                    <label><?php echo trans("files_included"); ?></label>
                </div>
                <div class="right">
                    <span><?php echo html_escape($product->files_included); ?></span>
                </div>
            </div>
        <?php endif; ?>
        <div class="item-details">
            <div class="left">
                <label><?php echo trans("uploaded"); ?></label>
            </div>
            <div class="right">
                <span><?php echo time_ago($product->created_at); ?></span>
            </div>
        </div>
    </div>
<?php if ($product->listing_type == 'sell_on_site'): ?>
    <?php echo form_open(lang_base_url() . 'add-to-cart', ['id' => 'form_add_cart_mobile']); ?>
    <?php $this->load->view('product/details/_product_variations', ['input_id_suffix' => 'mb']); ?>
    <?php if ($product->is_sold == 0): ?>
        <?php if ($product->quantity > 1 && $product->product_type == 'physical'): ?>
            <div class="row-custom">
                <label class="lbl-quantity"><?php echo trans("quantity"); ?></label>
            </div>
            <div class="row-custom">
                <div class="touchspin-container">
                    <input id="quantity_touchspin_mobile" type="text" value="1" class="form-input">
                </div>
            </div>
        <?php endif; ?>
        <div class="row-custom m-t-15">
            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
            <input type="hidden" name="product_quantity" value="1">
            <button class="btn btn-md btn-block"><?php echo trans("add_to_cart") ?></button>
        </div>
    <?php endif; ?>
    <?php echo form_close(); ?>
<?php else:
    if (!empty($product->external_link)): ?>
        <div class="row-custom">
            <a href="<?php echo $product->external_link; ?>" class="btn btn-md btn-block" target="_blank"><?php echo trans("buy_now") ?></a>
        </div>
    <?php endif;
endif; ?>

<?php if (!empty($product->demo_url)): ?>
    <div class="row-custom m-t-10">
        <a href="<?php echo $product->demo_url; ?>" target="_blank" class="btn btn-favorite"><i class="icon-preview"></i><?php echo trans("live_preview") ?></a>
    </div>
<?php endif; ?>

    <div class="row-custom m-t-10">
        <?php if (auth_check()): ?>
            <!-- form start -->
            <?php echo form_open('product_controller/add_remove_favorites'); ?>
            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
            <?php if (is_product_in_favorites(user()->id, $product->id)): ?>
                <button class="btn btn-favorite"><i class="icon-heart"></i><?php echo trans("remove_from_favorites") ?></button>
            <?php else: ?>
                <button class="btn btn-favorite"><i class="icon-heart-o"></i><?php echo trans("add_to_favorites") ?></button>
            <?php endif; ?>
            <?php echo form_close(); ?>
            <!-- form end -->
        <?php else: ?>
            <button class="btn btn-favorite" data-toggle="modal" data-target="#loginModal"><i class="icon-heart-o"></i><?php echo trans("add_to_favorites") ?></button>
        <?php endif; ?>
    </div>

    <!--Include social share-->
<?php $this->load->view("product/details/_product_share"); ?>