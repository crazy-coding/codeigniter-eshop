<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import_admin_controller extends Admin_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        //check user
        if (!is_admin()) {
            redirect(admin_url() . 'login');
        }
        $this->load->model('import_admin_model');
        $this->load->model('import_external_db');
    }


    public function imports()
    {
        $data['title'] = trans("imports");

        $db_name = $this->input->get('db_name', true);
        $table = $this->input->get('table', true);

        if ($db_name) $imports['tables'] = $this->import_external_db->get_tables($this->get_config($db_name));
        if ($table) $imports['columns'] = $this->import_external_db->get_columns($this->get_config($db_name), $table);
        $imports['categories'] = $this->category_model->get_categories_all();
        $imports['current'] = $this->import_admin_model->get_current($db_name, $table);
        // print_r($imports);
        $data['imports'] = $imports;

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/imports/imports', $data);
        $this->load->view('admin/includes/_footer');
    }

    public function get_config($db_name) 
    {
        $config['hostname'] = 'localhost';
        $config['username'] = 'westcom';
        $config['password'] = 'Wxcwxc1212';
        $config['database'] = $db_name;
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';
        return $config;
    }


    /**
     * Imports Post
     */
    public function imports_post()
    {
        if ($this->import_admin_model->update_imports()) {
            $this->session->set_flashdata('success', trans("msg_updated"));
            $this->session->set_flashdata('submit', $this->input->post('submit', true));
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            $this->session->set_flashdata('submit', $this->input->post('submit', true));
            redirect($this->agent->referrer());
        }
    }
    

    /**
     * Load More Data
     */
    public function load_more_data()
    {
        $result = '';
        if ($this->input->get('table', true)) 
            $result = $this->import_external_db->load_more_data($this->get_config($this->input->get('db_name', true)));
        echo json_encode($result);
    }


    /**
     * Upload Object
     */
    public function upload_object()
    {
        $result = '';
        $db_name = $this->input->get('db_name', true);
        $table = $this->input->get('table', true);
        $upload_id = $this->input->get('upload_id', true);

        if($table) {
            $load_item = $this->import_external_db->load_item($this->get_config($db_name));
            $upload_item = $this->import_admin_model->upload_item($load_item);
            var_dump($upload_item);
        }

        echo json_encode($result);
    }
}
