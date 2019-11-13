<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_fans_data')) require 'model/weixin/lib_fans_data.php';
if(!class_exists('Statistics')) require 'include/wechatUtil/Statistics.php';
/**
 * 微信数据中心
 * @name weixin_data.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-13
 */
class  weixin_data extends admin_controller{
    private $lib_data;
    
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_data = new lib_fans_data();
	}
	
	/**
	 * 粉丝数据统计页面
	 */
	function userDataList(){
		$this->getSetMenu($this);
		$result = $this->lib_data->getKeyIndexOfUser();
		$keyIndex['todayCount'] = $result['data']['todayCount'];//今日关注量
		$keyIndex['totalCount'] = $result['data']['totalCount'];//总粉丝量
		$this->keyIndex = $keyIndex;
		$this->log(__CLASS__, __FUNCTION__, "粉丝数据统计页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/weixin/data/page/dataList.html");
	}
	
	/**
	 * 获取粉丝增减数据
	 */
	function getUserSummary(){
		$dayCount = $this->spArgs('dayCount');//天数 不超过7天
		$from = $this->spArgs('from');
		$to = $this->spArgs('to');
		/*0代表其他（包括带参数二维码）3代表扫二维码
		* 17代表名片分享 35代表搜号码（即微信添加朋友页的搜索）
		* 39代表查询微信公众帐号 43代表图文页右上角菜单
		* 99代表所有，默认是所有
		* */
		$userSource = $this->spArgs('userSource');
		if($dayCount == 7 &&  $userSource == 99){
			$cache = true;
		}else{
			$cache = false;
		}
		$result = $this->lib_data->getUserSummary($dayCount,$from,$to,$userSource,$cache);
		echo json_encode($result);
	}
	
	/**
	 *  获取累计粉丝数据
	 */
	function getUserCumulate(){
		$dayCount = $this->spArgs('dayCount');//天数 不超过7天
		$from = $this->spArgs('from');
		$to = $this->spArgs('to');
		if($dayCount == 7){
			$cache = true;
		}else{
			$cache = false;
		}
		$result = $this->lib_data->getUserCumulate($dayCount,$from,$to,$cache);
		echo json_encode($result);
	}
	
	/**
	 * 获取粉丝来源比例数据
	 */
	function getUserSourceRate(){
		$dayCount = $this->spArgs('dayCount');//天数 不超过7天
		$from = $this->spArgs('from');
		$to = $this->spArgs('to');
		if($dayCount == 7){
			$cache = true;
		}else{
			$cache = false;
		}
		$result = $this->lib_data->getUserSourceRate($dayCount,$from,$to,$cache);
		echo json_encode($result);
	}
	
	/**
	 *  获取某天所有阅读的群发消息数据
	 */
	function getArticleSummary(){
		$date = $this->spArgs('date');$date = '2015-08-24';
		$result = Statistics::getArticleSummary($date);
	
		if(isset($result['errcode'])){
			echo json_encode(common::errorArray(1, "接口错误", $result));
			exit();
		}
		echo json_encode(common::errorArray(0, "获取数据", $result['list']));
	}
	
	/**
	 *  获取图文群发消息后7日统计数据
	 */
	function getArticleTotal(){
		$date = $this->spArgs('date');$date = '2015-08-8';
		$result = Statistics::getArticleTotal($date);
	
		if(isset($result['errcode'])){
			echo json_encode(common::errorArray(1, "接口错误", $result));
			exit();
		}
		echo json_encode(common::errorArray(0, "获取数据", $result['list']));
	}
	
	/**
	 *  获取图文统计分时数据
	 */
	function getUserReadHour(){
		$date = $this->spArgs('date');$date = '2015-08-8';
		$result = Statistics::getUserReadHour($date);
	
		if(isset($result['errcode'])){
			echo json_encode(common::errorArray(1, "接口错误", $result));
			exit();
		}
		echo json_encode(common::errorArray(0, "获取数据", $result['list']));
	}
	
	/**
	 *  获取图文分享转发分时数据
	 */
	function getUserShareHour(){
		$date = $this->spArgs('date');$date = '2015-08-8';
		$result = Statistics::getUserShareHour($date);
	
		if(isset($result['errcode'])){
			echo json_encode(common::errorArray(1, "接口错误", $result));
			exit();
		}
		echo json_encode(common::errorArray(0, "获取数据", $result['list']));
	}
	
	/**
	 *  获取图文统计数据
	 */
	function getUserRead(){
		$from = $this->spArgs('from');$from = '2015-08-22';
		$to = $this->spArgs('to');$to = '2015-08-24';
		$result = Statistics::getUserRead($from, $to);
	
		if(isset($result['errcode'])){
			echo json_encode(common::errorArray(1, "接口错误", $result));
			exit();
		}
		echo json_encode(common::errorArray(0, "获取数据", $result['list']));
	}
	
	/**
	 *  获取图文分享转发数据
	 */
	function getUserShare(){
		$from = $this->spArgs('from');$from = '2015-08-18';
		$to = $this->spArgs('to');$to = '2015-08-24';
		$result = Statistics::getUserShare($from, $to);
	
		if(isset($result['errcode'])){
			echo json_encode(common::errorArray(1, "接口错误", $result));
			exit();
		}
		echo json_encode(common::errorArray(0, "获取数据", $result['list']));
	}
	
}