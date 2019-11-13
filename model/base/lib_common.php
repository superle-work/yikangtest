<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
/**
 * 提供页面公共数据管理服务
 * @name lib_common.php
 * @package cws
 * @category model
 * @link http://www.changekeji.com
 * @author Lay
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_common extends base_model{
	/**
	 * 移动网站获取页面模板
	 * @param string $controller
	 * @param string $module
	 */
	public function getMobileCommonDataFront($controller,$module){
	    if(!class_exists('lib_config')) include 'model/base/lib_config.php';
	    $lib_config = new lib_config();
	    $configInfo = $lib_config->findConfig(array('item_key' => $module));
	    $controller->theme = $configInfo['data']['item_value'];
	}
	
	/**
	 * 识别移动设备，返回地址
	 * @param array $args
	 * @return boolean | mobilurl
	 */
	function getMobileUrl($args){
	    if(!class_exists('m_base_menu')) include 'model/base/table/m_base_menu.php';
	    $m_base_menu = new m_base_menu();
	    
		$mbase_menu = $m_base_menu;
		$reslut = $mbase_menu -> findSql("SELECT * FROM base_menu WHERE unique_name= 'mobile'");
		$is_use = $reslut[0]['is_use'];
		//识别移动设备，自动跳转
		if ($is_use && $this->isMobile()) {
			$rootUrl = ROOT_URL . "/";
			$mobileUrl = '';
			if($args['args'] == null  || $args['args'] == '' || $args['args'] == array()){//没带参数直接返回
				$mobileUrl = "{$rootUrl}{$args['pre']}.html";
			}else{
				$mobileUrl ="{$rootUrl}{$args['pre']}";
				$urlArgs = $args['args'];
				for($i = 0;$i < sizeof($urlArgs);$i++){
					$key = key($urlArgs);
					$current = current($urlArgs);
					if($i  == (sizeof($urlArgs) - 1)){
						if($current != null  &&  $current != ''){
							$mobileUrl .= "-{$key}-{$current}.html";
						} else{
							$mobileUrl .= ".html";
						}
					}else{
						if($current != null  &&  $current != ''){
							$mobileUrl .= "-{$key}-{$current}";
						}
					}
					next($urlArgs);
				}
			}
			return $mobileUrl;
		}else{
			return false;
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 获取指定id的上一条和下一条记录
	 * +----------------------------------------------------------
	 * @param $table 对应的数据库表
	 * @param $id 当前记录的id值
	 * @return $stepList
	 * +----------------------------------------------------------
	 */
	function getStepListOld($table,$id){
		$stepList = array();
		 
		if($table == "site_product"){
			$name = 'product_name';
		}elseif($table == "site_news" || $table == "site_case"){
			$name = 'title';
		}
		
		$cate = spClass("m_{$table}")->findSql("SELECT cat_id FROM {$table} WHERE id = {$id}");
		//上一条记录
		$sql = "SELECT id , {$name} FROM {$table} WHERE id > {$id} AND cat_id = {$cate[0]['cat_id']}  ORDER BY id DESC";
		$resultLast = spClass("m_{$table}")->findSql($sql);
		$lastIndex = count($resultLast)-1;
		if($resultLast[$lastIndex]['id'] == NULL || $resultLast[$lastIndex]['id'] == ""){
				$lastList[] = '';
			}else{
				$lastList[] = array(
						"id" => $resultLast[$lastIndex]['id'],
						'name' => $resultLast[$lastIndex][$name]
				);
			}
		$stepList['last'] = $lastList[0];
		
		//下一条记录
		$sql = "SELECT id ,{$name} FROM {$table} WHERE id < {$id} AND cat_id = {$cate[0]['cat_id']}  ORDER BY id DESC LIMIT 1";
		$resultNext = spClass("m_{$table}")->findSql($sql);
		if($resultNext[0]['id'] == NULL || $resultNext[0]['id'] == ""){
				$nextList[] = '';
			}else{
				$nextList[] = array(
						"id" => $resultNext[0]['id'],
						'name' => $resultNext[0][$name]
				);
			}
		$stepList['next'] = $nextList[0];
		return $stepList;
	}
	
	/**
	 * 获取上下页数据
	 * @param string $table
	 * @param int $id
	 * @param string $sort
	 * @return array $stepList
	 */
	public function getStepList($table,$id,$sort = 'sort asc'){
		$stepList = array();
		if($table == "site_product"){
			$name = 'product_name';
		}elseif($table == "site_news" || $table == "site_case"){
			$name = 'title';
		}
		if(!class_exists("m_$table")) include "model/site/table/m_{$table}.php";
		$cate = spClass("m_{$table}")->findSql("SELECT cat_id FROM {$table} WHERE id = {$id}");
				$curRowSql = "select * from (select @row_num:=@row_num+1 as row_num,b.id from(select a.*,@row_num:=0 from $table as a where cat_id = {$cate[0]['cat_id']} order by $sort) b) as c where id = $id";
				$idRow = spClass("m_{$table}")->findSql($curRowSql);
				//上一条
				$lastRowNum = $idRow['0']['row_num'] - 1;
				$lastSql = "select * from (select @row_num:=@row_num+1 as row_num,b.* from(select a.*,@row_num:=0 from $table as a where cat_id = {$cate[0]['cat_id']} order by $sort) b) as c where row_num = $lastRowNum";
				$lastRow = spClass("m_{$table}")->findSql($lastSql);
				if($lastRow){
					$stepList['last'] = array(
							'id' => $lastRow[0]['id'],
							'name'=> $lastRow[0][$name]
					);
				}else{
					$stepList['last'] = '';
				}
				//下一条
				$nextRowNum = $idRow['0']['row_num'] + 1;
				$nextSql = "select * from (select @row_num:=@row_num+1 as row_num,b.* from(select a.*,@row_num:=0 from $table as a where cat_id = {$cate[0]['cat_id']} order by $sort) b) as c where row_num = $nextRowNum";
				$nextRow = spClass("m_{$table}")->findSql($nextSql);
				if($nextRow){
					$stepList['next'] = array(
							'id' => $nextRow[0]['id'],
							'name'=> $nextRow[0][$name]
					);
				}else{
					$stepList['next'] = '';
				}
				return $stepList;
	}
	
	/**
	 * 自定义栏目列表下一页上一页
	 * @param string $table
	 * @param int $cid
	 * @param int $id
	 * @return array $stepList
	 */
	public function getColumnStepList($table,$cid,$id){
		$stepList = array();
		$name = 'title';
				//上一条记录
		$sql = "SELECT id , {$name} FROM {$table} WHERE cid = {$cid} AND id > {$id}  ORDER BY id DESC";
		$resultLast = spClass("m_{$table}")->findSql($sql);
		$lastIndex = count($resultLast)-1;
		if($resultLast[$lastIndex]['id'] == NULL || $resultLast[$lastIndex]['id'] == ""){
				$lastList[] = '';
		}else{
			$lastList[] = array(
				"id" => $resultLast[$lastIndex]['id'],
				'name' => $resultLast[$lastIndex][$name]
			);
		}
		$stepList['last'] = $lastList[0];
		//下一条记录
		$sql = "SELECT id ,{$name} FROM {$table} WHERE cid = {$cid} AND id < {$id} ORDER BY id DESC LIMIT 1";
		$resultNext = spClass("m_{$table}")->findSql($sql);
		if($resultNext[0]['id'] == NULL || $resultNext[0]['id'] == ""){
			$nextList[] = '';
		}else{
			$nextList[] = array(
				"id" => $resultNext[0]['id'],
				'name' => $resultNext[0][$name]
			);
		}
		$stepList['next'] = $nextList[0];
		
		return $stepList;
	}
	
	/**
	 * 获取菜单列表
	 * @return array $menuList
	 */
	private function getMenuList(){
	    if(!class_exists('lib_menu')) include 'model/site/lib_menu.php';
	    $lib_menu = new lib_menu();
	    
		//获取顶级菜单
		$sqlMenuTop = "SELECT * FROM base_menu_top WHERE is_use = 1 ORDER BY sort ASC";
		$resultMenuTop = $lib_menu->findSql($sqlMenuTop);
		foreach ($resultMenuTop as $menuTop){
			//获取二级菜单
			$sqlMenu= "SELECT * FROM base_menu WHERE is_use = 1 AND menu_top_id = {$menuTop['id']} ORDER BY sort ASC, team ASC";
			$resultMenu = $lib_menu->findSql($sqlMenu);
			//获取二级菜单的所有team
			$sqlTeam = "SELECT DISTINCT team FROM base_menu WHERE menu_top_id = {$menuTop['id']}  ORDER BY  team ASC";
			$resultTeam = $lib_menu->findSql($sqlTeam);
			//按照team分组排序返回subList
			$menuSubList = array();//初始化menuSubList
			for($i = 0; $i < count($resultTeam);$i++){
				foreach ($resultMenu as $menu){
					if($resultTeam[$i]['team'] == $menu['team']){
						$menuSubList[$i] [] = $menu;
					}
				}
			}
			//构造对象化的menuList
			$menuList[] = array(
					'name' => $menuTop['name'],
					'id' => $menuTop['id'],
					'alias' => $menuTop['alias'],
					'sort' => $menuTop['sort'],
					'subList' => $menuSubList
			);
		}
		return $menuList;
	}
	
	/**
	 * 判断是否是移动客户端
	 * @return $isMobile
	 */
	function isMobile() {
		static $isMobile = FALSE;
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (empty($user_agent)) {
			$isMobile = false;
		} else {
			// 移动端UA关键字
			$mobile_agents = array (
					'Mobile',
					'Android',
					'Silk/',
					'Kindle',
					'BlackBerry',
					'Opera Mini',
					'Opera Mobi'
			);
			$isMobile = false;
			foreach ($mobile_agents as $device) {
				if (strpos($user_agent, $device) !== false) {
					$isMobile = true;
					break;
				}
			}
		}
		return $isMobile;
	}
	
}