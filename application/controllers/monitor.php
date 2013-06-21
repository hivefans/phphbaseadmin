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
    public function getserverinfo()
    {
        $qry=$this->input->get("qry");
        $command=$this->input->get("command");
        $server=$this->input->get("server");
        $port=$this->input->get("port");
        $this->load->model('Zookeeper_model','monitor');
        $host="http://192.168.205.208:8080";
        if($qry=="stat")
         {
           $url=$host."/stat?server=".$server."&port=".$port."&command=".$command;
           $str=$this->monitor->getnodeinfo($url);
           if($command=="stat")
            {                
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
                $result.='"sent":"'.$sent.'","zxid":"'.$zxid.'","received":"'.$received.'","latency":"'.$latency.'"}';
                
            }
            else
            {
               $result=$str; 
            }             
         }
         elseif($qry=="get")
         {
            $url=$host."/get?server=".$server."&port=".$port;
            $result=$this->monitor->getnodeinfo($url);
         }
         elseif($qry=="getchild")
         {
            $url=$host."/getchild?server=".$server."&port=".$port;
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
		$this->load->view('node_stat');        
        $this->load->view('div_end');
		$this->load->view('div_end');		
		$this->load->view('footer');
    }
}