<?php
class DocModel extends Model{
	/**
	 * 递归查看指定目录下的所有文件（可以指定类型）和目录
	 * @author heyu
	 * @param array $node		Upload下的相对路径下的目录结构
	 */
	private function createDirTree(&$node) 
	{
		if (!is_array($node))
			return false;

		//根节点处理
		if (!$node || count($node)<=0)
		{
			$node['path'] = 'Documents';	//节点的相对路径，字符集与系统相关，用于DirectoryIterator
			$node['name'] = 'Documents';	//节点名，UTF-8
			$node['updtime'] = 0;			//节点的最近更新时间unix时间戳
			$node['depth'] = 0;				//节点的层级
			$node['forefather'][] = array('name'=>'配置管理','path'=>'Documents');	//节点的直系族谱，包含自己
			$node['type']='dir';			//节点类型，目录还是文件
		}

		$path = '.'.APP_UPLOADPATH.$node['path'];
		foreach(new DirectoryIterator($path) as $file) 
		{
			if (!$file->isDot()) 
			{
				unset($child);
				$filename = $file->getFilename();
				if (strpos($filename,'.')===false || strpos($filename,'.')>0)	//跳过隐藏文件
				{
					//对文件名进行字符集转换
					$encode = mb_detect_encoding($filename, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
					$filenameUtf8 = ($encode == "UTF-8") ? 	$filename : mb_convert_encoding($filename,"UTF-8",$encode);
					
					//构建子节点，并建立父子关系node->child
					$child['path']=$node['path']."/".$filename;
					$child['name']=$filenameUtf8;
					if ($_SERVER['SERVER_ADDR'] != '127.0.0.1') 
						$child['updtime']=$file->getMTime();
					$child['depth']=$node['depth'] + 1;
					
					//在子节点记录直系族谱
					$child['forefather']=$node['forefather']; //继承父辈的族谱
					$self['name']=$child['name'];
					$self['path']=$child['path'];
					$child['forefather'][]=$self;	//把自己加进去
					
					//记录子节点类型
					if ($file->isDir()) 
					{
						$child['type']='dir';
						$this->createDirTree($child);	//递归创建子树
					} 
					else 
					{
						$child['type']='file';
					}

					//建立父子关系node->child
					$node['children'][$child['name']]=$child;
				}
			}
		}
	}
	
	/**
	 * 根据传入的路径，查找节点并返回该节点下的文件结构
	 * @author heyu
	 * @param string $path		Upload下的相对路径下的目录结构
	 */
	public function getDirTree($path='Documents', $fresh=false) 
	{
		//获取以Documents为根的完整的目录树
		$node = S(KEY_DOCTREE);
		if (!$node || count($node)<=0 || $fresh)
		{
			unset($node);
			$node = array();
			$this->createDirTree($node);

			if (count($node)>0)
			{
				S(KEY_DOCTREE,$node,CACHE_TIME_DEFAULT);
			}
		}

		//根据传入的path，从目录树中找到子树
		$encode = mb_detect_encoding($path, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
		if ($encode != "UTF-8")	//兼容windows下的中文目录
		{
			$path = mb_convert_encoding($path,"UTF-8",$encode);
		}
		$pieces = explode("/", $path);
		foreach ($pieces as $k => $v) {
			if ($k>0 && $v != "" && $v != ".")	//跳过根、无效节点
			{
				$node = $node['children'][$v];
			}
		}

		//读取当前节点的svn log
		$node['svnlogs'] = $this->getSvnLog($node['path']);

		return $node;
	}

	/**
	 * 读取路径节点的svn log
	 * @author heyu
	 * @param string $path		Upload下的相对路径下的目录结构
	 */
	public function getSvnLog($path='Documents') 
	{
		$key = KEY_DOC_SVNLOG.':'.md5($path);
		$logs = S($key);
		if (!$logs || count($logs)<=0)
		{
			//$logs[] = $path;
			$auth = '--no-auth-cache --username devcms --password devcms';

			//UPDATE，获取最新log
			$cmd = 'svn update '.$auth.' "'.$_SERVER['DOCUMENT_ROOT'].'/'.APP_UPLOADPATH.$path.'"';
			exec($cmd);

			//读取路径节点的svn log
			$cmd = 'svn log -l 2 '.$auth.' "'.$_SERVER['DOCUMENT_ROOT'].'/'.APP_UPLOADPATH.$path.'"';
			//$logs[] = '**********cmd='.$cmd;
			exec($cmd, $logs);

			//读取路径节点的svn diff
			$cmd = 'svn diff -r PREV:HEAD '.$auth.' "'.$_SERVER['DOCUMENT_ROOT'].'/'.APP_UPLOADPATH.$path.'"';
			//$logs[] = '**********cmd='.$cmd;
			exec($cmd, $logs);

			if (count($logs)>0)
			{
				S($key,$logs,CACHE_TIME_DEFAULT);
			}
		}

		return $logs;
	}
}
?>