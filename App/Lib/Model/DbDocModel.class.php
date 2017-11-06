<?php
class DbDocModel extends Model{
	protected $connection = 'DB_DOC';

	public function getTabList($database){
		$res = S('tablist_'.$database);
		if (is_array($res) && count($res)>0)
			return $res;

		$res = $this->createDbDoc($database,"tablist");
		return (is_array($res)) ? $res : false;
	}

	public function getTabIndex($database){
		$res = S('tabindex_'.$database);
		if (is_array($res) && count($res)>0)
			return $res;

		$res = $this->createDbDoc($database,"tabindex");
		return (is_array($res)) ? $res : false;
	}

	public function getColList($database,$table){
		$res = S('collist_'.$database.'_'.$table);
		if (is_array($res) && count($res)>0)
			return $res;

		return false;
	}

	private function createDbDoc($database, $returntype="tablist"){
		$res = $this->query("SELECT
				t.table_name,
				t.table_comment,
				t.engine,
				t.create_time,
				t.update_time,
				c.column_name,
				c.ordinal_position,
				c.column_default,
				c.is_nullable,
				c.column_type,
				c.character_set_name,
				c.column_key,
				c.extra,
				c.column_comment
			FROM
				information_schema.tables t,
				information_schema.columns c
			WHERE 
				t.table_schema = c.table_schema AND
				t.table_name = c.table_name AND
				t.table_schema = '$database'
			order by t.table_name, c.ordinal_position");

		$curTable='';
		unset($tableIndex);
		if(is_array($res) && count($res)>0)
		{
			foreach ($res as $key=>$val)
			{
				if ($val['table_name'] != $curTable)
				{
					if (is_array($columList) && count($columList)>0)
					{
						S('collist_'.$database.'_'.$curTable,$columList,3600);
						unset($columList);
					}
					$curTable = $val['table_name'];

					$idxarr = explode("_",$curTable);
					$indexkey = ($idxarr[0] == "wanbu") ? $idxarr[1] : "other";
					if (!$tableIndex[$indexkey]) 
						$tableIndex[$indexkey] = $curTable;

					$tableList[$curTable]['table_comment'] = $val['table_comment'];
					$tableList[$curTable]['engine'] = $val['engine'];
					$tableList[$curTable]['create_time'] = $val['create_time'];
					$tableList[$curTable]['update_time'] = $val['update_time'];
				}
				$curColumn = $val['column_name'];
				$columList[$curColumn]['ordinal_position'] = $val['ordinal_position'];
				$columList[$curColumn]['column_default'] = $val['column_default'];
				$columList[$curColumn]['is_nullable'] = $val['is_nullable'];
				$columList[$curColumn]['column_type'] = $val['column_type'];
				$columList[$curColumn]['character_set_name'] = $val['character_set_name'];
				$columList[$curColumn]['column_key'] = $val['column_key'];
				$columList[$curColumn]['extra'] = $val['extra'];
				$columList[$curColumn]['column_comment'] = $val['column_comment'];
			}
		}

		S('collist_'.$database.'_'.$curTable,$columList,3600);
		S('tablist_'.$database,$tableList,3600);
		S('tabindex_'.$database,$tableIndex,3600);

		switch ($returntype) {
			case "tablist": return $tableList;
			case "tabindex": return $tableIndex;
		}
		return $tableList;
	}
}
?>