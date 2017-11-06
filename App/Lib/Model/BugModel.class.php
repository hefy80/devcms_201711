<?php
class BugModel extends Model{
//	protected $connection = 'DB_ZT';
	protected $trueTableName = 'zt_bug'; 

	public function getBugStatQA(){
		$res = $this->
			field('year(`openedDate`), month(`openedDate`), `product`, `openedBy`, count(*)')->
			where("`deleted`='0' and `resolution` in ('fixed','postponed')")->
			group('year(`openedDate`), month(`openedDate`), `product`, `openedBy`')->select();
		
		return (!$res) ? false : $res;
	}

	public function getBugStatDEV(){
		$res = $this->
			field('year(`openedDate`), month(`openedDate`), `product`, `resolvedBy`, count(*)')->
			where("`deleted`='0' and `resolution` in ('fixed','postponed')")->
			group('year(`openedDate`), month(`openedDate`), `product`, `resolvedBy`')->select();
		
		return (!$res) ? false : $res;
	}

	private function getBugList(){
		$res = S('buglist');
		if ($res) 
			return $res;
		$res = $this->
			where("deleted='0'")->
			order('account asc')->select();

		if(is_array($res) && count($res)>0)
		{
			foreach ($res as $key=>$val)
			{
				$userDept = $_dept->getDept($val['dept']);
				$val['deptname'] = $userDept['name'];
				$_userList[$val['id']] = $val;
				$_userList_P[$val['dept']][$val['id']] = $val;
			}
		}
		return $_userList;
	}
}
?>