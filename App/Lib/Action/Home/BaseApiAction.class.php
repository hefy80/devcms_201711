<?php
class BaseApiAction extends BaseAction {
    public function index(){
		$this->display();
    }
    
	public function getSeq()
	{ 
		//获取Sequence，做为每次交互会话的唯一标识
		$seq = array(
			'date'=>date('d',time()), 
			'seq'=>0
			);
		$date = 
		$seq = '1'.date('d',time()).'0000000'; //初始化sequence，前3位是与日期相关的前缀

		$key = KEY_API_SEQ;
		$res = S($key);
		if (!$res) {
			S($key,$seq);	//redis里没有，就用初始值创建，无限保存期
		}

		if ($newsession)	//不是一个会话，sequence要加一
		{
			if (substr($sequence_old, 0, 3) != $date)
			{
				$redis->set($key, $sequence, $expire = null);
			}
			$sequence = $redis->increase($key);
		}
	}

	public function log($msg,$seq)
	{

		if ($res && count($res)>0 && !$fresh)
			return $res;

		//redis没有，就从DB里获取权限配置
		$res = $this->query("select group from zentao.zt_usergroup where account = '".$account."' order by group");

		S($key,$res,CACHE_TIME_DEFAULT);
		return (!$res) ? false : $res;

////////////////
		if ($redis->isConnected())
		{
			$sequence_old = $redis->get($key);
			if ($newsession) 
			{
				if (substr($sequence_old, 0, 3) != $date)
				{
					$redis->set($key, $sequence, $expire = null);
				}
				$sequence = $redis->increase($key);
			}
			else
			{
				$sequence = $sequence_old;
			}
		}
			
		Log::write('<'.$sequence.'>'.$msg, Log::DEBUG);
		return $sequence;
	}

