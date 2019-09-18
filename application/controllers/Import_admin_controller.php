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
     * Email Verification Post
     */
    public function email_verification_post()
    {
        if ($this->settings_model->update_email_verification()) {
            $this->session->set_flashdata('success', trans("msg_updated"));
            $this->session->set_flashdata('submit', "verification");
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            $this->session->set_flashdata('submit', "verification");
            redirect($this->agent->referrer());
        }
    }

    /**
     * Email Options Post
     */
    public function email_options_post()
    {
        if ($this->settings_model->update_email_options()) {
            $this->session->set_flashdata('success', trans("msg_updated"));
            $this->session->set_flashdata('submit', "options");
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            $this->session->set_flashdata('submit', "options");
            redirect($this->agent->referrer());
        }
    }
}
