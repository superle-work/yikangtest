<?php
if(!class_exists('front_controller')) require 'include/base/controller/front/front_controller.php';
if(!class_exists('lib_user_address')) require 'model/base/lib_user_address.php';
if(!class_exists('lib_user')) require 'model/base/lib_user.php';
if(!class_exists('lib_common')) require 'model/base/lib_common.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';

/**
 *前台用户基础
 * @name base_user.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-07
 */
class base_user extends front_controller{
	private $lib_user_address;
	private $lib_user;
    private $lib_common;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
	    $this->lib_user_address =  new lib_user_address();
	    $this->lib_user =  new lib_user();
	    $this->lib_common = new lib_common();
	}
	
	/**
	 * 用户登录页面
	 */
	function login(){
		if(!class_exists('lib_common')) include 'model/base/lib_common.php';
		$lib_common = new lib_common();
		spClass('lib_common')->getMobileCommonDataFront($this,'store_theme');
		$this->display("../template/front/store/{$this->theme}/page/user/login.html");
	}

	/**
	 * 验证登录，首次登陆需要手机号码验证
	 */
	function userLogin(){
		//先验证图片验证码
		$captcha=$this->spArgs("captcha");
		if(md5(strtoupper($captcha)) != $_SESSION['captcha']){
			echo json_encode(common::errorArray(1, "图片验证码输入不一致", false));
			exit;
		}
		
		//验证手机验证码
		$code=$this->spArgs("phoneCode");
		if($code != $_SESSION['phone_register_number']){
			echo json_encode(common::errorArray(1, "手机验证码输入不一致", false));
			exit;
		}
		
		//验证成功后，保存手机号
		$uid=$_SESSION['user']['id'];
		$phone=$this->spArgs("phone");
		$result=$this->lib_user->updateUser(array('id'=>$uid), array('phone'=>$phone,'is_login'=>1));

		if(!class_exists('lib_distributor')) include "model/fen/lib_distributor.php";
		$lib_distributor=new lib_distributor();
		$lib_distributor->updateDistributor(array('user_id'=>$uid),array('phone'=>$phone));

		if($result['errorCode']==0){
			echo json_encode(common::errorArray(0, "验证成功", false));
		}
		else{
			echo json_encode(common::errorArray(1, "验证失败", false));
		}
	}

	/**
	 * 图形校验码
	 */
	function captcha(){
	    // 开启SESSION
	    session_start(5*60*1000);
	    if(!class_exists('captcha')) include 'include/captcha.php';
	    // 实例化验证码
	    $captcha = new Captcha(70, 25);
	    // 清除之前出现的多余输入
	    @ob_end_clean();
	    $captcha->createCaptcha('captcha');
	}
	
	
	/**
	 * 发送短信
	 */
	function sendSMS(){
		//先验证图片验证码
		$captcha=$this->spArgs("captcha");
		if(md5(strtoupper($captcha)) != $_SESSION['captcha']){
			echo json_encode(common::errorArray(1, "图片验证码输入不一致", false));
			exit;
		}
		
		include_once 'include/UtilConfig.php';
		$mallConfig = new UtilConfig("store_config");
		$verifyResult = $mallConfig->getConfigValue('verify_code');
		
		if(!$verifyResult['data']){
			echo json_encode(common::errorArray(1, "未启用手机短信验证", false));
			exit;
		}
		$phone = $this->spArgs('phone');
		dump($code);
		// include_once 'model/plugin/lib_sms.php';
		// $sms = new lib_sms();
			
		//发送验证码，值存在 $_SESSION['phone_register_number']中
		// $smsResult = $sms->sendFengHuoYunSMS(15,array('phone'=>$phone,'time'=>60));
		// echo json_encode($smsResult);

    
        $num=rand(1111,9999);
      
        include_once 'include/dayusms/api_demo/SmsDemo.php';
        // var_dump($code);
        $a=\SmsDemo::sendSms($num,$phone);
        dump($a);
        if($a->Message=='OK'){
            //$arr=array('num'=>$num);
         
         	 $_SESSION['phone_register_number'] = $num;
          	return common::errorArray(0, "短信发送成功");
            //echo json_encode($arr);
        }else{
            //$arr=array('num'=>1);
            //echo json_encode($arr);
          return common::errorArray(1, "短信发送失败");
        }

   
	}


	
	//---------------------用户地址管理--------------------
	/**
	 * 收货地址列表页面
	 */
	function addressList(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'user_theme');
	    //收货地址类别
	
	    $result = $this->lib_user_address->getAddressList(array('user_id'=>$_SESSION['user']['id']));
	    $this->addressList = $result['data'];
	    $this->log(__CLASS__, __FUNCTION__, "收货地址列表页面", 1, 'view');
	    $this->display("../template/front/common/user/{$this->theme}/page/address/addressList.html");
	}
	
	/**
	 * 添加收货地址页面
	 */
	function addAddress(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'user_theme');
	    $this->log(__CLASS__, __FUNCTION__, "添加收货地址页面", 1, 'add');
	    $this->display("../template/front/common/user/{$this->theme}/page/address/addAddress.html");
	}
	
	/**
	 * 编辑收货地址页面
	 */
	function editAddress(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this,'user_theme');
	    $conditions  = array('id' =>$this->spArgs('id'));
	    //收货地址
	    $result = $this->lib_user_address -> getAddressInfo($conditions);
	    $this->address = $result['data'];
	    $this->log(__CLASS__, __FUNCTION__, "编辑收货地址页面", 1, 'edit');
	    $this->display("../template/front/common/user/{$this->theme}/page/address/editAddress.html");
	}
	
	/**
	 * 添加收货地址
	 */
	function insertAddress(){
		$this->lib_user_address-> updateAddress(array('user_id' => $_SESSION['user']['id']),array('is_default' => 0));
	    $addressInfo = $this->getArgsList($this, array(phone,call_name,post));
	    $addressInfo['user_id'] = $_SESSION['user']['id'];
		$addressInfo['is_default'] = 1;
	    $arrDetail = array();
	    if($this->spArgs('addr') != null && $this->spArgs('addr') != ''){
	        $addr = explode(' ', $this->spArgs('addr'));
	        $arrDetail['province'] = $addr['0'];
	        $arrDetail['city'] = $addr['1'];
	        $arrDetail['area'] = $addr['2'];
	    }
	    if($this->spArgs('detail') != null && $this->spArgs('detail') != ''){
	        $arrDetail['detail'] = $this->spArgs('detail');
	    }
	    $addressInfo['address'] = json_encode($arrDetail, JSON_UNESCAPED_UNICODE); //必须PHP5.4+
	    if($this->spArgs('is_default') != null && $this->spArgs('is_default') != ''){
	        $addressInfo['is_default'] = 1;
	    }
//	    else{

//	        $result = $this->lib_user_address->getAddressInfo(array('user_id'=>$_SESSION['user']['id']));

//	        if($result['errorCode'] == 0){

//	            $addressInfo['is_default'] = 0;

//	        }else if($result['errorCode'] == 1){

//	            $addressInfo['is_default'] = 1;

//	        }

//	    }
	    $result = $this->lib_user_address->addAddress ($addressInfo);
	    $this->log(__CLASS__, __FUNCTION__, "添加收货地址", 0, 'add');
	    echo json_encode($result);
	}
	
	/**
	 * 删除收货地址
	 */
	function deleteAddress() {
	    $result = $this->lib_user_address->deleteAddress( $this->spArgs('ids'));
	    $this->log(__CLASS__, __FUNCTION__, "删除收货地址", 0, 'del');
	    echo json_encode($result);
	}
	
	/**
	 * 用户地址修改
	 */
	function updateAddress(){
	    $conditions = array( 'id' => $this->spArgs('id'));
	    $addressInfo = $this->getArgsList($this, array(phone,call_name,post));
	    $arrDetail = array();
	    if($this->spArgs('addr') != null && $this->spArgs('addr') != ''){
	        $addr = explode(' ', $this->spArgs('addr'));
	        $arrDetail['province'] = $addr['0'];
	        $arrDetail['city'] = $addr['1'];
	        $arrDetail['area'] = $addr['2'];
	    }
	    if($this->spArgs('detail') != null && $this->spArgs('detail') != ''){
	        $arrDetail['detail'] = $this->spArgs('detail');
	    }
	    $addressInfo['address'] = json_encode($arrDetail, JSON_UNESCAPED_UNICODE); //必须PHP5.4+  
	    $result = $this->lib_user_address-> updateAddress($conditions,$addressInfo);
	    $this->log(__CLASS__, __FUNCTION__, "用户地址修改", 0, 'edit');
	    echo json_encode($result);
	}
	
	/**
     * 获取收货地址
     */
    function getAddress() {
        $result = $this->lib_user_address-> getAddressList(array('account'=>$_SESSION['user']['account']));
        $this->log(__CLASS__, __FUNCTION__, "获取收货地址", 0, 'view');
        echo json_encode($result);
    }

    /**
     * 获取指定id收货地址
     */
    function getAddressById() {
        $conditions  = array('id' =>$this->spArgs('id'));
        $result = $this->lib_user_address-> getAddressInfo($conditions);
        $this->log(__CLASS__, __FUNCTION__, "获取指定id收货地址", 0, 'view');
        echo json_encode($result);
    }

    /**
     * 设置默认收货地址
     */
    function setDefaultAddress(){
        $conditions = array('id' => $this->spArgs('id'));
        //将该用户下收货地址所有isdefault置为0
        $this->lib_user_address-> updateAddress(array('user_id' => $_SESSION['user']['id']),array('is_default' => 0));
        $result = $this->lib_user_address-> updateAddress($conditions,array('is_default' => 1));
        $this->log(__CLASS__, __FUNCTION__, "设置默认收货地址", 0, 'edit');
        echo json_encode($result);
    }

    //-----------------------------------------个人信息管理----------------------
    /**
     * 我的个人信息
     */
    function personalInfo(){
    	$this->verifyLogin($this);
        $this->lib_common->getMobileCommonDataFront($this,'user_theme');
        $condition = array('id'=>$_SESSION['user']['id']);
        $userResult = $this->lib_user->findUser($condition);
        $this->userInfo = $userResult['data'];
        $this->log(__CLASS__, __FUNCTION__, "我的个人信息", 1, "view");
        $this->display("../template/front/common/user/{$this->theme}/page/user/personalInfo.html");
    }
    
    /**
     ** 更新用户信息
     **/
    function updateUser(){
        $conditions = array('id' => $this->spArgs('id'));
        $userInfo = $this->getArgsList($this, array('id','nick_name','name','phone','address','detail'));
        $result = $this->lib_user->updateUser ($conditions, $userInfo );
        $this->log(__CLASS__, __FUNCTION__, "更新用户信息", 0, "edit");
        echo json_encode($result);
    }
    
    //-----------------------------------------消息管理--------------------------------------
    
    /**
     * 用户消息列表页面
     */
    function messageList(){
        $this->getSetMenu();
        $this->log(__CLASS__, __FUNCTION__, "用户消息列表页面", 1, 'view');
        $this->display("../template/base/{$this->theme}/base/log/logList.html");
    }
    
    /**
     * 获取最新未读消息个数
     */
    function getNoReadCount(){
        $result = $this->lib_message->getNoReadMessageCount($_SESSION['user']['id']);
        echo json_encode($result);
    }
    
    /**
     * 标记用户消息已读
     */
    function tagMessageRead(){
        $ids = $this->spArgs('ids');
        $result = $this->lib_message->tagMessageRead($ids);
        $this->log(__CLASS__, __FUNCTION__, "标记用户消息已读", 0, 'edit');
        echo json_encode($result);
    }
    
    /**
     * 删除用户消息
     */
    function deleteMessage(){
        if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
        $lib_user_message = new lib_user_message();
        $ids = $this->spArgs('ids');
        $result = $lib_user_message->deleteMessage($ids);
        $this->log(__CLASS__, __FUNCTION__, "删除用户消息", 0, 'del');
        echo json_encode($result);
    }
    
    /**
     * 分页查询用户消息
     */
    function pagingMessage(){
        $page = $this->getPageInfo($this);
        $keyValueList = array('title'=>'like','content'=>'like','user_id'=>'=','is_read'=>'=','type'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
        $conditionList = $this->getPagingList($this, $keyValueList);
        $sort = "add_time desc";
        $result = $this->lib_message->pagingMessage($page,$conditionList,$sort);
        echo json_encode($result);
    }
    
    //*******************个人积分管理******************/
    /**
     * 个人积分中心
     */
    function pointsCenter(){
        $this->verifyLogin($this);
        $this->lib_common->getMobileCommonDataFront($this, 'user_theme');//验证和选择模板
		//$_SESSION = "";
        //个人信息
        $this->type = $this->spArgs('type');
        if(!class_exists('lib_user')) include 'model/base/lib_user.php';
        $lib_user = new lib_user();
        $userResult = $lib_user ->findUser(array('id' => $_SESSION['user']['id']));
        $this->userInfo = $userResult['data'];
        //积分配置
        $lib_config = new UtilConfig($this->type.'_config');
        $configResult = $lib_config->findConfigKeyValue('points');
        $this->configInfo = $configResult['data'];
        $base_config = new UtilConfig('base_config');
        $baseConfigResult = $base_config->findConfigKeyValue('points');
        $this->baseConfigInfo = $baseConfigResult['data'];
        $this->display("../template/front/common/user/{$this->theme}/page/points/withdrawPointsPage.html");
    }
    
    /**
     * 每个模块积分提现记录
     */
    function pointsRecordPage(){
        $this->verifyLogin($this);
        $this->lib_common->getMobileCommonDataFront($this, 'user_theme');
        if(!class_exists('lib_points_record')) include 'model/base/lib_points_record.php';
        $lib_points_record = new lib_points_record();
        $type = $this->spArgs('type');
        $this->type = $type;
        $recordResult = $lib_points_record->findAllRecord(array('user_id' => $_SESSION['user']['id'],'type' => $type));
        $this->recordList = $recordResult['data'];
        $this->display("../template/front/common/user/{$this->theme}/page/points/pointsRecordPage.html");
    }
    
    /**
     * 积分提现
     */
    function addPointsRecord(){
        if(!class_exists('lib_points_record')) include 'model/base/lib_points_record.php';
        $lib_points_record = new lib_points_record();
        $info = array(
            'user_id' => $_SESSION['user']['id'],
            'nick_name' => $_SESSION['user']['nick_name'],
            'open_id' => $_SESSION['user']['open_id'],
        );
        $info['type'] = $this->spArgs('type');
        $info['points'] = $this->spArgs('points');
        $result = $lib_points_record->payPoints($info);
        echo json_encode($result);
    }

//--------------------------余额充值模块---------------------------
	/**
	 * 余额充值记录页面
	 */
	function rechargeList(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this, 'user_theme');//验证和选择模板
	    $this->log(__CLASS__, __FUNCTION__, '余额充值记录页面', 1, 'view');
	    $this->display("../template/front/common/user/{$this->theme}/page/recharge/rechargeList.html");
	}
	
	/**
	 * 余额充值页面
	 */
	function rechargePay(){
	    $this->verifyLogin($this);
	    $moduleName = $this->spArgs('moduleName');
	    $this->lib_common->getMobileCommonDataFront($this, 'user_theme');//验证和选择模板
	    if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
	    $lib_base_config = new UtilConfig('base_config');
	    $config = $lib_base_config->findConfigKeyValue('balance');
	    $this->config = $config['data'];
	    $this->moduleName = $moduleName;
	    $this->log(__CLASS__, __FUNCTION__, '余额充值页面', 1, 'view');
	    $this->display("../template/front/common/user/{$this->theme}/page/recharge/rechargePay.html");
	}
	
	/**
	 * 异步显示余额充值列表页面
	 */
	function pagingRecharge(){
	    if(!class_exists('lib_balance')) include 'model/base/lib_balance.php';
	    $lib_balance = new lib_balance();
	    $dataInfo['start'] = $this->spArgs('start');
	    $dataInfo['num'] = $this->spArgs('num');
	    $start = $dataInfo['start']*$dataInfo['num'];
	    $limit = "{$start},{$dataInfo['num']} ";
	    $where = " status = 1 and user_id = {$_SESSION['user']['id']}";
	    $sort = "add_time desc";
	    $orderList = $lib_balance->findAllBalance($where,$sort,'',$limit);
	    echo json_encode($orderList);
	}
	
	/**
	 * 提现余额
	 */
