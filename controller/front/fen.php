<?php
if(!class_exists('front_controller')) require 'include/base/controller/front/front_controller.php';
if(!class_exists('lib_distributor')) include "model/fen/lib_distributor.php";
if(!class_exists('lib_user')) require 'model/base/lib_user.php';
if(!class_exists('lib_common')) include 'model/base/lib_common.php';
/**
 * 前台分销商相关功能
 * @name fen.php
 * @package cwms
 * @category controller
 * @link http://www.changekeji.com
 * @author lay
 * @version 1.0
 * @copyright CHANGE INC
 * @since 2016-12-24
 */
class fen extends front_controller{
    private $lib_user;
    private $lib_common;
    private $lib_distributor;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->debug();
		$this->lib_user = new lib_user();
		$this->lib_common = new lib_common();
		$this->lib_distributor = new lib_distributor();
	}
	
	/*
	 * 分销商中心
	 * */
	function fenCenter(){
		$this->verifyLogin($this);
		$condition['user_id']= $_SESSION['user']['id'];
		
		//引入分销用户类文件
		if(!class_exists('lib_distributor'))include "model/fen/lib_distributor.php";
		$lib_distributor=new lib_distributor();
		$fenInfo=$lib_distributor->findDistributor($condition);
		$this->fenInfo=$fenInfo['data'];
		$this->log(__CLASS__, __FUNCTION__, "分销商中心", 1, "view");
		$this->display("../template/front/common/fen/page/fen.html");
	}
	
	/*
	 * 收益统计 
	 * */
	function incomeCount(){
		$this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'store_theme');
		$this->log(__CLASS__, __FUNCTION__, "收益统计", 1, "view");
		$this->display("../template/front/common/fen/page/incomeCount.html");
	}

	/*
	 * 异步请求展示收益统计
	 * 查看我的子级(或子子级)的累计收益 
	 * */
	function showIncomeCount(){
		$this->verifyLogin($this);
		$page = $this->getPageInfo($this);
		$condition['user_id']= $_SESSION['user']['id'];
		extract($page);
		//引入分销用户类文件
		if(!class_exists('lib_distributor'))include "model/fen/lib_distributor.php";
		$lib_distributor=new lib_distributor();
		$fenInfo=$lib_distributor->findDistributor($condition);
		
		//查询我的子级分销商
		$conditions[]=array("field"=>'parent_id','operator'=>'=','value'=>$fenInfo['data']['id']);
		$incomeResult=$lib_distributor->pagingDistributor($page,$conditions);

		/*
		//若要查询子级以及子级的子级
		if(!class_exists('m_fen_distributor'))include "model/fen/table/m_fen_distributor.php";
		$m_fen_distributor=new m_fen_distributor();
		$sql="select * from fen_distributor where parent_id={$fenInfo['data']['id']} or grand_id={$fenInfo['data']['id']}";
		$result['dataList']=$m_fen_distributor->spPager($pageIndex,$pageSize)->findSql($sql);
		$result['pageInfo']=$m_fen_distributor->spPager()->getPager();
		
		if($result['pageInfo']==NULL){
			$all_pages=count($result['dataList'])%$pageSize==0?intval(count($result['dataList'])/$pageSize):intval(count($result['dataList'])/$pageSize)+1;
			$all=array();
			for ($i=1; $i<=$all_pages; $i++) { 
				$all[]=$i;
			}
			$result['pageInfo']['current_page']=$pageIndex;
			$result['pageInfo']['first_page']=1;
			$result['pageInfo']['prev_page']=($pageIndex-1)>0?($pageIndex-1):1;
			$result['pageInfo']['next_page']=($pageIndex+1)<=$all_pages?($pageIndex+1):$all_pages;
			$result['pageInfo']['last_page']=$all_pages;
			$result['pageInfo']['total_count']=count($result['dataList']);
			$result['pageInfo']['total_page']=$all_pages;
			$result['pageInfo']['page_size']=$pageSize;
			$result['pageInfo']['all_pages']=$all;
		}

		$incomeResult['errorCode']=0;
		$incomeResult['data']=$result;
		*/
		
		echo json_encode($incomeResult);
	}
	
	/*
	 * 提现界面（线下打款模式）
	 * 参数:type (1:分销中心，2:诊所中心,3:医院中心)
	 * 		id   (分销商id、诊所id、医院id)
	 * */
	function withdrawal(){
		$this->verifyLogin($this);
		
		//判断从哪里跳转过来
		$type=$this->spArgs("type");
		
		//提现的账户id
		$id=$this->spArgs("id");
		$this->id=$id;
		$this->type=$type;
		
		//提现者是分销商
		if($type==1){
			if(!class_exists('UtilConfig'))include_once 'include/UtilConfig.php';
	        $lib_config = new UtilConfig('fen_config');
			
	       	//获取分销商信息
	       	$distributorResult = $this->lib_distributor->findDistributor(array("id"=>$id));
	       	$this->my_fee = $distributorResult["data"]['my_fee'];	
		}
		//提现者是诊所
		else if($type==2){
			if(!class_exists('lib_clinic'))include_once 'model/store/lib_clinic.php';
	        $lib_clinic = new lib_clinic();
			$result=$lib_clinic->getGoods($id);
			$this->my_fee = $result['data']['money'];
		}
		//提现者是医院
		else{
			if(!class_exists('lib_hospital'))include_once 'model/store/lib_hospital.php';
	        $lib_hospital = new lib_hospital();
			$result=$lib_hospital->getGoods($id);
			$this->my_fee = $result['data']['money'];
		}
		
		//获取对应的提现用户账户列表
       	if(!class_exists('lib_cash_account')) include 'model/fen/lib_cash_account.php';
       	$lib_cash_account = new lib_cash_account();
       	$accountListResult = $lib_cash_account->findAllAccount(array("tx_uid"=>$id,'tx_type'=>$type,"addUid"=>$_SESSION['user']['id']),'is_default desc');
       	$this->accountList = $accountListResult["data"];
       	
       	$this->display("../template/front/common/fen/page/withdrawFeePage.html");
	} 
     
    /**
     * 处理提现界面(注意分销配置)
    */
    function cashApply(){
     	$withdraw_money = $this->spArgs('money');  //提现金额
     	
     	if($withdraw_money<0){
     		echo json_encode(common::errorArray(1,'提现失败',false));
     		die;
     	}
		
     	$user_id = $_SESSION['user']['id'];
		$nick_name = $_SESSION['user']['nick_name'];
		
     	//数据提交到分销商申请
     	include_once 'model/fen/lib_cash_apply.php';
     	$lib_cash_apply = new lib_cash_apply();
		$applyInfo = array(
			'money' => $withdraw_money,
			'account_id' => $this->spArgs('aid'),
			'distributor_id' => $user_id,
			'distributor_name' => $nick_name,
			'total_money' =>  $this->spArgs('myfee'),
			'tx_uid' =>  $this->spArgs('tx_uid'),
			'tx_type' =>  $this->spArgs('tx_type'),
			'is_check'=> 0,
		);
		
		$result = $lib_cash_apply->addApply($applyInfo);
 		echo json_encode($result);
    }
     
    /**
     ** 提现记录页面
     **/
    function cashRecordPage(){
        $this->verifyLogin($this);
       	$this->display("../template/front/common/fen/page/cashRecordPage.html");
    }

	/*
	 * 异步请求显示收支明细(提现记录、收入记录)
	 * 参数:type:1:提现记录,2:收入记录
	 * */
    function pageingRecord(){
    	$type = $this->spArgs("type");
    	$page = $this->getPageInfo($this);
		$user_id = $_SESSION['user']['id'];
		if($type==1){       //提现记录
			include_once 'model/fen/lib_cash_record.php';
	       	$lib_cash_record = new lib_cash_record();
			$condition=array(array("field"=>"distributor_id","operator"=>'=',"value"=>$user_id));
	       	$recordList = $lib_cash_record->pagingRecord($page ,$condition ,"add_time desc");
			echo json_encode($recordList);
		}
		else{              //收入记录
			include_once 'model/fen/lib_deal_record.php';
	       	$lib_deal_record = new lib_deal_record();
			$condition=array(array("field"=>"user_id","operator"=>'=',"value"=>$user_id));
			$recordList = $lib_deal_record->pagingDealRecord($page ,$condition ,"add_time desc");
			$recordList['nick_name']=$_SESSION['user']['nick_name'];
			echo json_encode($recordList);
		}
    }
	
    /**
     ** 提现账户列表页面
	 *  参数:type (1:分销中心，2:诊所中心,3:医院中心)
	 * 		 id   (分销商id、诊所id、医院id)
     **/
    function accountList(){
        $this->verifyLogin($this);

        $id = $this->spArgs("id");
        $type = $this->spArgs("type");
        $this->id =$id;
        $this->type =$type;
        //分销商账户列表
//      include_once 'model/fen/lib_cash_account.php';

//      $lib_cash_account = new lib_cash_account();

//      $accountListResult = $lib_cash_account->findAllAccount(array("distributor_id"=>$did));

//      $this->accountList = $accountListResult["data"];
        $this->display("../template/front/common/fen/page/accountList.html");
    }

	/**
	 * 分页渲染账户管理页面
	 */
	function pagingAccount(){
		$page['pageIndex'] = $this->spArgs('pageIndex');
		$page['pageSize'] = $this->spArgs('pageSize');
		$id  = $this->spArgs("id");
		$type  = $this->spArgs("type");
		$conditionList[] = array('field'=>'tx_uid','operator'=>'=','value'=>$id);
		$conditionList[] = array('field'=>'tx_type','operator'=>'=','value'=>$type);

		$conditionList[] = array('field'=>'addUid','operator'=>'=','value'=>$_SESSION['user']['id']);
        $sort = 'add_time desc';	
		if(!class_exists('lib_cash_account')) include "model/fen/lib_cash_account.php";
		$lib_cash_account = new lib_cash_account();	 
		$result =$lib_cash_account->pagingAccount($page,$conditionList,$sort);
		
		$this->log(__CLASS__, __FUNCTION__, "分页渲染账户管理页面", 0, "view");		
		echo json_encode($result);		
	}
     
    /**
	 * 添加账户页面
	 */
	function addAccount(){
		$this->verifyLogin($this);
		
		$id = $this->spArgs("id");
        $type = $this->spArgs("type");
        $this->id =$id;
        $this->type =$type;
		$this->log(__CLASS__, __FUNCTION__, '提交成功页面', 1, 'view');
		$this->display("../template/front/common/fen/page/addAccount.html");
	}
	
	/**
	 * 账户添加
	 */
	function insertAccount(){
		$accountInfo = $this->getArgsList($this,array("name","account","account_type","account_bank","tx_uid","tx_type"));
		
		//添加时的用户id
		$accountInfo['addUid']=$_SESSION['user']['id'];
		if(!class_exists('lib_cash_account')) include "model/fen/lib_cash_account.php";
		$lib_cash_account = new lib_cash_account();
		$result = $lib_cash_account->addAccount($accountInfo);
		$this->log(__CLASS__, __FUNCTION__, "账户添加", 0, "add");		
		echo json_encode($result);
	}
	
	/**
	 * 编辑账户页面
	 */
	function editAccount(){
		$this->verifyLogin($this);
		
		$id = $this->spArgs("id");
		//获取账户信息
		if(!class_exists('lib_cash_account')) include "model/fen/lib_cash_account.php";
		$lib_cash_account = new lib_cash_account();
		$account = $lib_cash_account->findAccount(array("id"=>$id));
		
		$this->account = $account['data'];
		$this->log(__CLASS__, __FUNCTION__, '编辑账户页面', 1, 'view');
		$this->display("../template/front/common/fen/page/editAccount.html");
	}

	/**
	 * 更新账户
	 */
	function updateAccount(){
		$accountInfo = $this->getArgsList($this, array("name","account","account_type","account_bank"));
		if(!class_exists('lib_cash_account')) include "model/fen/lib_cash_account.php";
		$lib_cash_account = new lib_cash_account();
		$result = $lib_cash_account->updateAccount(array("id"=>$this->spArgs("id")),$accountInfo);
		$this->log(__CLASS__, __FUNCTION__, "更新账户", 0, "add");		
		echo json_encode($result);
	}
	
	/*
	 * 设置为默认选项
	 * */
    function setDefault(){
    	$id=$this->spArgs('id');
    	$uid=$this->spArgs('uid');
    	$type=$this->spArgs('type');
    	$is_default=$this->spArgs('is_default');
		if($is_default==1){
			if(!class_exists('lib_cash_account')) include "model/fen/lib_cash_account.php";
			$lib_cash_account = new lib_cash_account();
			$res=$lib_cash_account->runSql("update fen_cash_account set is_default=0 where tx_uid={$uid} and tx_type={$type}");
			if($res){
				$result=$lib_cash_account->updateAccount(array('id'=>$id),array('is_default'=>$is_default));
				$this->log(__CLASS__, __FUNCTION__, "更新账户", 0, "add");		
				echo json_encode($result);
			}
			else{
				echo json_encode(array('errorCode'=>1,'errorInfo'=>''));
			}
		}
    }
	
	
    /**
     ** 分销商推广二维码
     **/
    function promotionPage(){
       	$this->verifyLogin($this);
		$condition['user_id']= $_SESSION['user']['id'];
        //查询当前分销商信息
        $distributorResult = $this->lib_distributor->findDistributor($condition);
        $this->distributorInfo = $distributorResult['data'];
		
		$did=$distributorResult['data']['id'];
        
        //用户信息
        $condition = array('id'=>$distributorResult['data']['user_id']);
        $userResult = $this->lib_user->findUser($condition);
        $this->userInfo = $userResult['data'];
        
		if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
		$fenConfig = new UtilConfig('fen_config');
		$fenResult = $fenConfig->getConfigValue('page_title');
		$this->pageTitle = $fenResult['data']?$fenResult['data']:'千界科技';
		
        //二维码
		$bgUrl = "template/front/store/ma17004/image/qrcode_background.jpg";
		$targetPath ="upload/image/qrcode";
		$headImgUrl = explode(":", $userResult['data']['head_img_url']);
	    $headImgUrl = "https:".$headImgUrl[1];
		if(!class_exists('lib_code')) include 'model/fen/lib_code.php';
        $lib_code = new lib_code();
		$wechatCode = $lib_code->getCode(array('did'=>$did),$targetPath,$headImgUrl,$bgUrl);
        $this->wechatCode = $wechatCode['data'];	
        //分享功能
	    $this->signPackage = JsSdk::getSignPackage();
		header('Content-Type: text/html');         // 设置内容类型标头 为  text/html
        //待确定设计方案
        $this->display("../template/front/common/fen/page/promotionPage.html");
    }
}