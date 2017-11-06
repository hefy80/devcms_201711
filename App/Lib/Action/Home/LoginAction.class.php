<?php
// 本类由系统自动生成，仅供测试用途
class LoginAction extends Action {
	public function doLogout(){
		session('userinfo',null);
		cookie('userinfo',null);
		header("Location:".U('Home/Login/login'));
    }

	public function login(){
		$this->display();
    }

	public function doLogin(){
		session('userinfo',null);

		//去数据库中匹配账号和密码
	    $username = trim($_POST['username']);
	    $password = md5(trim($_POST['password']));
	
		$user = D('User');
		$res = $user->loginVerify($username, $password);

		if ($res['code']==0) 
		{
			//鉴权通过，更新session、cookie
			session('userinfo',$res['userinfo']);
			cookie('userinfo',$res['userinfo'],60*60*24*30);

			header("Location:".U('Home/Index/index'));
		}
		trace('登录结果',$res);
		$this->assign('err_msg',$res['msg']);
		$this->display('login');
    }

	public function doRecover(){
		echo "recover";exit();
		$this->display();
    }
}
