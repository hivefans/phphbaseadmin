<?php

class Monitor extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Zookeeper()
	{
       	$this->load->model('hbase_table_model','monitor');
        $this->monitor->headnav();
        $this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		$this->load->view('zookeeper_admin');
        $this->load->view('cluster_add');
        $this->load->view('div_end');
		$this->load->view('div_end');		
		$this->load->view('footer');
	}
    public function Addcluster()
    {
        $this->load->model('Zookeeper_model','monitor');
        $clustername=$this->input->post("clustername");
        $serverlist=$this->input->post("serverlist");
        $this->monitor->clusteradd($clustername,$serverlist);
        echo "add cluster success";
    }
}