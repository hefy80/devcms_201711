<?php
class SideBarWidget extends Widget {
    private function initMenu()
    {
		//��ʼ���̶��˵�
        $menu = array(
			mb_convert_encoding('��������','UTF-8','gb2312') => array(
				'module' => 'Index',
				'icon' => 'icon-home',
				'url' => __APP__.'/Index'
			),
			mb_convert_encoding('ͨѶ¼','UTF-8','gb2312') => array(
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
					mb_convert_encoding('����ͳ��','UTF-8','gb2312') => array(
						'func'=>'line',
						'param'=>'lid',
						'value'=>'2'
					),
					mb_convert_encoding('����ͳ��','UTF-8','gb2312') => array(
						'func'=>'line',
						'param'=>'lid',
						'value'=>'3'
					)
				)
			)
		);

		//��ʼ����Ŀ�˵�
		$project = D('Project');
		unset($res);
		$res = $project->getSprintProjects();

		unset($menuTmp);
		$menuTmp['module']='Project';
		$menuTmp['icon']='icon-th-large';
		$menuTmp['url']='#';
		$menuTmp['subcount']=count($res);
		$menuTmp['submenu']=$res;
		$menu[mb_convert_encoding('��Ŀ','UTF-8','gb2312')]=$menuTmp;

 		//��ʼ��md������ϵ�Ŀ¼���ĵ���������豸��
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
//dump($menu[mb_convert_encoding('�ĵ�','UTF-8','gb2312')]);exit();
		return $menu;
	}

	public function render($data){
		$data['menu']=$this->initMenu();

		return $this->renderFile('',$data);
	}
}
?>
