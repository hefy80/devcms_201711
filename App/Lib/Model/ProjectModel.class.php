<?php
class ProjectModel extends Model{
//	protected $connection = 'DB_ZT';
	protected $trueTableName = 'zt_project'; 

	/**
	 * 获取指定项目的故事列表
	 */
	function getSprintProjects(){
		$res = $this->
			field('id, name, pri, begin, end, days, PM, PO, team')->
			where("status != 'done' and id not in (23,26,37,99,117)")->
			order('id')->select();

		return (!$res) ? false : $res;
	}

	function getProjects(){
		//优先从缓存获取
		$res = S('devcms:projects');
		if ($res && count($res)>0)
//echo "1"; dump($res); exit(0);
			return $res;

		//重新从DB获取并更新缓存
		$res = $this->query("
			select
				pj.id as '编号', pj.name as '名称', 
				pj.begin as '开始日期', pj.end as '计划结束日期', 
				datediff(current_date(),pj.begin) as '实际天数', datediff(pj.end,pj.begin) as '项目天数',
				sum(ta.estimate) as '预期人时', sum(ta.consumed) as '消耗人时'
			from 
				zt_project pj, zt_task ta
			where 
				pj.id = ta.project and ta.deleted = '0' and
				pj.status != 'done' and pj.id not in (23,26,37,99,117) and pj.deleted = '0'
				group by pj.id");

		foreach ($res as $key => $value) {
			$processT = ($value['项目天数']==0) ? $value['实际天数'] : $value['实际天数']/$value['项目天数'];
			$processC = ($value['预期人时']==0) ? $value['消耗人时'] : $value['消耗人时']/$value['预期人时'];

			if ($processT<0 && $processC<=0) $res[$key]['状态'] = '未启动';
			else if ($processT<0 && $processC>0) $res[$key]['状态'] = '提前启动';
			else if ($processT>1 && $processC<1) $res[$key]['状态'] = '延迟，人时还剩';
			else if ($processT>1 && $processC>=1) $res[$key]['状态'] = '延迟，人时已超';
			else if ($processT>=0 && $processC>0 && abs($processT-$processC)<=0.2) $res[$key]['状态'] = '正常';
			else if ($processT>=0 && $processC>0 && $processT-$processC<-0.2) $res[$key]['状态'] = '预估人时不足';
			else if ($processT>=0 && $processC<=1 && $processT-$processC>0.2) $res[$key]['状态'] = '缓慢';
			else if ($processT>=0 && $processC>1 && $processT-$processC>0.2) $res[$key]['状态'] = '缓慢，人时已超';
			else $res[$key]['状态'] = '异常';

			unset($res[$key]['实际天数']);
			unset($res[$key]['项目天数']);
		}

		S('devcms:projects',$res,600);
//echo "2"; dump($res); exit(0);
		return (!$res) ? false : $res;
	}

}
?>