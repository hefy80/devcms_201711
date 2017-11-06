<?php
include 'Public/parsedown-master/Parsedown.php';

// 本类由系统自动生成，仅供测试用途
class DocAction extends BaseAction {
    public function index(){
		//根据传入的path，获取当前节点的类型(目录、文件)并区分展示
		$path = base64_decode($_REQUEST['path']);

		unset($node);
		$doc = D('Doc');
		$node = $doc->getDirTree($path,$this->_fresh);

		$path = '.'.APP_UPLOADPATH.$path;
		if (is_dir($path))
		{
			//展示目录下的内容
			$this->assign('node',$node);
			$this->display();
		}
		else if (is_file($path))
		{
			//展示文件内容
			$pathinfo = pathinfo($path);
			$txt=file_get_contents($path);
			if ($pathinfo['extension']=='md')
			{
				$Parsedown = new Parsedown();
				$txt=$Parsedown->text($txt);
			}
			$this->assign('txt',$txt);
			$this->assign('node',$node);

//			$admin = $this->isAdmin();
			$this->assign('admin',$admin);

			if ($_REQUEST['print'])	{
				$this->display('print');
			} else {
				$this->display('detail');
			}
		}
		else
		{
			//暂无
		}
    }
}

