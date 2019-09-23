<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import_admin_model extends CI_Model
{
    //get imports
    public function get_imports($db_name, $table_from)
    {
        $this->db->where('imports.db_name', $db_name);
        $this->db->where('imports.table_from', $table_from);
        $this->db->order_by('imports.created_at', 'DESC');
        $query = $this->db->get('imports');
        return $query->result();
    }

    //get current data
    public function get_current($db_name, $table_from)
    {
        // $this->custom_query();
        $return = array(
            'db_name'                   => $db_name,
            'table'                     => $table_from,
            'users-username'            => '',
            'users-email'               => '',
            'users-phone_number'        => '',
            'users-avatar'              => '',
            'users-ext_url'             => '',
            'users-city'                => '',
            'users-ext_location'        => '',
            'products-title'            => '',
            'products-category_id'      => '',
            'products-description'      => '',
            'products-price'            => '',
            'products-city'             => '',
            'products-ext_location'     => '',
            'products-address'          => '',
            'products-zip_code'         => '',
            'products-external_link'    => '',
            'products-ext_main_image'   => '',
            'products-ext_images'       => '',
            'products-ext_addtional_description'   => '',
            'products-ext_addtional_fields'        => '',
            'imports-last_uploaded_id'  => '',
        );

        foreach ($this->get_imports($db_name, $table_from) as $row) 
        {
            $return[$row->table_to."-".$row->column_to] = $row->column_from;
        }
        return $return;
    }

    //add imports
    public function add_imports($add_data)
    {
        $data = array(
            'db_name' => $add_data['db_name'],
            'table_from' => $add_data['table_from'],
            'column_from' => $add_data['column_from'],
            'table_to' => $add_data['table_to'],
            'column_to' => $add_data['column_to'],
            'created_at' => date('Y-m-d H:i:s')
        );

        return $this->db->insert('imports', $data);
    }

    //update imports
    public function update_imports()
    {
        $data = array(
            'db_name'                   => $this->input->post('db_name', true),
            'table'                     => $this->input->post('table', true),
            'users-username'            => $this->input->post('users-username', true),
            'users-email'               => $this->input->post('users-email', true),
            'users-phone_number'        => $this->input->post('users-phone_number', true),
            'users-avatar'              => $this->input->post('users-avatar', true),
            'users-ext_url'             => $this->input->post('users-ext_url', true),
            'users-city'                => $this->input->post('users-city', true),
            'users-ext_location'        => $this->input->post('users-ext_location', true),
            'products-title'            => $this->input->post('products-title', true),
            'products-category_id'      => $this->input->post('products-category_id', true),
            'products-description'      => $this->input->post('products-description', true),
            'products-price'            => $this->input->post('products-price', true),
            'products-city'             => $this->input->post('products-city', true),
            'products-ext_location'     => $this->input->post('products-ext_location', true),
            'products-address'          => $this->input->post('products-address', true),
            'products-zip_code'         => $this->input->post('products-zip_code', true),
            'products-external_link'    => $this->input->post('products-external_link', true),
            'products-ext_main_image'   => $this->input->post('products-ext_main_image', true),
            'products-ext_images'       => $this->input->post('products-ext_images', true),
            'products-ext_addtional_description'   => implode(",", $this->input->post('products-ext_addtional_description', true)),
            'products-ext_addtional_fields'        => implode(",", $this->input->post('products-ext_addtional_fields', true)),
            'imports-last_uploaded_id'             => $this->input->post('imports-last_uploaded_id', true),
        );
        
        $this->db->where('db_name', $data['db_name']);
        $this->db->where('table_from', $data['table']);
        $this->db->delete('imports');

        foreach ($data as $key => $row) {
            if (count(explode("-", $key)) == 2) {
                $table = explode("-", $key)[0];
                $column = explode("-", $key)[1];
                if($row) {
                    $this->add_imports(array(
                        'db_name'       => $data['db_name'],
                        'table_from'    => $data['table'],
                        'column_from'   => $row,
                        'table_to'      => $table,
                        'column_to'     => $column,
                        'created_at'    => date('Y-m-d H:i:s')
                    ));
                }
            } else if ($key == 'imports-last_uploaded_id') {
                $this->add_imports(array(
                    'db_name'       => $data['db_name'],
                    'table_from'    => $data['table'],
                    'column_from'   => $row,
                    'table_to'      => 'imports',
                    'column_to'     => 'last_uploaded_id',
                    'created_at'    => date('Y-m-d H:i:s')
                ));
            }
        }
        return true;
    }

    // Currently not matched columns.
    public function remain_columns($db_name, $table, $coulumns)
    {
        $this->db->where('db_name', $db_name);
        $this->db->where('table_from', $table);
        $this->db->select('column_from');
        $matched = $this->db->get('imports')->result();

        $mached_columns = [];
        foreach($matched as $matched_item) {
            foreach (explode(",", $matched_item->column_from) as $cf) {
                $mached_columns[] = $cf;
            }
        }
        foreach($coulumns as $item) {
            $ori_columns[] = $item->Field;
        }
        
        return array_diff($ori_columns, $mached_columns);
    }

    // Upload one item.
    public function upload_item($load_item)
    {
        $db_name = $this->input->get('db_name', true);
        $table = $this->input->get('table', true);
        $upload_id = $this->input->get('upload_id', true);
        
        $current = $this->get_current($db_name, $table);
        $users = [];
        $products = [];
        $images = "";
        $custom_descriptions = [];
        $custom_fields = [];

        foreach ($current as $key => $cur) {
            $keys = explode("-", $key);
            if(count($keys) == 2 && $cur) {
                switch($key) {
                    case 'users-username':
                    case 'users-email':
                    case 'users-phone_number':
                    case 'users-avatar':
                        $users[$keys[1]] = $load_item->$cur;
                        break;
                    case 'users-city':
                        $users['city_id'] = $this->get_city_id($load_item->$cur);
                        break;
                    case 'users-ext_url':
                        break;
                    case 'users-ext_location':
                        $users += $this->generate_position($load_item->$cur);
                        break;
                    case 'products-category_id':
                        $products['category_id'] = $cur;
                        break;
                    case 'products-title':
                    case 'products-description':
                    case 'products-address':
                    case 'products-zip_code':
                    case 'products-external_link':
                        $products[$keys[1]] = $load_item->$cur;
                        break;
                    case 'products-price':
                        $products[$keys[1]] = $load_item->$cur*100;
                        break;
                    case 'products-city':
                        $products['city_id'] = $this->get_city_id($load_item->$cur);
                        break;
                    case 'products-ext_location':
                        $products += $this->generate_position($load_item->$cur);
                        break;
                    case 'products-ext_main_image':
                    case 'products-ext_images':
                        $images .= ",".$load_item->$cur;
                        break;
                    case 'products-ext_addtional_description':
                        foreach (explode(",", $cur) as $col) {
                            $custom_descriptions[$col] = $load_item->$col;
                        }
                        break;
                    case 'products-ext_addtional_fields':
                        foreach (explode(",", $cur) as $col) {
                            $custom_fields[$col] = $load_item->$col;
                        }
                        break;
                    case 'imports-last_uploaded_id':
                        break;
                }
            }
        }
        $user_id = $this->add_users($users);
        if (isset($users['avatar']))
            $this->avatar_upload($users['avatar'], $user_id);
        
        $products['user_id'] = $user_id;
        $products['description'] = isset($products['description']) ? $products['description'] : "";
        $products['description'] = $this->add_products_description($custom_descriptions, $products['description']);
        $product_id = $this->add_products($products);
        $this->add_images($images, $product_id);

        $this->add_custom_fields($custom_fields, $products['category_id'], $product_id);

        return true;
    }


    // Add product data
    public function add_products($custom)
    {
        $data = array(
            'title' => "",
            'product_type' => "physical",
            'listing_type' => "sell_on_site",
            'category_id' => 0,
            'subcategory_id' => 0,
            'third_category_id' => 0,
            'price' => 0,
            'currency' => "USD",
            'description' => "",
            'product_condition' => "",
            'country_id' => 0,
            'state_id' => 0,
            'city_id' => 0,
            'address' => "",
            'zip_code' => "",
            'location' => "",
            'user_id' => 0,
            'status' => 1,
            'is_promoted' => 0,
            'promote_start_date' => date('Y-m-d H:i:s'),
            'promote_end_date' => date('Y-m-d H:i:s'),
            'promote_plan' => "none",
            'promote_day' => 0,
            'visibility' => 1,
            'rating' => 0,
            'hit' => 2,
            'demo_url' => "",
            'external_link' => "",
            'files_included' => "",
            'quantity' => 1,
            'shipping_time' => "",
            'shipping_cost_type' => "",
            'shipping_cost' => 0,
            'is_sold' => 0,
            'is_deleted' => 0,
            'is_draft' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );

        foreach ($custom as $column => $value) {
            $data[$column] = $value;
        }
        $data["slug"] = str_slug($data["title"]);
        $data["description"] = "<p>".$data["description"]."</p>";
        
        if ($this->db->insert('products', $data)) {
            $last_id = $this->db->insert_id();
            return $last_id;
        } else {
            return false;
        }
    }

    // If exist user, load user_id. if not add user data.
    public function add_users($custom)
    {
        $this->load->library('auth_model');
        $is_user = $this->auth_model->get_user_by_username($custom['username']);
        if($is_user) return $is_user->id;

        $data = array(
            'username' => "",
            'password' => "",
            'email' => "",
            'user_type' => "registered",
            'role' => "vendor",
            'slug' => "",
            'banned' => 0,
            'shop_name' => "",
            'show_phone' => 1,
            'is_active_shop_request' => 1,
            'token' => generate_token(),
            'created_at' => date('Y-m-d H:i:s')
        );

        foreach ($custom as $column => $value) {
            $data[$column] = $value;
        }

        $this->load->library('bcrypt');

        $data['email'] = $data['email'] ?: $data["username"]."@zappeur.com";
        $data['shop_name'] = $data["username"];
        $data['password'] = $this->bcrypt->hash_password($data['username']);
        $data["slug"] = $this->auth_model->generate_uniqe_slug($data["username"]);

        if ($this->db->insert('users', $data)) {
            $last_id = $this->db->insert_id();
            return $last_id;
        } else {
            return false;
        }
    }

    // Upload images and change image names and add image data.
    public function add_images($custom, $product_id)
    {
        $images = array_unique(explode(",", $custom));
        foreach ($images as $k => $url) {
            if($url) 
                $this->products_upload($url, $product_id);
        }
    }

    // If exist addtional description fields, add that to description.
    public function add_products_description($custom_descriptions, $description)
    {
        foreach ($custom_descriptions as $key => $val) {
            if($val)
                $description .= "<p><b>".ucfirst(str_replace(['_', '/', ','], [' ', ' ', ' '], $key)).":</b> $val</p>";
        }

        return $description;
    }

    // If not exist custom field, make it. then add custom field data.
    public function add_custom_fields($custom_fields, $category_id, $product_id)
    {
        foreach ($custom_fields as $key => $val) {
            if($val) {
                $key = str_replace(['house_', 'job_', 'description_'], ['', '', ''], $key);
                $field_id = $this->get_field_id($key, $category_id);
                $data = array(
                    'field_id'      => $field_id,
                    'product_id'    => $product_id,
                    'product_filter_key' => strtolower($key),
                    'field_value'   => $val,
                );
                $this->db->insert('custom_fields_product', $data);
            }
        }
    }

    // Update profile avatar.
    public function avatar_upload($url, $user_id)
    {
        $this->load->model('upload_model');

        $user_id = clean_number($user_id);
        $temp_path = $this->upload_image_to_temp($url);
        if (!empty($temp_path)) {
            //delete old avatar
            delete_file_from_server(user()->avatar);
            $data["avatar"] = $this->upload_model->avatar_upload($temp_path);
            $this->upload_model->delete_temp_image($temp_path);

            $this->db->where('id', $user_id);
            $this->db->update('users', $data);
        }
    }

    // Upload temp image by url.
    public function upload_image_to_temp($url = null)
    {
        $new = './uploads/temp/img_temp_' . generate_unique_id();
        set_time_limit(60); // 0: unlimited max execution time
        
        set_error_handler(
            function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
              if (error_reporting() === 0) return false;
          
              throw new ErrorException( $err_msg, 0, $err_severity, $err_file, $err_line );
            },
            E_WARNING
        );
        try {
            $data = file_get_contents($url);
            $result = file_put_contents($new, $data);
        } catch (Exception $e) {
            return false;
        }
        restore_error_handler();


        // $ch = curl_init();
        // $fp = fopen($new, "w");
        // $options = array(
        //     CURLOPT_FILE    => $fp,
        //     CURLOPT_TIMEOUT =>  28800,
        //     CURLOPT_URL     => $url
        // );
        // curl_setopt_array($ch, $options);
        // curl_exec($ch);
        // curl_close($ch);
        // $result = 1;
        // fclose($fp);

        if($result)
            return $new;
        else
            return false;
    }

    // Upload product image
    public function products_upload($url, $product_id)
    {
        $this->load->model('upload_model');

        $temp_path = $this->upload_image_to_temp($url);
        if (!empty($temp_path)) {
            $data = array(
                'product_id' => $product_id,
                'image_default' => $this->upload_model->product_default_image_upload($temp_path, "images"),
                'image_big' => $this->upload_model->product_big_image_upload($temp_path, "images"),
                'image_small' => $this->upload_model->product_small_image_upload($temp_path, "images"),
                'is_main' => 0,
                'storage' => "local"
            );
            $this->upload_model->delete_temp_image($temp_path);

            //move to s3
            if ($this->storage_settings->storage == "aws_s3") {
                $this->load->model("aws_model");
                $data["storage"] = "aws_s3";
                //move images
                if (!empty($data["image_default"])) {
                    $this->aws_model->put_product_object($data["image_default"], FCPATH . "uploads/images/" . $data["image_default"]);
                    delete_file_from_server("uploads/images/" . $data["image_default"]);
                }
                if (!empty($data["image_big"])) {
                    $this->aws_model->put_product_object($data["image_big"], FCPATH . "uploads/images/" . $data["image_big"]);
                    delete_file_from_server("uploads/images/" . $data["image_big"]);
                }
                if (!empty($data["image_small"])) {
                    $this->aws_model->put_product_object($data["image_small"], FCPATH . "uploads/images/" . $data["image_small"]);
                    delete_file_from_server("uploads/images/" . $data["image_small"]);
                }
            }
            $this->db->insert('images', $data);
            return  $this->db->insert_id();
        }
    }

    // Generate Country, City, States, Location, Zipcode ----todo----
    public function generate_position($location)
    {
        return [
            'country_id'    => 75,
            'location'      => $location
        ];
    }

    // Get city id by name
    public function get_city_id($val)
    {
        $this->load->model('location_model');
        foreach(explode(" ", $val) as $city) {
            $cities = $this->location_model->search_cities($city);
            if(count($cities) > 0) return $cities[0]->id;
        }
        return null;
    }

    // Get or update last_uploaded_id
    public function last_uploaded_id($db_name, $table_from, $upload_id = null)
    {
        $this->db->where('db_name', $db_name);
        $this->db->where('table_from', $table_from);
        $this->db->where('table_to', 'imports');
        $this->db->where('column_to', 'last_uploaded_id');
        $this->db->select('id, column_from');
        $row = $this->db->get('imports')->row();

        if ($row) {
            if ($upload_id) {
                $data['column_from'] = $upload_id;
                $this->db->where('id', $row->id);
                $this->db->update('imports', $data);
                return $upload_id;
            } else {
                return $row->column_from;
            }
        } else {
            $this->db->insert('imports', [
                'db_name'       => $db_name,
                'table_from'    => $table_from,
                'table_to'      => 'imports',
                'column_to'     => 'last_uploaded_id',
                'column_from'   => $upload_id ?: 0,
            ]);
            return $upload_id ?: 0;
        }
    }


    // Custom query for fixing database
    public function custom_query()
    {
        $query = $this->db->get("products");
        foreach($query->result() as $row) {
            $data = ["price" => $row->price*100];
            $this->db->where('id', $row->id);
            $this->db->update('products', $data);
        }
        die;
    }

    // Custom field, if exist load, not save
    public function get_field_id($field_name, $category_id)
    {
        $this->db->where('product_filter_key', strtolower($field_name));
        $field = $this->db->get("custom_fields")->row();
        if($field) {
            $field_id = $field->id;
        } else {
            $this->db->insert("custom_fields", [
                'field_type'    => 'text',
                'row_width'     => 'half',
                'is_required'   => 0,
                'status'        => 1,
                'field_order'   => 3,
                'is_product_filter'     => 0,
                'product_filter_key'    => strtolower($field_name),
            ]);
            $field_id = $this->db->insert_id();
            $this->db->insert("custom_fields_category", [
                'category_id'   => $category_id,
                'field_id'      => $field_id,
            ]);
            $this->db->insert("custom_fields_lang", [
                'field_id'      => $field_id,
                'lang_id'       => 1,
                'name'          => $field_name,
            ]);
            $this->db->insert("custom_fields_lang", [
                'field_id'      => $field_id,
                'lang_id'       => 2,
                'name'          => $field_name."(fr)",
            ]);
        }
        return $field_id;
    }
}