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
    public function getcluster()
    {
       $this->load->database();
       $sql="select * from zookeeper_cluster";
       $query=$this->db->query($sql);
       $result=""; 
       if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {               
              $result.='{"clustername":"'.$row->cluster_name.'","serverlist":"'.$row->server_list.'"},';            
           }
           $result=rtrim($result,",");
           $result= '['.$result.']';
           return $result;
        }        
    }
    public function getnodeinfo($url)
    {
        try
		{
			$str = file_get_contents($url);
		}
		catch(Exception $e)
		{
			$str = '{"Exception":"' . $e->getMessage() . '"}';
		}
		return $str;
    }
    
    
    
    
    
    
    
    
}    