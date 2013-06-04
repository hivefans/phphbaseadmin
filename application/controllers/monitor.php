<?php

class Monitor extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
       	$this->load->model('hbase_table_model','monitor');
        $this->monitor->headnav();
        
        
        
        
        
        
        
	}
}