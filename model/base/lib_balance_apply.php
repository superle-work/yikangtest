<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_balance_apply')) require "model/base/table/m_base_balance_apply.php";

/**
 * 提供余额提现申请服务
 * @name lib_balance_apply.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_balance_apply extends base_model{
    private $m_cash_apply;
    public $tableConfigObj;//配置表对象
    
    function __construct(){
        parent::__construct();
        $this->m_cash_apply = new m_base_balance_apply();
        if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
        $this->tableConfigObj = new UtilConfig('base_config');
    }
    
	/**
	 * 插入余额申请记录
	 * @param array $conditions
	 * @return array
	 */
	function addApply($applyInfo){
		try{
			$applyInfo['add_time'] = common::getTime();
			$result = $this->m_cash_apply->create ($applyInfo);
			if(true == $result){
			    if($applyInfo['is_check'] != 1){//需要审核的加入冻结账户
			        if(!class_exists('m_base_user')) require 'model/base/table/m_base_user.php';
			        $m_base_user = new m_base_user();
			        $m_base_user->update(array('id' => $applyInfo['user_id']),"freeze_balance = freeze_balance+{$applyInfo['money']},balance = balance-{$applyInfo['money']}");
			        return common::errorArray(0, "申请成功", "余额提现申请成功，请等待审核！");
			    }
			    return common::errorArray(0, "申请成功",$result);
			}else{
				return common::errorArray(1, "申请失败", "对不起！您的申请失败了！");
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	/**
	 * 更新余额申请记录(审核)
	 * @param array $conditions
	 * @param array $row
	 * @return array
	 */
	function updateApply($conditions, $row){
		try{
			if($row['is_check']){
				$row['check_time'] = date("Y-m-d H:i:s",time());
			}
			$result = $this->m_cash_apply->update($conditions,$row);
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 单个删除余额申请记录
	 * @param array $conditions
	 * @return array
	 */
	function deleteApply($conditions){
		try{
			$result = $this->m_cash_apply->delete ( $conditions);
			if(true == $result){
			    $this->returnFeeByDelete($conditions['id']);//同步资金
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 批量删除余额申请记录
	 * @param string $ids 如 1,2,3,4
	 * @return array
	 */
	function deleteApplyBatch($ids){
		try{
			$sql = "DELETE FROM base_balance_apply WHERE id in({$ids})";
			$result = $this->m_cash_apply->runSql ( $sql);
			if(true == $result){
			    $this->returnFeeByDelete($ids);//同步资金
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	

	/**
	 * 删除解冻资金
	 * @param array $apply_ids
	 * @return array
	 */
	private function returnFeeByDelete($apply_ids){
        if(!class_exists('lib_user')) include 'model/base/lib_user.php';
        $lib_user = new lib_user();
        $sql = "select user_id,sum(money) as money FROM base_balance_apply WHERE id in({$apply_ids}) and (is_check != 2 or cash_static != 3) group by user_id";
        $result = $this->m_cash_apply->findSql($sql);
        foreach($result as $item){
            $lib_user->updateUser(array('id' => $item['user_id']), "balance=balance+{$item['money']},freeze_balance=freeze_balance-{$item['money']}");
        }
        if(true == $result){
            return common::errorArray(0, "退还成功", $result);
        }else{
            return common::errorArray(1, "退还失败", $result);
        }
	}
	
	/**
	 * 查看余额申请记录
	 * @param array $condition
	 * @return array
	 */
	function findApply($condition){
		try{
			$result = $this->m_cash_apply->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	/**
	 * 验证是否可以再次提交申请
	 * @param int $user_id
	 * @return array
	 */
	function confCashApply($user_id){
		$Y = date("Y",time());
		$m = date("m",time());
		$d = date("d",time());
		$days = date('t',time());           // 本月一共有几天
		$start_time = date("Y-m-d H:i:s",mktime(0,0,0,$m,1,$Y));        // 创建本月开始时间 
		$end_time = date("Y-m-d H:i:s",mktime(23,59,59,$m,$days,$Y));   // 创建本月开始时间 
		
		try{
    		$sql = "select count(*) as sum from base_balance_record where user_id = {$user_id} and add_time >= '{$start_time}' and add_time <= '{$end_time}' union select count(*) from base_balance_apply where user_id ={$user_id}  and add_time >= '{$start_time}' and add_time <= '{$end_time}' and is_check in(0,1)";
    		$result = $this->findSql($sql);
		if($result){
			return common::errorArray(0, "查找成功", $result);
		}else{
			return common::errorArray(1, "查找为空", $result);
		}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	/**
	  *分页查询余额申请记录
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array
	 */
	function pagingApply($page, $conditionList,$sort){
		$result = $this->m_cash_apply->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 打款
	 * @param int $apply_id
	 * @param int $type 0不需审核 1需审核
	 * @return array $result
	 */
	function payMoney($apply_id,$type){
		try{
			//查看余额提现信息
			$cashApplyInfo = $this->m_cash_apply->find(array('id' => $apply_id),null);
			//查询该服务商的user_id获取到该用户的open_id
			if(!class_exists('m_base_user')) include 'model/base/table/m_base_user.php';
			$m_user = new m_base_user();
			$userInfo = $m_user->find(array('id' => $cashApplyInfo['user_id']));
			
			//if($userInfo['is_use'] != 1) return common::errorArray(1, "该账户已被禁用，无法打款", false);
			$myFee = $type == 1 ? $userInfo['freeze_balance'] : $userInfo['balance'];
			$leftMoney = $myFee-$cashApplyInfo['money'];//剩余余额
			if($leftMoney < 0){
				$this->m_cash_apply->update(array('id' => $apply_id),array('cash_static' => 4));
				return common::errorArray(1, "余额不足，零钱发送失败", $leftMoney);
			}
			//调用零钱接口
// 			if(!class_exists('lib_red_packet')) include 'model/activity/packet/lib_red_packet.php';
// 			$lib_red_packet = new lib_red_packet();
			$openId = $userInfo['open_id'];
			$money = $cashApplyInfo['money']*100;//微信金额以分为单位
			$payInfo = array(
			    'openId' => $openId,
			    'money' => (int)$money,
			    'desc' => '余额提现',
			    'check_name' => 'NO_CHECK',
			);
			include_once 'include/wechatUtil/Payment.php';
			$redPacketResult = Payment::EnterprisePay($payInfo);
			if($redPacketResult['errorCode'] == 0){//零钱发送成功
				$this->m_cash_apply->update(array('id' => $apply_id),array('cash_static' => 3));
				//插入余额提现记录
				if(!class_exists('lib_cash_record')) include 'model/base/lib_cash_record.php';
				$lib_cash_record = new lib_cash_record();
				$lib_cash_record->addRecord($cashApplyInfo);
				//可提现余额字段维护
				$field = $type == 1 ? 'freeze_balance' : 'balance';
				$m_user->update(array('id' => $cashApplyInfo['user_id']),"{$field} = {$field}-{$cashApplyInfo['money']}");
				$result = common::errorArray(0,'提现成功','余额已发送到您的零钱，请注意查收');
			}else{//零钱发送失败
				$cashStateResult = $this->m_cash_apply->update(array('id' => $apply_id),array('cash_static' => 4,'error_info' => $redPacketResult['errorInfo']));
				$result = common::errorArray(1,$redPacketResult['errorInfo'],$redPacketResult['data']);
			}
			return $result;
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1,"数据库操作失败",$ex);
		}
	}

	/**
	 * 积分提现
	 * @param int $apply_id
	 * @return array
	 */
	function payPoints($info){
		try{
			//查找提现率等
			if(!class_exists('lib_config')) include 'model/mall/lib_config.php';
			$lib_config = new lib_config();
			$configResult = $lib_config->findAllConfig(array('tab' => 'distribute'));
			if($info['points'] < $configResult['data']['withdraw_points']){
				return common::errorArray(1, "少于{$configResult['data']['withdraw_points']}积分不能提现", $info);
			}
			//判断积分是否够提现
			
			
			$info['money'] = substr(sprintf("%.3f",$info['points']/$configResult['data']['money_to_points']),0,-2);
			
			$cashApplyInfo = $this->m_cash_apply->find(array('id' => $apply_id),null,'user_id,user_name,total_money,money,checker');
			//查询该服务商的user_id获取到该用户的open_id
			if(!class_exists('m_base_user')) include 'model/base/table/m_base_user.php';
			$m_user = new m_base_user();
			$userInfo = $m_user->find(array('id' => $cashApplyInfo['user_id']),null,'user_id,balance');
			$leftMoney = $userInfo['balance']-$cashApplyInfo['money'];//剩余余额
			if($leftMoney < 0){
				$this->m_cash_apply->update(array('id' => $apply_id),array('cash_static' => 4));
				return common::errorArray(1, "余额不足，零钱发送失败", $leftMoney);
			}
			if(!class_exists('m_base_user')) include 'model/base/table/m_base_user.php';
			$m_user = new m_base_user();
			$userInfo = $m_user->find(array('id' => $userInfo['user_id']),null,'open_id');
			//调用零钱接口
			if(!class_exists('lib_red_packet')) include 'model/activity/packet/lib_red_packet.php';
			$lib_red_packet = new lib_red_packet();
			$openId = $userInfo['open_id'];
			$money = $cashApplyInfo['money']*100;//微信金额以分为单位
			
			include_once 'model/market/lib_market_config.php';
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
			$remark = "本次提现余额{$cashApplyInfo['money']}元，剩余余额{$leftMoney}元";
			$redPacketResult = $lib_red_packet->sendRedPacket($openId, $money,$sender, $mchId,$payKey, $appId, $wishing, $actName, $remark);
			if($redPacketResult['errorCode'] == 0){//零钱发送成功
				$this->m_cash_apply->update(array('id' => $apply_id),array('cash_static' => 3));
				//插入余额提现记录
				if(!class_exists('lib_cash_record')) include 'model/base/lib_cash_record.php';
				$lib_cash_record = new lib_cash_record();
				$lib_cash_record->addRecord($cashApplyInfo);
				//可提现余额字段维护
				$m_user->update(array('id' => $cashApplyInfo['user_id']),array('balance' => $leftMoney));
				$result = common::errorArray(0,'发送零钱成功',$redPacketResult['data']);
			}else{//零钱发送失败
				$cashStateResult = $this->m_cash_apply->update(array('id' => $apply_id),array('cash_static' => 4,'error_info' => $redPacketResult['errorInfo']));
				$result = common::errorArray(1,$redPacketResult['errorInfo'],$redPacketResult['data']);
			}
			return $result;
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1,"数据库操作失败",$ex);
		}
	}
}