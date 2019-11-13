<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_distributor')) require 'model/fen/lib_distributor.php';
if(!class_exists('lib_deal_record')) require 'model/fen/lib_deal_record.php';
/**
 *佣金提现申请
 * @name store_cash_apply.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-05
 */
class store_cash_apply extends admin_controller{
    private $lib_distributor;
    private $lib_deal_record;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_distributor = new lib_distributor();
		$this->lib_deal_record = new lib_deal_record();
	}
	
	//--------------------------------佣金提现申请----------------------------
	
	/**
	 *佣金提现申请列表页面
	 */
	function cashApplyList(){
		$this->getSetMenu($this);//设置侧边栏导航
		$this->log(__CLASS__, __FUNCTION__, "佣金提现申请列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/cashApply/cashApplyList.html");
	}
	
	/**
	 *查看用户账户信息
	 */
	function cashAccountDetail(){
		$this->getMenu($this);//设置页面公共数据
		//根据用户申请提现的account_id获取用户账户信息
		if(!class_exists('lib_cash_account')) include 'model/fen/lib_cash_account.php';
		$lib_cash_account = new lib_cash_account();
		$result = $lib_cash_account->findAccount(array('id' => $this->spArgs('account_id')));
		$this->cashAccountInfo = $result['data'];
		$this->log(__CLASS__, __FUNCTION__, "查看用户账户信息", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/cashApply/cashAccountDetail.html");
	}
	
	/**
	 * 查看用户其他账户信息列表
	 */
	function cashAccountList(){
		$this->getMenu($this);//设置页面公共数据
		//根据分销商的id来获取用户账户信息
		if(!class_exists('lib_cash_account')) include 'model/fen/lib_cash_account.php';
		$lib_cash_account = new lib_cash_account();
		$result = $lib_cash_account->findAllAccount(array('tx_uid' => $this->spArgs('id'),'tx_type'=>$this->spArgs('type')));
		$this->accountList = $result['data'];
		$this->log(__CLASS__, __FUNCTION__, "查看用户其他账户信息列表", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/cashApply/cashAccountList.html");
	}
	
	/**
	 * 审核佣金提现申请
	 */
	function checkCashApply(){
		$isCheck = $this->spArgs('isCheck');
		$error_info = $this->spArgs('info');
		//审核通过与否修改的共同信息
		if(!class_exists('lib_cash_apply')) include 'model/fen/lib_cash_apply.php';
		$lib_cash_apply = new lib_cash_apply();
		$applyInfo = $lib_cash_apply->findApply(array('id' => $this->spArgs('id')));//申请提现单条记录
		
		//判断是否允许审核通过
		if($isCheck == 1){//审核通过
			//提现金额大于所拥有的佣金

			if($applyInfo['data']['money'] > $applyInfo['data']['total_money']){

				$isCheck = 2;

				$error_info = '提现金额大于您所拥有的佣金，审核失败！';

				$cash_static = 0;

				$littleMoneyResult = common::errorArray(1, $error_info, '');

			}else{
				if($applyInfo['data']['model'] == 0){//线下模式
					$cash_static = 1;
					$notice_text = '即将打款到您的账户';
				}else{//红包模式
					$result = $lib_cash_apply->payMoney($applyInfo['data']['id'],1);

					
					if($result['errorCode'] == 0){

						$notice_text = "微信零钱发送成功，请注意查收";
						$cash_static = 3;
					}else{

						$notice_text = "微信零钱发送失败，请继续等待";
						$cash_static = 4;
						$error_info = $result['errorInfo'];

					}
				}
			}
		}else{//审核不通过
			$cash_static = 0;
		}
		
		$result = $lib_cash_apply->updateApply(
			array('id' => $this->spArgs('id')),
			array(
				"is_check" => $isCheck,//0未审核1审核通过2审核不通过
				"checker" => $_SESSION['admin']['account'],
				"error_info" => $error_info,
				'cash_static' => $cash_static
			)
		);
		
		if($cash_static == 0){//审核失败、未打款
			//退还提现冻结的金额
			if($applyInfo['data']['tx_type']==1){  //退还分销商
				$this->lib_distributor->updateDistributor(
			        array('id' => $applyInfo['data']['tx_uid']),"my_fee=my_fee+{$applyInfo['data']['money']},freeze_fee=freeze_fee-{$applyInfo['data']['money']}"
		        );
			}
			else if($applyInfo['data']['tx_type']==2){  //退还诊所中心
				if(!class_exists('lib_clinic')) include 'model/store/lib_clinic.php';
				$lib_clinic = new lib_clinic();
				$lib_clinic->updateClinic(array('id' => $applyInfo['data']['tx_uid']),"money=money+{$applyInfo['data']['money']},freeze_fee=freeze_fee-{$applyInfo['data']['money']}");
			}
			else if($applyInfo['data']['tx_type']==3){   //退还医院中心
				if(!class_exists('lib_hospital')) include 'model/store/lib_hospital.php';
				$lib_hospital = new lib_hospital();
				$lib_hospital->updateHospital(array('id' => $applyInfo['data']['tx_uid']),"money=money+{$applyInfo['data']['money']},freeze_fee=freeze_fee-{$applyInfo['data']['money']}");
			}
		}
		
		//审核结果信息插入用户消息表操作
		$content = ($isCheck == 1)?"恭喜恭喜！,您申请的佣金提现获得通过，{$notice_text}！":"十分抱歉！,您申请的佣金提现未获通过。提示：".$error_info;
		if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
		$lib_user_message = new lib_user_message();
		$messageResult = $lib_user_message->sendMessage(
			array(
				"user_id" => $applyInfo['data']['distributor_id'],
				"nick_name" => $applyInfo['data']['distributor_name'],
				"title" => "您申请的佣金提现审核结果出炉啦！",
				"content" => $content
			)
		);
		
		$this->log(__CLASS__, __FUNCTION__, "审核佣金提现申请", 0, 'edit');
		if($littleMoneyResult){
			echo json_encode($littleMoneyResult);
		}else{
			echo json_encode($result);
		}
	}
	
	/**
	 * 改变佣金提现状态
	 */
	function editCashStaticApply(){
		//更改佣金提现状态
		$cash_static = $this->spArgs('cash_static');
		if(!class_exists('lib_cash_apply')) include 'model/fen/lib_cash_apply.php';
		$lib_cash_apply = new lib_cash_apply();
		$applyInfo = $lib_cash_apply->findApply(array('id' => $this->spArgs('id')));//申请提现单条记录
		
		//打款成功：修改提现状态、可取金额、佣金提现记录、用户消息提示
		if($cash_static == 3){
			//打款成功后插入佣金提现记录
			if(!class_exists('lib_cash_record')) include 'model/fen/lib_cash_record.php';
			$lib_cash_record = new lib_cash_record();
			$result = $lib_cash_record->addRecord(
				array(
					'distributor_name' => $applyInfo['data']['distributor_name'],
					'distributor_id' => $applyInfo['data']['distributor_id'],

					'tx_uid' => $applyInfo['data']['tx_uid'],

					'tx_type' => $applyInfo['data']['tx_type'],
					'money' => $applyInfo['data']['money'],
					'checker' => $applyInfo['data']['checker']
				)
			);
			
			//审核结果信息插入用户消息表操作
			$content = "您申请提现的佣金已经打款成功，请及时查看您的账户金额！";
			if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
			$lib_user_message = new lib_user_message();
			$result = $lib_user_message->sendMessage(
				array(
					"user_id" => $applyInfo['data']['distributor_id'],
					"nick_name" => $applyInfo['data']['distributor_name'],
					"title" => "您申请的佣金提现打款到账啦！",
					"content" => $content
				)
			);
			
			//打款成功，清除对应的冻结资金
			if($applyInfo['data']['tx_type']==1){  //分销商
				$this->lib_distributor->updateDistributor(
			        array('id' => $applyInfo['data']['tx_uid']),"freeze_fee=freeze_fee-{$applyInfo['data']['money']}"
		        );
			}
			else if($applyInfo['data']['tx_type']==2){  //诊所中心
				if(!class_exists('lib_clinic')) include 'model/store/lib_clinic.php';
				$lib_clinic = new lib_clinic();
				$lib_clinic->updateClinic(array('id' => $applyInfo['data']['tx_uid']),"freeze_fee=freeze_fee-{$applyInfo['data']['money']}");
			}
			else if($applyInfo['data']['tx_type']==3){   //医院中心
				if(!class_exists('lib_hospital')) include 'model/store/lib_hospital.php';
				$lib_hospital = new lib_hospital();
				$lib_hospital->updateHospital(array('id' => $applyInfo['data']['tx_uid']),"freeze_fee=freeze_fee-{$applyInfo['data']['money']}");
			}
		}
		//修改提现状态

		$result = $lib_cash_apply->updateApply(

			array('id' => $this->spArgs('id')),

			array("cash_static" => $cash_static)//提现状态

		);
		$this->log(__CLASS__, __FUNCTION__, "改变佣金提现状态", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 单个删除分佣金提现申请
	 */
	function deleteCashApply(){
		if(!class_exists('lib_cash_apply')) include 'model/fen/lib_cash_apply.php';
		$lib_cash_apply = new lib_cash_apply();
		$result = $lib_cash_apply->deleteApply( array('id' => $this->spArgs('id')) );
		$this->log(__CLASS__, __FUNCTION__, "单个删除分佣金提现申请", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除佣金提现申请
	 */
	function deleteCashApplyBatch(){
		if(!class_exists('lib_cash_apply')) include 'model/fen/lib_cash_apply.php';
		$lib_cash_apply = new lib_cash_apply();
		$result = $lib_cash_apply->deleteApplyBatch( $this->spArgs('ids') );
		$this->log(__CLASS__, __FUNCTION__, "批量删除佣金提现申请", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询佣金提现申请记录
	 */
	function pagingCashApply(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('is_check'=>'=','tx_type'=>'=','cash_static'=>'=');
		$conditionList = $this->getPagingList($this, $keyValueList);
		
		if("" != $this->spArgs('from_add_time') && null != $this->spArgs('from_add_time')){
			array_push($conditionList,  array("field" => "date_format(add_time,'%Y-%m-%d')","operator" => ">=","value" => $this->spArgs('from_add_time')));
		}
		if("" != $this->spArgs('to_add_time') && null != $this->spArgs('to_add_time')){
			array_push($conditionList,  array("field" => "date_format(add_time,'%Y-%m-%d')","operator" => "<=","value" => $this->spArgs('to_add_time')));
		}

		if("" != $this->spArgs('low_money') && null != $this->spArgs('low_money')){
			array_push($conditionList,  array("field" => "money","operator" => ">=","value" => $this->spArgs('low_money')));
		}
		if("" != $this->spArgs('up_money') && null != $this->spArgs('up_money')){
			array_push($conditionList,  array("field" => "money","operator" => "<=","value" => $this->spArgs('up_money')));
		}
		$sort = "add_time desc";
		if(!class_exists('lib_cash_apply')) include 'model/fen/lib_cash_apply.php';
		$lib_cash_apply = new lib_cash_apply();
		$result = $lib_cash_apply->pagingApply($page, $conditionList, $sort);
		foreach($result['data']['dataList'] as &$val){
			if($val['tx_type']==1){
				$fenInfo=$this->lib_distributor->findDistributor(array('id'=>$val['tx_uid']));
				$val['name']=$fenInfo['data']['name'];
			}
			else if($val['tx_type']==2){
				if(!class_exists('lib_clinic')) include 'model/store/lib_clinic.php';
				$lib_clinic = new lib_clinic();
				$cliInfo=$lib_clinic->getGoods($val['tx_uid']);
				$val['name']=$cliInfo['data']['name'];
			}
			else if($val['tx_type']==3){
				if(!class_exists('lib_hospital')) include 'model/store/lib_hospital.php';
				$lib_hospital = new lib_hospital();
				$hosInfo=$lib_hospital->getGoods($val['tx_uid']);
				$val['name']=$hosInfo['data']['name'];
			}
		}
		echo json_encode($result);
	}
	
	/**
	 * 打款 发红包（不用）
	 */
	function sendRedPacket(){
		//查看佣金提现信息
		if(!class_exists('lib_cash_apply')) include 'model/fen/lib_cash_apply.php';
		$lib_cash_apply = new lib_cash_apply();
		
		if(!class_exists('UtilConfig'))include 'include/UtilConfig.php';
		$lib_config = new UtilConfig('fen_config');
		$fenConfig = $lib_config->findConfigThemeValue();
		if($fenConfig['data']['withdraw_model'] == 1){//1：微信零钱模式 2：红包模式
    		$result = $lib_cash_apply->payMoney($this->spArgs('id'),1);
		}else{
		    //申请信息
		    if(!class_exists('lib_cash_apply')) include 'model/fen/lib_cash_apply.php';
		    $lib_cash_apply = new lib_cash_apply();
		    $cashApplyInfo = $lib_cash_apply->findApply(array('id' => $this->spArgs('id')));
		    //查询该服务商的user_id获取到该用户的open_id
		    if(!class_exists('m_fen_distributor')) include 'model/fen/table/m_fen_distributor.php';
		    $m_distributor = new m_fen_distributor();
		    $distributorInfo = $m_distributor->find(array('id' => $cashApplyInfo['distributor_id']));
		    
		    //微信配置信息
		    $lib_config = new UtilConfig('weixin_config');
		    $weixinResult = $lib_config->findConfigThemeValue();
		    //用户信息
		    if(!class_exists('lib_user')) include 'model/base/lib_user.php';
		    $lib_user = new lib_user();
		    $userInfo = $lib_user->findUser(array('id' => $distributorInfo['user_id']));
		    
		    $openId = $userInfo['data']['open_id'];
		    $sender = $userInfo['data']['nick_name'];
		    $mchId = $weixinResult['data']['wechat_mchid'];
		    $appId = $weixinResult['data']['wechat_appid'];
		    $wishing = $fenConfig['data']['withdraw_wish'];
		    $actName = $fenConfig['data']['withdraw_name'];
		    $remark = $fenConfig['data']['withdraw_remark'];
		    $payKey = $weixinResult['data']['wechat_pay_key'];
		    $money = $cashApplyInfo['data']['money'] * 100;
		    
		    if(!class_exists('lib_red_packet')) include 'model/activity/packet/lib_red_packet.php';		
		    $lib_packet = new lib_red_packet();
		    $result = $lib_packet->sendRedPacket($openId, $money, $sender, $mchId, $appId, $wishing, $actName, $remark, $payKey);   
		}
		
		$this->log(__CLASS__, __FUNCTION__, "打款 发红包", 0, 'edit');
		echo json_encode($result);
	}
	
	//-----------------------------申请提现记录------------------------------
	
	/**
	 *提现记录列表页面
	 */
	function cashRecordList(){
		$this->getMenu($this);//设置页面公共数据
		$this->distributor_id = $this->spArgs('distributor_id');//接收客户管理跳转的account
		$this->log(__CLASS__, __FUNCTION__, "提现记录列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/cashApply/cashRecordList.html");
	}
	
	/**
	 * 分页查询提现记录
	 */
	function pagingCashRecord(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('distributor_id'=>'=','distributor_name'=>'like','from_add_time'=>'>=','to_add_time'=>'<=');
		$conditionList = $this->getPagingList($this, $keyValueList);
		if("" != $this->spArgs('low_money') && null != $this->spArgs('low_money')){
			array_push($conditionList,  array("field" => "money","operator" => ">=","value" => $this->spArgs('low_money')));
		}
		if("" != $this->spArgs('up_money') && null != $this->spArgs('up_money')){
			array_push($conditionList,  array("field" => "money","operator" => "<=","value" => $this->spArgs('up_money')));
		}
		$sort = "add_time desc";
		if(!class_exists('lib_cash_record')) include 'model/fen/lib_cash_record.php';
		$lib_cash_record = new lib_cash_record();
		$result = $lib_cash_record->pagingRecord($page, $conditionList, $sort);
		echo json_encode($result);
	}
}