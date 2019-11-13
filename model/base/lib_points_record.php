<?php
if(!class_exists('m_base_points_record')) require "model/base/table/m_base_points_record.php";
/**
 * 提供积分提现信息管理服务
 * @name lib_points_record.php
 * @package cws
 * @link http://www.changekeji.com
 * @author xdzhu
 * @version 1.0
 * @since 2016-11-07
 */
class lib_points_record extends spModel{
	private $m_record ;
	
	/**
	 * 构造函数
	 */
	function __construct(){
		parent::__construct();
		$this->m_record  = new m_base_points_record();
	}
	
	/**
	 * 积分提现添加
	 * @param array $recordInfo
	 * @return array
	 */
	public function addRecord($recordInfo) {
		try{
			$recordInfo['add_time'] = common::getTime();
			$addId = $this->m_record ->create( $recordInfo );
			if($addId){
				return  common::errorArray(0, "添加成功", $addId);
			}else{
				return  common::errorArray(1, "添加失败",$addId);
			}
		}catch (Exception $ex){return  common::errorArray(1, "数据库操作失败", $ex);}
	}

	/**
	 * 积分提现信息修改
	 * @param array $recordInfo
	 * @param array $conditions
	 * @return $resultArray
	 */
	public function updateRecord($conditions,$recordInfo) {
		try{
			$result = $this->m_record ->update ($conditions,$recordInfo );
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){return  common::errorArray(1, "数据库操作失败", $ex);}
	}

	/**
	 * 获取单个积分提现信息
	 * @param array $conditions
	 * @return array
	 */
	public function findRecord($conditions,$sort = null,$fields = null){
		try{
			$result = $this->m_record ->find($conditions,$sort,$fields);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查询为空", $result);
			}
		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}
	}

	/**
	 *  获取积分提现列表
	 * @param array $conditions
	 * @param string $sort
	 * @param int $limit
	 * @return array
	 */
	public function findAllRecord($conditions,$sort = null,$fields = null,$limit = null){
		try{
			$result = $this->m_record ->findAll($conditions,$sort,$fields,$limit);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查询为空", $result);
			}
		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}
	}
	
	/**
	 *  获取提现记录表中每种积分提现模块
	 *  @param string $field
	 *  @return array
	 */
	public function findRecordField($field){
	    try{
	        $sql = "SELECT  DISTINCT {$field} FROM base_points_record";
	        $result = $this->m_record ->findSql($sql);  
	        if(true == $result){
	            return common::errorArray(0, "删除成功", $result);
	        }else{
	            return common::errorArray(1, "删除失败", $result);
	        }
	    }catch (Exception $ex){
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}

	/**
	 * 删除积分提现(单条、批量)
	 * @param string $ids 如 1,2,3,4
	 * @return array
	 */
	function deleteRecord($ids,$field = 'id'){
		try{
			//删除积分提现
			$sql = "DELETE FROM base_points_record WHERE {$field} in ({$ids})";
			$result = $this->m_record ->runSql($sql);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){return  common::errorArray(1, "数据库操作失败", $ex);}
	}
	
	/**
	 * 分页查询积分提现
	 * @param array $page 分页信息
	 * @param array $conditionList 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return multitype:boolean |multitype:number string Ambigous <boolean, multitype:boolean >
	 */
	function pagingRecord($page, $conditionList, $sort = null){
		$result = $this->m_record ->paging($page, $conditionList, $sort);
		return $result;
	}
	
	/**
	 * 积分提现
	 * @param int $info
	 * @return array
	 */
	function payPoints($info){
		//判断积分是否有效
		if(!is_numeric($info['points'])){
			return common::errorArray(1, "积分填写错误", $info['points']);
		}
		$info['points'] = ltrim($info['points'],'0');
		
		//配置信息
		if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
		$lib_config = new UtilConfig('base_config');
		$configResult = $lib_config->findConfigKeyValue('points');
		if($info['points'] < $configResult['data']['points_cash_min']){
			return common::errorArray(1, "您的提现不足{$configResult['data']['points_cash_min']}积分", $info);
		}
		if(!class_exists('lib_user')) include 'model/base/lib_user.php';
		$lib_user = new lib_user();
		$userResult = $lib_user->findUser(array('id' => $info['user_id']));
		if($info['points'] > $userResult['data']['points']){
			return common::errorArray(1, "您提现的积分超过您所拥有的积分", $info);
		}
		
		$info['money'] = substr(sprintf('%.3f',$info['points']*$configResult['data']['points_cash']), 0,-2);
		if($info['money']>200){
		    return common::errorArray(1,"单次提现金额不能超过200元",$info);
		}
		//调用红包接口
		if(!class_exists('lib_red_packet')) include 'model/activity/packet/lib_red_packet.php';
		$lib_red_packet = new lib_red_packet();
		if(!class_exists(UtilConfig)) include_once 'include/UtilConfig.php';
		$lib_config = new UtilConfig('weixin_config');
		$weixinResult = $lib_config->findConfigThemeValue();
		$openId = $info['open_id'];
		$money = $info['money']*100;//微信金额以分为单位
	    $sender = $weixinResult['data']['wechat_mch_name'];
	    $mchId = $weixinResult['data']['wechat_mchid'];
	    $appId = $weixinResult['data']['wechat_appid'];
	    $payKey = $weixinResult['data']['wechat_pay_key'];
		$wishing = '恭喜发财';
		$actName = '积分提现';
		$remark = "本次提现消费积分{$info['points']}，提现金额{$info['money']}元";
		$redPacketResult = $lib_red_packet->sendRedPacket($openId,$money,$sender,$mchId,$appId,$wishing,$actName,$remark,$payKey);
		if($redPacketResult['errorCode'] == 0){//红包发送成功
			//插入积分提现记录
			include_once 'model/base/lib_points_record.php';
			$lib_points_record = new lib_points_record();
			$lib_points_record->addRecord($info);
			//个人积分维护
			$lib_user->updateUser(
				array('id' => $info['user_id']),
				array(
					'points' => $userResult['data']['points'] - $info['points'],
					'cash_points_money' => $userResult['data']['cash_points_money'] + $info['money'],
					'cash_points' => $userResult['data']['cash_points'] + $info['points'],
				));
			$result = common::errorArray(0,'发送红包成功',$info);
		}else{//红包发送失败
			$result = common::errorArray(1,$redPacketResult['errorInfo'],$redPacketResult['data']);
		}
		return $result;
	}
}