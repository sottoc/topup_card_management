<?php //can use one row return result
class readonlyresultset 
{
	private $rs;
	private $foundrows;
	private $field;
	private $current_record;
	private $results;
	
	function __construct($rs) 
	{
		$this->rs = $rs;
		$this->current_record = 0;
		$this->results = $this->rs->fetchAll(PDO::FETCH_ASSOC);
		$this->foundrows = $this->queryFoundRows();
	}
	
	function getNext() 
	{
		for($i=$this->current_record;$i<$this->rs->rowCount();$i++)
		{
			$resultset = $this->results;
			$this->current_record++;
			if(isset($resultset[$i]))
				return $resultset[$i];
			else
				return false;
		}
	}
	
	function reset() 
	{
		$this->current_record = 0;
	}
	
	function seek($index=0)
    {
       $this->current_record = $index;
    }
	
	function rowCount() 
	{
		return $this->rs->rowCount();
	}
	
	function getFoundRows() 
	{
		return $this->foundrows;
	}
	
	function queryFoundRows()
	{
		$qry = "SELECT FOUND_ROWS()";
		global $conn;
		$result=$conn->prepare($qry);
		$result->execute();
		$result->bindColumn(1,$aResultFilterTotal);
		$result->fetch();
		return $aResultFilterTotal;
	}
	
	function toArray($key="")
	{
		$resultset = $this->rs;
		$rtn_array = array();
		if ($resultset->rowCount()>0)
		{
			while($row = $resultset->fetch())
			{
				if ( $key == "")
					$rtn_array[] = $row;
				else 
					$rtn_array[$row[$key]] = $row;
			}
		}
		return $rtn_array;
	}
}
?>