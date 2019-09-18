<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- File Manager -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/file-manager/file-manager.css">
<script src="<?php echo base_url(); ?>assets/vendor/file-manager/file-manager.js"></script>
<!-- Ckeditor js -->
<script src="<?php echo base_url(); ?>assets/vendor/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/ckeditor/lang/<?php echo $this->selected_lang->ckeditor_lang; ?>.js"></script>

<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div id="content" class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb"></ol>
                </nav>
                <h1 class="page-title page-title-product"><?php echo trans("sell_now"); ?></h1>
                <div class="form-add-product">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-12 col-lg-11">
                            <div class="row">
                                <div class="col-12">
                                    <!-- include message block -->
                                    <?php $this->load->view('product/_messages'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 m-b-30">
                                    <label class="control-label font-600"><?php echo trans("images"); ?></label>
                                    <?php $this->load->view("product/_image_update_box"); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <?php echo form_open('product_controller/edit_product_post', ['id' => 'form_validate','class' => 'validate_terms', 'onkeypress' => "return event.keyCode != 13;"]); ?>
                                    <input type="hidden" name="id" value="<?php echo $product->id; ?>">

                                    <div class="form-box">
                                        <div class="form-box-head">
                                            <h4 class="title"><?php echo trans('product_type'); ?></h4>
                                        </div>
                                        <div class="form-box-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <?php if ($general_settings->physical_products_system == 1): ?>
                                                        <div class="col-12 col-sm-6 col-option">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="product_type" value="physical" id="product_type_1" class="custom-control-input" <?php echo ($product->product_type == 'physical') ? 'checked' : ''; ?> required>
                                                                <label for="product_type_1" class="custom-control-label"><?php echo trans('physical'); ?></label>
                                                                <p class="form-element-exp"><?php echo trans('physical_exp'); ?></p>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($general_settings->digital_products_system == 1): ?>
                                                        <div class="col-12 col-sm-6 col-option">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="product_type" value="digital" id="product_type_2" class="custom-control-input" <?php echo ($product->product_type == 'digital') ? 'checked' : ''; ?> required>
                                                                <label for="product_type_2" class="custom-control-label"><?php echo trans('digital'); ?></label>
                                                                <p class="form-element-exp"><?php echo trans('digital_exp'); ?></>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-box">
                                        <div class="form-box-head">
                                            <h4 class="title"><?php echo trans('listing_type'); ?></h4>
                                        </div>
                                        <div class="form-box-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <?php if ($general_settings->marketplace_system == 1): ?>
                                                        <div class="col-12 col-sm-6 col-option listing_sell_on_site">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="listing_type" value="sell_on_site" id="listing_type_1" class="custom-control-input" <?php echo ($product->listing_type == 'sell_on_site') ? 'checked' : ''; ?> required>
                                                                <label for="listing_type_1" class="custom-control-label"><?php echo trans('add_product_for_sale'); ?></label>
                                                                <p class="form-element-exp"><?php echo trans('add_product_for_sale_exp'); ?></p>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($general_settings->classified_ads_system == 1): ?>
                                                        <div class="col-12 col-sm-6 col-option listing_ordinary_listing <?php echo ($product->product_type == 'digital') ? 'hidden' : ''; ?>">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="listing_type" value="ordinary_listing" id="listing_type_2" class="custom-control-input" <?php echo ($product->listing_type == 'ordinary_listing') ? 'checked' : ''; ?> required>
                                                                <label for="listing_type_2" class="custom-control-label"><?php echo trans('add_product_services_listing'); ?></label>
                                                                <p class="form-element-exp"><?php echo trans('add_product_services_listing_exp'); ?></>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <ul id="all_categories_list" style="display: none;">
                                        <?php foreach ($all_categories as $item):
                                            if ($item->parent_id != 0): ?>
                                                <li class="category-option-<?php echo $item->parent_id; ?>" data-id="<?php echo $item->id; ?>" data-name="<?php echo $item->name; ?>"></li>
                                            <?php endif;
                                        endforeach; ?>
                                    </ul>

                                    <div class="form-box">
                                        <div class="form-box-head">
                                            <h4 class="title"><?php echo trans('details'); ?></h4>
                                        </div>
                                        <div class="form-box-body">

                                            <div class="form-group">
                                                <label class="control-label"><?php echo trans("title"); ?></label>
                                                <input type="text" name="title" class="form-control form-input" value="<?php echo $product->title; ?>" placeholder="<?php echo trans("title"); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label"><?php echo trans('category'); ?></label>
                                                <div class="selectdiv">
                                                    <select id="categories" name="category_id" class="form-control selecter" onchange="show_subcategories_by_parent_id(this.value);" required>
                                                        <option value=""><?php echo trans('select_category'); ?></option>
                                                        <?php if (!empty($parent_categories)):
                                                            foreach ($parent_categories as $item): ?>
                                                                <option value="<?php echo html_escape($item->id); ?>" <?php echo ($item->id == $product->category_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                                            <?php endforeach;
                                                        endif; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group" id="subcategory_dropdown" style="<?php echo ($product->subcategory_id == 0) ? 'display: none' : ''; ?>">
                                                <div class="selectdiv">
                                                    <select name="subcategory_id" class="form-control selecter" onchange="show_third_categories_by_parent_id(this.value);" required>
                                                        <option value=""><?php echo trans('select_category'); ?></option>
                                                        <?php if (!empty($subcategories)):
                                                            foreach ($subcategories as $item): ?>
                                                                <option value="<?php echo html_escape($item->id); ?>" <?php echo ($item->id == $product->subcategory_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                                            <?php endforeach;
                                                        endif; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group" id="third_category_dropdown" style="<?php echo ($product->third_category_id == 0) ? 'display: none' : ''; ?>">
                                                <div class="selectdiv">
                                                    <select name="third_category_id" class="form-control selecter" required>
                                                        <option value=""><?php echo trans('select_category'); ?></option>
                                                        <?php if (!empty($third_categories)):
                                                            foreach ($third_categories as $item): ?>
                                                                <option value="<?php echo html_escape($item->id); ?>" <?php echo ($item->id == $product->third_category_id) ? 'selected' : ''; ?>><?php echo html_escape($item->name); ?></option>
                                                            <?php endforeach;
                                                        endif; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label"><?php echo trans('description'); ?></label>
                                                <div class="row">
                                                    <div class="col-sm-12 m-b-10">
                                                        <button type="button" class="btn btn-sm btn-secondary color-white btn_ck_add_image"><i class="icon-image"></i><?php echo trans("add_image"); ?></button>
                                                        <button type="button" class="btn btn-sm btn-info color-white btn_ck_add_video"><i class="icon-image"></i><?php echo trans("add_video"); ?></button>
                                                        <button type="button" class="btn btn-sm btn-warning color-white btn_ck_add_iframe"><i class="icon-image"></i><?php echo trans("add_iframe"); ?></button>
                                                    </div>
                                                </div>
                                                <textarea name="description" id="ckEditor" class="text-editor"><?php echo $product->description; ?></textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group m-t-15">
                                        <div class="custom-control custom-checkbox custom-control-validate-input">
                                            <input type="checkbox" class="custom-control-input" name="terms_conditions" id="terms_conditions" value="1" checked required>
                                            <label for="terms_conditions" class="custom-control-label"><?php echo trans("terms_conditions_exp"); ?>&nbsp;<a href="<?php echo lang_base_url(); ?>terms-conditions" class="link-terms" target="_blank"><strong><?php echo trans("terms_conditions"); ?></strong></a></label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <?php if ($product->is_draft == 1): ?>
                                            <button type="submit" class="btn btn-lg btn-custom float-right"><?php echo trans("save_and_continue"); ?></button>
                                        <?php else: ?>
                                            <a href="<?php echo lang_base_url(); ?>sell-now/product-details/<?php echo $product->id; ?>" class="btn btn-lg btn-custom float-right"><?php echo trans("next"); ?></a>
                                            <button type="submit" class="btn btn-lg btn-custom float-right m-r-10"><?php echo trans("save_changes"); ?></button>
                                        <?php endif; ?>
                                    </div>

                                    <?php echo form_close(); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->

<?php $this->load->view("product/_file_manager_ckeditor"); ?>

<!-- Ckeditor -->
<script>
    var ckEditor = document.getElementById('ckEditor');
    if (ckEditor != undefined && ckEditor != null) {
        CKEDITOR.replace('ckEditor', {
            language: '<?php echo $this->selected_lang->ckeditor_lang; ?>',
            filebrowserBrowseUrl: 'path',
            removeButtons: 'Save',
            allowedContent: true,
            extraPlugins: 'videoembed,oembed'
        });
    }

    function selectFile(fileUrl) {
        window.opener.CKEDITOR.tools.callFunction(1, fileUrl);
    }

    CKEDITOR.on('dialogDefinition', function (ev) {
        var editor = ev.editor;
        var dialogDefinition = ev.data.definition;

        // This function will be called when the user will pick a file in file manager
        var cleanUpFuncRef = CKEDITOR.tools.addFunction(function (a) {
            $('#ckFileManagerModal').modal('hide');
            CKEDITOR.tools.callFunction(1, a, "");
        });
        var tabCount = dialogDefinition.contents.length;
        for (var i = 0; i < tabCount; i++) {
            var browseButton = dialogDefinition.contents[i].get('browse');
            if (browseButton !== null) {
                browseButton.onClick = function (dialog, i) {
                    editor._.filebrowserSe = this;
                    var iframe = $('#ckFileManagerModal').find('iframe').attr({
                        src: editor.config.filebrowserBrowseUrl + '&CKEditor=body&CKEditorFuncNum=' + cleanUpFuncRef + '&langCode=en'
                    });
                    $('#ckFileManagerModal').appendTo('body').modal('show');
                }
            }
        }
    });

    CKEDITOR.on('instanceReady', function (evt) {
        $(document).on('click', '.btn_ck_add_image', function () {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('image');
            }
        });
        $(document).on('click', '.btn_ck_add_video', function () {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('videoembed');
            }
        });
        $(document).on('click', '.btn_ck_add_iframe', function () {
            if (evt.editor.name != undefined) {
                evt.editor.execCommand('iframe');
            }
        });
    });
</script>
