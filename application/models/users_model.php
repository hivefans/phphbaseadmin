<?php

class Users_model extends CI_Model {

    /**
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
	function validate($user_name, $password)
	{
		$this->db->where('user_name', $user_name);
		$this->db->where('pass_word', $password);
		$query = $this->db->get('member');		
		if($query->num_rows == 1)
		{
			return $query->result_array();
		}		
	}

    /**
    * Serialize the session data stored in the database, 
    * store it in a new array and return it to the controller 
    * @return array
    */
	function get_db_session_data()
	{
		$query = $this->db->select('user_data')->get('ci_sessions');
		$user = array(); /* array to store the user data we fetch */
		foreach ($query->result() as $row)
		{
		    $udata = unserialize($row->user_data);
		    /* put data in array using username as key */
		    $user['user_name'] = $udata['user_name']; 
		    $user['is_logged_in'] = $udata['is_logged_in']; 
		}
		return $user;
	}
	
    /**
    * Store the new user's data into the database
    * @return boolean - check the insert
    */	
	function create_member()
	{

		$this->db->where('user_name', $this->input->post('username'));
		$query = $this->db->get('member');
        if($query->num_rows > 0){
            $result=false;
		}else{

			$new_member_insert_data = array(
				'user_name' => $this->input->post('username'),
				'group' => $this->input->post('groupname'),
				'email_address' => $this->input->post('email'),			
				'grant' => $this->input->post('grant'),
				'pass_word' => md5($this->input->post('password'))						
			);
			$result = $this->db->insert('member', $new_member_insert_data);
		    
		}
	    return $result;  
	}//create_member
    
    function create_group()
	{

		$this->db->where('name', $this->input->post('groupname'));
		$query = $this->db->get('usergroup');
        if($query->num_rows > 0){
            $result=false;
		}else{

			$new_group_insert_data = array(
				'name' => $this->input->post('groupname')										
			);
			$result = $this->db->insert('usergroup', $new_group_insert_data);
		    
		}
	    return $result;  
	}
    
    function update_member($id)
	{
	   $password=$this->input->post('password');
	    if($password=="")
         {
            $updatepass=$this->input->post('password2');
         }
         else
         {
            $updatepass=md5($this->input->post('password'));
         }
    	$member_update_data = array(
				'user_name' => $this->input->post('username'),
				'group' => $this->input->post('groupname'),
				'email_address' => $this->input->post('email'),			
				'grant' => $this->input->post('grant'),
				'pass_word' => $updatepass						
			);
		$username=$this->input->post('username');
        $this->db->where('user_name', $username);
        $query = $this->db->get('member');
        if($query->num_rows > 0){
            $result=false;
         }
        else{
            $this->db->where('id', $id);
            $this->db->update('member', $member_update_data);
            $result=true;
            if($this->input->post('username')==$this->session->userdata('user_name'))
              {
                 $data = array(
    				'user_name' => $this->input->post('username'),
    				'is_logged_in' => true,
                    'group'=>$this->input->post('groupname'),
                    'grant'=>$this->input->post('grant')
    			);
                $this->session->set_userdata($data);
              }
        }
       return $result; 
	}
    
    
    function update_group($id)
    {
       $group_update_data = array(
				'name' => $this->input->post('groupname')										
			);
       $groupname=$this->input->post('groupname');     
       $this->db->where('name', $groupname);
       $query = $this->db->get('usergroup');
       if($query->num_rows > 0){
            $result=false;
         }
         else{
            $this->db->where('id', $id);
            $this->db->update('usergroup', $group_update_data);
            $result=true;            
         }
       return $result;  
    }
    
    /**
    * Get the user's group data from the database
    * @return Array 
    */
    function get_usergroup($id = FALSE)
    {
       if($id==FALSE)
       { 
           $this->db->select('*');
    	   $this->db->from('usergroup'); 
           $query = $this->db->get();
       }
       else
       {
          $query = $this->db->get_where('usergroup', array('id' => $id));
       }    		
	   return $query->result_array();
    }
    
     function get_users($id = FALSE)
    {
       if($id==FALSE)
       {
          $this->db->select('*');
	      $this->db->from('member'); 
          $query = $this->db->get();	
       }
       else
       {
          $query = $this->db->get_where('member', array('id' => $id));
       }
	   return $query->result_array();
    } 
    
    function delete_user($id)
    {
       $this->db->where('id', $id);
	   $this->db->delete('member'); 
    } 
    
    function delete_group($id)
    {
       $this->db->where('id', $id);
	   $this->db->delete('usergroup'); 
    }   
  
    
}
