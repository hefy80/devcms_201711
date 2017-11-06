<?php
class HeadWidget extends Widget {
    private function init(){
		$res = array();

		return $res;
    }

	public function render($data){
		return $this->renderFile('',$data);
	}
}
?>