	public function resp($resp, $paramRes)
	{
		$res=json_encode($resp);
    	$this->log('Response: '.$res,false);

    	// if ($resp['status']!='0000' && $paramRes)
    	// {
    	// 	$param = $paramRes['param'];
    	// 	//发邮件
	  		// $mailContent=array(
     // 			'userid'=>($param['userid'] ? $param['userid']:'null'),
    	// 		'username'=>($paramRes['user']['username'] ? $paramRes['user']['username']:'null'),
	   	// 		'appname'=>($param['appid'] ? $param['appid']:'null'),
    	// 		'appversion'=>($param['appver'] ? $param['appver']:'null'),
    	// 		'abnormal'=>'['.$resp['status'].']'.$resp['info'],
    	// 		'type'=>4,
    	// 		'environment'=>'DataSleepApi');

    	// 	R('Abnormal/Abnormal',$mailContent);
    	// }

    	header("Content-type: application/json; charset=utf-8");
		exit($res);
	}
	/**
	 * 解析、检查请求消息中的固定字段，并返回所有的参数
	 * @return array
	 * @date:2016-12-12
	 */
	private function checkParam($post, $checktoken=true)
	{ 
		$param = $post;
		$res['param']=$param;
		$this->log('Request: '.json_encode($res['param']));
//		unset($param['_URL_']);

	    //校验固定参数
	    if (!$param['appid'] || !$param['appver'] || !$param['version'] || (!$param['token'] && $checktoken))
	    {
	    	$res['status']='0001';
	    	$res['info']='Insufficient parameters'; //参数不足
	    	return $res;
	    }

	    //判断是否检查token，不检查就直接返回
	    if (!$checktoken) 
	    {
	    	$res['status']='0000';
	    	return $res;
	    }

	    //在redis中检查token是否存在
		$token = $param['token'];
        $redis = Cache::getInstance('redis');
		if ($redis->isConnected())
	    {
			$key = 'hash:member:token:' . $token;
		    $user = $redis->hgetall($key);

		    if ($user)
		    {
		    	$res['status']='0000';
		    	$res['user']=$user;
		    	return $res;
		    }
	    }

	    //redis中没有，或者连不上redis就在db中检查用户的token是否存在，如果存在，重新更新redis
		$userid = $param['userid'];
	    if ($userid && $userid > 0)
	    {
	    	//从member_profile_t1表中查找token
			$MemberProfileT1 = M('MemberProfileT1');
			$resdb = $MemberProfileT1->where("userid='".$userid."'")->find();
			$tokenDB = $resdb['accessToken'];

			if ($tokenDB)
			{
				$key = 'hash:member:token:' . $tokenDB;

			    $DataUser = M('DataUser');
			    $user = $DataUser->where("userid='".$userid."'")->find();

				$user['appid'] = $param['appid'];
				$expireAt = strtotime(date('Ymd').' +30 day');

				if ($redis->isConnected())
				{
					$redis->hmset($key, $user);
					$redis->expireAt($key, $expireAt);
				}

				if ($tokenDB == $token)
			    {
			    	$res['status']='0000';
			    	$res['user']=$user;
			    	return $res;
			    }
			}
	    }
    	$res['status']='0002';
    	$res['info']='Token is invalid.';
    	return $res;
	}
	/**
	 * 获取新的token
	 * @param  userid
	 * @return string
	 * @date:2016-12-12
	 */
	public function GetToken()
	{ 
		//获取post上来的输入信息
		$checkRes = $this->checkParam($_POST,false);
	    if ($checkRes['status']!='0000')
	    {
	    	$res['status']=$checkRes['status'];
	    	$res['info']=$checkRes['info'];
	    	$this->resp($res, $checkRes);
	    }
	    $param = $checkRes['param'];

	    //校验appid和appsecret
	   	if (($param['appid']=='TH_IOS' && $param['appsecret']!='3764249ea5d2a4f1f9edfb3957067cf0') || 
	    	($param['appid']=='TH_AND' && $param['appsecret']!='34c9d41677b623316c99cb4b6b39188d') ||
	    	($param['appid']!='TH_IOS' && $param['appid']!='TH_AND'))
	    {
	    	$res['status']='0001';
	    	$res['info']='appid or appsecret is invalid.';
			$this->resp($res,$checkRes);
	    }

	    //检查userid/mobile
	    if ($param['userid'] && $param['userid']!=0) {
	    	$where = "userid='".$param['userid']."'";
	    } 
	    else if ($param['mobile']) {
	    	$where = "mobile='".$param['mobile']."'";
	    }
		else {
	    	$res['status']='0001';
	    	$res['info']='Sorry! userid or mobile been lost!';
			$this->resp($res,$checkRes);

			//如果用mobile，要考虑记录登录的信息，比如clientinfo表什么的
			//--- 未完善
		}

	    //校验用户密码
	    $DataUser = M('DataUser');
	    $user = $DataUser->where($where)->find();
	    $password = md5($param['password'].$user['salt']);
	    if ($password != $user['password'])
	    {
	    	$res['status']='0001';
	    	$res['info']='userid/password is incorrect.';
			$this->resp($res,$checkRes);
	    }
	    
        //检查用户当前的token
		$MemberProfileT1 = M('MemberProfileT1');
		$resMemberProfileT1 = $MemberProfileT1->where("userid='".$param['userid']."'")->field('accessToken')->find();
		$tokenOld = $resMemberProfileT1['accessToken'];
 
        if (!$param['reuse'] || !$tokenOld)
        {
	        //生成token
	 	    $token = $appid."+".$param['userid']."+".time();
			$token = md5($token);

	        //并且将用户信息保存到redis的token中，生存期控制为1个月
	        $redis = Cache::getInstance('redis');
			if ($redis->isConnected())
		    {
		    	//写入新的token到redis
				$key = 'hash:member:token:' . $token;
				$user['appid'] = $appid;
				$expireAt = strtotime(date('Ymd').' +30 day');

				$redis->hmset($key, $user);
				$redis->expireAt($key, $expireAt);

				//从redis中删掉老token
				$key = 'hash:member:token:' . $tokenOld;
				$redis->rm($key);
		    }

	        //将token保存到DB
	        $data['accessToken'] = $token;
	        if ($resMemberProfileT1)
	        {
	        	//更新
				$MemberProfileT1->where("userid='".$param['userid']."'")->setField($data);
	        }
			else
			{
				//插入
				$data['userid'] = $param['userid'];
				$data['accessToken'] = $token;
				$MemberProfileT1->where("userid='".$param['userid']."'")->add($data);
			}
        }
        else
        {
        	$token = $tokenOld;
        }
 
 		//生成返回包
    	$res['status']='0000';
    	$res['info']='Congratulation! You get the token~';
    	$res['data']=$token;

		$this->resp($res,$checkRes);
	}
	/**
	 * 接收上传的睡眠数据，支持多天数据
	 * @param  userid
	 * @return boolean
	 * @date:2016-12-12
	 */
	public function UploadSleepData()
	{
		//获取post上来的输入信息
		$checkRes = $this->checkParam($_POST);
	    if ($checkRes['status']!='0000')
	    {
	    	$res['status']=$checkRes['status'];
	    	$res['info']=$checkRes['info'];
			$this->resp($res,$checkRes);
	    }
	    $param = $checkRes['param'];

	    //检查userid
		if (!$param['userid'] || $param['userid']==0) {
	    	$res['status']='0001';
	    	$res['info']='Sorry! You foget the userid!';
			$this->resp($res,$checkRes);
		}

	    //检查data有没有数据
		$data = json_decode(trim($param['data']));
		if (!$data || count($data)<=0) {
			//$this->log('data='.$param['data']);
	    	$res['status']='0001';
	    	$res['info']='Sorry! You foget the Sleep Data!';
			$this->resp($res,$checkRes);
		}

		//解析data并保存到DB（data是多天的睡眠数据）
	    foreach ($data as $k => $v) {
	    	foreach ($v as $kk => $vv) {
	    		$tmp[$kk] = $vv; //把对象转为数组
	    	}
	    	//是否要检查recorddate和deviceserial参数？
	    	$tmp['from'] = $param['appid'];
	    	$tmp['userid'] = $param['userid'];
	    	$tmp['recorddate'] = strtotime($tmp['recorddate']);
	    	$tmp['timestamp'] = time();

	    	$DataSleep = M('DataSleep');

	    	$dbres = $DataSleep->where("userid='".$tmp['userid']."' and recorddate='".$tmp['recorddate']."'")->find();

	    	if ($dbres && count($dbres)>0){
	    		$dbres = $DataSleep->where("userid='".$tmp['userid']."' and recorddate='".$tmp['recorddate']."'")->save($tmp);
	    	} else {
		    	$dbres = $DataSleep->data($tmp)->add();
	    	}

	    	if (!$dbres){	    		
		    	$res['status']='0003';
		    	$res['info']='Save the Sleep Data failed.';//:'.$DataSleep->getLastSql();
				$this->resp($res,$checkRes);
	    	}
	    }

		//生成返回包
    	$res['status']='0000';
    	$res['info']='You have upload '.count($data).' Sleep Data records successfully!';
    	$res['data']=array();

		$this->resp($res,$checkRes);
	}
	/**
	 * 提供睡眠数据下载
	 * @param  userid
	 * @return json
	 * @date:2016-12-12
	 */
	public function GetSleepData()
	{
		//获取post上来的输入信息
		$checkRes = $this->checkParam($_POST);
	    if ($checkRes['status']!='0000')
	    {
	    	$res['status']=$checkRes['status'];
	    	$res['info']=$checkRes['info'];
			$this->resp($res,$checkRes);
	    }
	    $param = $checkRes['param'];
	    $param['startdate'] = strtotime($param['startdate']);
	    $param['enddate'] = strtotime($param['enddate']);

		//从DB中提取睡眠数据
		$DataSleep = M('DataSleep');
		$res['data']=$DataSleep->field('deviceserial, recorddate, starttime, endtime, totalminutes, awakeminutes, awakeminutes, lightminutes, deepminutes, awakecount, lightcount, deepcount')->
			where("userid='".$param['userid']."' and recorddate>='".$param['startdate']."' and recorddate<='".$param['enddate']."'")->
			order('recorddate')->limit(30)->select();

    	if (!$res['data']){	    		
	    	$res['status']='0003';
	    	$res['info']='Get the Sleep Data failed.';//:'.$DataSleep->getLastSql();
			$this->resp($res,$checkRes);
    	}

    	if (count($res['data'])>0){	    		
	     	foreach ($res['data'] as $k => $v) {
	    		$res['data'][$k]['recorddate'] = date('Ymd',$v['recorddate']);
	    	}
	     	$res['info']='You have got '.count($res['data']).' Sleep Data records successfully!';
    	}
    	else{
	    	$res['info']='Nothing has been got!';
   		}

    	$res['status']='0000';
		$this->resp($res,$checkRes);
	}
	/**
	 * 检查企信用户是否关联了TH账号
	 * @param  qxid
	 * @return json
	 * @date:2017-02-14
	 */
	public function GetQXUser()
	{
		//获取post上来的输入信息
		$checkRes = $this->checkParam($_POST,false);
	    if ($checkRes['status']!='0000')
	    {
	    	$res['status']=$checkRes['status'];
	    	$res['info']=$checkRes['info'];
			$this->resp($res,$checkRes);
	    }
	    $param = $checkRes['param'];

		//从DB中提取关联关系
		$QixinWbuser = M('QixinWbuser');
		$res['data']=$QixinWbuser->field('userid,timestamp')->where("qxid='".$param['qxid']."'")->find();

    	if (!$res['data']){	    		
	    	$res['status']='0003';
	    	$res['info']='Get the QixinWbuser Data failed.';//:'.$QixinWbuser->getLastSql();
			$this->resp($res,$checkRes);
    	}

    	if (count($res['data'])>0){	    		
	     	$res['info']='You have got the relationship successfully!';
    	}
    	else{
	    	$res['info']='Nothing has been got!';
   		}

    	$res['status']='0000';
		$this->resp($res,$checkRes);
	}
	/**
	 * 将企信用户与TH账号进行关联
	 * @param  qxid, mobile
	 * @return json
	 * @date:2017-02-14
	 */
	public function SetQXUser()
	{
		//获取post上来的输入信息
		$checkRes = $this->checkParam($_POST,false);
	    if ($checkRes['status']!='0000')
	    {
	    	$res['status']=$checkRes['status'];
	    	$res['info']=$checkRes['info'];
			$this->resp($res,$checkRes);
	    }
	    $param = $checkRes['param'];

		//根据mobile获取userid
		$DataUser = M('DataUser');
		unset($dbres);
		$dbres = $DataUser->where("mobile='".$param['mobile']."'")->field('userid')->find();
    	if (!$dbres){	    		
	    	$res['status']='0003';
	    	$res['info']='Can not find the user by the mobile:'.$param['mobile'];
			$this->resp($res,$checkRes);
    	}

    	$tmp['userid'] = $dbres['userid'];
    	$tmp['qxid'] = $param['qxid'];
    	$tmp['qxname'] = $param['name'];
    	$tmp['timestamp'] = time();

		//保存关联关系到DB
		$QixinWbuser = M('QixinWbuser');
		unset($dbres);
		$dbres = $QixinWbuser->where("qxid='".$param['qxid']."'")->find();

    	if ($dbres && count($dbres)>0){
    		$dbres = $QixinWbuser->where("qxid='".$param['qxid']."'")->save($tmp);
    	} else {
	    	$dbres = $QixinWbuser->data($tmp)->add();
    	}

    	if (!$dbres){
	    	$res['status']='0003';
	    	$res['info']='Save the QixinWbuser Data failed.';//:'.$QixinWbuser->getLastSql();
			$this->resp($res,$checkRes);
    	}

    	$res['data']['userid'] = $tmp['userid'];
    	$res['data']['timestamp'] = $tmp['timestamp'];
      	$res['info']='You have set the relationship successfully!';

    	$res['status']='0000';
		$this->resp($res,$checkRes);
	}
	/**
	 * 获取用户参加的竞赛列表
	 * @param  qxid, mobile
	 * @return json
	 * @date:2017-02-14
	 */
	public function GetMyActiveList()
	{
		//获取post上来的输入信息
		$checkRes = $this->checkParam($_POST,false);
	    if ($checkRes['status']!='0000')
	    {
	    	$res['status']=$checkRes['status'];
	    	$res['info']=$checkRes['info'];
			$this->resp($res,$checkRes);
	    }
	    $param = $checkRes['param'];

		//根据userid获取竞赛列表
		unset($dbres);
		$dbres = M()->query("
			SELECT 
				co.activeid,co.activename,co.activetype,
				co.logo,co.trackid,co.background,co.maptype,co.operateconfig,
				co.prestarttime,co.preendtime,co.preclosetime,
				co.inittime,co.starttime,co.endtime,co.closetime,
				CASE WHEN co.endtime < UNIX_TIMESTAMP(CURRENT_DATE()) THEN 1 ELSE 0 END AS endstatus,
				gu.activetime,gu.groupid 
			FROM 
				wanbu_club_online co, wanbu_group_user gu 
			WHERE 
				gu.activeid=co.activeid and 
				co.visibility<>'5' and co.belong<>'0' and co.activestatus>1 and 
				co.activetype >'30000' and gu.userid='".$param['userid']."'
			ORDER BY endstatus ASC,inittime DESC,activename DESC");

    	if (!$dbres){	    		
	    	$res['status']='0003';
	    	$res['info']='Can not find any active for userid:'.$param['userid'];
			$this->resp($res,$checkRes);
    	}

    	$res['data'] = $dbres;
      	$res['info']='You have get the activelist successfully!';

    	$res['status']='0000';
		$this->resp($res,$checkRes);
	}
}


}

