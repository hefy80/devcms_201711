<?php
class IndexAction extends BaseAction {
    public function index(){
		//初始化上传目录
        $webs = array(
				'项目管理系统' => array(
				'url' => 'http://192.168.1.21/zentao',
				'img' => 'zentao.jpg',
			),
				'文库' => array(
				'url' => 'http://192.168.1.21/mtceo',
				'img' => 'mtceo.jpg',
			),
				'论坛' => array(
				'url' => 'http://192.168.1.21/x2',
				'img' => 'discuz.jpg',
			),
				'办公OA系统' => array(
				'url' => 'http://192.168.1.21/seeyon',
				'img' => 'OA.jpg',
			),
				'培训考试系统' => array(
				'url' => 'http://192.168.1.21/ppf',
				'img' => 'ppf.jpg',
			)
		);
		$this->assign('webs',$webs);

		$this->display();
    }
}
