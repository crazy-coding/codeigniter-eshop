<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $query_string = "";
if (!empty($_SERVER["QUERY_STRING"])) {
    $query_string = "?" . $_SERVER["QUERY_STRING"];
} ?>

<!--product filters-->
<div class="col-12 col-md-3 sidebar-products">
    <div id="collapseExample" class="product-filters">
        <?php if (!empty($third_categories)): ?>
            <div class="filter-item">
                <h4 class="title">
                    <a href="<?php echo generate_category_url($category) . $query_string; ?>"><i class="icon-angle-left"></i><?php echo trans("categories_all"); ?></a>
                </h4>
                <div class="subcategory-link"><strong class="font-600"><?php echo $subcategory->name; ?></strong>&nbsp;(<?php echo get_products_count_by_subcategory($subcategory->id); ?>)</div>
                <div class="filter-list-container">
                    <ul class="filter-list filter-custom-scrollbar">
                        <?php foreach ($third_categories as $item): ?>
                            <li>
                                <div class="left">
                                    <div class="custom-control custom-checkbox">
                                        <input type="radio" class="custom-control-input" name="" id="cat_<?php echo $item->id; ?>" value="<?php echo $item->id; ?>" <?php echo (!empty($third_category) && $third_category->id == $item->id) ? 'checked' : ''; ?>>
                                        <label for="cat_<?php echo $item->id; ?>" class="custom-control-label" onclick="window.location.href= '<?php echo generate_category_url($item) . $query_string; ?>'"></label>
                                    </div>
                                </div>
                                <div class="rigt">
                                    <label for="cat_<?php echo $item->id; ?>" class="checkbox-list-item-label" onclick="window.location.href= '<?php echo generate_category_url($item) . $query_string; ?>'"><?php echo html_escape($item->name); ?>
                                        <span class="product-count">(<?php echo get_products_count_by_third_category($item->id); ?>)</span>
                                    </label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php elseif (!empty($subcategories)): ?>
            <div class="filter-item">
                <h4 class="title">
                    <a href="<?php echo lang_base_url() . "products" . $query_string; ?>"><i class="icon-angle-left"></i><?php echo trans("categories_all"); ?></a>
                </h4>
                <div class="filter-list-container">
                    <ul class="filter-list filter-custom-scrollbar">
                        <?php foreach ($subcategories as $item): ?>
                            <li>
                                <div class="left">
                                    <div class="custom-control custom-checkbox">
                                        <input type="radio" class="custom-control-input" name="subcategory_id" id="cat_<?php echo $item->id; ?>" value="<?php echo $item->id; ?>" <?php echo (!empty($subcategory) && $subcategory->id == $item->id) ? 'checked' : ''; ?>>
                                        <label for="cat_<?php echo $item->id; ?>" class="custom-control-label" onclick="window.location.href= '<?php echo generate_category_url($item) . $query_string; ?>'"></label>
                                    </div>
                                </div>
                                <div class="rigt">
                                    <label for="cat_<?php echo $item->id; ?>" class="checkbox-list-item-label" onclick="window.location.href= '<?php echo generate_category_url($item) . $query_string; ?>'"><?php echo html_escape($item->name); ?>
                                        <span class="product-count">(<?php echo get_products_count_by_subcategory($item->id); ?>)</span>
                                    </label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php elseif (!empty($categories)): ?>
            <div class="filter-item">
                <h4 class="title"><?php echo trans("categories"); ?></h4>
                <div class="filter-list-container">
                    <ul class="filter-list filter-custom-scrollbar">
                        <?php foreach ($categories as $item): ?>
                            <li>
                                <div class="left">
                                    <div class="custom-control custom-checkbox">
                                        <input type="radio" class="custom-control-input" name="category_id" id="cat_<?php echo $item->id; ?>" value="<?php echo $item->id; ?>" <?php echo (!empty($category) && $category->id == $item->id) ? 'checked' : ''; ?>>
                                        <label for="cat_<?php echo $item->id; ?>" class="custom-control-label" onclick="window.location.href= '<?php echo generate_category_url($item) . $query_string; ?>'"></label>
                                    </div>
                                </div>
                                <div class="rigt">
                                    <label for="cat_<?php echo $item->id; ?>" class="checkbox-list-item-label" onclick="window.location.href= '<?php echo generate_category_url($item) . $query_string; ?>'"><?php echo html_escape($item->name); ?>
                                        <span class="product-count">(<?php echo get_products_count_by_category($item->id); ?>)</span>
                                    </label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php
        if ($show_location_filter == true):
            if ($this->form_settings->product_location == 1):
                $country_id = $this->input->get('country', true);
                $state_id = $this->input->get('state', true);
                $city_id = $this->input->get('city', true); ?>
                <div class="filter-item filter-location">
                    <h4 class="title"><?php echo trans("location"); ?></h4>
                    <div class="input-group input-group-location">
                        <i class="icon-map-marker"></i>
                        <input type="text" id="input_location" class="form-control form-input" value="<?php echo get_location_input($country_id, $state_id, $city_id); ?>" placeholder="<?php echo trans("enter_location") ?>" autocomplete="off">
                    </div>
                    <div id="response_search_location" class="search-results-ajax search-results-location"></div>
                </div>
                <?php if (!empty($country_id)): ?>
                <input type="hidden" name="country" value="<?php echo $country_id; ?>" class="input-location-filter">
            <?php endif; ?>
                <?php if (!empty($state_id)): ?>
                <input type="hidden" name="state" value="<?php echo $state_id; ?>" class="input-location-filter">
            <?php endif; ?>
                <?php if (!empty($city_id)): ?>
                <input type="hidden" name="city" value="<?php echo $city_id; ?>" class="input-location-filter">
            <?php endif; ?>
            <?php endif; ?>

            <?php if ($this->form_settings->product_conditions == 1): ?>
            <div class="filter-item">
                <h4 class="title"><?php echo trans("condition"); ?></h4>
                <div class="filter-list-container">
                    <ul class="filter-list filter-custom-scrollbar">
                        <?php
                        $get_condition = get_filter_query_string_key_value('condition');
                        $product_conditions = get_active_product_conditions($selected_lang->id);
                        if (!empty($product_conditions)): ?>
                            <?php foreach ($product_conditions as $option): ?>
                                <li>
                                    <div class="left">
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" name="condition" value="<?php echo $option->option_key; ?>" id="condition_<?php echo $option->id; ?>" class="custom-control-input" onchange="this.form.submit();" <?php echo ($get_condition == $option->option_key) ? 'checked' : ''; ?>>
                                            <label for="condition_<?php echo $option->id; ?>" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                    <div class="rigt">
                                        <label for="condition_<?php echo $option->id; ?>" class="checkbox-list-item-label"><?php echo $option->option_label; ?></label>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endif;
        endif; ?>

        <?php
        $category_id = NULL;
        $subcategory_id = NULL;
        $third_category_id = NULL;
        if (!empty($category)) {
            $category_id = $category->id;
        }
        if (!empty($subcategory)) {
            $subcategory_id = $subcategory->id;
        }
        if (!empty($third_category)) {
            $third_category_id = $third_category->id;
        }

        $custom_filters = get_custom_filters($category_id, $subcategory_id, $third_category_id);
        if (!empty($custom_filters)):
            foreach ($custom_filters as $custom_filter): ?>
                <div class="filter-item">
                    <h4 class="title"><?php echo $custom_filter->name; ?></h4>
                    <div class="filter-list-container">
                        <ul class="filter-list filter-custom-scrollbar">
                            <?php $options = get_custom_field_options_by_lang($custom_filter->id, $this->selected_lang->id); ?>
                            <?php if (!empty($options)):
                                foreach ($options as $option):
                                    $get_option_value = get_filter_query_string_key_value($custom_filter->product_filter_key); ?>
                                    <li>
                                        <div class="left">
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" name="<?php echo $custom_filter->product_filter_key; ?>" id="filter_option_<?php echo $custom_filter->id . '-' . $option->id ?>" value="<?php echo $option->field_option; ?>" class="custom-control-input" onchange="this.form.submit();" <?php echo ($get_option_value == $option->field_option) ? 'checked' : ''; ?>>
                                                <label for="filter_option_<?php echo $custom_filter->id . '-' . $option->id ?>" class="custom-control-label"></label>
                                            </div>
                                        </div>
                                        <div class="rigt">
                                            <label for="filter_option_<?php echo $custom_filter->id . '-' . $option->id ?>" class="checkbox-list-item-label"><?php echo $option->field_option; ?></label>
                                        </div>
                                    </li>
                                <?php
                                endforeach;
                            endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach;
        endif; ?>
        <?php if ($this->form_settings->price == 1):
            $filter_p_max = @(float)get_filter_query_string_key_value('p_max');
            $filter_p_min = @(float)get_filter_query_string_key_value('p_min'); ?>
            <div class="filter-item">
                <h4 class="title"><?php echo trans("price"); ?></h4>
                <div class="price-filter-inputs">
                    <div class="row align-items-baseline row-price-inputs">
                        <div class="col-4 col-md-4 col-lg-5 col-price-inputs">
                            <span><?php echo trans("min"); ?></span>
                            <input type="input" name="<?php echo (get_filter_query_string_key_value('p_min')) ? 'p_min' : ''; ?>" id="price_min" value="<?php echo ($filter_p_min != 0) ? $filter_p_min : ''; ?>" class="form-control price-filter-input" placeholder="<?php echo trans("min"); ?>"
                                   onchange="this.name='p_min'"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        </div>
                        <div class="col-4 col-md-4 col-lg-5 col-price-inputs">
                            <span><?php echo trans("max"); ?></span>
                            <input type="input" name="<?php echo (get_filter_query_string_key_value('p_max')) ? 'p_max' : ''; ?>" id="price_max" value="<?php echo ($filter_p_max != 0) ? $filter_p_max : ''; ?>" class="form-control price-filter-input" placeholder="<?php echo trans("max"); ?>"
                                   onchange="this.name='p_max'"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        </div>
                        <div class="col-4 col-md-4 col-lg-2 col-price-inputs text-left">
                            <button class="btn btn-sm btn-default btn-filter-price float-left"><i class="icon-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="row-custom">
        <!--Include banner-->
        <?php $this->load->view("partials/_ad_spaces_sidebar", ["ad_space" => "products_sidebar", "class" => "m-b-15"]); ?>
    </div>
</div>