<?php
class OmAction extends BaseAction {
    public function index(){
       $menu = array(
			'System' => array(
				'phpinfo' => array(
					'icon' => 'icon-book',
					'url' => __APP__.'/Team'
				),
				'redis' => array(
					'icon' => 'icon-database',
					'url' => __APP__.'/Team'
				),
				'memcache' => array(
					'icon' => 'icon-cabinet',
					'url' => __APP__.'/Team'
				)
			),
			'Monitor' => array(
				'session & cookie' => array(
					'icon' => 'icon-tag',
					'url' => __APP__.'/Team'
				),
				'distributed statistics' => array(
					'icon' => 'icon-graph',
					'url' => __APP__.'/Team'
				)
			),
			'Api' => array(
				'PHP Api' => array(
					'icon' => 'icon-download',
					'url' => __URL__.'/apidetail'
				),
				'JAVA Server Api' => array(
					'icon' => 'icon-download',
					'url' => __URL__.'/apidetail'
				)
			)
		);
		$this->assign('menu',$menu);

		$this->display();
    }
    
    public function system(){
       $menu = array(
			'System' => array(
				'phpinfo' => array(
					'icon' => 'icon-book',
					'url' => __APP__.'/Team'
				),
				'redis' => array(
					'icon' => 'icon-database',
					'url' => __APP__.'/Team'
				),
				'memcache' => array(
					'icon' => 'icon-cabinet',
					'url' => __APP__.'/Team'
				)
			)
		);
		$this->assign('menu',$menu);

		$this->display('index');
    }
    
    public function monitor(){
       $menu = array(
			'Monitor' => array(
				'session & cookie' => array(
					'icon' => 'icon-tag',
					'url' => __APP__.'/Team'
				),
				'distributed statistics' => array(
					'icon' => 'icon-graph',
					'url' => __APP__.'/Team'
				)
			)
		);
		$this->assign('menu',$menu);

		$this->display('index');
    }
    
    public function api(){
       $menu = array(
			'Api' => array(
				'PHP Api' => array(
					'icon' => 'icon-download',
					'url' => __URL__.'/apidetail'
				),
				'JAVA Server Api' => array(
					'icon' => 'icon-download',
					'url' => __URL__.'/apidetail'
				)
			)
		);
		$this->assign('menu',$menu);

		$this->display('index');
    }
    
    public function om(){

		echo json_encode($_SERVER);exit;
    }
    public function apidetail(){
		$this->assign('menu',$menu);

		$this->display();
    }
}

