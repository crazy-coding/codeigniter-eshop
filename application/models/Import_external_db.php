<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import_external_db extends CI_Model
{
    //get external db tables
    public function get_tables($config)
    {
        $external_db = $this->load->database($config, TRUE);
        $query = $external_db->query("SHOW TABLES");
        $column_name = 'Tables_in_'.$config['database'];
        foreach ($query->result() as $row) {
            $return[] = ['name' => $row->$column_name];
        }
        return $return;
    }

    //get external db columns
    public function get_columns($config, $table)
    {
        $external_db = $this->load->database($config, TRUE);
        $query = $external_db->query("SHOW COLUMNS FROM $table;");
        return $query->result();
    }

    //load more data
    public function load_more_data($config)
    {
        $this->load->model('import_admin_model');

        $db_name = $this->input->get('db_name', true);
        $table = $this->input->get('table', true);
        $last_id = $this->input->get('last_id', true) ?: $this->import_admin_model->last_uploaded_id($db_name, $table);

        $external_db = $this->load->database($config, TRUE);
        $query = $external_db->query("SELECT * FROM $table WHERE id > $last_id ORDER BY id ASC LIMIT 10;");
        return $query->result();
    }
    
    //load more data
    public function load_item($config)
    {
        $table = $this->input->get('table', true);
        $upload_id = $this->input->get('upload_id', true);

        $external_db = $this->load->database($config, TRUE);
        $query = $external_db->query("SELECT * FROM $table WHERE id = $upload_id");
        return $query->row();
    }

    // Calculate progress
    public function calculate_progress($config)
    {
        $table = $this->input->get('table', true);
        $upload_id = $this->input->get('upload_id', true);

        $external_db = $this->load->database($config, TRUE);
        $current = $external_db->query("SELECT count(*) count FROM $table WHERE id <= $upload_id")->row()->count;
        $total = $external_db->query("SELECT count(*) count FROM $table")->row()->count;
        
        return [
            "percent"   => (round($current/$total*10000)/100)."%",
            "current"   => $current,
            "total"     => $total
        ];
    }
}