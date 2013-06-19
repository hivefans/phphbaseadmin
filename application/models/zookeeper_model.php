<?php
class Zookeeper_model extends CI_Model
{
    public function clusteradd($clustername,$serverlist)
    {
       $this->load->database();      
       $this->db->set('cluster_name', $clustername); 
       $this->db->set('server_list', $serverlist);       
       $this->db->insert('zookeeper_cluster'); 
    }
    
}    