<?php
class LinkWidget extends Widget {
    private function init(){
		$res = array();
		$res['author'] = 'heyu';
		$res['keywords'] = '办公助手,内部工具';
		$res['description'] = '啪呗技术部内部办公系统';
		$res['title'] = '啪呗技术部办公助手';

		return $res;
    }

	public function render($data){
//		$templateFile = PUBLIC_PATH.'/Lib/Widget/Link/Default/index.html';

		$data[headinfo]=$this->init();;
		return $this->renderFile('',$data);
	}
}
?>