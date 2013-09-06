<?php

class Tables extends CI_Controller
{
     public function __construct()
    {
        parent::__construct();       

        if(!$this->session->userdata('is_logged_in')){
            redirect('/');
        }
    }
	
	public function Index()
	{	    
		$this->load->model('hbase_table_model','table');
        $this->table->headnav();
        $hbaseinfo =$this->table->get_hbase_info();
        $hbasedata=json_decode($hbaseinfo,true);
        foreach($hbasedata["beans"] as $mbean)
         {
            if ($mbean["name"] == "hadoop:service=HBase,name=Info") 
            {
                $data['version']=$mbean['version'].', r'.$mbean['revision'];  
            }
            elseif ($mbean["name"] =="hadoop:service=Master,name=Master")
            {
                $data['ServerName']=explode(",",$mbean["ServerName"]);
                $data['ZookeeperQuorum']=$mbean["ZookeeperQuorum"];
                $data['DeadRegionServers']=$mbean["DeadRegionServers"];
                $data['AverageLoad']=$mbean['AverageLoad'];
                $data['MasterStartTime']=round($mbean["MasterStartTime"]/1000, 0);
                $data["live_regionservers"] = count($mbean["RegionServers"]);
                $data["Coprocessors"]=$mbean["Coprocessors"]; 
            }            
         }
		
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		$this->load->view('table_lists',$data);		
		$this->load->view('table_admin', $data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		$this->load->view('footer');
	}
	
	public function TableList()
	{		
        $grant_tables=$this->session->userdata('grant');
        $grant_arr=explode(",",$grant_tables);
        sort($grant_arr);
		$table_list = array('table_names' => $grant_arr);
		echo json_encode($table_list);
	}
	
    public function AddTable()
	{
       if($this->session->userdata('group')=='admin'){
    	    $tablename=$this->input->post("tablename");        
            if(strlen($this->GetTableRegions($tablename))==2)
            {
                $column=$this->input->post("column");
                $maxversions=$this->input->post("maxversions");
                $compression=$this->input->post("compression");
                $columnarr=explode(",",$column);
                $maxversionsarr=explode(",",$maxversions);
                $compressionarr=explode(",",$compression);
                 $columns=array();
                $this->load->model('hbase_table_model', 'table');        
                foreach($columnarr as $index=>$val)
                 {
                    $coldes=new ColumnDescriptor();
                    $coldes->name=$val.":";
                    $coldes->maxVersions=(int)$maxversionsarr[$index];
                    $coldes->compression=$compressionarr[$index];
                    array_push($columns,$coldes);                        
                 }
        	    
        		$result = $this->table->create_table($tablename,$columns);
            }
            else
            {
                $result="table already exsits";
            }
        }
        else $result="sorry,No authority!";		
		echo($result);
	}
    
	public function GetTableRegions($table_name)
	{
		$this->load->model('hbase_table_model', 'table');
		$regions = $this->table->get_table_regions($table_name);
		return json_encode($regions);
	}
    
    public function GetDescriptors($table_name)
	{
		$this->load->model('hbase_table_model', 'table');
		$descriptors = $this->table->get_table_descriptors($table_name);
		echo json_encode($descriptors);
	}
    public function GetColumn($table_name)
    {
        $this->load->model('hbase_table_model', 'table');
		$descriptors = $this->table->get_table_descriptors($table_name);
        $column="";
		foreach($descriptors as $key=>$value)
         {
            $column.=str_replace(":","",$key).',';
         }         
        $column=rtrim($column,",");
        return $column; 
    } 
     public function GetColumnJson($table_name)
     {
        $column=$this->GetColumn($table_name);
        $column=explode(",",$column);
        $result="";
        foreach($column as $col)
        {
           $result.='{"columnfamily":"'.$col.'"},'; 
        }
        $result=rtrim($result,",");
        $result='['.$result.']';
        echo $result;
     }
    
    public function GetTableRecords($table_name)
    {
        $this->load->model('hbase_table_model', 'table');        
        $count=200;
        $records= $this->table->get_table_records($table_name,$count); 
        if(is_array($records))
        {       
            $result="";
            foreach($records as $index=>$cols){            
                foreach($cols->columns as $key=>$vals)
                 {  
                    $row=$cols->row;
                    $row=mysql_real_escape_string($row);                                            
                    $column=explode(":",$key);                        
                    $value=$vals->value;  
                    $value=mysql_real_escape_string($value);                       
                    $value=json_encode($value);                        
                    $result=$result."{\"row\":\"".$row."\",\"columnfamily\":\"".$column[0]."\",\"columnqualifier\":\"".$column[1];
                    $result=$result."\",\"timestamp\":\"".$vals->timestamp."\",\"value\":".$value."},";
                  }
             }
              
            $result=rtrim($result,",");
            $result= '['.$result.']';
            echo($result); 
        }
        else
        {
            echo '{"row":"","columnfamily":""}'; 
        }         
    }
    
    public function SearchTable($table_name)
    {        
		$this->load->model('hbase_table_model','table');
        $this->table->headnav();
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');		
		$data['tablename']=$table_name;	
        $data['searchrecord']=$this->SearchTableQuery($table_name);
        
		$this->load->view('table_lists',$data);        	
		$this->load->view('table_search',$data);		
		$this->load->view('div_end');
		$this->load->view('div_end');		
		$this->load->view('footer'); 
    }
    
    public function SearchTableQuery($table_name)
    {       
       $srow=$this->input->post("startrow");       
       $erow=$this->input->post("stoprow");
       $timestamp=$this->input->post("starttime");      
       $column=$this->input->post("column");       
       $this->load->model('hbase_table_model', 'table');
       $count=100;
       $records= $this->table->search_table($table_name,$srow,$erow,$timestamp,$column,$count);
       
       if(is_array($records))
        {       
            $result="";
            foreach($records as $index=>$cols){            
                foreach($cols->columns as $key=>$vals)
                 {  
                    $row=$cols->row;                          
                    $column=explode(":",$key);                        
                    $value=$vals->value;                         
                    $value=json_encode($value);                        
                    $result=$result."{\"row\":\"".$row."\",\"columnfamily\":\"".$column[0]."\",\"columnqualifier\":\"".$column[1];
                    $result=$result."\",\"timestamp\":\"".$vals->timestamp."\",\"value\":".$value."},";
                  }
             }
              
            $result=rtrim($result,",");
            $result= '['.$result.']';
            
        }
        else
        {
            $result='{"row":"no record"}'; 
        } 
        
        return($result);       
        
    }
    
    
    
    public function UpdateRecords($table_name)
    {
       $mutation=$this->input->get("models");      
       $this->load->model('hbase_table_model', 'table');      
       $mutationarr=json_decode($mutation,true);
       $row=$mutationarr[0]["row"];
       $timestamp=intval($mutationarr[0]["timestamp"]);       
       $column=$mutationarr[0]["columnfamily"].":".$mutationarr[0]["columnqualifier"];       
       $value=$mutationarr[0]["value"];  
       $mutations = array(  
        new Mutation( array(
        'isDelete'=>0,  
        'column' => $column,  
        'value' => $value          
        ) ),  
        );
       $colres=explode(",",$this->GetColumn($table_name));         
       if(in_array($mutationarr[0]["columnfamily"],$colres))
       {                   
          $result=$this->table->mutate_rowts($table_name,$row,$mutations,$timestamp);
          $mutationarr[0]["result"]=$result;
       }
       else
       {
          $mutationarr[0]["result"]="column family not exist";
       }        
          
       echo(json_encode($mutationarr));        
    }
    
     public function DestroyRecords($table_name)
    {
       $mutation=$this->input->get("models");
       $callback=$this->input->get("callback");
       $this->load->model('hbase_table_model', 'table');      
       $mutationarr=json_decode($mutation,true);
       $row=$mutationarr[0]["row"];
       $timestamp=intval($mutationarr[0]["timestamp"]);        
       $column=$mutationarr[0]["columnfamily"].":".$mutationarr[0]["columnqualifier"];
       $value=$mutationarr[0]["value"];  
       $mutations = array(  
        new Mutation( array(
        'isDelete'=>1,  
        'column' => $column,  
        'value' => $value          
        ) ),  
        );   
       $result=$this->table->mutate_rowts($table_name,$row,$mutations,$timestamp);
       $mutationarr[0]["result"]=$result;
       echo(json_encode($mutationarr));
        
    }
    
    
    public function ListTableRecords($table_name)
    {
        
		$this->load->model('hbase_table_model','table');
        $this->table->headnav();
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');		
		$data['tablename']=$table_name;	
		$this->load->view('table_lists',$data);  
        $columns=$this->GetColumn($table_name);
        $columns=explode(",",$columns);
        $data['column']=$columns[0];      	
		$this->load->view('table_records',$data);		
		$this->load->view('div_end');
		$this->load->view('div_end');		
		$this->load->view('footer');
    }
    public function TruncateTable($table_name)
    {
      if($this->session->userdata('group')=='admin'){ 
        $this->load->model('hbase_table_model', 'table');
        $result=$this->table->truncate_table($table_name);
        echo($result);
       }
       else echo "sorry,No authority!";  
    }
    
    public function DelTable($table_name)
    { 
      if($this->session->userdata('group')=='admin'){
        $this->load->model('hbase_table_model', 'table');
        $this->table->disable_table($table_name);
        $result=$this->table->delete_table($table_name);
        echo($result);
         }
       else echo "sorry,No authority!";
    }
    
    public function DelAllTable()
    {
       if($this->session->userdata('group')=='admin'){ 
           $tables=$this->input->post("tables");
           $tablesarr=explode(";",$tables);
           foreach($tablesarr as $tablename)
            {
               $this->DelTable($tablename); 
            }
            
           echo "tables deleted success";
       }
       else echo "sorry,No authority!";
        
    }
}

?>