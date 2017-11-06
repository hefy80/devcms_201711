<?php
// 本类由系统自动生成，仅供测试用途
class WhiteBoardAction extends BaseAction {
    public function index(){
		//获取项目列表
		$res = $this->getProjectList();
		if(is_array($res) && count($res)>0)
		{
			foreach ($res as $key=>$val)
			{
				$projectList[$val['id']] = $val;
			}
		}
		$this->assign('ProjectList',$projectList);

		//获取当前项目
		$pid = ($_REQUEST['pid']) ? $_REQUEST['pid'] : $res[0][id];
		$this->assign('Project',$projectList[$pid]);

		//获取指定项目的故事集和任务集
		$storyList = $this->getStoryList($pid);
		$taskList = $this->getTaskList($pid);

		//构建指定项目的故事、任务树
		$storyTree = array();
		if(is_array($storyList) && count($storyList)>0)
		{
			foreach ($storyList as $key=>$val)
			{
				$storyTree[$val['id']]['story'] = $val;
			}
		}
		if(is_array($taskList) && count($taskList)>0)
		{
			foreach ($taskList as $key=>$val)
			{
				$storyTree[$val[story]][$val['status']][$val['id']] = $val;
			}
		}
		$storyTree[0][story][title] = "其他";
		$this->assign('StoryTree',$storyTree);
//		dump($storyTree); exit();

		$this->display();
    }

    public function getProjectList(){
		$project = D('Project');
		$res = $project->getSprintProjects();

		return $res;
	}

    private function getStoryList($projectid){
		$story = D('Story');
		$res = $story->getProjectStorys($projectid);

		return $res;
	}

    private function getTaskList($projectid){
		$story = D('Task');
		$res = $story->getProjectTasks($projectid);

		return $res;
	}

}