//	function withdrawBalance(){

//		$info = array(

//          'user_id' => $_SESSION['user']['id'],

//          'nick_name' => $_SESSION['user']['nick_name'],

//          'open_id' => $_SESSION['user']['open_id'],

//      );

//      $info['money'] = $this->spArgs('money');

//		if(!class_exists('lib_balance')) include 'model/base/lib_balance.php';

//		$lib_balance = new lib_balance();

//      $result = $lib_balance->withdrawBalance($info);

//      echo json_encode($result);

//	}
	
	/**
	 * 首次插入余额充值记录
	 */
	function insertRecharge(){
	    $rechargeInfo['money'] = $this->spArgs('money');
	    $rechargeInfo['type'] = $this->spArgs('type');
	    $rechargeInfo['module'] = $this->spArgs('name');
	    preg_match('/^[1-9]\d{0,3}$/',$rechargeInfo['money']) or die("金额错误");
	    if($rechargeInfo['type'] != 0 && $rechargeInfo['type'] != 1){
	        exit('参数错误');
	    }
	    $rechargeInfo['nick_name'] = $_SESSION['user']['nick_name'];
	    $rechargeInfo['user_id'] = $_SESSION['user']['id'];
	    if(!class_exists('lib_balance')) include 'model/base/lib_balance.php';
	    $lib_balance = new lib_balance();
		//查找充值余额配置
	    if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
	    $lib_base_config = new UtilConfig('base_config');
	    $config = $lib_base_config->findConfigKeyValue('balance');
	    //如何赠送充值赠送
	    if($rechargeInfo['type'] == 0 && $config['data']['balance_fixed_send'] == 1){//开启固定余额充值送夺宝币
	        if($rechargeInfo['money'] == 20){//20元
	            $rechargeInfo['reward_coin'] = $config['data']['balance_send_twenty'];
	            $rechargeInfo['get_real_coin'] = $rechargeInfo['money'] + $config['data']['balance_send_twenty'];
	        }else if($rechargeInfo['money'] == 50){//50元
	            $rechargeInfo['reward_coin'] = $config['data']['balance_send_fifty'];
	            $rechargeInfo['get_real_coin'] = $rechargeInfo['money'] + $config['data']['balance_send_fifty'];
	        }else if($rechargeInfo['money'] == 100){//100元
	            $rechargeInfo['reward_coin'] = $config['data']['balance_send_one_hundred'];
	            $rechargeInfo['get_real_coin'] = $rechargeInfo['money'] + $config['data']['balance_send_one_hundred'];
	        }else if($rechargeInfo['money'] == 200){//200元
	            $rechargeInfo['reward_coin'] = $config['data']['balance_send_two_hundred'];
	            $rechargeInfo['get_real_coin'] = $rechargeInfo['money'] + $config['data']['balance_send_two_hundred'];
	        }else{//其他
	            $rechargeInfo['reward_coin'] = 0;
	            $rechargeInfo['get_real_coin'] = $rechargeInfo['money'];
	        }
	    }else if($rechargeInfo['type'] == 1 && $config['data']['balance_defined_send'] == 1){//开启自定义余额充值送夺宝币
	        if($rechargeInfo['money'] >= $config['data']['balance_defined_min']){//大于起送金额
	            $rechargeInfo['reward_coin'] = $config['data']['balance_defined_value'];
	            $rechargeInfo['get_real_coin'] = $rechargeInfo['money'] + $config['data']['balance_defined_value'];
	        }else{//小于起送金额
	            $rechargeInfo['reward_coin'] = 0;
	            $rechargeInfo['get_real_coin'] = $rechargeInfo['money'];
	        }
	    }else{//普通状态
	        $rechargeInfo['reward_coin'] = 0;
	        $rechargeInfo['get_real_coin'] = $rechargeInfo['money'];
	    }
	    $result = $lib_balance->addBalance($rechargeInfo);
	    $this->log(__CLASS__, __FUNCTION__, '首次插入余额充值记录', 0, 'add');
	    echo json_encode($result);
	}
	
	/**
	 * 修改余额充值记录状态（通知）
	 */
	function updateRecharge(){
	    $id = $this->spArgs('id');
	    //查找余额充值记录信息
	    if(!class_exists('lib_balance')) include 'model/base/lib_balance.php';
	    $lib_balance = new lib_balance();
	    $rechargeResult = $lib_balance->findBalance(array('id' => $id));
	    $rechargeInfo['user_id'] = $rechargeResult['data']['user_id'];
	    $rechargeInfo['status'] = 1;
		$rechargeInfo['get_real_coin'] = $rechargeResult['data']['get_real_coin'];
		$result = $lib_balance->updateBalance(array('id' => $id),$rechargeInfo);
		$this->log(__CLASS__, __FUNCTION__, '修改余额充值记录状态（通知）', 0, 'edit');
	    return $result;
	}
	
	//***************************余额提现****************************
	/**
     ** 余额提现页面
     **/
    function withdrawBalance(){
       $this->verifyLogin($this);
	   $moduleName = $this->spArgs('moduleName');

	   $this->lib_common->getMobileCommonDataFront($this, 'user_theme');//验证和选择模板
       
	   $user_id = $_SESSION['user']['id'];
       if(!class_exists('UtilConfig'))include_once 'include/UtilConfig.php';
       $lib_config = new UtilConfig('base_config');
       
       //提现最低金额
	   $configResult = $lib_config->findConfigKeyValue('balance');
       $this->withdraw_limit = $configResult['data']['balance_withdraw_min'];
	   $withdraw_model = $configResult['data']['balance_withdraw_model'];      
	   $this->withdraw_model = $withdraw_model;
	       
	   if($withdraw_model == 0){//线下打款模式  
		   	//获取限制日期                                                                   
		    $this->configDate = $configResult['data']['balance_withdraw_date'];                        
	        //判断是否满足申请提现                                                               
	                                                    
	        if(!class_exists('lib_balance_apply')) include 'model/base/lib_balance_apply.php';                       
	        $lib_balance_apply = new lib_balance_apply();                                    
	        $confApply = $lib_balance_apply->confCashApply($user_id);  
			ChromePhp::info($confApply);                       
	        $errorCode = 0;                                                            
	        foreach($confApply['data'] as $value){                                     
	        	if($value['sum'] != 0){                                                 
	        		$errorCode = "1";                                                     
	        		break;                                                                
	        	}                                                                         
	        }                                                                          
	        if($errorCode == 1){                                                       
	        		$this->errorInfo = '本月您已经操作过咯，再等等吧';                                  
	        		$this->errorInfoData = '1';                                           
	        }else if(date("d",time()) < $configResult['data']['value']){               
	        		$this->errorInfo = '本月提现时间还没到，不能提现哦';                                 
	        }else{                                                                     
	        		$this->errorInfo = "提现申请提交后，管理员会及时进行审核，通过后款项将到达指定账户。";                
	        }                                                                          
	      //账户列表
	       if(!class_exists('lib_base_cash_account')) include 'model/base/lib_base_cash_account.php';
	       $lib_base_cash_account = new lib_base_cash_account();
	       $accountListResult = $lib_base_cash_account->findAllAccount(array("user_id"=>$user_id),'is_default desc');
	       $this->accountList = $accountListResult["data"];
       }
       
       //获取用户信息
       $userResult = $this->lib_user->findUser(array('id'=>$user_id));
       $this->userInfo = $userResult["data"];
       //成功提现金额
       if(!class_exists('lib_balance_record')) include 'model/base/lib_balance_record.php';
       $lib_balance_record = new lib_balance_record();
       $success_fee = $lib_balance_record->findAllbalanceMoney($user_id);
       $this->success_fee = $success_fee['data'];
       $this->display("../template/front/common/user/{$this->theme}/page/balance/withdrawBalance.html");
    }
     
     /**
      * 提交佣金提现申请
      */
     function balanceApply(){
     	$user_id = $_SESSION['user']['id'];
     	
     	$withdraw_money = $this->spArgs('money');
     	//获取用户信息
     	$userInfo = $this->lib_user->findUser(array('id'=>$user_id));
     	if($userInfo['data']['balance'] < $withdraw_money) exit(json_encode(common::errorArray(1, '您的可提现佣金不足', $distributorInfo['data']['my_fee'])));
     	
     	if(!class_exists('UtilConfig'))include 'include/UtilConfig.php';
     	$lib_config = new UtilConfig('base_config');
     	$configResult = $lib_config->findConfigKeyValue('balance');
     	$withdraw_limit = $configResult['data']['balance_withdraw_min'];
     	if($withdraw_money < $withdraw_limit){
     	    echo json_encode(common::errorArray(1, '提现失败，提现金额低于最低要求' . $withdraw_limit . '元', $withdraw_limit));
     	    exit;
     	}
     	
     	//数据提交到分销商申请
     	include_once 'model/base/lib_balance_apply.php';
     	$lib_balance_apply = new lib_balance_apply();
		$applyInfo = array(
     						'money' => $withdraw_money,
							'account_id' => $this->spArgs('aid'),
							'model' => $this->spArgs('model'),
     						'user_id' => $user_id,
     						'nick_name' => $userInfo['data']['nick_name'],
     						'total_money' =>  $userInfo['data']['balance']
     					);
		if($this->spArgs('model') == 1 || $this->spArgs('model') == 2){//提现模式 0：线下打款模式  1：微信零钱模式 2：红包模式
			//提现是否需要审核 
	     	$configResult = $lib_config->findConfigKeyValue('balance'); 
		    $withdraw_check = $configResult['data']['balance_withdraw_check'];
			if($withdraw_check == 0){//不需要审核
				//零钱模式，自动审核通过
				$applyInfo['is_check'] = 1;
				$applyInfo['check_time'] = date("Y-m-d H:i:s",time());
				$applyInfo['checker'] = '自动审核';
				$result = $lib_balance_apply->addApply($applyInfo);
				if($this->spArgs('model') == 1){//微信零钱模式
    				$result = $lib_balance_apply->payMoney($result['data'],0);//发零钱
				}else{
				    //微信配置信息
				    $lib_config = new UtilConfig('weixin_config');
				    $weixinResult = $lib_config->findConfigKeyValue();
				    
   				    if(!class_exists('lib_red_packet')) include 'model/activity/packet/lib_red_packet.php';
   				    $lib_red_packet = new lib_red_packet();
   				    //用户信息
   				    if(!class_exists('lib_user')) include 'model/base/lib_user.php';
   				    $lib_user = new lib_user();
   				    $userInfo = $lib_user->findUser(array('id' => $user_id));
   				    $openId = $userInfo['data']['open_id'];
   				    $sender = $userInfo['data']['nick_name'];
   				    $mchId = $weixinResult['data']['wechat_mchid'];
   				    $appId = $weixinResult['data']['wechat_appid'];
   				    $wishing = $configResult['data']['balance_withdraw_wish'];
   				    $actName = $configResult['data']['balance_withdraw_name'];
   				    $remark = $configResult['data']['balance_withdraw_remark'];
   				    $payKey = $weixinResult['data']['wechat_pay_key'];
   				    $money = $withdraw_money * 100;
   				    $result = $lib_red_packet->sendRedPacket($openId, $money, $sender, $mchId, $appId, $wishing, $actName, $remark, $payKey);
				}
				//不审核时零钱发送成功与否通知
				$leftMoney = $applyInfo['total_money'] - $applyInfo['money'];
				if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
				$lib_user_message = new lib_user_message();
				$title = ($result['errorCode'] == 0)?'恭喜！零钱提现成功':'对不起！零钱提现失败';
				$content = ($result['errorCode'] == 0)? "发送成功，请及时查收":"发送失败，原因：{$result['errorInfo']}";
				$lib_user_message->sendMessage(
						array(
								"user_id" => $distributorInfo['data']['user_id'],
								"nick_name" => $distributorInfo['data']['name'],
								"title" => $title,
								"content" => "您好{$distributorInfo['data']['name']}，您刚申请提现的{$applyInfo['money']}元零钱".$content."，剩余可提现佣金{$leftMoney}元"
							)
				);
			}else{
				$result = $lib_balance_apply->addApply($applyInfo);
			}
     		echo json_encode($result);
		}else{//线下打款模式
			$result = $lib_balance_apply->addApply($applyInfo);
     		echo json_encode($result);	
		}
     }
	
	/**
     ** 余额提现记录页面
     **/
    function balanceRecord(){
       $this->verifyLogin($this);
	   $moduleName = $this->spArgs('moduleName');
	   $this->lib_common->getMobileCommonDataFront($this, 'user_theme');//验证和选择模板

       $user_id = $_SESSION['user']['id'];
       include_once 'model/base/lib_balance_record.php';
       $lib_balance_record = new lib_balance_record();
       $recordList = $lib_balance_record->findAllRecord(array('user_id' => $user_id),"add_time desc");
       $this->recordList = $recordList['data'];
       $this->userInfo = array('id'=>$user_id);
       $this->display("../template/front/common/user/{$this->theme}/page/balance/balanceRecord.html");
    }
	//*****************************积分提现********************
	
	

	/**
	 ** 消息管理
	 **/
	function userMessage(){
	    $this->verifyLogin($this);
	    $this->lib_common->getMobileCommonDataFront($this, 'user_theme');//验证和选择模板
	
	    if(!class_exists('lib_user_message')) include 'model/base/lib_user_message.php';
	    $lib_message = new lib_user_message();
	
	    $user_id = $_SESSION['user']['id'];
	    //读出我的所有消息，未读的置上，最新置上
	    $messageResult = $lib_message->findAllMessage(array("user_id"=>$user_id),"is_read asc, add_time desc");
	    $this->messageList = $messageResult["data"];
	
	    //未读消息数
	    $noReadCount = $this->spArgs("noReadCount");
	    if($noReadCount > 0){
	        //将我的所有未读消息置为已读
	        $conditions = array(
	            'user_id'=>$user_id,
	            'is_read'=>'0'
	        );
	        $row = array("is_read"=>1);
	        $result = $lib_message->updateMessage($conditions,$row);
	    }
	
	    $this->display("../template/front/common/user/{$this->theme}/page/user/userMessage.html");
	}
	
	/**
	 * 余额提现
	 */
