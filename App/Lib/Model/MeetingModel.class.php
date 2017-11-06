<?php
class MeetingModel extends Model{
	// 映射数据表中的部分字段
	function getMeetingsByDateRange($start, $endtime){
		$res = $this->field("visibility,addtionrule,timezone")
		->where("userid = '$userid'")
		->find();
		!$res && $res = false;
		
		return $res;
	}
	
	function getMeetingsByUserid($userid){
		if(!(int)$userid) return false;
		$userid = (int)$userid;
		return true;
	}
	
}
?>