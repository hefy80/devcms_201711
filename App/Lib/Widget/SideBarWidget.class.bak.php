<?php
class SideBarWidget extends Widget {
    private function initMenu()
    {
		//初始化固定菜单
        $menu = array(
			mb_convert_encoding('常用链接','UTF-8','gb2312') => array(
				'module' => 'Index',
				'icon' => 'icon-home',
				'url' => __APP__.'/Index'
			),
			mb_convert_encoding('通讯录','UTF-8','gb2312') => array(
				'module' => 'Team',
				'icon' => 'icon-user',
				'url' => __APP__.'/Team'
			),
			'KPI' => array(
				'module' => 'Kpi',
				'icon' => 'icon-pencil',
				'url' => '#',
				'subcount' => 2,
				'submenu' => array(
					mb_convert_encoding('二线统计','UTF-8','gb2312') => array(
						'func'=>'line',
						'param'=>'lid',
						'value'=>'2'
					),
					mb_convert_encoding('三线统计','UTF-8','gb2312') => array(
						'func'=>'line',
						'param'=>'lid',
						'value'=>'3'
					)
				)
			)
		);

		//初始化项目菜单
		$project = D('Project');
		unset($res);
		$res = $project->getSprintProjects();

		unset($menuTmp);
		$menuTmp['module']='Project';
		$menuTmp['icon']='icon-th-large';
		$menuTmp['url']='#';
		$menuTmp['subcount']=count($res);
		$menuTmp['submenu']=$res;
		$menu[mb_convert_encoding('项目','UTF-8','gb2312')]=$menuTmp;

 		//初始化md相关资料的目录（文档、软件、设备）
		unset($res);
		$res = array();
		$doc = D('Doc');
		$doc->createDirTree($res);

		foreach($res['children'] as $k => $v) 
		{
			unset($menuTmp);
			$menuTmp['module']='Doc';
			$menuTmp['icon']='icon-th-large';
			$menuTmp['subcount']=count($v['children']);
			$menuTmp['submenu']=$v['children'];
			foreach($menuTmp['submenu'] as $kk=>$vv)
			{
				$menuTmp['submenu'][$kk]['func']='index';
				$menuTmp['submenu'][$kk]['param']='path';
				$menuTmp['submenu'][$kk]['value']=urlencode(base64_encode($vv['path']));
			}
			$menu[$k]=$menuTmp;
		}
//dump($menu[mb_convert_encoding('文档','UTF-8','gb2312')]);exit();
		return $menu;
	}

	public function render($data){
		$data['menu']=$this->initMenu();

		return $this->renderFile('',$data);
	}
}
?>
