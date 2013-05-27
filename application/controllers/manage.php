<?php

class Manage extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
       	$this->lang->load('commons');
        $data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
        $data['common_table_list'] = $this->lang->line('common_table_list');
		$data['common_table_manage'] = $this->lang->line('common_table_manage');
		$this->load->view('nav_bar',$data);
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		$this->load->view('table_lists',$data);
		$this->load->view('table_admin', $data);
		$this->load->view('div_end');
		$this->load->view('div_end');		
		$this->load->view('footer');
	}
}