<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_balance')) require 'model/base/table/m_base_balance.php';

/**
 * 提供账户余额(金币)充值管理服务
 * @name lib_balance.php
 * @package cws
 * @category modle
 * @link http://www.changekeji.com
 * @author lynn
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-11-08
 **/
class lib_balance extends base_model{
	private $m_base_balance;
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		if(!$this->m_base_balance) $this->m_base_balance = new m_base_balance();
	}
	
	/**
	 * 添加充值记录
	 * @param array $condition
	 * @return array $result
	 */
	public function addBalance($condition){
		try{
		    $condition['add_time'] = common::getTime();
		    $orderNumName = $this->generateOrderNum();
		    $condition['order_num'] = $orderNumName['order_number'];
		    $addId = $this->m_base_balance->create($condition);
			if($addId){
				return common::errorArray(0, '添加成功', $addId);
			}else{
				return common::errorArray(1, '添加失败', '');
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, '数据库操作失败', $ex);
		}
	}
	
	/**
	 * 删除充值记录
	 * @param int/string $ids
	 * @param string $field
	 * @return array $result
	 */
	public function deleteBalance($ids,$field = 'id'){
		try{
			$whereString = $this->fieldIsNum($ids, $field);
			$sql = "delete from base_balance where {$whereString}";
			$result = $this->m_base_balance->runSql($sql);
			if($result){
				return common::errorArray(0, '删除成功', $result);
			}else{
				return common::errorArray(1, '删除失败', $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, '数据库操作失败', $ex);
		}
	}
	
	/**
	 * 更新充值记录
	 * @param array $conditions
	 * @param array $row
	 * @return array $result
	 */
	public function updateBalance($conditions,$row){
		try{
		    $result = $this->m_base_balance->update($conditions,$row);
			if($result){
			    //同步到用户表的夺宝币
			    if($row['get_real_balance'] > 0){
			        if(!class_exists('m_base_user')) include 'model/base/table/m_base_user.php';
			        $m_user = new m_base_user();
			        $m_user->incrField(array('id' => $row['user_id']), 'balance',$row['get_real_coin']);
			    }
				return common::errorArray(0, '更新成功', $result);
			}else{
				return common::errorArray(1, '更新失败', $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, '数据库操作失败', $ex);
		}
	}
	
	/**
	 * 查找单个充值记录
	 * @param array $conditions
	 * @param string $sort
	 * @param string $fields
	 * @return array $result
	 */
	public function findBalance($conditions,$sort= null,$fields = null){
		try{
		    $result = $this->m_base_balance->find($conditions,$sort,$fields);
			if($result){
				return common::errorArray(0, '查找成功', $result);
			}else{
				return common::errorArray(1, '查找为空', $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, '数据库操作失败', $ex);
		}
	}
	
	/**
	 * 查找多个充值记录
	 * @param array $conditions
	 * @param string $sort
	 * @param string $fields
	 * @return array $result
	 */
	public function findAllBalance($conditions,$sort= null,$fields = null,$limit = null){
		try{
			$result = $this->m_base_balance->findAll($conditions,$sort,$fields,$limit);
			return common::errorArray(0, '查找成功', $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, '数据库操作失败', $ex);
		}
	}
	
	/**
	 *  获取提现记录表中每种积分提现模块
	 */
	public function findRecordField($field){
	    try{
	        $sql = "SELECT  DISTINCT {$field} FROM base_balance";
	        $result = $this->m_base_balance ->findSql($sql);
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
	 * 分页查询充值记录
	 * @param array $page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return array $result
	 */
	public function pagingBalance($page, $conditionList,$sort = null){
		$result = $this->m_base_balance->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 用余额支付
	 * @param 用户id $user_id
	 * @param 实际支付金额  $money
	 * @return array $result
	 */
	function payBalance($user_id,$money){
	    if(!class_exists('lib_user')) include 'model/base/lib_user.php';
	    $lib_user = new lib_user();
	    $userResult = $lib_user->findUser(array('id' => $user_id));
	    if($money <= $userResult['data']['balance']){//余额大于支付金额
	        //更新用户所拥有的余额
	        $result = $lib_user->decreaseField(array('id' => $user_id), 'balance',$money);
	        return common::errorArray(0, '余额扣除成功', $money);
	    }else{//余额小于支付金额
	        return common::errorArray(1, '您的余额不足',$userResult['data']['balance']);
	    }
	}
	
	/**
	 * 判断$ids是数字还是字符串用 in 或者 =
	 * @param string $ids 数字或字符串
	 * @param string $field 字段名称
	 * @return string
	 */
	private function fieldIsNum($ids,$field){
	    if(is_numeric($ids)){
	        $whereString = "{$field} = {$ids}";
	    }else{
	        $whereString = "{$field} in ({$ids})";
	    }
	    return $whereString;
	}
	
	/**
	 * 生成订单号
	 * @return string
	 */
	private function generateOrderNum(){
	    if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
	    $lib_config = new UtilConfig('duo_config');
	    $config = $lib_config->findConfigKeyValue('order');
	    $orderName = $config['data']['order_name'];
	    $num = $config['data']['order_bit'] - 8;
	    if($num <  2){
	        $num = 2;
	    }else if($num > 16){
	        $num = 8;
	    }
	    $date = date ( 'Ymd', time () );
	    switch ($num){
	        case 2:
	            $bit = 99;
	            break;
	        case 3:
	            $bit = 999;
	            break;
	        case 4:
	            $bit = 9999;
	            break;
	        case 5:
	            $bit = 99999;
	            break;
	        case 6:
	            $bit = 999999;
	            break;
	        case 7:
	            $bit = 9999999;
	            break;
	        case 8:
	            $bit = 99999999;
	            break;
	        default:
	            $bit = 999999;
	            $num = 6;
	    }
	    $serialNumber = sprintf("%0{$num}s", rand(1,   $bit));
	    return array('order_number' => "{$config['data']['order_prefix']}" .  $serialNumber.$date,'order_name' => $orderName);
	}

	/**
	 * 余额提现
	 */
	function withdrawBalance($info){
		//判断余额是否有效
		if(!is_numeric($info['money'])){
			return common::errorArray(1, "余额填写错误", $info['money']);
		}
		$info['money'] = ltrim($info['money'],'0');
		if(!class_exists('lib_user')) include 'model/base/lib_user.php';
		$lib_user = new lib_user();
		$userResult = $lib_user->findUser(array('id' => $info['user_id']));
		if($info['money'] > $userResult['data']['balance']){
			return common::errorArray(1, "您提现的金额超过您所拥有的余额", $info);
		}
		
		if($info['money']>200){
		    return common::errorArray(1,"单次提现金额不能超过200元",$info);
		}
		//调用红包接口
		if(!class_exists('lib_red_packet')) include 'model/activity/packet/lib_red_packet.php';
		$lib_red_packet = new lib_red_packet();
		$openId = $info['open_id'];
		$money = $info['money']*100;//微信金额以分为单位
		
		include_once 'model/market/config/lib_market_config.php';
		$lib_config = new lib_market_config();
		
		$configInfo = $lib_config->findConfig(array('name' => 'wechat_mch_name'));
		$sender = $configInfo['data']['value'];//商户名称
		
		$configInfo = $lib_config->findConfig(array('name' => 'wechat_mchid'));
		$mchId =$configInfo['data']['value'];//微信支付商户号
		
		$configInfo = $lib_config->findConfig(array('name' => 'wechat_pay_key'));
		$payKey =$configInfo['data']['value'];//微信商户支付密钥
		
		$configInfo = $lib_config->findConfig(array('name' => 'wechat_appid'));
		$appId =$configInfo['data']['value'];//微信appid
		
		$wishing = '恭喜发财';
		$actName = '余额提现';
		$remark = "本次提现金额{$info['money']}元";
		$redPacketResult = $lib_red_packet->sendRedPacket($openId, $money,$sender, $mchId,$payKey, $appId, $wishing, $actName, $remark);
		if($redPacketResult['errorCode'] == 0){//红包发送成功
			$result = $lib_user->decreaseField(array('id' => $info['user_id']),'balance',$info['money']);
			if($result['errorCode'] == 0){
				$result = common::errorArray(0,'发送红包成功',$info);
			}
		}else{//红包发送失败
			$result = common::errorArray(1,$redPacketResult['errorInfo'],$redPacketResult['data']);
		}
		return $result;
	}
}