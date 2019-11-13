<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_market_sign')) require 'model/market/sign/table/m_market_sign.php';
if(!class_exists('m_market_sign_record')) require 'model/market/sign/table/m_market_sign_record.php';
/**
 * 提供签到管理服务
 * @name lib_sign.php
 * @package cws
 * @category modle
 * @link http://www.changekeji.com
 * @author Lay
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-05
 */
class lib_sign extends base_model{
    private $market_sign;
    private $market_sign_record;
    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct ();
        $this->market_sign = new m_market_sign();
        $this->market_sign_record = new m_market_sign_record();
    }
    
	/**
	 * 添加签到配置
	 * @param array $signInfo
	 * @return array $id
	 */
	function addSign($signInfo){
		try {
			$signInfo['add_time'] = date("Y-m-d H:i:s",time());
			$id = $this->market_sign->create($signInfo);
			if($id){
				return common::errorArray(0, "添加成功", $id);
			}else{
				return common::errorArray(1, "添加失败", $id);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看签到配置信息
	 * @param array $condition
	 * @return array $result
	 */
	function findSign($condition){
		try {
			$result = $this->market_sign->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取所有签到日期
	 * @param string $user_id
	 * @return array $result
	 */
	function findAllDate($user_id){
	    try {
	        $nowDate = date("Y-m",strtotime("-1 month"));
	        $sql = "select id,add_date as start from market_sign_record where user_id = ({$user_id}) AND add_date >= ('{$nowDate}')";
	        $result = $this->market_sign_record->findSql($sql);
	        if($result){
	            return common::errorArray(0, "查找成功", $result);
	        }else{
	            return common::errorArray(1, "查找为空", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 签到配置列表
	 * @param array $condition
	 * @param string $sort
	 * @return array $result
	 */
	function findAllSign($condition,$sort){
		try {
			$result = $this->market_sign->findAll($condition,$sort);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 签到配置列表
	 * @param array $condition
	 * @param string $sort
	 * @return array $result
	 */
	function findAllSignRecord($condition,$sort = ''){
		try {
			$result = $this->market_sign_record->findAll($condition,$sort);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除签到配置
	 * @param string $ids
	 * @return array $result
	 */
	function deleteSign($ids){
		try {
			$sql = "DELETE FROM market_sign WHERE id IN ({$ids})";
			$result = $this->market_sign->runSql($sql);
			if($result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "无删除项", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 更新签单信息
	 * @param array $condition
	 * @param array $signInfo
	 * @return array $result
	 */
	function updateSign($condition,$signInfo){
		try {
			$result = $this->market_sign->update($condition,$signInfo);
			if($result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 用户签到主接口
	 * @param array $userInfo
	 * @return boolean
	 */
	function sign($userInfo){
		//判断当前有无签到项目
		$signResult = $this->findAllSign(null, 'days asc');
		if($signResult['errorCode'] != 0){
			return common::errorArray(1, "当前无签到配置", false);
		}
		$signList = $signResult['data'];//当前的签到配置
		//判断今日签到过没有
		$todaySignResult = $this->isSignToday($userInfo['user_id']);
		if($todaySignResult['errorCode'] == 0){
			return common::errorArray(1, "今日已经签到过了", false);
		}
		//获取用户信息
		if(!class_exists('lib_user')) include 'model/base/lib_user.php';
		$lib_user = new lib_user();
		$userResult = $lib_user->findUser(array('id'=>$userInfo['user_id']));
		$canSignDays = $userResult['data']['con_sign_days'];//用户当前连续签到天数
		$signInfo['user_id'] = $userInfo['user_id'];
		$signInfo['nick_name'] = $userInfo['nick_name'];
		//判断用户是否连续签到
		//$lastRecordResult = $this->market_sign_record->find(array('add_date'=>date('Y-m-d',time() - 3600 * 24),'user_id'=>$userInfo['user_id']));//昨日签到记录
		$lastRecordResult = $this->isConSign($userInfo['user_id']);
		if($lastRecordResult){//昨日签到了即是连续签到
			$signInfo['con_sign_days'] = $lastRecordResult['con_sign_days'] + 1;//签到记录连续签到数加1
			$getSignResult = $this->getSign($canSignDays, $signList);//获取对应的积分
			$getSign = $getSignResult;
			$userConSignDays = $canSignDays + 1;//用户连续签到数加1
		}else{//不是连续签到
			$getSignResult = $this->getSign(0, $signList);//获取对应的积分
            $getSign = $getSignResult;
			$signInfo['con_sign_days'] = 1;//签到记录连续签到数设为1
			$userConSignDays = 1;//用户连续签到数设为1
		}
		//为用户签到
		$signInfo['points'] = $getSign['points'];
		$result = $this->addSignRecord($signInfo);//添加签到记录
		if($result['errorCode'] == 0){
			//为用户添加积分
			$this->addPoints($userInfo['user_id'], $userConSignDays, $userResult['data']['points'], $getSign['points']);
		}
		return $result;
	}
	
	/**
	 * 判断是否连续签到
	 * @param int $userId
	 * @return Ambigous <boolean, mixed>
	 */
	public function isConSign($userId){
		$lastRecordResult = $this->market_sign_record->find(array('add_date'=>date('Y-m-d',time() - 3600 * 24),'user_id'=>$userId));//昨日签到记录
		return $lastRecordResult;
	}
	
	/**
	 * 判断今天是否签到
	 * @param int $userId
	 * @return boolean
	 */
	public function isSignToday($userId){
		$recordResult = $this->market_sign_record->find(array('add_date'=>date('Y-m-d',time()),'user_id'=>$userId));
		if($recordResult){
			return common::errorArray(0, "今日已经签到", true);
		}else{
			return common::errorArray(1, "今天没有签到", false);
		}
	}
	
	/**
	 * 为用户添加积分
	 * @param int $userId
	 * @param int $userConSignDays
	 * @param int $prevPoints
	 * @param int $addPoints
	 * @return array $userResult
	 */
	private function addPoints($userId,$userConSignDays,$prevPoints,$addPoints){
		$conditon = array('id'=>$userId);
		$userInfo['points'] = $prevPoints + $addPoints;
		$userInfo['con_sign_days'] = $userConSignDays;
		if(!class_exists('lib_user')) include 'model/base/lib_user.php';
		$lib_user = new lib_user();
		$userResult = $lib_user->updateUser($conditon,$userInfo);
		return $userResult;
	}
	
	/**
	 * 判断当前连续天数所处配置项的哪个区间
	 * @param int $curConDays 当前连续签到天数
	 * @param array $signList
	 * @return array $signList[$i]
	 */
	public function getSign($curConDays,$signList){
		//如果配置只有一个直接返回第一组
		$count  =  count($signList);
		if($count == 1){//count为1
			return $signList[0];
		}
		$prevConDays = $curConDays + 1;//今天如果签到后的连续签到数
		for($i = 0;$i < $count ;$i++){
			if(($i + 1) == $count){//判断是不是最后一次
				if($prevConDays >= $signList[$i - 1]['days'] && $prevConDays < $signList[$i]['days'] ){
					return $signList[$count-2];
				}else{
					return  $signList[$count-1];
				}
			}else{
				if($prevConDays >= $signList[$i]['days']  && $prevConDays < $signList[$i+1]['days'] ){
					return $signList[$i];
				}
			}
		}
	}
	
	/**
	 * 添加签到记录
	 * @param array $signInfo
	 * @return array $id
	 */
	private function addSignRecord($signInfo){
		try {
			$signInfo['add_time'] = date("Y-m-d H:i:s",time());
			$signInfo['add_date'] = date("Y-m-d",time());
			$signInfo['ip'] = common::getRealIp();
			$signInfo['device'] = common::detectDevice();
			$id = $this->market_sign_record->create($signInfo);
			if($id){
				return common::errorArray(0, "添加成功", $id);
			}else{
				return common::errorArray(1, "添加失败", $id);
			}
		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}
	}
	
	/**
	 * 分页查询签到记录活动
	 * @param array$page 分页信息
	 * @param array $conditionList 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return array $result
	 */
	function pagingSignRecord($page, $conditionList, $sort){
		$result = $this->market_sign_record->paging($page, $conditionList,$sort);
		return $result;
	}
	
	/**
	 * 删除签到记录
	 * @param string $ids
	 * @return array $result
	 */
	function deleteSignRecord($ids){
	    try {
	        $sql = "DELETE FROM market_sign_record WHERE id IN ({$ids})";
	        $result = $this->market_sign_record->runSql($sql);
	        if($result){
	            return common::errorArray(0, "删除成功", $result);
	        }else{
	            return common::errorArray(1, "无删除项", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
}
