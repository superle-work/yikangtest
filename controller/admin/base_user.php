<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_user')) require 'model/base/lib_user.php';
if(!class_exists('lib_user_balance')) require 'model/base/lib_user_balance.php';
if(!class_exists('lib_balance')) require 'model/base/lib_balance.php';
if(!class_exists('lib_points_record')) require 'model/base/lib_points_record.php';
/**
 *用户管理
 * @name base_user.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_user extends admin_controller{
    private $lib_user;
    private $lib_balance;
    private $lib_points;
    private $lib_user_balance;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_user = new lib_user();
		$this->lib_user_balance = new lib_user_balance();
		$this->lib_points = new lib_points_record();
		$this->lib_balance = new lib_balance();
	}
	
	//----------------------------------用户管理------------------------------
	
	/**
	 * 用户列表页面
	 */
	function userList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "用户列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/user/page/userList.html");
	}
	
	/**
	 * 用户详情页面
	 */
	function userDetail(){
		$this->getMenu($this);
		$id = $this->spArgs('id');
		$account = $this->spArgs('account');
		if($id != null && $id != ""){
		    $conditions = array('id' => $id);
		}elseif($account != null && $account != ""){
		    $conditions = array('account' => $account);
		}
		//用户基本信息
		$userResult = $this->lib_user->findUser($conditions);
		$this->userInfo = $userResult['data'];
		//用户收货地址列表
		if(!class_exists('lib_user_address')) require 'model/base/lib_user_address.php';
		$lib_address = new lib_user_address();
		$addressResult = $lib_address->getAddressList(array('user_id'=>$id));
		if($addressResult['errorCode'] == 0){
		    $this->addressList = $addressResult['data'];
		}else{
		    $this->addressList = false;
		}
		$this->log(__CLASS__, __FUNCTION__, "用户详情页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/user/page/userDetail.html");
	}
	/**
	 * 用户详情页面
	 */
	function orderUserDetail(){
	    $this->getMenu($this);
	    $account = $this->spArgs('account');
	    //用户基本信息
	    $userResult = $this->lib_user->findUser(array(' account'=> $account));
	    $this->userInfo = $userResult['data'];
	    //用户收货地址列表
	    if(!class_exists('lib_user_address')) require 'model/base/lib_user_address.php';
	    $lib_address = new lib_user_address();
	    $addressResult = $lib_address->getAddressList(array('user_id'=> $userResult['data']['id']));
	    if($addressResult['errorCode'] == 0){
	        $this->addressList = $addressResult['data'];
	    }else{
	        $this->addressList = false;
	    }
	    $this->log(__CLASS__, __FUNCTION__, "用户详情页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/base/user/page/userDetail.html");
	}

    /**
     * 设为管理员
     */
    function userAdmin(){
        $this->getMenu($this);
        $id = $this->spArgs('id');
        $conditions = array('id' => $id);
        $userResult = $this->lib_user->findUser($conditions);
        $adminMap['user_id'] = $userResult['data']['id'];
        $adminMap['account'] = time();
        $adminMap['password'] = 123;
        $adminMap['admin_name'] = $userResult['data']['nick_name'];
        $adminMap['type'] = 1;
        $admin = new lib_admin();
        $list = $admin->addAdmin($adminMap);
        if($list['data']){
            $map['is_admin'] = 1;
            $userResult = $this->lib_user->updateUser(array('id' => $this->spArgs('id')), $map);
        }
        $this->log(__CLASS__, __FUNCTION__, "添加管理员页面", 1, 'add');
        echo json_encode($list);
    }
	
	/**
	 * 编辑用户页面
	 */
	function editUser(){
		$this->getMenu($this);
		$userResult = $this->lib_user->findUser(array('id'=>$this->spArgs('id')));
		$this->user = $userResult['data'];
		$this->log(__CLASS__, __FUNCTION__, "编辑用户页面", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/base/user/page/editUser.html");
	}
	
	/**
	 * 删除用户
	 */
	function deleteUser(){
		$ids = $this->spArgs('ids');
		$result = $this->lib_user->deleteUser($ids);
		$this->log(__CLASS__, __FUNCTION__, "删除用户", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 编辑用户
	 */
	function updateUser(){
	    $userInfo = $this->getArgsList($this,array(email,phone,birthday,name,points));
		$result = $this->lib_user->updateUser(array('id'=>$this->spArgs('id')),$userInfo);
		$this->log(__CLASS__, __FUNCTION__, "编辑用户", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询用户
	 */
	function pagingUser(){
	    $page = $this->getPageInfo($this);
	    //$keyValueList = array('name'=>'like','nick_name'=>'like','subscribe'=>'=','country'=>'like','province'=>'like','city'=>'like','phone'=>'=','sex'=>'=','remark'=>'like','from_add_time'=>'>=','to_add_time'=>'<=');
	    
	    $keyValueList = array('nick_name'=>'like','phone'=>'=','user_type'=>'=','name'=>'like','subscribe'=>'=');
	    $conditionList = $this->getPagingList($this, $keyValueList);
	    $sort = "subscribe_time desc,add_time desc";
	    $result = $this->lib_user->pagingUser($page,$conditionList,$sort);
	    echo json_encode($result);
	}
	
	//--------------------------------------用户余额充值记录-------------------------------
	
	/**
	 * 余额充值列表页面
	 */
	function balanceList(){
	    $this->getSetMenu($this);
	    $result = $this->lib_balance->findRecordField('module');
	    $this->modules = $result['data'];
	    $this->log(__CLASS__, __FUNCTION__, "余额充值列表页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/base/balance/page/balanceList.html");
	}
	
	/**
	 * 分页查询充值记录
	 */
	function pagingBalance(){
	    $page = $this->getPageInfo($this);
	    $keyValueList = array('nick_name'=>'like','type'=>'=','module'=>'=','status'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
	    $conditionList = $this->getPagingList($this, $keyValueList); 
	    $sort = "add_time desc";
	    $result = $this->lib_balance->pagingBalance($page,$conditionList,$sort);
	    echo json_encode($result);
	}
	
	/**
	 * 删除充值余额记录
	 */
	function deleteBalance(){
	    $ids = $this->spArgs('ids');
	    $result = $this->lib_balance->deleteBalance($ids);
	    $this->log(__CLASS__, __FUNCTION__, "删除余额充值记录", 0, 'del');
	    echo json_encode($result);
	}
	
	/**
	 * 分页查询充值记录
	 */
	function pagingPay(){
	    $page = $this->getPageInfo($this);
	    $keyValueList = array('user_id'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
	    $conditionList = $this->getPagingList($this, $keyValueList);
	    $sort = "add_time desc";
	    $result = $this->lib_user_balance->pagingPay($page,$conditionList,$sort);
	    echo json_encode($result);
	}
	
	/**
	 * 余额充值
	 */
	function addBalance(){
	    $userId = $this->spArgs('user_id');
	    $money = $this->spArgs('money');
	    $result = $this->lib_user_balance->addBalance($userId, $money);
	    $this->log(__CLASS__, __FUNCTION__, "余额充值", 0, 'edit');
	    echo json_encode($result);
	}
	
	/**
	 * 余额消费列表页面
	 */
	function costList(){
	    $this->getSetMenu($this);
	    $this->log(__CLASS__, __FUNCTION__, "余额消费列表页面", 1, 'view');
	    $this->display("../template/user/{$this->theme}/base/user/page/costList.html");
	}
	
	/**
	 * 余额消费
	 */
	function costBalance(){
	    $userId = $this->spArgs('user_id');
	    $money = $this->spArgs('money');
	    $result = $this->lib_user_balance->costBalance($userId, $money);
	    $this->log(__CLASS__, __FUNCTION__, "余额消费", 0, 'edit');
	    echo json_encode($result);
	}
	
	/**
	 * 分页查询消费记录
	 */
	function pagingCost(){
	    $page = $this->getPageInfo($this);
	    $keyValueList = array('type'=>'=','content'=>'like','user_id'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
	    $conditionList = $this->getPagingList($this, $keyValueList);
	    $sort = "add_time desc";
	    $result = $this->lib_user_balance->pagingCost($page,$conditionList,$sort);
	    echo json_encode($result);
	}
	
	//--------------------------------余额提现申请----------------------------
	
	/**
	 *余额提现申请列表页面
	 */
	function balanceApplyList(){
		$this->getSetMenu($this);//设置侧边栏导航
		$this->log(__CLASS__, __FUNCTION__, "余额提现申请列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/balance/page/cashApply/cashApplyList.html");
	}
	
	/**
	 *查看用户账户信息
	 */
	function cashAccountDetail(){
		$this->getMenu($this);//设置页面公共数据
		//根据用户申请提现的account_id获取用户账户信息
		if(!class_exists('lib_base_cash_account')) include 'model/base/lib_base_cash_account.php';
		$lib_base_cash_account = new lib_base_cash_account();
		$result = $lib_base_cash_account->findAccount(array('id' => $this->spArgs('account_id')));
		$this->cashAccountInfo = $result['data'];
		$this->log(__CLASS__, __FUNCTION__, "查看用户账户信息", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/balance/page/cashApply/cashAccountDetail.html");
	}
	
	/**
	 * 查看用户其他账户信息列表
	 */
	function cashAccountList(){
		$this->getMenu($this);//设置页面公共数据
		//根据分销商的id来获取用户账户信息
		if(!class_exists('lib_base_cash_account')) include 'model/base/lib_base_cash_account.php';
		$lib_base_cash_account = new lib_base_cash_account();
		$result = $lib_base_cash_account->findAllAccount(array('user_id' => $this->spArgs('id')));
		$this->accountList = $result['data'];
		$this->log(__CLASS__, __FUNCTION__, "查看用户其他账户信息列表", 1, 'view');
		$this->display("../template/admin/{$this->theme}/base/balance/page/cashApply/cashAccountList.html");
	}
	
	/**
	 * 审核余额提现申请
	 */
	function checkCashApply(){
		$isCheck = $this->spArgs('isCheck');
		$error_info = $this->spArgs('info');
		//审核通过与否修改的共同信息
		if(!class_exists('lib_balance_apply')) include 'model/base/lib_balance_apply.php';
		$lib_balance_apply = new lib_balance_apply();
		$applyInfo = $lib_balance_apply->findApply(array('id' => $this->spArgs('id')));//申请提现单条记录
		//查找分销商表里面的user_id和nick_name
		$userInfo = $this->lib_user->findUser(array('id' => $applyInfo['data']['user_id']));
		//判断是否允许审核通过
		if($isCheck == 1){//审核通过
			//提现金额大于所拥有的余额
			if($userInfo['data']['freeze_balance'] < $applyInfo['data']['money']){
				$isCheck = 2;
				$error_info = '提现金额大于您所拥有的余额，审核失败！';
				$cash_static = 0;
				$littleMoneyResult = common::errorArray(1, $error_info, '');
			}else{
				if($applyInfo['data']['model'] == 0){//线下模式
					$cash_static = 1;
					$notice_text = '即将打款到您的账户';
				}else{//红包模式
					$result = $lib_balance_apply->payMoney($applyInfo['data']['id'],1);
					
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
		$result = $lib_balance_apply->updateApply(
				array('id' => $this->spArgs('id')),
				array(
					"is_check" => $isCheck,//0未审核1审核通过2审核不通过
					"checker" => $_SESSION['admin']['account'],
					"error_info" => $error_info,
					'cash_static' => $cash_static
				)
		);
		
		if($cash_static == 0){//审核失败
			if(!class_exists('m_base_user')) include_once 'model/base/table/m_base_user.php';
	        $m_base_user = new m_base_user();
		    $m_base_user->update(
		        array('id' => $applyInfo['data']['user_id']),
		        "balance=balance+{$applyInfo['data']['money']},freeze_balance=freeze_balance-{$applyInfo['data']['money']}"
		        );
		}
		
		//审核结果信息插入用户消息表操作
		$content = ($isCheck == 1)?"恭喜恭喜！,您申请的余额提现获得通过，{$notice_text}！":"十分抱歉！,您申请的余额提现未获通过。提示：".$error_info;
		if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
		$lib_user_message = new lib_user_message();
		$messageResult = $lib_user_message->sendMessage(
				array(
						"user_id" => $userInfo['data']['user_id'],
						"nick_name" => $userInfo['data']['nick_name'],
						"title" => "您申请的余额提现审核结果出炉啦！",
						"content" => $content
				)
		);
		
		$this->log(__CLASS__, __FUNCTION__, "审核余额提现申请", 0, 'edit');
		if($littleMoneyResult){
			echo json_encode($littleMoneyResult);
		}else{
			echo json_encode($result);
		}
		
	}
	
	/**
	 * 改变余额提现状态
	 */
	function editCashStaticApply(){
		if(!class_exists('m_base_user')) include_once 'model/base/table/m_base_user.php';
        $m_base_user = new m_base_user();
		//更改余额提现状态
		$cash_static = $this->spArgs('cash_static');
		if(!class_exists('lib_balance_apply')) include 'model/base/lib_balance_apply.php';
		$lib_balance_apply = new lib_balance_apply();
		$applyInfo = $lib_balance_apply->findApply(array('id' => $this->spArgs('id')));//申请提现单条记录
		//查找分销商表里面的user_id和nick_name
		$userInfo = $this->lib_user->findUser(array('id' => $applyInfo['data']['user_id']));
		//更改打款状态时判断金额是否足够
		if($userInfo['data']['freeze_balance'] < $applyInfo['data']['money']){
			$result = $lib_balance_apply->updateApply(
					array('id' => $this->spArgs('id')),
					array("is_check" => 2)//审核不通过
			);
			
		    $m_base_user->update(
			    array('id' => $applyInfo['data']['user_id']),
			    "balance=balance+{$applyInfo['data']['money']},freeze_balance=freeze_balance-{$applyInfo['data']['money']}"
			);
			echo json_encode(common::errorArray(1, '提现金额不足，审核不通过', ''));
			exit;
		}
		//打款成功：修改提现状态、可取金额、余额提现记录、用户消息提示
		if($cash_static == 3){
			//打款成功后插入余额提现记录
			if(!class_exists('lib_balance_record')) include 'model/base/lib_balance_record.php';
			$lib_balance_record = new lib_balance_record();
			$result = $lib_balance_record->addRecord(
					array(
							'nick_name' => $applyInfo['data']['nick_name'],
							'user_id' => $applyInfo['data']['user_id'],
							'money' => $applyInfo['data']['money'],
							'checker' => $applyInfo['data']['checker']
					)
			);
			
			//审核结果信息插入用户消息表操作
			$content = "您申请提现的余额已经打款成功，请及时查看您的账户金额！";
			if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
			$lib_user_message = new lib_user_message();
			$result = $lib_user_message->sendMessage(
					array(
							"user_id" => $userInfo['data']['id'],
							"nick_name" => $userInfo['data']['nick_name'],
							"title" => "您申请的余额提现打款到账啦！",
							"content" => $content
					)
			);
			
			//维护分销商资金
		 	$m_base_user->update(
			    array('id' => $applyInfo['data']['user_id']),
			    "freeze_balance=freeze_balance-{$applyInfo['data']['money']}"
			);
		}
		//修改提现状态
		$result = $lib_balance_apply->updateApply(
				array('id' => $this->spArgs('id')),
				array("cash_static" => $cash_static)//提现状态
		);
		$this->log(__CLASS__, __FUNCTION__, "改变余额提现状态", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 单个删除分余额提现申请
	 */
	function deleteCashApply(){
		if(!class_exists('lib_balance_apply')) include 'model/base/lib_balance_apply.php';
		$lib_balance_apply = new lib_balance_apply();
		$result = $lib_balance_apply->deleteApply( array('id' => $this->spArgs('id')) );
		$this->log(__CLASS__, __FUNCTION__, "单个删除分余额提现申请", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除余额提现申请
	 */
	function deleteCashApplyBatch(){
		if(!class_exists('lib_balance_apply')) include 'model/base/lib_balance_apply.php';
		$lib_balance_apply = new lib_balance_apply();
		$result = $lib_balance_apply->deleteApplyBatch( $this->spArgs('ids') );
		$this->log(__CLASS__, __FUNCTION__, "批量删除余额提现申请", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询余额提现申请记录
	 */
	function pagingCashApply(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('is_check'=>'=','nick_name'=>'like','cash_static'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
		$conditionList = $this->getPagingList($this, $keyValueList);
		if("" != $this->spArgs('low_money') && null != $this->spArgs('low_money')){
			array_push($conditionList,  array("field" => "money","operator" => ">=","value" => $this->spArgs('low_money')));
		}
		if("" != $this->spArgs('up_money') && null != $this->spArgs('up_money')){
			array_push($conditionList,  array("field" => "money","operator" => "<=","value" => $this->spArgs('up_money')));
		}
		$sort = "add_time desc";
		if(!class_exists('lib_balance_apply')) include 'model/base/lib_balance_apply.php';
		$lib_balance_apply = new lib_balance_apply();
		$result = $lib_balance_apply->pagingApply($page, $conditionList, $sort);
		echo json_encode($result);
	}
	
	/**
	 * 打款 发红包
	 */
	function sendRedPacket(){
		//查看余额提现信息
		if(!class_exists('lib_balance_apply')) include 'model/base/lib_balance_apply.php';
		$lib_balance_apply = new lib_balance_apply();
		
		if(!class_exists('UtilConfig'))include 'include/UtilConfig.php';
		$lib_config = new UtilConfig('base_config');
		$baseConfig = $lib_config->findConfigThemeValue();
		if($baseConfig['data']['withdraw_model'] == 1){//1：微信零钱模式 2：红包模式
    		$result = $lib_balance_apply->payMoney($this->spArgs('id'),1);
		}else{
		    //申请信息
		    if(!class_exists('lib_balance_apply')) include 'model/base/lib_balance_apply.php';
		    $lib_balance_apply = new lib_balance_apply();
		    $cashApplyInfo = $lib_balance_apply->findApply(array('id' => $this->spArgs('id')));
		    //查询该服务商的user_id获取到该用户的open_id
		    
		    //微信配置信息
		    $lib_config = new UtilConfig('weixin_config');
		    $weixinResult = $lib_config->findConfigThemeValue();
		    //用户信息
		    if(!class_exists('lib_user')) include 'model/base/lib_user.php';
		    $lib_user = new lib_user();
		    $userInfo = $lib_user->findUser(array('id' => $cashApplyInfo['data']['user_id']));
		    
		    $openId = $userInfo['data']['open_id'];
		    $sender = $userInfo['data']['nick_name'];
		    $mchId = $weixinResult['data']['wechat_mchid'];
		    $appId = $weixinResult['data']['wechat_appid'];
		    $wishing = $baseConfig['data']['balance_withdraw_wish'];
		    $actName = $baseConfig['data']['balance_withdraw_name'];
		    $remark = $baseConfig['data']['balance_withdraw_remark'];
		    $payKey = $weixinResult['data']['wechat_pay_key'];
		    $money = $cashApplyInfo['data']['money'] * 100;
		    
		    if(!class_exists('lib_red_packet')) include 'model/activity/packet/lib_red_packet.php';		
		    $lib_packet = new lib_red_packet();
		    $result = $lib_packet->sendRedPacket($openId, $money, $sender, $mchId, $appId, $wishing, $actName, $remark, $payKey);   
		}
		
		$this->log(__CLASS__, __FUNCTION__, "打款 发红包", 0, 'edit');
		echo json_encode($result);
	}
	
	//------------------------------------------维护粉丝信息------------------------------------------
	
	/**
	 * 获取所有的Openid
	 */
	function groupFans(){
	    $defineCount = 100;//openid每组分100个
	    //获取所有粉丝openid
	    $rawFansList = FansManage::getAllFansList();
	    $count = $rawFansList['total'];
	    $groupCount = ceil($count / $defineCount);
	    //openid将分组存入数据库
	    $openIdList = $rawFansList['data']['openid'];
	    $result = array_chunk($openIdList, $defineCount);
	    $_SESSION['groupFans'] = $result;
	    echo json_encode(common::errorArray(0, "分组完成", $groupCount));
	}
	
	/**
	 * 同步微信头像
	 */
//	function asynGroupFansImg(){

//	    if(!$_SESSION['groupFans']){

//	        echo json_encode(common::errorArray(1, "没有可以处理的数据", false));

//	        exit;

//	    }

//	    //获取当前处理批次

//	    $batch = $this->spArgs('batch');

//	    $currentGroup = $_SESSION['groupFans'][$batch - 1];

//	    $userInfoList = FansManage::getFansInfoBatch($currentGroup);

//	    $batchUpateSql = "UPDATE base_user SET head_img_url = CASE open_id ";

//	    $when = '';

//	    //处理

//	    foreach ($userInfoList['user_info_list'] as $fansInfo){

//	        $when .= " WHEN '{$fansInfo['openid']}' THEN '{$fansInfo['headimgurl']}' ";

//	        $openIds .= "'{$fansInfo['openid']}',";

//	    }

//	    $when .= $when . " END ";

//	    $openIds = rtrim($openIds,',');

//	    $where = "WHERE open_id IN({$openIds})";

//	    $batchUpateSql = $batchUpateSql . $when . $where;

//	    include_once  'model/base/lib_user.php';

//	    $lib_user = new lib_user();

//	    $lib_user->runSql($batchUpateSql);

//	    if($this->spArgs('groupCount') == $batch){

//	        echo json_encode(common::errorArray(3, "更新头像完成", true));

//	    }else{

//	        echo json_encode(common::errorArray(0, "更新头像成功", true));

//	    }

//	}

	/**
	 * 异步处理微信头像和昵称
	 */
	function asynGroupFansNickname(){
	    if(!$_SESSION['groupFans']){
	        echo json_encode(common::errorArray(1, "没有可以处理的数据", false));
	        exit;
	    }
	    //获取当前处理批次
	    $batch = $this->spArgs('batchs');
	    $currentGroup = $_SESSION['groupFans'][$batch - 1];
	    $userInfoList = FansManage::getFansInfoBatch($currentGroup);
	    $batchUpateSql = "UPDATE base_user SET nick_name = CASE open_id ";
		$batchUpateSql_head = "UPDATE base_user SET head_img_url = CASE open_id ";
	    $when = '';
		$when_head = '';
	    //处理
	    //微信昵称
	    foreach ($userInfoList['user_info_list'] as $fansInfo){
	    	$nickname=addslashes($fansInfo['nickname']);
	        $when .= " WHEN '{$fansInfo['openid']}' THEN '{$nickname}'";
	        $openIds .= "'{$fansInfo['openid']}',";
	    }
		//微信头像
		foreach ($userInfoList['user_info_list'] as $fansInfo){
	        $when_head .= " WHEN '{$fansInfo['openid']}' THEN '{$fansInfo['headimgurl']}' ";
	        $openIds .= "'{$fansInfo['openid']}',";
	    }
	    $when .= $when . " END ";
		$when_head .= $when_head . " END ";
	    $openIds = rtrim($openIds,',');
	    $where = "WHERE open_id IN({$openIds})";
	    $batchUpateSql = $batchUpateSql . $when . $where;
		$batchUpateSql_head = $batchUpateSql_head . $when_head . $where;
	    include_once  'model/base/lib_user.php';
	    $lib_user = new lib_user();
	    $lib_user->runSql($batchUpateSql);
	    $lib_user->runSql($batchUpateSql_head);
	    if($this->spArgs('groupCount') == $batch){
	        echo json_encode(common::errorArray(3, "更新头像完成", true));
	    }else{
	        echo json_encode(common::errorArray(0, "更新头像成功", true));
	    }
	}

	//------------------------------------------积分提现------------------------------------------
	
	/**
	 * 所有模块积分提现记录
	 */
	function cashList(){
	    $this->getSetMenu($this);
 	    $result = $this->lib_points->findRecordField('type');
 	    $this->types = $result['data'];
	    $this->log(__CLASS__, __FUNCTION__, "积分提现列表页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/base/points/page/cashList.html");
	}
	
	/**
	 * 分页积分提现记录
	 */
	function pagingCash(){
	    $page = $this->getPageInfo($this);
	    $keyValueList = array('nick_name'=>'like','type'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
	    $conditionList = $this->getPagingList($this, $keyValueList);
	    $sort = "add_time desc";
	    $result = $this->lib_points->pagingRecord($page,$conditionList,$sort);
	    echo json_encode($result);
	}
	
	/**
	 * 删除提现记录
	 */
	function deleteCash(){
	    $ids = $this->spArgs('ids');
	    $result = $this->lib_points->deleteRecord($ids);
	    $this->log(__CLASS__, __FUNCTION__, "删除提现记录", 0, 'del');
	    echo json_encode($result);
	}
	
	/**
	 * 积分提现记录详情
	 */
	function cashDetail(){
	    $this->getMenu($this);
	    $result = $this->lib_points->findRecord(array('id'=>$this->spArgs('id')));
	    $this->recordInfo = $result['data'];
	    $this->log(__CLASS__, __FUNCTION__,"积分提现记录详情", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/base/points/page/cashDetail.html");
	}
}