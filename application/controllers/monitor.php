<?php

class Monitor extends CI_Controller
{
	 public function __construct()
    {
        parent::__construct();       

        if(!$this->session->userdata('is_logged_in')){
            redirect('/');
        }
    }
	
	public function Zookeeper()
	{
       	$this->load->model('hbase_table_model','hbasetable');
        $this->hbasetable->headnav();
        $this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		$this->load->view('zookeeper_admin');
        $this->load->model('Zookeeper_model','monitor');
        $data["clusterinfo"]=$this->monitor->getcluster();
        $this->load->view('cluster_add',$data);
        $this->load->view('div_end');
		$this->load->view('div_end');		
		$this->load->view('footer');
	}
    public function Addcluster()
    {
        if($this->session->userdata('group')=='admin'){
            $this->load->model('Zookeeper_model','monitor');
            $clustername=$this->input->post("clustername");
            $serverlist=$this->input->post("serverlist");
            $result=$this->monitor->clusteradd($clustername,$serverlist);
         }
         else $result="sorry,No authority!";   
        echo $result;
    }
    
    public function delcluster()
    {
      if($this->session->userdata('group')=='admin'){  
        $this->load->model('Zookeeper_model','monitor');
        $clustername=$this->input->post("clustername"); 
        $result=$this->monitor->clusterdel($clustername);
       }
       else $result="sorry,No authority!"; 
       echo $result;
    }
    
    public function Cluster_monitor()
    {
        $this->load->model('hbase_table_model','hbasetable');
        $this->hbasetable->headnav();
        $this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
        $this->load->view('zookeeper_admin');
        $this->load->model('Zookeeper_model','monitor');
        $data["clusterinfo"]=$this->monitor->getcluster();        
		$this->load->view('cluster_monitor',$data);        
        $this->load->view('div_end');
		$this->load->view('div_end');		
		$this->load->view('footer');
    }
    
    public function Stat_trend()
    {
        $this->load->model('hbase_table_model','hbasetable');
        $this->hbasetable->headnav();
        $this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
        $this->load->view('zookeeper_admin');
        $this->load->model('Zookeeper_model','monitor');               
		$this->load->view('stat_trend');        
        $this->load->view('div_end');
		$this->load->view('div_end');		
		$this->load->view('footer');
        
    }
    
    
    public function getserverinfo()
    {
        $qry=$this->input->get("qry");
        $command=$this->input->get("command");
        $server=$this->input->get("server");
        $port=$this->input->get("port");
        $path=$this->input->get("path");
        $this->load->model('Zookeeper_model','monitor');
        $host="http://".$this->config->item('cherrypy_host').":".$this->config->item('cherrypy_port');;
        if($qry=="stat")
         {
           $staturl=$host."/stat?server=".$server."&port=".$port."&command=".$command;
           $wchsurl=$host."/stat?server=".$server."&port=".$port."&command=wchs";
           $str=$this->monitor->getnodeinfo($staturl);
           $wchs_str=$this->monitor->getnodeinfo($wchsurl);
           if(preg_match("/watches:(\S+)/i",$wchs_str,$wchsarr))
           {
              $watches=$wchsarr[1];                    
            }
           if($command=="stat")
            {   
                $connection=0;
                if(preg_match("/Mode: (\S+)/i",$str,$modestr))
                {
                    $mode=$modestr[1];                    
                }
                if(preg_match("/Node count: (\S+)/i",$str,$nodecountstr))
                {
                    $nodecount=$nodecountstr[1];                    
                }
                if(preg_match("/Connections: (\S+)/i",$str,$connectionstr))
                {                    
                    $connection=$connectionstr[1];                    
                }
                if(preg_match("/Outstanding: (\S+)/i",$str,$outstandstr))
                {
                    $outstanding=$outstandstr[1];                    
                }
                if(preg_match("/Sent: (\S+)/i",$str,$sendstr))
                {
                    $sent=$sendstr[1];                    
                }
                if(preg_match("/Zxid: (\S+)/i",$str,$zxidstr))
                {
                    $zxid=$zxidstr[1];                    
                }
                if(preg_match("/Received: (\S+)/i",$str,$receivedstr))
                {
                    $received=$receivedstr[1];                    
                }
                if(preg_match("/Latency min\/avg\/max: (\S+)/i",$str,$latencystr))
                {
                    $latency=$latencystr[1];                    
                }
                
                $result='{"mode":"'.$mode.'","nodecount":"'.$nodecount.'","connection":"'.$connection.'","outstand":"'.$outstanding.'",';
                $result.='"sent":"'.$sent.'","zxid":"'.$zxid.'","received":"'.$received.'","latency":"'.$latency.'","watches":"'.$watches.'"}';
                
            }
            else
            {
               $result=$str; 
            }             
         }
         elseif($qry=="get")
         {
            $url=$host."/get?server=".$server."&port=".$port."&path=".$path;
            $result=$this->monitor->getnodeinfo($url);
         }
         elseif($qry=="getchild")
         {
            $url=$host."/getchild?server=".$server."&port=".$port."&path=".$path;
            $result=$this->monitor->getnodeinfo($url);
         }       
        
         echo $result;
        
    }
    
    public function Nodestat()
    {
        $this->load->model('hbase_table_model','hbasetable');
        $this->hbasetable->headnav();
        $this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
        $this->load->view('zookeeper_admin');
        $this->load->model('Zookeeper_model','monitor'); 
        $data["server"]=$this->input->get("server");
        $data["port"]=$this->input->get("port");                    
		$this->load->view('node_stat',$data);        
        $this->load->view('div_end');
		$this->load->view('div_end');		
		$this->load->view('footer');
    }
}