<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_distributor')) require 'model/fen/lib_distributor.php';
if(!class_exists('lib_deal_record')) require 'model/fen/lib_deal_record.php';
if(!class_exists('lib_apply')) require 'model/fen/lib_apply.php';
/**
 *用户管理
 * @name fen_distributor.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-05
 */
class fen_distributor extends admin_controller{
    private $lib_distributor;
    private $lib_apply;
    private $lib_deal_record;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->lib_distributor = new lib_distributor();
		$this->lib_apply = new lib_apply();
		$this->lib_deal_record = new lib_deal_record();
	}
	
	//--------------------------分销商申请---------------------------------------
	
	/**
	 *分销商申请列表页面
	 */
	function applyList(){
		$this->getSetMenu($this);//设置侧边栏导航
		$this->log(__CLASS__, __FUNCTION__, "分销商申请列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/apply/applyList.html");
	}
	/**
	 * 分销商申请详情页面
	 */
	function applyDetail(){
		$this->getMenu($this);//设置页面公共数据
		//获取该分销商信息
		$result = $this->lib_apply->findApply(array('id' => $this->spArgs('id')));
		$this->applyInfo = $result[data];
		//获取上级分销商的信息
		$result = $this->lib_distributor->findDistributor(array('id' => $result['data']['parent_id']));
		$this->distributorInfo = $result[data];
		$this->log(__CLASS__, __FUNCTION__, "分销商申请详情页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/apply/applyDetail.html");
	}
	
	/**
	 * 审核分销商申请
	 */
	function checkApply(){
		$id = $this->spArgs('id');
		$isCheck = $this->spArgs('isCheck');//0未审核1审核通过2审核不通过
		//修改审核通过与否的分销商申请共同信息
		$applyInfo = $this->lib_apply->findApply(array('id' =>$id));//获取该分销商信息
		//判断是否已经是分销商
		if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
		$lib_user_message = new lib_user_message();
		$result = $this->lib_distributor->findDistributor(array('user_id' => $applyInfo['data']['user_id']));
		if($result['errorCode'] == 0){
			$this->lib_apply->updateApply(
					array('id' => $id),
					array(
							"is_check" => 2,
							"checker" => $_SESSION['admin']['account'],
							"error_info" => "对不起，您已经是分销商了，不能重复申请"
					)
			);
			$lib_user_message->addUserMessage(
					array(
							"user_id" => $applyInfo['data']['user_id'],
							"nick_name" => $applyInfo['data']['nick_name'],
							"title" => "您的分销商申请审核结果出炉啦！",
							"content" => "对不起，您已经是分销商了，不能重复申请"
					)
			);
			$result['errorCode'] = 1;
			$result['errorInfo'] = '该用户已经是分销商';
		}else{//不是分销商时
			//更改审核后的分销商申请表信息
			$result['errorCode'] = 0;
			$this->lib_apply->updateApply(
					array('id' => $id),
					array(
							"is_check" => $isCheck,
							"checker" => $_SESSION['admin']['account'],
							"error_info" => $this->spArgs('info')
					)
			);
			//审核结果信息插入用户消息表操作
			$content = ($isCheck == 1)?"恭喜恭喜！".$applyInfo['data']['nick_name'].",您的分销商申请获得通过":"对不起！".$applyInfo['data']['nick_name'].",您的分销商申请未获通过。提示：".$this->spArgs('info');
			
			$lib_user_message->addUserMessage(
					array(
						"user_id" => $applyInfo['data']['user_id'],
						"nick_name" => $applyInfo['data']['nick_name'],
						"title" => "您的分销商申请审核结果出炉啦！",
						"content" => $content
						)
					);
			//判断审核通过后的状况
			if($isCheck == 1){
				//更改user表用户状态
				include_once 'model/lib_user.php';
				$lib_user = new lib_user();
				$lib_user->updateUser(
						array('id' => $applyInfo['data']['user_id']),
						array('type' => 3)
					);
				//生成分销商信息
				$this->lib_distributor->addDistributor(
					array(
						'name' => $applyInfo['data']['name'],
						'phone' => $applyInfo['data']['phone'],
						'parent_id' => $applyInfo['data']['parent_id'],
						'grand_id' => $applyInfo['data']['grand_id'],
						'rank' => $applyInfo['data']['rank'],
						'user_id' => $applyInfo['data']['user_id'],
						'nick_name' => $applyInfo['data']['nick_name'],
						'shop_name' => $applyInfo['data']['nick_name'].'的店铺',
						'shop_logo' => $applyInfo['data']['head_img_url']
					)	
				);	
			}
		}
		$this->log(__CLASS__, __FUNCTION__, "审核分销商申请", 0, 'edit');
		echo json_encode($result);
	}

	/**
	 ** 分销商配置页面
	 **/
	function distributorConfig(){
	    $this->getSetMenu($this);//设置侧边栏导航
        $configResult = $this->lib_apply->tableConfigObj->findAllConfigComplete();
        $this->configInfo = $configResult['data'];
        $this->log(__CLASS__, __FUNCTION__, "分销商配置页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/distributor/distributorConfig.html");
	}
    
	/**
	 * 设置分销配置
	 */
	function setFeeRatioConfig(){
        $configInfo = $this->getConfigInfo();
        $result = $this->lib_apply->tableConfigObj->updateConfigList($configInfo);
        $this->log(__CLASS__, __FUNCTION__, "设置分销配置", 0, 'edit');
        echo json_encode($result);
    }
    
	/**
	 * 单个删除分销商申请
	 */
	function deleteApply(){
		//删除分销商申请信息
		$result = $this->lib_apply->deleteApply( array('id' => $this->spArgs('id')) );
		$this->log(__CLASS__, __FUNCTION__, "单个删除分销商申请", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除分销商申请
	 */
	function deleteApplyBatch(){
		$result = $this->lib_apply->deleteApplyBatch( $this->spArgs('ids') );
		$this->log(__CLASS__, __FUNCTION__, "批量删除分销商申请", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询分销商申请记录
	 */
	function pagingApply(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('name'=>'like','phone'=>'=','is_check'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
		$conditionList = $this->getPagingList($this, $keyValueList);
		$sort = "add_time desc";//排序
		$result = $this->lib_apply->pagingApply($page, $conditionList, $sort);
		echo json_encode($result);
	}
	
	//----------------------------------分销商管理----------------------
	
	/**
	 * 分销商列表页面
	 */
	function distributorList(){
		$this->getSetMenu($this);//设置侧边栏导航
		$this->log(__CLASS__, __FUNCTION__, "分销商列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/distributor/distributorList.html");
	}
	
	/**
	 * 查看上下级分销商页面
	 */
	function UpDownDistributorList(){
		$this->getMenu($this);//设置页面公共数据
		$id = $this->spArgs('id'); //获取本级分销商id
		//获取当前分销商信息
		$firDistributorResult = $this->lib_distributor->findDistributor(array('id' => $id));
		$this->distributorInfo = $firDistributorResult['data'];
		
		//获取上下级分销商信息
		$result = $this->lib_distributor ->findUpDownDistributor($id,$firDistributorResult['data']['parent_id'],$firDistributorResult['data']['grand_id'],$firDistributorResult['data']['grand_grand_id']);
		$this->upDownDistributorList = $result['data'];
		$this->log(__CLASS__, __FUNCTION__, "查看上下级分销商页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/distributor/upDownDistributorList.html");
	}
	
	/*
	 * 佣金来源
	 * */
	function feeFromRecord(){
		$this->getMenu($this);//设置页面公共数据
		$id=$this->spArgs("distributor_id");
		//获取当前分销商信息
		$firDistributorResult = $this->lib_distributor->findDistributor(array('id' => $id));
		$this->distributorInfo = $firDistributorResult['data'];
		
		//获取来源信息
		$sql="select * from fen_deal_record where distributor_type=1 and distributor_id={$id}";
		$result=$this->lib_deal_record->findSql($sql);	
		foreach($result as &$val){
			//获取订单信息
			if(!class_exists('lib_order')) include 'model/store/lib_order.php';
		    $lib_order = new lib_order();
			$orderInfo=$lib_order->getOrderInfo(array('id'=>$val['oid']));
			$val['orderInfo']=$orderInfo['data'];
		}
		
		$this->feeRecord=$result;
		$this->log(__CLASS__, __FUNCTION__, "查看佣金来源页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/distributor/feeFromRecord.html");
	}
	
	/**
	 * 添加分销商页面
	 */
	function addDistributor(){
		$this->getMenu($this);//设置页面公共数据
		$this->log(__CLASS__, __FUNCTION__, "添加分销商页面", 1, 'add');
		$this->display("../template/admin/{$this->theme}/fen/page/distributor/addDistributor.html");
	}
	
	/**
	 * 绑定分销商页面
	 **/
	function showBindDistributor(){
		$this->log(__CLASS__, __FUNCTION__, "绑定分销商页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/distributor/bindDistributor.html");
	}
	
	/**
	 * 修改分销商信息页面
	 */
	function editDistributor(){
		$this->getMenu($this);//设置页面公共数据
		//获取该分销商信息
		$result = $this->lib_distributor->findDistributor(array('id' => $this->spArgs('id')));
		$this->distributorInfo = $result[data];
		$this->log(__CLASS__, __FUNCTION__, "修改分销商信息页面", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/fen/page/distributor/editDistributor.html");
	}
	
	/**
	 * 修改分销商信息页面
	 */
	function distributorInfo(){
	    $this->getMenu($this);//设置页面公共数据
	    //获取该分销商信息
	    $result = $this->lib_distributor->findDistributor(array('id' => $this->spArgs('id')));
	    $this->distributorInfo = $result[data];
	    $this->log(__CLASS__, __FUNCTION__, "分销商详情页面", 1, 'view');
	    $this->display("../template/admin/{$this->theme}/fen/page/distributor/distributorInfo.html");
	}
	
	/**
	 * 删除单条分销商信息
	 */
	function deleteDistributor(){
		$result = $this->lib_distributor->deleteDistributor( array('id' => $this->spArgs('id')) );
		$this->log(__CLASS__, __FUNCTION__, "删除单条分销商信息", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 删除多条分销商信息
	 */
	function deleteDistributorBatch(){
		$result = $this->lib_distributor->deleteDistributorBatch( $this->spArgs('ids') );
		$this->log(__CLASS__, __FUNCTION__, "删除多条分销商信息", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 修改分销商信息
	 */
	function updateDistributor(){
	    $distributorInfo = $this->getArgsList($this, array(user_id,name,phone,profits,level,my_fee,fir_fee,sec_fee,thr_fee,level,freeze_fee));
		$distributorInfo['total_fee'] = $distributorInfo['fir_fee'] + $distributorInfo['sec_fee'] + $distributorInfo['thr_fee'];
		$result = $this->lib_distributor->updateDistributor( 
				array('id' => $this->spArgs('id')),
				$distributorInfo
		);
		$this->log(__CLASS__, __FUNCTION__, "修改分销商信息", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 修改分销商信息
	 */
	function updateDistributorStatic(){
		$result = $this->lib_distributor->updateDistributor( 
				array('id' => $this->spArgs('id')),
				array('is_use' => $this->spArgs('is_use'))
		);
		$this->log(__CLASS__, __FUNCTION__, "修改分销商状态", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 获取三级分销商信息（包括自己及以下两级）
	 */
	function getThrRankDistributor(){
		$id = $this->spArgs('id'); //根据id获取本级分销商信息
		$result = $this->lib_distributor->findThrDistributor($id);
		$this->log(__CLASS__, __FUNCTION__, "获取三级分销商信息（包括自己及以下两级）", 0, 'view');
		echo json_encode($result);
	}

	/**
	 * 分页查询分销商信息
	 */
	function pagingDistributor(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('is_use' => '=','phone'=>'=','name'=>'like','rank'=>'=','level'=>'=','from_add_time'=>'>=','to_add_time'=>'<=','from_total_fee' => '>=','to_total_fee' => '<=');
		$conditionList = $this->getPagingList($this, $keyValueList);
		
		$sort = "add_time desc";
		$result = $this->lib_distributor->pagingDistributor($page, $conditionList, $sort);
		echo json_encode($result);
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
		$result = $lib_cash_account->findAllAccount(array('distributor_id' => $this->spArgs('id')));
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
		//查找分销商表里面的user_id和nick_name
		$distributorInfo = $this->lib_distributor->findDistributor(array('id' => $applyInfo['data']['distributor_id']));
		//判断是否允许审核通过
		if($isCheck == 1){//审核通过
			//提现金额大于所拥有的佣金
			if($distributorInfo['data']['freeze_fee'] < $applyInfo['data']['money']){
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
		
		if($cash_static == 0){//审核失败
		    $this->lib_distributor->updateDistributor(
		        array('id' => $applyInfo['data']['distributor_id']),
		        "my_fee=my_fee+{$applyInfo['data']['money']},freeze_fee=freeze_fee-{$applyInfo['data']['money']}"
		        );
		}
		
		//审核结果信息插入用户消息表操作
		$content = ($isCheck == 1)?"恭喜恭喜！,您申请的佣金提现获得通过，{$notice_text}！":"十分抱歉！,您申请的佣金提现未获通过。提示：".$error_info;
		if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
		$lib_user_message = new lib_user_message();
		$messageResult = $lib_user_message->sendMessage(
				array(
						"user_id" => $distributorInfo['data']['user_id'],
						"nick_name" => $distributorInfo['data']['nick_name'],
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
		//查找分销商表里面的user_id和nick_name
		$distributorInfo = $this->lib_distributor->findDistributor(array('id' => $applyInfo['data']['distributor_id']));
		//更改打款状态时判断金额是否足够
		if($distributorInfo['data']['freeze_fee'] < $applyInfo['data']['money']){
			$result = $lib_cash_apply->updateApply(
					array('id' => $this->spArgs('id')),
					array("is_check" => 2)//审核不通过
			);
			$this->lib_distributor->updateDistributor(
			    array('id' => $applyInfo['data']['distributor_id']),
			    "my_fee=my_fee+{$applyInfo['data']['money']},freeze_fee=freeze_fee-{$applyInfo['data']['money']}"
			);
			echo json_encode(common::errorArray(1, '提现金额不足，审核不通过', ''));
			exit;
		}
		//打款成功：修改提现状态、可取金额、佣金提现记录、用户消息提示
		if($cash_static == 3){
			//打款成功后插入佣金提现记录
			if(!class_exists('lib_cash_record')) include 'model/fen/lib_cash_record.php';
			$lib_cash_record = new lib_cash_record();
			$result = $lib_cash_record->addRecord(
					array(
							'distributor_name' => $applyInfo['data']['distributor_name'],
							'distributor_id' => $applyInfo['data']['distributor_id'],
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
							"user_id" => $distributorInfo['data']['user_id'],
							"nick_name" => $distributorInfo['data']['nick_name'],
							"title" => "您申请的佣金提现打款到账啦！",
							"content" => $content
					)
			);
			
			//维护分销商资金
			$this->lib_distributor->updateDistributor(
			    array('id' => $applyInfo['data']['distributor_id']),
			    "freeze_fee=freeze_fee-{$applyInfo['data']['money']}"
			);
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
		$keyValueList = array('is_check'=>'=','distributor_name'=>'like','cash_static'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
		$conditionList = $this->getPagingList($this, $keyValueList);
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
		echo json_encode($result);
	}
	
	/**
	 * 打款 发红包
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
	
	
	//交易记录
	
	/**
	 *交易列表页面
	 */
	function dealList(){
		$this->getSetMenu($this);//设置侧边栏导航
		/*
		$this->did = $this->spArgs('did');//接收客户管理跳转did
		$this->account = $this->spArgs('account');//接收客户管理跳转的account
		$moduleResult = $this->lib_deal_record->getDistinctModule();
		$this->moduleList = $moduleResult['data'];
		*/
		$this->log(__CLASS__, __FUNCTION__, "交易成功列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/dealAnalyse/dealList.html");
	}
	
	/**
	 * 分页查询交易成功记录
	 * 查询分销商的记录(distributor_type=1)
	 */
	function pagingDeal(){
		$page = $this->getPageInfo($this);
		$keyValueList = array('order_num' => '=','distributor_name' => 'like','from_add_time' => '>=','to_add_time' => '<=','from_money' => '>=','to_money' => '<=');
		$conditionList = $this->getPagingList($this, $keyValueList);
		
		//加上分销商类型
		$conditionList[]=array('field'=>'distributor_type','operator' =>'=','value' =>1);
		$sort = "add_time desc";
		$result = $this->lib_deal_record->pagingDealRecord($page, $conditionList, $sort);
		
		//查询分销商的用户昵称
		foreach($result['data']['dataList'] as &$val){
			if(!class_exists('lib_user')) include 'model/base/lib_user.php';
		    $lib_user = new lib_user();
		    $userInfo = $lib_user->findUser(array('id' => $val['user_id']));
		    $val['nick_name']=$userInfo['data']['nick_name'];
		}
		echo json_encode($result);
	}
	
	
	//交易成功统计分析
	
	/**
	 *统计分析页面
	 */
	function dealAnalyse(){
		$this->getSetMenu($this);//设置侧边栏导航
		$this->log(__CLASS__, __FUNCTION__, "统计分析页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/dealAnalyse/dealAnalyse.html");
	}
	
	/**
	 *统计报表分析页面
	 */
	 function dealAnalyseDetail(){
		 $this->getMenu($this);//设置侧边栏导航
		 $did = $this->spArgs('did');
		 $distributorInfo = $this->lib_distributor->findDistributor(array('id' => $did));
		 $this->distributor = $distributorInfo['data'];
		 $this->log(__CLASS__, __FUNCTION__, "统计报表分析页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/dealAnalyse/dealAnalyseDetail.html");
	}
	
	/**
	 * 分页查询交易统计记录
	 */
	function pagingDealAnalyse(){
		$page = $this->getPageInfo($this);
		
		$keyValueList = array('name' => 'like','from_add_time' => '>=','to_add_time' => '<=','from_total_sales_fee' => '>=','to_total_sales_fee' => '<=');
		$conditionList = $this->getPagingList($this, $keyValueList);
		$keyValueList = array('from_add_time' => '>=','to_add_time' => '<=');
		$timeList = $this->getPagingList($this, $keyValueList);
		
		$sort = "order by total_sales_fee desc,add_time desc";
		$result = $this->lib_deal_record->pagingDealAnalyse($page, $conditionList,$timeList,$sort);
		echo json_encode($result);
	}
	
	/**
	 * 分销交易统计饼状图
	 */
	function dealPieChartData(){
	    $info = $this->getArgsList($this,array(did, state,date));
	    $result = $this->lib_deal_record->dealPieChartData($info);
	    echo json_encode($result);
	}
	
	/**
	 * 分销交易统计折线图
	 */
	function dealLineChartData(){
	    $info = $this->getArgsList($this,array(did, state,date));
	    $result = $this->lib_deal_record->dealLineChartData($info);
	    echo json_encode($result);
	}
	
	//客户管理
	
	/**
	 * 客户列表页面
	 */
	function dealAccountList(){
		$this->getSetMenu($this);//设置侧边栏导航
		$this->log(__CLASS__, __FUNCTION__, "客户列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/fen/page/dealAnalyse/dealAccountList.html");
	}
	/**
	 * 分页查询客户列表页面
	 */
	function pagingDealAccount(){
		$page = $this->getPageInfo($this);
		$conditionList = array();//默认无条件 必须有
		if("" != $this->spArgs('from') && null != $this->spArgs('from')){
			array_push($conditionList,  array("field" => "add_time","operator" => ">=","value" => $this->spArgs('from')));
		}
		if("" != $this->spArgs('to') && null != $this->spArgs('to')){
			array_push($conditionList,  array("field" => "add_time","operator" => "<=","value" => $this->spArgs('to')));
		}
		$money = array();//默认无条件 必须有
		if("" != $this->spArgs('nick_name') && null != $this->spArgs('nick_name')){
			array_push($money,  array("field" => "nick_name","operator" => "like","value" => $this->spArgs('nick_name')));
		}
		if("" != $this->spArgs('low_money') && null != $this->spArgs('low_money')){
			array_push($money,  array("field" => "total_money","operator" => ">=","value" => $this->spArgs('low_money')));
		}
		if("" != $this->spArgs('up_money') && null != $this->spArgs('up_money')){
			array_push($money,  array("field" => "total_money","operator" => "<=","value" => $this->spArgs('up_money')));
		}
		$sort = "total_money desc";
		$group = "account";
		$result = $this->lib_deal_record->pagingDealAccount($page, $conditionList,$money,$sort,$group);
		echo json_encode($result);
	}

	/**
     * 构造修改信息
     * @param string lib_config
     * @return array
     */
    private function getConfigInfo(){
        $configInfo = array();
        $result = $this->lib_apply->tableConfigObj->findAllConfigComplete();//获取所有配置$result['data']
        foreach ($result['data'] as $per){
            $configInfo[$per['item_key']] = $this->spArgs($per['item_key']);
            //判断是否改变晋升分销商的条件
            if($per['item_key'] == 'distribute_fee' && $configInfo[$per['item_key']] != $per['item_value']){
                $this->lib_distributor->maintenanceDistributorTable($configInfo[$per['item_key']],$per['item_value']);
            }
        }
        return $configInfo;
    }
}