<?php

interface RowIf
{
	public function get($table_name, $row, $column);
	public function get_row($table_name, $row);
	
}

class Row extends CI_Model
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
		$this->socket = new TSocket($this->hbase_host, $this->hbase_port);
		$this->socket->setSendTimeout(30000);
		$this->socket->setRecvTimeout(30000);
		$this->transport = new TBufferedTransport($this->socket);
		$this->protocol = new TBinaryProtocol($this->transport);
		$this->hbase = new HbaseClient($this->protocol);
	}
	
	public function get($table_name, $row, $column)
	{
		try
		{
			$this->transport->open();
			$array = $this->hbase->get($table_name, $row, $column);
			$this->transport->close();
			return $array;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function get_row($table_name, $row)
	{
		try
		{
			$this->transport->open();
			$array = $this->hbase->getRow($table_name, $row, $column);
			$this->transport->close();
			return $array;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function get_row_with_columns($table_name, $row, $columns)
	{
		try
		{
			$this->transport->open();
			$array = $this->hbase->getRowWithColumns($table_name, $row, $columns);
			$this->transport->close();
			return $array;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function get_row_timestamp($table_name, $row, $timestamp)
	{
		try
		{
			$this->transport->open();
			$array = $this->hbase->getRowTs($table_name, $row, $timestamp);
			$this->transport->close();
			return $array;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function get_row_columns_timestamp($table_name, $row, $columns, $timestamp)
	{
		try
		{
			$this->transport->open();
			$array = $this->hbase->getRowWithColumnsTs($table_name, $row, $columns, $timestamp);
			$this->transport->close();
			return $array;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function mutate_row($table_name, $row, $mutations);
	{
		try
		{
			$this->transport->open();
			$this->hbase->mutateRow($table_name, $row, $mutations);
			$this->transport->close();
			return TRUE;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function mutate_row_timestamp($table_name, $row, $mutations, $timestamp)
	{
		try
		{
			$this->transport->open();
			$this->hbase->mutateRowTs($table_name, $row, $mutations, $timestamp);
			$this->transport->close();
			return TRUE;
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
}

?>