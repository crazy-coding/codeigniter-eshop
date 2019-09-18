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
            'products-cateogory_id'     => '',
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

        $this->db->where('db_name', $data['db_name']);
        $this->db->where('table_from', $data['table_from']);
        $this->db->where('table_to', $data['table_to']);
        $this->db->where('column_to', $data['column_to']);
        $this->db->delete('imports');

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
            'products-cateogory_id'     => $this->input->post('products-cateogory_id', true),
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
        );

// print_r($this->input->post());die;
        foreach ($data as $key => $row) {
            if (count(explode("-", $key)) == 2) {
                $table = explode("-", $key)[0];
                $column = explode("-", $key)[1];
                if(!strpos($column, "ext") && $row) {
                    $this->add_imports(array(
                        'db_name'       => $data['db_name'],
                        'table_from'    => $data['table'],
                        'column_from'   => $row,
                        'table_to'      => $table,
                        'column_to'     => $column,
                        'created_at'    => date('Y-m-d H:i:s')
                    ));
                }
            }
        }
        return true;
    }
}