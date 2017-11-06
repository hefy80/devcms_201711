	<?php
class StoryModel extends Model{
//	protected $connection = 'DB_ZT';
	protected $trueTableName = 'zt_story'; 

	/**
	 * 获取指定项目的故事列表
	 */
	public function getProjectStorys($projectid){
		if (!$projectid)
			return false;

		$res = $this->query("
			select s.`id`, s.`title`, s.`pri`
			from zt_projectstory ps, zt_story s
			where 
				ps.`story` = s.`id` and
				ps.`project` = '$projectid' and
				s.`deleted` = '0'
			order by s.`pri` desc, s.`id` asc
			");
		
		return (!$res) ? false : $res;
	}

	/**
	 * 获取指定外部bug列表
	 */
	public function getBugOutsideStat($fresh=false){
		$res = S(KEY_STORY_BUGONLINESTAT);
		if ($res && count($res)>0 && !$fresh)
			return $res;

		$restmp = $this->query("
			select 
				s.id, m.name, s.title, s.closedDate, s.closedReason, 
				unix_timestamp(case when s.closedDate='0000-00-00' then now() else s.closedDate end) as timestamp
			from 
				zentao.zt_story s left join zentao.zt_module m on (s.module = m.id)
			where 
				s.product=9 and s.title like '%三线问题%' and s.deleted = '0' and
				(s.`closedDate` >= '2016-01-01' or s.`closedDate` = '0000-00-00')
			order by s.id desc
			");

		foreach ($restmp as $k => $v) {
			$t=getdate($v['timestamp']);

			//考核月是从上月的26日到本月的25日
			$m0 = ($t['mday'] <= 25) ? $t['mon'] : $t['mon']+1;
			$m = ($m0 <= 12) ? $m0 : 1;
			$y = ($m0 <= 12) ? $t['year'] : $t['year']+1;

			if ($v['closedReason'] == 'bug'){
				$res[$y]['stat'][$m]['stat'][$v['name']]['bug']+=1;
				$res[$y]['stat'][$m]['stat'][$v['name']]['buglist'][]=$v['id'];
			}
			else{
				$res[$y]['stat'][$m]['stat'][$v['name']]['nobug']+=1;
				$res[$y]['stat'][$m]['stat'][$v['name']]['nobuglist'][]=$v['id'];
			}
		}

		foreach ($res as $k => $v) {
			foreach ($v['stat'] as $kk => $vv) {
				$res[$k]['stat'][$kk]['count'] = count($vv['stat']);
				$res[$k]['count'] += count($vv['stat']);
			}
		}
		
	//	dump($res['2016']['stat']['11']['stat']); exit();
		S(KEY_STORY_BUGONLINESTAT,$res,CACHE_TIME_DEFAULT);
		return (!$res) ? false : $res;
	}

	/**
	 * 获取指定非正常运营列表
	 */
	public function getAccidentStat($fresh=false){
		$res = S(KEY_KPI_ACCIDENT);
		if ($res && count($res)>0 && !$fresh)
		 	return $res;

		$restmp = $this->query("
			select 
				s.id, s.title, unix_timestamp(s.openedDate) as timestamp, 
				s.mailto, s.reviewedBy
			from 
				zentao.zt_story s
			where 
				s.product = 39 and s.deleted = '0'
			order by s.openedDate desc
			");

		unset($res);
		foreach ($restmp as $k => $v) {
			//更换特定的“部门”名称(暂时用这种方法)
			$v['reviewedBy'] = str_replace('_SA_', '架构', $v['reviewedBy']);
			$v['reviewedBy'] = str_replace('_SE_', '工程', $v['reviewedBy']);
			$v['reviewedBy'] = str_replace('_RD_', '研发', $v['reviewedBy']);
			$v['reviewedBy'] = str_replace('_QA_', '测试', $v['reviewedBy']);
			$v['reviewedBy'] = str_replace('_PO_', '产品', $v['reviewedBy']);
			$v['mailto'] = str_replace('_SA_', '架构', $v['mailto']);
			$v['mailto'] = str_replace('_SE_', '工程', $v['mailto']);
			$v['mailto'] = str_replace('_RD_', '研发', $v['mailto']);
			$v['mailto'] = str_replace('_QA_', '测试', $v['mailto']);
			$v['mailto'] = str_replace('_PO_', '产品', $v['mailto']);

			//确定问题的归属考核周期（考核月是从上月的26日到本月的25日）
			$t=getdate($v['timestamp']);

			$m0 = ($t['mday'] <= 25) ? $t['mon'] : $t['mon']+1;
			$m = ($m0 <= 12) ? $m0 : 1;
			$y = ($m0 <= 12) ? $t['year'] : $t['year']+1;

			$res[$y]['stat'][$m]['stat'][]=$v;
		}

		foreach ($res as $k => $v) {
			foreach ($v['stat'] as $kk => $vv) {
				$res[$k]['stat'][$kk]['count'] = count($vv['stat']);
				$res[$k]['count'] += count($vv['stat']);
			}
		}
//		echo json_encode($res);exit;
		S(KEY_KPI_ACCIDENT,$res,CACHE_TIME_DEFAULT);
		return (!$res) ? false : $res;
	}

	/**
	 * 获取二线统计列表
	 */
	public function getLine2Stat($fresh=false){
		$res = S(KEY_STORY_LINE2STAT);
		if ($res && count($res)>0 && !$fresh)
			return $res;

		$restmp = $this->query("
			select 
				s.`id`, s.`title`, 
				unix_timestamp(min(case when a.`product` = ',8,' then a.`date` else now() end)) as `begindate`,
				unix_timestamp(min(case when a.`action` != 'opened' and a.`product` = ',8,' then a.`date` else now() end)) as `respdate`,
				unix_timestamp(min(case when a.`action` != 'opened' and a.`product` = ',8,' then a.`date` else now() end)) - unix_timestamp(min(case when a.`product` = ',8,' then a.`date` else now() end)) as `resptime`,
				unix_timestamp(min(case when a.`action` = 'closed' and a.`product` = ',8,' then a.`date` else now() end)) as `donedate`,
				unix_timestamp(min(case when a.`action` = 'closed' and a.`product` = ',8,' then a.`date` else now() end)) - unix_timestamp(min(case when a.`product` = ',8,' then a.`date` else now() end)) as `donetime`
			from 
				zt_story s 
				left join zt_action a on (s.`id` = a.`objectID` and a.`objectType` = 'story')
			where 
				s.`product` = '8' and a.`objectType` = 'story' and
				(s.`closedDate` >= '2016-01-01' or s.`closedDate` = '0000-00-00')
			group by s.`id` 
			order by s.`id` desc");

		foreach ($restmp as $k => $v) {
			//开始时间小于本月26日，并且完成时间大于等于上个月26日，都算到本月
  			$m1 = strtotime(date("Y-m-01",$v['begindate']));
//  				echo "m1:".date("Y-m-d H:i:s",$m1)."<br/>";
  			while ($m1 <= strtotime(date("Y-m-01",$v['donedate'])." +1 month"))
  			{	
  				$mS = strtotime(date("Y-m-01",$m1)." -1 month +25 day");
  				$m26 = $m1 + 25*24*3600;
//  				echo "start:".date("Y-m-d H:i:s",$mS)."; end:".date("Y-m-d H:i:s",$m26)."<br/>"; exit();
  				if ($v['begindate'] < $m26 && $v['donedate'] >= $mS){
  					$res[date("Y",$m1)]['stat'][date("m",$m1)]['count']+=1;
  					$res[date("Y",$m1)]['stat'][date("m",$m1)]['resptimeall']+=$v['resptime'];
  					$res[date("Y",$m1)]['stat'][date("m",$m1)]['donetimeall']+=$v['donetime'];
  					$res[date("Y",$m1)]['stat'][date("m",$m1)]['list'][]=array('id'=>$v['id'],'time'=>round($v['donetime']/3600,2));
   				}
  				$m1 = strtotime(date("Y-m-01",$m1)." +1 month");
//  				echo "m1s:".date("Y-m-d H:i:s",$m1)."<br/>"; exit(0);
  			}
//  			dump($res); exit(0);
		}

		foreach ($res as $k => $v) {
			$res[$k]['count'] = count($v['stat']);
		}
		
//		dump($res['2016']['stat']['11']['list']); exit();
		S(KEY_STORY_LINE2STAT,$res,CACHE_TIME_DEFAULT);
		return (!$res) ? false : $res;
	}

	/**
	 * 获取三线统计列表
	 */
	public function getLine3Stat($fresh=false){
		$res = S(KEY_STORY_LINE3STAT);
		if ($res && count($res)>0 && !$fresh)
			return $res;

		$restmp = $this->query("
			select 
				s.`id`, s.`title`, m.`name` as `module`,
				unix_timestamp(min(case when a.`product` = ',9,' then a.`date` else now() end)) as `begindate`,
				unix_timestamp(min(case when a.`action` != 'opened' and a.`product` = ',9,' then a.`date` else now() end)) as `respdate`,
				unix_timestamp(min(case when a.`action` = 'closed' and a.`product` = ',9,' then a.`date` else now() end)) as `donedate`,
				unix_timestamp(min(case when a.`action` = 'closed' and a.`product` = ',9,' then a.`date` else now() end)) - unix_timestamp(min(case when a.`product` = ',9,' then a.`date` else now() end)) as `donetime`
			from 
				zt_story s 
				left join zt_action a on (s.`id` = a.`objectID` and a.`objectType` = 'story')
				left join zt_module m on (s.`module` = m.id)
			where 
				s.`product` = '9' and a.`objectType` = 'story' and s.`title` like '%三线问题%' and
				(s.`closedDate` >= '2016-01-01' or s.`closedDate` = '0000-00-00')
			group by s.`id` 
			order by s.`id` desc");
//		dump($restmp); exit(0);

		foreach ($restmp as $k => $v) {
			//开始时间小于本月26日，并且完成时间大于等于上个月26日，都算到本月
  			$m1 = strtotime(date("Y-m-01",$v['begindate']));
//  				echo "m1:".date("Y-m-d H:i:s",$m1)."<br/>";
  			while ($m1 <= strtotime(date("Y-m-01",$v['donedate'])." +1 month"))
  			{	
  				$mS = strtotime(date("Y-m-01",$m1)." -1 month +25 day");
  				$m26 = $m1 + 25*24*3600;
  				if ($v['begindate'] < $m26 && $v['donedate'] >= $mS){
  					$res[date("Y",$m1)]['stat'][date("m",$m1)]['stat'][$v['module']]['count']+=1;
  					$res[date("Y",$m1)]['stat'][date("m",$m1)]['stat'][$v['module']]['timeall']+=$v['donetime'];
  					$res[date("Y",$m1)]['stat'][date("m",$m1)]['stat'][$v['module']]['list'][]=array('id'=>$v['id'],'time'=>round($v['donetime']/3600,2));
  				}
  				$m1 = strtotime(date("Y-m-01",$m1)." +1 month");
//  				echo "m1s:".date("Y-m-d H:i:s",$m1)."<br/>"; exit(0);
  			}
//  			dump($res); exit(0);
		}

		foreach ($res as $k => $v) {
			foreach ($v['stat'] as $kk => $vv) {
				$res[$k]['stat'][$kk]['count'] = count($vv['stat']);
				$res[$k]['count'] += count($vv['stat']);
			}
		}
		
//		dump($res['2016']['stat']['11']['stat']); exit();
		S(KEY_STORY_LINE3STAT,$res,CACHE_TIME_DEFAULT);
		return (!$res) ? false : $res;
	}

	/**
	 * 获取二、三线问题处理进度统计
	 */
	public function getLineStat($line){
		if ($line!=2 && $line!=3)
			$line=3;

		$buf = ($line==2) ? 'line2list' : 'line3list';
/*		$res = S($buf);
		if ($res) 
			return $res;*/
		
		$response_t = ($line==2) ? 2 : 24; //2线响应超2小时，3线超24小时，要单独统计次数
		$done_t = ($line==2) ? 8 : 14; //2线解决超8小时，3线超14天，要单独统计次数

		$sid = ($line==2) ? 8 : 9;
		$res['list'] = $this->getLineStoryList($sid);
		if(is_array($res['list']) && count($res['list'])>0)
		{
			foreach ($res['list'] as $key=>$val)
			{
				if ($line==3 && substr_compare($val['title'],'【三线',0,9)!=0)
				{
//					echo substr($val['title'],0,9);
					continue;
				}

				//计算每条记录的响应时长、处理时长
				$resp_interval=floor(($val['responsedate']-$val['begindate'])/3600);
				$done_interval=floor(($val['donedate']-$val['begindate'])/(3600*24)); //单位:天
				if ($line==2)
					$done_interval=$done_interval*24; //单位:小时

				$res['list'][$key]['resp_interval']=$resp_interval;
				$res['list'][$key]['done_interval']=$done_interval;

				//获取开始日期，以便按问题进入的日期来划分统计阶段
				$beginDate=getdate($val['begindate']);
				$year=$beginDate['year'];
				$month=$beginDate['mon'];
				switch ($month){
					case 1:
					case 2:
					case 3:
						$quarter='Q1';
						break;
					case 4:
					case 5:
					case 6:
						$quarter='Q2';
						break;
					case 7:
					case 8:
					case 9:
						$quarter='Q3';
						break;
					case 10:
					case 11:
					case 12:
						$quarter='Q4';
						break;
				}

				//按年、季度、月汇总，建立统计数据树形存储结构
				$res['staty'][$year]['year']=$year;
				$res['staty'][$year]['statq'][$quarter]['quarter']=$quarter;
				$res['staty'][$year]['statq'][$quarter]['statm'][$month]['month']=$month;

				//问题数量统计，以便算平均值
				$res['staty'][$year]['count']+=1;
				$res['staty'][$year]['statq'][$quarter]['count']+=1;
				$res['staty'][$year]['statq'][$quarter]['statm'][$month]['count']+=1;

				//问题响应时长统计
				$res['staty'][$year]['resp_sum']+=$resp_interval;
				$res['staty'][$year]['statq'][$quarter]['resp_sum']+=$resp_interval;
				$res['staty'][$year]['statq'][$quarter]['statm'][$month]['resp_sum']+=$resp_interval;

				//问题解决时长统计
				$res['staty'][$year]['done_sum']+=$done_interval;
				$res['staty'][$year]['statq'][$quarter]['done_sum']+=$done_interval;
				$res['staty'][$year]['statq'][$quarter]['statm'][$month]['done_sum']+=$done_interval;

				//超过限定时间未响应问题数量统计
				if ($resp_interval > $response_t){
					$res['staty'][$year]['response_t']+=1;
					$res['staty'][$year]['statq'][$quarter]['response_t']+=1;
					$res['staty'][$year]['statq'][$quarter]['statm'][$month]['response_t']+=1;
				}

				//超过限定时间未解决问题数量统计
				if ($done_interval > $done_t){
					$res['staty'][$year]['done_t']+=1;
					$res['staty'][$year]['statq'][$quarter]['done_t']+=1;
					$res['staty'][$year]['statq'][$quarter]['statm'][$month]['done_t']+=1;
				}

				//平均响应时长
				$res['staty'][$year]['resp_avg']=floor($res['staty'][$year]['resp_sum']/$res['staty'][$year]['count']);
				$res['staty'][$year]['statq'][$quarter]['resp_avg']=floor($res['staty'][$year]['statq'][$quarter]['resp_sum']/$res['staty'][$year]['statq'][$quarter]['count']);
				$res['staty'][$year]['statq'][$quarter]['statm'][$month]['resp_avg']=floor($res['staty'][$year]['statq'][$quarter]['statm'][$month]['resp_sum']/$res['staty'][$year]['statq'][$quarter]['statm'][$month]['count']);

				//平均解决时长
				$res['staty'][$year]['done_avg']=floor($res['staty'][$year]['done_sum']/$res['staty'][$year]['count']);
				$res['staty'][$year]['statq'][$quarter]['done_avg']=floor($res['staty'][$year]['statq'][$quarter]['done_sum']/$res['staty'][$year]['statq'][$quarter]['count']);
				$res['staty'][$year]['statq'][$quarter]['statm'][$month]['done_avg']=floor($res['staty'][$year]['statq'][$quarter]['statm'][$month]['done_sum']/$res['staty'][$year]['statq'][$quarter]['statm'][$month]['count']);
			}

			foreach ($res['staty'] as $ky=>$vy){
				foreach ($vy['statq'] as $kq=>$vq){
					$res['staty'][$ky]['months']+=count($vq['statm']);
					$res['staty'][$ky]['statq'][$kq]['months']+=count($vq['statm']);
				}
			}
		}
		S($buf,$res,CACHE_TIME_DEFAULT);
		return $res;
	}

	private function getLineStoryList($sid){
		$res = $this->query("
			select 
				s.`id`, s.`title`, s.`module`,
				unix_timestamp(min(case when a.`product` = ',".$sid.",' then a.`date` else now() end)) as `begindate`,
				unix_timestamp(min(case when a.`action` != 'opened' and a.`product` = ',".$sid.",' then a.`date` else now() end)) as `responsedate`,
				unix_timestamp(min(case when a.`action` = 'closed' and a.`product` = ',".$sid.",' then a.`date` else now() end)) as `donedate`
			from 
				zt_story s 
				left join zt_action a on (s.`id` = a.`objectID` and a.`objectType` = 'story')
			where 
				s.`product` = ".$sid." and a.`objectType` = 'story'
			group by s.`id` order by `begindate` desc, s.`id` desc");
		
		return (!$res) ? false : $res;
	}

	function getProducts($fresh=false){
		//优先从缓存获取
		$res = S(KEY_STORY_PRODUCTS);
		if ($res && count($res)>0 && !$fresh)
			return $res;

		//重新从DB获取并更新缓存
		$bugs = $this->query("
			select 
				pd.id as '编号', pd.name as '产品', 
				sum(case when bg.deleted = '0' and bg.status != 'closed' then 1 else 0 end) as '当前bug'
			from 
				zt_product pd left join zt_bug bg on pd.id = bg.product
			where 
				pd.deleted = '0' and pd.status != 'closed' and pd.id not in (8,9,15,19,20,21,22,29)
			group by pd.id");

		foreach ($bugs as $key => $value) {
				$res[$value['编号']]=$value;
				$res[$value['编号']]['待做']=0;
				$res[$value['编号']]['开发中']='';
		}

		$storys = $this->query("
			select 
				pd.id, st.id as sid, st.title, st.stage
			from 
				zt_product pd, zt_story st
			where 
				pd.id = st.product and st.deleted = '0' and st.status != 'closed' and
				pd.deleted = '0' and pd.status != 'closed' and pd.id not in (8,9,15,19,20,21,22,29)
			order by pd.id, st.stage, st.id");

		foreach ($storys as $key => $value) {
			if ($value['stage']=='' || $value['stage']=='wait' || $value['stage']=='planned')
			{
				$res[$value['id']]['待做']+=1;
			}
			else if ($value['stage']!='release')
			{
				$res[$value['id']]['开发中'][]=array('id'=>$value['sid'],'title'=>$value['title']);
			}
		}

		S(KEY_STORY_PRODUCTS,$res,CACHE_TIME_DEFAULT);
		return (!$res) ? false : $res;
	}

	function get3lineStatTmp(){
		$res = $this->query("
			select
				round(avg(hour(timediff(`responsedate`,`begindate`)))) as `resp`,
				round(avg(datediff(`donedate`,`begindate`))) as `done`,
				sum(case when hour(timediff(`responsedate`,`begindate`))>24 then 1 else 0 end) as `resp24`,
				sum(case when datediff(`donedate`,`begindate`)>14 then 1 else 0 end) as `done14`
			from
			(select 
				s.`id`, 
				min(case when a.`product` = ',9,' then a.`date` else now() end) as `begindate`,
				min(case when a.`action` != 'opened' and a.`product` = ',9,' then a.`date` else now() end) as `responsedate`,
				min(case when a.`action` = 'closed' and a.`product` = ',9,' then a.`date` else now() end) as `donedate`
			from 
				zt_story s 
				left join zt_action a on (s.`id` = a.`objectID` and a.`objectType` = 'story')
			where 
				s.`product` = 9 and a.`objectType` = 'story'
			group by s.id 
			having min(a.`date`) > '2014-01-01') t");
		
		return (!$res) ? false : $res[0];
	}
}
?>