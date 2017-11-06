<?php
// 本类由系统自动生成，仅供测试用途
class BaseAction extends Action {
	public $_fresh = false;
	public function actionLog(){
		//每个用户在redis里有一个操作记录的队列
	}

	// public function isAdmin(){
	// 	//检查是否有管理员权限
	// 	$userinfo = session('userinfo');
	// 	return ($userinfo && $userinfo['role']=='top') ? true : false;
	// }

	// public function isDept($dept=2){
	// 	//检查是否有XX部权限
	// 	$userinfo = session('userinfo');
	// 	return ($userinfo && $userinfo['dept']==$dept) ? true : false;
	// }

    /**
     * 判断用户是否具有相应权限
     * @param  string $account
     */
    public function auth($account)
    {
    	//获取用户所在的权限组列表
		$user = D('User');
		$res = $user->getAuth($account);

    	//管理员有所有权限
        if(in_array(1, $res))
        {
           return true; 
        }

        //检查当前Action是否有权限组的限定

        return false;
    }

    public function _initialize(){
    	//初始化成员变量
    	$this->_fresh = $_REQUEST['fresh'] ? true : false;

		//对于print页面，忽略登录状态和权限检查
		if($_REQUEST['print']=='true')
		{ 
			return true;
		}

		//检查登录状态（根据session判断是否当前在线，若无，则用cookie登录）
		$userinfo = session('userinfo');
		$usercookie = cookie('userinfo');
		if(!$userinfo && !$usercookie)
		{
			header("Location:".U('Home/Login/login'));
		}
		if (!$userinfo && $usercookie)	//自动登录
		{
			$user = D('User');
			$res = $user->loginVerify($usercookie['account'], $usercookie['password']);
			if ($res['code']==0)
			{
				//鉴权通过，更新cookie和session
				cookie('userinfo',$res['userinfo'],60*60*24*30);
				session('userinfo',$res['userinfo']);
			}
		}

		//鉴权
		
	}
}