//	function withdrawRecharge(){

//		$this->verifyLogin($this);

//	    $moduleName = $this->spArgs('moduleName');

//	    $this->lib_common->getMobileCommonDataFront($this, 'user_theme');//验证和选择模板

//	    if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';

//	    $lib_base_config = new UtilConfig('base_config');

//	    $config = $lib_base_config->findConfigKeyValue('balance');

//	    $this->config = $config['data'];

//	    $this->moduleName = $moduleName;

//	    $condition = array('id'=>$_SESSION['user']['id']);

//		if(!class_exists('lib_user')) include 'model/base/lib_user.php';

//		$lib_user = new lib_user();

//      $userResult = $lib_user->findUser($condition);

//      $this->userInfo = $userResult['data'];

//	    $this->log(__CLASS__, __FUNCTION__, '余额提现页面', 1, 'view');

//      $this->display("../template/front/common/user/{$this->theme}/page/recharge/withdrawRecharge.html");

//	}
	
	//*******************用户提现账户模块*************************
	/**
     ** 用户账户列表页面
     **/
     function accountList(){
       $this->verifyLogin($this);
	   $moduleName = $this->spArgs('moduleName');
	   $this->lib_common->getMobileCommonDataFront($this, 'user_theme');//验证和选择模板
       include_once 'model/base/lib_base_cash_account.php';
       $lib_cash_account = new lib_base_cash_account();
       $accountListResult = $lib_cash_account->findAllAccount(array("user_id"=>$_SESSION['user']['id']));
       $this->accountList = $accountListResult["data"];
	   $this->display("../template/front/common/user/{$this->theme}/page/user/accountList.html");
     }
     
	/**
	 * 添加用户账号信息
	 */
	function saveOrUpdateAccount(){
		$this->verifyLogin($this);
		$moduleName = $this->spArgs('moduleName');
		$this->lib_common->getMobileCommonDataFront($this, 'user_theme');
		include_once 'model/base/lib_base_cash_account.php';
        $lib_cash_account = new lib_base_cash_account();
        $accountListResult = $lib_cash_account->findAccount(array("id"=>$this->spArgs('id')));
        $this->accountList = $accountListResult["data"];
		$this->display("../template/front/common/user/{$this->theme}/page/user/saveOrUpdateAccount.html");
	}
	 
     /**
      * 添加账户
      */
     function addAccount(){
         include_once 'model/base/lib_base_cash_account.php';
         $lib_cash_account = new lib_base_cash_account();
         $result = $lib_cash_account->addAccount(
         				array(
         				    'account' => $this->spArgs('account'),
         				    'account_type' => $this->spArgs('account_type'),
         				    'account_bank' => $this->spArgs('account_bank'),
         				    'name' => $this->spArgs('name'),
         				    'user_id' => $_SESSION['user']['id'],
         				    'nick_name' => $_SESSION['user']['nick_name'],
         				)
         );
         echo json_encode($result);
     }
     
     /**
      * 添加更新账户
      */
     function addOrUpdateAccount(){
         include_once 'model/base/lib_base_cash_account.php';
         $lib_cash_account = new lib_base_cash_account();
		 $id = $this->spArgs('id');
		 $row = array(
			    'account' => $this->spArgs('account'),
			    'account_type' => $this->spArgs('account_type'),
			    'account_bank' => $this->spArgs('account_bank'),
			    'name' => $this->spArgs('name'),
			    'user_id' => $_SESSION['user']['id'],
			    'nick_name' => $_SESSION['user']['nick_name'],
			);
		 if(!empty($id)){
		 	$result = $lib_cash_account->updateAccount(array('id' => $id),$row);
		 }else{
		 	$result = $lib_cash_account->addAccount($row);
		 }
         echo json_encode($result);
     }

     /**
      * 获取要修改的值
      */
     function getAccountById(){
         include_once 'model/base/lib_base_cash_account.php';
         $lib_cash_account = new lib_base_cash_account();
         $result = $lib_cash_account->findAccount(array('id' => $this->spArgs('id')));
         echo json_encode($result);
     }

     /**
      * 批量删除账户
      */
     function deleteAccount(){
         include_once 'model/base/lib_base_cash_account.php';
         $lib_cash_account = new lib_base_cash_account();
         $result = $lib_cash_account->deleteAccountBatch($this->spArgs('ids'));
         echo json_encode($result);
     }

     /**
      * 设置默认账户
      */
     function setDefaultAccount(){
         include_once 'model/base/lib_base_cash_account.php';
         $lib_cash_account = new lib_base_cash_account();
         $result = $lib_cash_account->setDefaultAccount($this->spArgs('id'), $_SESSION['user']['id']);
         echo json_encode($result);
     }
}