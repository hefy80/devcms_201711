<?php
class TaskModel extends Model{
//	protected $connection = 'DB_ZT';
	protected $trueTableName = 'zt_task'; 

	/**
	 * 获取指定项目的任务列表
	 */
	function getProjectTasks($projectid){
		if (!$projectid)
			return false;

		$res = $this->
			field('id, story, name, pri, estimate, consumed, left, deadline, status, assignedTo, finishedBy')->
			where("`project` = '$projectid' and `deleted` = '0'")->
			order('pri, id')->select();

		return (!$res) ? false : $res;
	}
}
?>