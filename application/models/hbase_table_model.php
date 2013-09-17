<?php

interface TableIf
{
	public function get_table_names();
	public function get_table_regions($table_name);
	public function enable_table($table_name);
	public function disable_table($table_name);
	public function is_table_enabled($table_name);
	public function create_table($table_name, $column_families);
	public function delete_table($table_name);
}

class Hbase_table_model extends CI_Model
{
	public $hbase_host;
	public $hbase_port;
	public $socket;
	public $transport;
	public $protocol;
	public $hbase;
	
	public function __construct()
	{ 
		parent::__construct();
		$GLOBALS['THRIFT_ROOT'] = __DIR__ . "/../../libs/";
		include_once $GLOBALS['THRIFT_ROOT'] . 'packages/Hbase/Hbase.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
		include_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
		
		$this->hbase_host = $this->config->item('hbase_host');
		$this->hbase_port = $this->config->item('hbase_port');
        $this->hbaseadmin_port = $this->config->item('hbaseadmin_port');
		$this->socket = new TSocket($this->hbase_host, $this->hbase_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->hbase = new HbaseClient($this->protocol);
	}
    
    public function headnav()
    {
        $this->lang->load('commons');		
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
        $data['common_table_view'] = $this->lang->line('common_table_view');		
		$data['common_table_list'] = $this->lang->line('common_table_list');
		$data['common_create_table'] = $this->lang->line('common_create_table');        
        $data['common_table_deltable'] = $this->lang->line('common_table_deltable');
        $data['common_monitor'] = $this->lang->line('common_monitor');
        $this->load->view('nav_bar',$data);  
    }
	
    public function get_hbase_info()
    {
        try
        {
           $host=$this->hbase_host;
           $port=$this->hbaseadmin_port;
           $url="http://".$host.":".$port."/jmx";
           $data=file_get_contents($url);
           return $data;
        }
        catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
        
    }
    
    public function get_zookeeper_info()
    {
        try
        {
           
        }
        catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
        
    }
    
    
    
	public function get_table_names()
	{
		try
		{
			$this->transport->open();
			$table_names_array = $this->hbase->getTableNames();
			$this->transport->close();
			return $table_names_array;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function get_table_descriptors($table_name)
	{
		try
		{
			$this->transport->open();
			$descriptors = $this->hbase->getColumnDescriptors($table_name);
			$this->transport->close();
			return $descriptors;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
    
	public function get_table_regions($table_name)
	{
		try
		{
			$this->transport->open();
			$regions = $this->hbase->getTableRegions($table_name);
			$this->transport->close();
			return $regions;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
    
    public function get_table_columns($table_name)
    {
        try
        {
           $this->transport->open();
           $descriptors = $this->hbase->getColumnDescriptors($table_name);
           $columns="";
           foreach($descriptors as $key=>$value)
            {
                $columns.=str_replace(":","",$key).",";
            } 
            $columns=rtrim($columns,",");
            return $columns;
        }
        catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
		
    } 
    
    public function get_table_records($table_name,$count)
    {
        try
        {
           $this->transport->open();
           $scan = new TScan();
           $scan->caching=200;
           $scanner = $this->hbase->scannerOpenWithScan($table_name,$scan);           
           $get_arr = $this->hbase->scannerGetList($scanner,$count);           
           if($get_arr==null)
           {
              return "null";
           }
           else
           {
               return $get_arr;
           }
           $this->transport->close();          
           
        }
        catch(exception $e)
        {
            echo 'Caught exception: '.  $e->getMessage(). "\n";            
        } 
        
    }
    
    public function search_table($table_name,$startrow,$stoprow,$timestamp,$column,$count)
    {
        try
        {
           $this->transport->open();
           $result="";
           if($startrow!="" && $stoprow=="" && $timestamp=="" && $column=="")
              {                
                $result=$this->hbase->getRow($table_name,$startrow);
              }
           if($startrow!="" && $stoprow!="" && $timestamp=="" && $column=="")
              { 
                $allcolumns=$this->get_table_columns($table_name);
                $columns=explode(",",$allcolumns);                
                $record=$this->hbase->scannerOpenWithStop($table_name,$startrow,$stoprow,$columns);
                $result=$this->hbase->scannerGetList($record,$count);
              }   
           if($startrow!="" && $stoprow=="" && $column=="" && $timestamp!="")
              {
                $result=$this->hbase->getRowTs($table_name,$startrow,$timestamp);                
              }   
           if($startrow!="" && $stoprow=="" && $timestamp=="" && $column!="")
              {
                $columns=explode(",",$column);
                $result=$this->hbase->getRowWithColumns($table_name,$startrow,$columns);
              }
           if($startrow!="" && $stoprow=="" && $timestamp!="" && $column!="")
              {
                $columns=explode(",",$column);
                $result=$this->hbase->getRowWithColumnsTs($table_name,$startrow,$columns,$timestamp);
              }
           if($startrow!="" && $timestamp=="" && $stoprow!="" && $column!="")
              {
                $columns=explode(",",$column);                
                $record=$this->hbase->scannerOpenWithStop($table_name,$startrow,$stoprow,$columns);
                $result=$this->hbase->scannerGetList($record,$count);
              }
           if($startrow!="" && $stoprow!="" && $timestamp!="" && $column!="") 
              {
                $columns=explode(",",$column);
                $record=$this->hbase->scannerOpenWithStopTs($table_name,$startrow,$stoprow,$columns,$timestamp);
                $result=$this->hbase->scannerGetList($record,$count);
              }       
           return $result;
           $this->transport->close(); 
            
        }
        catch(Exception $e)
        {
           echo 'Caught exception: '.  $e->getMessage(). "\n"; 
        }
        
    }
    
    
	public function enable_table($table_name)
	{
		try
		{
			$this->transport->open();
			$this->hbase->enableTable($table_name);
			$this->transport->close();
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function disable_table($table_name)
	{
		try
		{
			$this->transport->open();
			$this->hbase->disableTable($table_name);
			$this->transport->close();
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function is_table_enabled($table_name)
	{
		try
		{
			$this->transport->open();
			$bool = $this->hbase->isTableEnabled($table_name);
			$this->transport->close();
			return $bool;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function create_table($table_name, $column_families)
	{
		try
		{
			$this->transport->open();
			$bool = $this->hbase->createTable($table_name, $column_families);
			$this->transport->close();
			return "success";
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
    public function mutate_rowts($table_name, $row, $mutations, $timestamp)
    {
        try
        {
           $this->transport->open();
		   $bool = $this->hbase->mutateRowTs($table_name,$row,$mutations,$timestamp);
		   $this->transport->close();           
	       return "success"; 
        }
        catch (Exception $e)
		{
			return 'Caught exception: '.  $e->getMessage(). "\n";
		}
    }    
       
    
    
	public function delete_table($table_name)
	{
		try
		{
			$this->transport->open();
			$bool = $this->hbase->deleteTable($table_name);
			$this->transport->close();
			return $bool;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
    
    public function truncate_table($table_name)
    {
        try
		{
			$this->transport->open();
			$descriptors=$this->get_table_descriptors($table_name);
            if($this->is_table_enabled($table_name))
             {
                $this->disable_table($table_name);
                $this->delete_table($table_name);
             }
             else
             {
                $this->delete_table($table_name);
             }
             $result=$this->create_table($table_name,$descriptors);
             return $result;			
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
    }
}

?>