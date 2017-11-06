<?php
class DeptModel extends Model{
//	protected $connection = 'DB_ZT';
	protected $trueTableName = 'zt_dept'; 

	private $_deptList = array();	//全部部门列表的一维数组
	private $_deptList_P = array();	//部门以及他们的直属子部门的二维数组

	/**
	* 获取部门列表的树形结构
	* @return array
	*/
	public function getDeptTree(){
		$this->getDeptList($fresh);
		//优先从缓存获取
		$res = S('devcms:depttree');
		if ($res && count($res)>0)
			return $res;

		//重新从DB获取并更新缓存
		$res = array();
		$this->createDeptTree($res);
		S('devcms:depttree',$res,3600);

		return $res;
	}

	/**
	* 获取指定部门的下级部门列表的树形结构
	* @param integer $parentid 父节点部门id
	* @return array
	*/
	public function getDeptChilds($parentid=0){
		$res = S('deptlist_p');
		if (!$res || count($res)<=0)
			$res=$this->getDeptList();

		$res = $res[$parentid];
		return (!$res) ? false : $res;
	}

	/**
	* 获取指定部门的族谱线
	* @param integer $deptid 部门id
	* @return array
	*/
	public function getDeptPath($deptid){
		global $_deptList;

		$res = array();
		while ($deptid>0)
		{
			$res[] = $_deptList[$deptid];
			$deptid = $_deptList[$deptid]['parent'];
		}
		krsort($res);

		return (!$res) ? false : $res;
	}

	/**
	* 获取指定部门
	* @param integer $deptid 部门id
	* @return array
	*/
	public function getDept($deptid){
		global $_deptList;

		$res = $_deptList[$deptid];

		return (!$res) ? false : $res;
	}

	/**
	* 获取部门列表数组和直属子部门列表二维数组
	* @return array 全部部门列表数组
	*/
	private function getDeptList(){
		global $_deptList, $_deptList_P;
		$deptList = S('deptlist');
		$deptList_P = S('deptlist_p');
		if ($deptList && count($deptList)>0)
			return $deptList;

		if (count($_deptList)>0)
			return $_deptList;

//		unset($_deptList, $_deptList_P);
		$res = $this->
			field('`id`, `name`, `parent`, `grade`, `order`, `manager`')->
			order('`grade`, `order`')->select();

		if(is_array($res) && count($res)>0)
		{
			foreach ($res as $key=>$val)
			{
				$_deptList[$val['id']] = $val;
				$_deptList_P[$val['parent']][$val['id']] = $val;
			}
		}

		S('deptlist',$_deptList,3600);
		S('deptlist_p',$_deptList_P,3600);

		return $_deptList;
	}

	/**
	* 创建部门树形结构（递归）
	* @return array
	*/
	private function createDeptTree(&$node){
		if (!is_array($node))
			return false;

		//根节点处理
		if (count($node)==0)
		{
			$node['id'] = 0;
			$node['name'] = '啪呗';
		}

		//查找当前节点的孩子
		$res = $this->getDeptChilds($node['id']);
		$node['childnum'] = count($res);
		if(is_array($res) && count($res)<=0)
		{
			return true;	//叶子节点返回
		}

		//和孩子建立树形关系
		foreach ($res as $key=>$val)
		{
			$node['child'][$val['id']] = $this->createDeptTree($val);
		}

		return $node;
	}
}
?>