<?php
// 本类由系统自动生成，仅供测试用途
class ProjectAction extends BaseAction {
    public function products(){
		$products = D('Story');
		$res = $products->getProducts();
//		dump($res);
		$this->assign('storys',$res);
		$this->display();
    }

    public function projects(){
		$projects = D('Project');
		$res = $projects->getProjects();

		$this->assign('projects',$res);
		$this->display();
    }
}

