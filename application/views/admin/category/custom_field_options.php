<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 form-header">
        <h1 class="form-title"><?php echo trans('custom_field_options'); ?></h1>
    </div>
</div>
<div class="callout" style="margin-top: 10px;background-color: #fff; border-color:#00c0ef;max-width: 600px;">
    <h4><?php echo trans("custom_field"); ?></h4>
    <p><?php echo trans('field_name'); ?>:&nbsp;<strong><?php echo $field_name; ?></strong></p>
    <p>
        <?php echo trans('type'); ?>:&nbsp;
        <strong>
            <?php echo trans($field->field_type); ?>
        </strong>
    </p>
</div>

<div class="row">
    <?php if ($field->field_type == "checkbox" || $field->field_type == "radio_button" || $field->field_type == "dropdown"): ?>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo trans("options"); ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php if (!empty($options)): ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="custom-field-options" style="max-height: 600px; overflow: auto">
                                        <?php $count = 0;
                                        $last_common_id = "";
                                        foreach ($options as $option):?>
                                            <?php if ($last_common_id != $option->common_id):
                                                $count++; ?>
                                                <div class="field-option-item">
                                                    <?php echo form_open_multipart('category_controller/update_custom_field_option_post', ['onkeypress' => 'return event.keyCode != 13;']); ?>
                                                    <input type="hidden" name="common_id" value="<?php echo $option->common_id; ?>">
                                                    <div class="option-title">
                                                        <strong><?php echo trans("option") . " " . $count; ?></strong>
                                                    </div>
                                                    <?php $options_answers = $this->field_model->get_field_option_by_common_id($option->common_id);
                                                    if (!empty($options_answers)):
                                                        foreach ($options_answers as $options_answer): ?>
                                                            <p>
                                                                <?php
                                                                $answers = $this->field_model->get_field_option($options_answer->id);
                                                                if (!empty($answers)):
                                                                    $language = get_language($options_answer->lang_id); ?>
                                                                    <input type='text' class="form-control" name="option_lang_<?php echo $options_answer->lang_id; ?>" value='<?php echo html_escape($answers->field_option); ?>' placeholder="Option (<?php echo @$language->name; ?>)" style='width: 100%;padding: 0 5px; bottom: 0 !important;box-shadow: none !important;height: 26px;' required>
                                                                <?php endif; ?>
                                                            </p>
                                                        <?php endforeach;
                                                    endif; ?>
                                                    <div>
                                                        <button type="button" class="btn btn-xs btn-danger pull-right" onclick="delete_custom_field_option('<?php echo trans("confirm_delete"); ?>','<?php echo $option->common_id; ?>');"><?php echo trans("delete"); ?></button>
                                                        <button type="submit" class="btn btn-xs btn-success pull-right m-r-5"><?php echo trans("save_changes"); ?></button>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            <?php endif;
                                            $last_common_id = $option->common_id;
                                        endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- form start -->
                    <?php echo form_open_multipart('category_controller/add_custom_field_option_post', ['onkeypress' => 'return event.keyCode != 13;']); ?>
                    <input type="hidden" name="field_id" value="<?php echo $field->id; ?>">
                    <div class="form-group m-b-10">
                        <label><?php echo trans("add_option"); ?></label>
                        <?php foreach ($languages as $language): ?>
                            <input type="text" class="form-control option-input m-b-5" name="option_lang_<?php echo $language->id; ?>" placeholder="Option (<?php echo $language->name; ?>)" required>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_option'); ?></button>
                    </div>
                    <?php echo form_close(); ?><!-- form end -->
                </div>
            </div>
            <!-- /.box -->
        </div>
    <?php endif; ?>

    <div class="col-sm-6">
        <div class="box box-primary" style="min-height: 252px;">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("categories"); ?></h3>
                <small>(<?php echo trans("show_under_these_categories"); ?>)</small>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <?php echo form_open_multipart('category_controller/add_category_to_custom_field', ['onkeypress' => 'return event.keyCode != 13;']); ?>
            <input type="hidden" name="field_id" value="<?php echo $field->id; ?>">
            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('select_category'); ?></label>
                    <select id="select_field_add_category" class="form-control" name="category_id" onchange="this.form.submit();" required>
                        <option value="0"><?php echo trans('none'); ?></option>
                        <?php foreach ($categories as $category):
                            if (empty($this->field_model->get_category_field($field->id, $category->id))): ?>
                                <option value="<?php echo $category->id; ?>"><?php echo html_escape($category->name); ?></option>
                                <?php
                                $subcategories = $this->category_model->get_subcategories_by_parent_id($category->id);
                                if (!empty($subcategories)):
                                    foreach ($subcategories as $subcategory):
                                        if (empty($this->field_model->get_category_field($field->id, $subcategory->id))): ?>
                                            <option value="<?php echo $subcategory->id; ?>"><?php echo html_escape($category->name . " / " . $subcategory->name); ?></option>
                                            <?php
                                            $thirdcategories = $this->category_model->get_subcategories_by_parent_id($subcategory->id);
                                            if (!empty($thirdcategories)):
                                                foreach ($thirdcategories as $thirdcategory):
                                                    if (empty($this->field_model->get_category_field($field->id, $thirdcategory->id))):?>
                                                        <option value="<?php echo $thirdcategory->id; ?>"><?php echo html_escape($category->name . " / " . $subcategory->name . " / " . $thirdcategory->name); ?></option>
                                                    <?php
                                                    endif;
                                                endforeach;
                                            endif;
                                        endif;
                                    endforeach;
                                endif;
                            endif;
                        endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped" role="grid">
                            <tbody>
                            <?php if (!empty($field_categories)):
                                foreach ($field_categories as $item):
                                    $category = get_category_joined($item->category_id);
                                    if (!empty($category)): ?>
                                        <?php if ($category->category_level == 1): ?>
                                            <tr>
                                                <td>
                                                    <?php echo html_escape($category->name); ?>
                                                    <button type="button" class="btn btn-xs btn-danger pull-right" onclick="delete_custom_field_category('<?php echo trans("confirm_delete"); ?>',<?php echo $field->id; ?>,<?php echo $category->id; ?>);"><?php echo trans("delete"); ?></button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php if ($category->category_level == 2):
                                            $parent = $this->category_model->get_category_joined($category->parent_id); ?>
                                            <tr>
                                                <td>
                                                    <?php if (!empty($parent)) {
                                                        echo html_escape($parent->name);
                                                    }
                                                    echo " / " . html_escape($category->name);
                                                    ?>
                                                    <button type="button" class="btn btn-xs btn-danger pull-right" onclick="delete_custom_field_category('<?php echo trans("confirm_delete"); ?>',<?php echo $field->id; ?>,<?php echo $category->id; ?>);"><?php echo trans("delete"); ?></button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php if ($category->category_level == 3):
                                            $top_parent = $this->category_model->get_category_joined($category->top_parent_id);
                                            $parent = $this->category_model->get_category_joined($category->parent_id); ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if (!empty($top_parent)) {
                                                        echo html_escape($top_parent->name);
                                                    }
                                                    if (!empty($parent)) {
                                                        echo " / " . html_escape($parent->name);
                                                    }
                                                    echo " / " . html_escape($category->name);
                                                    ?>
                                                    <button type="button" class="btn btn-xs btn-danger pull-right" onclick="delete_custom_field_category('<?php echo trans("confirm_delete"); ?>',<?php echo $field->id; ?>,<?php echo $category->id; ?>);"><?php echo trans("delete"); ?></button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                    <?php endif;
                                endforeach;
                            endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>