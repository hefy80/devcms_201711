<?php
// 本类由系统自动生成，仅供测试用途
class KpiAction extends BaseAction {
    public function index(){
		//获取技术中心的三级部门
		$story = D('Bug');
		$res = $story->getBugStatDEV();

//		$res = date('Y-m-d H:i:s','1397122429');
		dump($res); exit();
		$this->assign('line3Stat',$res);

		$res = $this->getExecutionList();
		$this->assign('ExecutionList',$res);
		
		$res = $this->getBugList();
		$this->assign('BugList',$res);
		
		$res = $this->getReleaseList();
		$this->assign('ReleaseList',$res);
		
		$this->display();
    }

	public function kpi(){
		header("Location: http://devcms.ppyx.com/index.php/Doc/index?path=RG9jdW1lbnRzL8bky%2FsvvKjQp7%2B8usux6te8Lm1k"); 
		exit();
	}

	public function line2(){
		$story = D('Story');
		$res = $story->getLine2Stat($this->_fresh);
		$this->assign('Line2',$res);

		$this->display();
	}

	public function line3(){
		$story = D('Story');
		$res = $story->getLine3Stat($this->_fresh);
		$this->assign('Line3',$res);

		$this->display();
	}

	public function accident(){
		$story = D('Story'); 
		$res = $story->getAccidentStat($this->_fresh);
		$this->assign('Accidents',$res);

		$this->display();
	}

	public function line(){
		$line = ($_REQUEST['lid']) ? $_REQUEST['lid'] : 3;

		$story = D('Story');
		$res = $story->getLineStat($line);
		$this->assign('Line',$res['staty']);

		$this->display();
	}

	public function bug(){
		$type = ($_REQUEST['type']) ? $_REQUEST['type'] : 0;

		if ($type == 1){ //外部bug
			$story = D('Story');
			$res = $story->getBugOutsideStat($this->_fresh);
			$this->assign('Bugs',$res);

			$this->display('bug_o');
		}
		else{ //内部bug
			$story = D('Bug');
			$res = $story->getBugStatDEV();
			$this->assign('Bug',$res);

			$this->display();
		}
	}

    private function getExecutionList(){
		$Executions = array(
			array('department'=>'规划部', 'finish'=>'90', 'all'=>'100', 'rate'=>'90%'),
			array('department'=>'产品部', 'finish'=>'80', 'all'=>'100', 'rate'=>'80%'),
			array('department'=>'研发部', 'finish'=>'90', 'all'=>'100', 'rate'=>'90%'),
			array('department'=>'测试部', 'finish'=>'70', 'all'=>'100', 'rate'=>'70%'),
			array('department'=>'工程部', 'finish'=>'90', 'all'=>'100', 'rate'=>'90%')
			);

		return $Executions;
	}

    private function getBugList(){
		$Executions = array(
			array('team'=>'系统组', 'manhour'=>'9000', 'bug_i'=>'90', 'rate_i'=>'15%', 'bug_o'=>'50', 'rate_o'=>'2%'),
			array('team'=>'支撑组', 'manhour'=>'9000', 'bug_i'=>'90', 'rate_i'=>'15%', 'bug_o'=>'50', 'rate_o'=>'2%'),
			array('team'=>'公众线', 'manhour'=>'9000', 'bug_i'=>'90', 'rate_i'=>'15%', 'bug_o'=>'50', 'rate_o'=>'2%'),
			array('team'=>'团队线', 'manhour'=>'9000', 'bug_i'=>'90', 'rate_i'=>'15%', 'bug_o'=>'50', 'rate_o'=>'2%'),
			array('team'=>'移动组', 'manhour'=>'9000', 'bug_i'=>'90', 'rate_i'=>'15%', 'bug_o'=>'50', 'rate_o'=>'2%')
			);
//		dump($Executions); exit;

		return $Executions;
	}

    private function getReleaseList(){
		$Executions = array(
			array('team'=>'系统组', 'delay'=>'5', 'all'=>'100', 'rate'=>'95%'),
			array('team'=>'支撑组', 'delay'=>'5', 'all'=>'100', 'rate'=>'95%'),
			array('team'=>'公众线', 'delay'=>'5', 'all'=>'100', 'rate'=>'95%'),
			array('team'=>'团队线', 'delay'=>'5', 'all'=>'100', 'rate'=>'95%'),
			array('team'=>'移动组', 'delay'=>'5', 'all'=>'100', 'rate'=>'95%')
			);
//		dump($Executions); exit;

		return $Executions;
	}
}
