<?php
// 本类由系统自动生成，仅供测试用途
class DbDocAction extends BaseAction {
    public function index(){
		$database = ($_REQUEST['db']) ? $_REQUEST['db'] : 'test';

		$dbdoc = D('DbDoc');

		$res = $dbdoc->getTabIndex($database);
		$this->assign('TabIndex',$res);
//		dump($res); exit();

		$res = $dbdoc->getTabList($database);
		$this->assign('TabList',$res);
//		dump($res); exit();

		$res = $dbdoc->getColList($database,'wanbu_data_user');
		$this->assign('ColList',$res);
//		dump($res); 

		$this->display();
    }
 }
