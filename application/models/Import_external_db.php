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
}