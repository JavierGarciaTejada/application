<?php

defined("PROJECTPATH") OR die("Access denied");

class DataTables
{
	protected static $columns;
	public static $draw;
	public static $length;
	protected static $order;
	protected static $search;
	protected static $start;
	public static $recordsFiltered;
	public static $data;
	public static $recordsTotal;
	
	private static function request()
	{
		self::$columns = isset($_REQUEST['columns']) ? $_REQUEST['columns'] : '';
		self::$draw = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : '';
		self::$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
		self::$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
		self::$length = isset($_REQUEST['length']) ? $_REQUEST['length'] : '';
		self::$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	}
	
	private static function dataColumn()
	{
		$count = count(self::$columns);
		$columna = "";
		foreach (self::$columns as $key => $col)
		{
			if( $key === $count-1 )
			{
				$columna .= $col['name'];
			}
			else
			{
				$columna .= $col['name'] . ", ";
			}
		}
		return $columna;
	}
	
	private static function limit()
	{
		$limit = "";
		if ( isset(self::$start) && self::$length != -1 ) 
		{
			$limit = "LIMIT ". intval(self::$start) .", ". intval(self::$length);
		}
		return $limit;
	}
	
	private static function order()
	{
		$order = "";
		$count = count(self::$order);
		$column = "";
		if( is_array(self::$order) && isset(self::$order) )
		{
			for( $i = 0; $i < $count; $i++ )
			{
				$col = self::$order[$i]['column'];
				$dir = self::$order[$i]['dir'] === "asc" ? "ASC" : "DESC";
				if ( $i === $count-1 )
				{
					if( self::$columns[intval($col)]['orderable'] == 'true' )
					{
						$column .= self::$columns[intval($col)]['name'] ." ". $dir;
					}
				}
				else
				{
					if( self::$columns[intval($col)]['orderable'] == 'true' )
					{
						$column .= self::$columns[intval($col)]['name'] .", ". $dir;
					}
				}
			}
			$order = "ORDER BY ". $column;
		}
		return $order;
	}
	
	private static function filter()
	{
		$orLike = "";
		$andLike = "";
		$search = "";
		$val = isset(self::$search['value']) ? self::$search['value'] : '';
		$reg = isset(self::$search['regex']) ? self::$search['regex'] : '';
		$count = empty(self::$columns) ? 0 : count(self::$columns);
		
		for( $i = 0; $i < $count; $i++ )
		{
			$column = isset(self::$columns[$i]['name']) ? self::$columns[$i]['name'] : '';
			$isFind = isset(self::$columns[$i]['searchable']) ? self::$columns[$i]['searchable'] : '';
			$colFindValue = isset(self::$columns[$i]['search']['value']) ? self::$columns[$i]['search']['value'] : '';
			$colFindRegex = isset(self::$columns[$i]['search']['regex']) ? self::$columns[$i]['search']['regex'] : '';
			if( $isFind == "true" )
			{
				if( $val != '' )
				{
					if( $reg == "false" )
					{
						$orLike[] = $column ." LIKE '%". $val ."%'";
					}
					else
					{
						$lastChar = substr($val, -1);
						$firstChar = substr($val, 0, 1);
						if( $lastChar == "|" || $firstChar == "|" )
						{
							$val = preg_replace("/(\|*)$|^(\|*)/i", "",$val);
						}
						$orLike[] = $column ." RLIKE '". (!empty($val) ? $val : "." ) ."'";
					}
				}
					
				if( $colFindValue != '' )
				{
					if( $colFindRegex == "false" )
					{
						$andLike[] = $column ." LIKE '%". $colFindValue ."%'";
					}
					else
					{
						$lastChar = substr($colFindValue, -1);
						$firstChar = substr($colFindValue, 0, 1);
						if( $lastChar == "|" || $firstChar == "|" )
						{
							$colFindValue = preg_replace("/(\|*)$|^(\|*)/i", "",$colFindValue);
						}
						$andLike[] = $column ." RLIKE '". $colFindValue ."'";
					}
				}
			}
		}
		
		if( is_array($orLike) )
		{
			$orLike = "(". implode(' OR ', $orLike) .")";
		}
		
		if( is_array($andLike) )
		{
			$andLike = "(". implode(' AND ', $andLike) .")";
		}
		
		if( $orLike != '' && $andLike != '' )
		{
			$search = "WHERE ". $orLike ." OR ".$andLike;
		}
		elseif( $orLike != '' && $andLike == '' )
		{
			$search = "WHERE ". $orLike;
		}
		elseif( $andLike != '' && $orLike == '' )
		{
			$search = "WHERE ". $andLike;
		}
		return $search;
	}
	
	public static function onlyWhere()
	{
		self::request();
		$filter = self::filter();
		$order = self::order();
		$limit = self::limit();
		$where = (!empty($filter) ? $filter : "");
		return $where;
	}
	
	public static function whereLimit()
	{
		self::request();
		$filter = self::filter();
		$order = self::order();
		$limit = self::limit();
		$where = (!empty($filter) ? $filter : "") ." ". (!empty($order) ? $order : "") ." ". (!empty($limit) ? $limit : "");
		return $where;
	}
	
	public static function completeQuery($table)
	{
		self::request();
		$filter = self::filter();
		$order = self::order();
		$limit = self::limit();
		$where = " ". (!empty($filter) ? $filter ." " : "") . (!empty($order) ? $order." " : "") . (!empty($limit) ? $limit : "");
		$query = "SELECT ". self::dataColumn() ." FROM ". $table ." ". $where;
		return $query;
	}
	
	public static function resultDataTables()
	{
		return array(
			"draw" => self::$draw,
			"recordsTotal" => self::$recordsTotal,
			"recordsFiltered" => self::$recordsFiltered,
			"data" => self::$data
		);
	}
}
