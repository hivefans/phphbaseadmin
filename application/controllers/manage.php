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
        $this->load->library('session');
        if($this->session->userdata('is_logged_in')){
			redirect('tables');
        }else{
        	$this->load->view('manage/login',$data);	
        }
	}
    
     function __encrip_password($password) {
        return md5($password);
    }
     /**
    * check the username and the password with the database
    * @return void
    */
    
    
	function validate_user()
	{	

		$this->load->model('users_model');
		$user_name = $this->input->post('user_name');
		$password = $this->__encrip_password($this->input->post('password'));

		$is_valid = $this->users_model->validate($user_name, $password);
		
		if($is_valid)
		{
			$data = array(
				'user_name' => $user_name,
				'is_logged_in' => true
			);
			$this->session->set_userdata($data);
			redirect('tables');
		}
		else // incorrect username or password
		{
		    $this->lang->load('commons');		
		    $data['common_lang_set'] = $this->lang->line('common_lang_set');
			$data['message_error'] = TRUE;
			$this->load->view('manage/login', $data);	
		}
	}	
    
    /**
    * Destroy the session, and logout the user.
    * @return void
    */		
	function logout()
	{
		$this->session->sess_destroy(); 
        redirect('/');		
		
	}
    
    function userinfo()
    {
      $this->load->model('hbase_table_model','table');
      $this->table->headnav();  
      $this->load->model('users_model');  
      $data['usergroup'] = $this->users_model->get_usergroup();  
      $data['users'] = $this->users_model->get_users();  
      $this->load->view('manage/userinfo',$data);  
      $this->load->view('footer');
    }
    
    function adduser()
    {
      $this->load->model('hbase_table_model','table');
      $this->table->headnav();  
      $this->load->model('users_model');  
      $data['usergroup'] = $this->users_model->get_usergroup();  
      $data['users'] = $this->users_model->get_users();  
      $this->load->view('manage/adduser',$data);  
      $this->load->view('footer');  
    }
    
    
    
    
}