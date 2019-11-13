<?php
if(!class_exists('base_controller')) require 'include/base/controller/base_controller.php';
/**
 *前台控制器基类
 * @name front_controller.php
 * @package cws
 * @category base
 * @link http://www.changekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class front_controller extends base_controller{
	/**
	 * 验证微信是否登录授权
	 * 控制器和动作不传入，默认已经获得授权，否则会被拦截
	 * $scope snsapi_base不弹出授权页面，只能获得OpenId;snsapi_userinfo弹出授权页面，可以获得所有信息
	 * @param string $c
	 * @param string $a
	 */
	public function verifyLogin($controller,$goBreak= "",$scope='snsapi_userinfo'){
	    $this->getPageTitle();
		$this->debug();
		$this->chooseType($controller,$goBreak,$scope);
	}
	
	/**
	 * DEBUG模式下写入session
	 */
	public function debug(){
		if(DEBUG){
 			if(!$_SESSION['user']){
		        $_SESSION['user']['account'] = 'ooMjDs3dQcRgMsmZtvhkY8dR6Bss';
		        $_SESSION['user']['open_id'] = 'ooMjDs3dQcRgMsmZtvhkY8dR6Bss';
		        $_SESSION['user']['head_img_url'] = 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLBtqFk6svzLzBkCT1MS3fjDwh1EB1SPEEh4e02lJKEt2FPgic8ia8pYr847NxWrerarSujUoaqBy0Rw/0';
		        $_SESSION['user']['is_user'] = 1;
		        $_SESSION['user']['nick_name'] = 'A东俊.微信营销.网站建设';
		        $_SESSION['user']['name'] = 'A东俊.微信营销.网站建设';
		        $_SESSION['user']['id'] = 1;
		        $_SESSION['user']['type'] = 3;
		        //地址的session
		        $_SESSION['area']['city'] = '合肥市';
		        $_SESSION['area']['address'] = '安徽省合肥市蜀山区南二环路辅路';
		        $_SESSION['area']['longitude'] = '117.239664';
		        $_SESSION['area']['latitude'] = '31.823726';
 		    }
		}else{
			if(!$this->isWeixin()){//不是微信
				$log = Log::getInstance();
				$log->log("试图不在微信端打开",'ERROR');
				exit();
			}
		}
	}
	
	public function chooseType($controller,$goBreak,$scope){
		if(WECHAT_TYPE == 0){//服务号
			if($_SESSION['user']['open_id'] == null ||$_SESSION['user']['open_id'] == ''){//到公众号二维码页面
			    $goBack = "./index.php?".http_build_query($controller->spArgs());
				UserAuth::verify($goBack,$scope,$goBreak);
			}
		}else if(WECHAT_TYPE == 1){//订阅号
			if($_SESSION['user'] == null ||$_SESSION['user'] == ''){//到公众号二维码页面
				$openId = $this->spArgs('open_id');
				if(null != $openId && '' != $openId){//未接收到open_id,视为非法入侵
					if(!class_exists('base_user')) include 'model/base/table/m_base_user.php';
					$base_user = new m_base_user();
					$userInfo = $base_user->find(array('open_id'=>$openId));
					if($userInfo){
						$_SESSION['user'] = $userInfo;
					}else{
						exit();
					}
				}else{//非法入侵
					exit();
				}
			}//放行
		}
	}
	
	/**
	 * 判断是否在微信客户端打开
	 * @return boolean
	 */
	public function isWeixin(){
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}
		return false;
	}
	
	/**
	 * 判断是否是移动客户端
	 */
	private function isMobile() {
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

	/**
	 * 判断是否微信PC端
	 */
	public function isPCWeixin(){
	    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'WindowsWechat') !== false ) {
	        return true;
	    }
	    return false;
	}
	
	/**
	 * 获取url
	 * @param unknown $goBack
	 * @param unknown $goBreak
	 */
	private function getBreak($goBack,$goBreak){
	    if(is_array($goBreak)){
	        $goBreak = $goBack.'&'.http_build_query($goBreak);
	    }
	    return $goBreak;
	}
	//----------------------------------------消息通知-------------------------------
	
	/**
	 * 发送通知信息
	 * @param int $userId
	 * @param String $title
	 * @param String $content(link:超链接)
	 * @param int $type
	 * @return array $result
	 */
	function sendMessage($userId,$title,$content,$type = 0){
		$message = array(
						'user_id' => $userId,
						'title' => $title,
						'content' => $content,
						'type' => $type
					);
		if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
		$lib_message = new lib_user_message();
		$result = $lib_message->sendMessage($message);
		return $result;
	}
	
	//--------------------------------------微信配置-------------------------------------
	/**
	 * 获取微信配置
	 * @param string $group
	 * @return array $configInfo
	 */
	protected function getWeixinConfig($group = ''){
	    if(!class_exists('lib_config')) include 'model/weixin/lib_config.php';
	    $lib_config = new lib_config();
	    if(!$group){
	        $condition = array('item_group' => $group);
	    }
	    $result = $lib_config->findAllConfig($condition);
	    foreach ($result['data']['share'] as $per){
	        $configInfo[$per['item_name']] = $per['item_value'];
	    }
	    return $configInfo;
	}
	
	/**
	 * 页面top部分标题分配
	 */
	public function getPageTitle(){
	    $module = $this->spArgs('c') ? $this->spArgs('c') : $GLOBALS['G_SP']['default_controller'];
	    $result = $this->getConfigPosition($module);
	    if($result['errorCode'] == 0){         //获取数据表页面头部标题
	        $configTable = $result['data'];
	        if(!class_exists('lib_user')) include 'model/base/lib_user.php';
	        $lib_user = new lib_user();
	        $itemGroup = substr($module, 0, 10);  //数据库设计时长度,最大为10,为了兼容acti_bargain
	        $sql = "SELECT * FROM {$configTable} WHERE item_group = '{$itemGroup}' AND item_key = 'page_title'";
	        $result = $lib_user->findSql($sql);
	        $pageResult = array_shift($result);
	        $pageTitle = $pageResult['item_value'];
	    }elseif($result['errorCode'] == 1){    //获取文件页面头部标题
	        $configFile = file_get_contents($result['data']);
	        $result = json_decode($configFile, true);
	        $pageTitle = $result['page_title'];
	    }
	    $this->pageTitle = $pageTitle;
	}
	
	/**
	 * 说明： 模块的配置文件表明必须与模块存在一一对应
	 * 获取模块的配置文件信息：errorCode = 0;表示配置信息存在数据表中；errorCode = 1 ：表示配置信息存在配置文件中
	 * @param string $module 
	 * @return $result 
	 */
	public function getConfigPosition($module='store'){
    	if(!class_exists('db')) include 'db.php';
            $db = new db();
            $sql = "select TABLE_NAME AS 'tableName' from INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA='".DATABASE_NAME."' and TABLE_NAME LIKE '%config%' ";
            $moduleConfigTable = $module.'_config';
            $configTableList = $db->findAll($sql);
            $tableList = array();
            foreach ($configTableList as $tableName){
                $tableList[] = $tableName['tableName'];
            }
           if(in_array($moduleConfigTable, $tableList)){
               $result = common::errorArray(0, "存在配置表", $moduleConfigTable);
           }else{
               if($module == 'agent'){  
                   $filename = 'model/agent/agent.json';
                   if(is_file($filename)){
                        $result = common::errorArray(1, "存在json配置文件", $filename);
                   }
               }else{
                   if(strpos($module, '_') !== false && strpos($module, 'acti') !== false){  //存在 _即默认为微活动
                       $fileInfo = explode('_', $module);
                       $filename = 'model/activity/'.$fileInfo[1].'/'.$fileInfo[1].'.json';
                   	    if(is_file($filename)){
                   	         $result = common::errorArray(1, "存在json配置文件", $filename);
                   	    }
                   }
                   $result = common::errorArray(1, "存在json配置文件", $filename);
               }
           }
           return $result;
	}
	
	//---------------------------------------------地图定位-------------------------
	/**
	 * 百度地图：根据经纬度来获取地址信息
	 */
	public function getLocationByLonAndLat($longitude,$latitude){
	    //转为百度地图坐标
	    $url="http://api.map.baidu.com/geoconv/v1/?coords={$latitude},{$longitude}&from=3&to=5&ak=E8585e29607347015477b67178b530ab";
	    $str=file_get_contents($url);
	    $res=json_decode($str,true);
	    $longitude = $res['result'][0]['y'];
	    $latitude = $res['result'][0]['x'];
	    $url="http://api.map.baidu.com/geocoder?location=".$latitude.",".$longitude."&output=json&key=E8585e29607347015477b67178b530ab";
	    $str=file_get_contents($url);
	    $res=json_decode($str,true);
	    //         if(!class_exists('lib_city')) include 'model/motor/lib_city.php';
	    //         $lib_city = new lib_city();
	    if($res['status'] == 'OK'){//成功
	        $area['city'] = $res['result']['addressComponent']['city'];
	        //             $area['city'] = mb_substr($city,0,mb_strlen($city,'utf-8')-1);
	        //             $cityInfo = $lib_city->findCity(array('city' => $area['city']));
	        //             if($cityInfo['errorCode'] == 1){
	        //                 $cityInfo = $lib_city->findCity(array('city' => '合肥'),'sort asc');
	        //             }
	        $area['address'] = $res['result']['formatted_address'];
	    }else{//失败
	        $area['city'] = "未知城市";
	        $area['address'] = "地址获取失败";
	    }
	    //         $area['id'] = $cityInfo['data']['id'];//城市id
	    //         $area['city'] = $cityInfo['data']['city'];//城市
	    $area['longitude'] = $longitude;
	    $area['latitude'] = $latitude;
	    return $area;
	}
	
	/**
	 * 获取两个经纬度之间距离
	 */
	public function getDistance($lat1, $lng1, $lat2, $lng2){
	    $earthRadius = 6367000; //地球直径
	    $lat1 = ($lat1 * pi() ) / 180;
	    $lng1 = ($lng1 * pi() ) / 180;
	    $lat2 = ($lat2 * pi() ) / 180;
	    $lng2 = ($lng2 * pi() ) / 180;
	    $calcLongitude = $lng2 - $lng1;
	    $calcLatitude = $lat2 - $lat1;
	    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
	    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
	    $calculatedDistance = $earthRadius * $stepTwo;
	    return round($calculatedDistance);
	}
}