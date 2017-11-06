<?php
/**
 * BookAction	会议预定的相关功能
 * @author		何羽
 */
class BookAction extends Action {
    public function book($roomid='1'){
		$res = date('Y-m-d');
		$this->assign('Now',$res);

		$res = R('Show/getRoom',array($roomid));
		$this->assign('RoomX',$res);

		trace($res,'调试','debug');
		$this->display();
    }

    public function summary($roomid='1'){
		$res = date('Y-m-d');
		$this->assign('Now',$res);

		$res = $this->getRoom($roomid);
		$this->assign('RoomX',$res);

		$res = $this->getRoomList();
		$this->assign('RoomList',$res);
		$this->assign('RoomCount',count($res));
		
		$res = $this->getMeetingList();
		$this->assign('MeetingList',$res);

		trace($res,'调试','debug');
		$this->display();
    }

    public function getAjaxMeetingList(){
		$res = $this->getMeetingList();
		exit(json_encode($res));
	}

    public function AddMeeting($roomid='1'){
		dump($_POST); exit();
		$data['inituid'] = '1';
		$data['chairuid'] = $_POST['chairman'];
		$data['topic'] = $_POST['topic'];
		$data['roomid'] = $roomid;
		$data['startdate'] = $_POST['startdate'];
		$data['starttime'] = $_POST['starttime'];
		$data['enddate'] = $_POST['enddate'];
		$data['endtime'] = $_POST['endtime'];
		$data['summary'] = $_POST['summary'];
		$data['repeat'] = '0';
		$data['interval'] = '0';

		$Meeting = D('Meeting');
		$Meeting->create($data);
		$Meeting->add();

		$this->redirect(MODULE_NAME.'/index',array('roomid'=>$roomid));
	}

    public function sendMeetingEmail(){
		$to = "heyu@wanbu.com.cn, heyu@dascom.net.cn";
		$subject = "HTML email test";
		$message = "
		<html>
			<head>
				<title>HTML email</title>
			</head>
			<body>
				<p>This email contains HTML Tags!</p>
				<table>
					<tr>
						<th>Firstname</th>
						<th>Lastname</th>
					</tr>
					<tr>
						<td>John</td>
						<td>Doe</td>
					</tr>
				</table>
			</body>
		</html>
		";

		// 当发送 HTML 电子邮件时，请始终设置 content-type
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

		// 更多报头
		$headers .= 'From: <zentao@wanbu.com.cn>' . "\r\n";
		$headers .= 'Cc: heyu@wanbu.com.cn' . "\r\n";

		$res = mail($to,$subject,$message,$headers);
		var_dump($res);
	}

    public function getRoomList(){
		$Room = D('Room');
		return $Room->select();
	}

    private function getRoom($roomid='1'){
		$Room = D('Room');
		return $Room->find($roomid);
	}

    private function getMeetingList(){
		$Meeting = D('Meeting');
		$Meetings = $Meeting->field('id,topic as title,from_unixtime(starttime) as start,from_unixtime(endtime) as end')->select();

		foreach ($Meetings as $key=>$value) {
			$Meetings[$key][allDay] = false;
			$Meetings[$key][url] = __URL__.'/showdetail/id/'.$value[id];
		}

		return $Meetings;
	}

}