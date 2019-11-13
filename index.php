<?php
// 载入配置与定义文件
require("config.php");
//禁用外部实体引用，由于XML组件默认没有禁用外部实体引用，贴到可到时xml注入漏洞，非微信支付系统存在漏洞。
libxml_disable_entity_loader(true);
//SpeedPhp配置
$spConfig = array(
		'mode'=>'debug',
		'default_controller' => 'site', 	// 默认的控制器名称
		'default_action' => 'home',  		// 默认的动作名称
		'url_controller' => 'c',  				// 请求时使用的控制器变量标识
		'url_action' => 'a',  					// 请求时使用的动作变量标识
		'dispatcher_error' => "import(APP_PATH.'/template/front/default/404.html');exit();", // 定义处理路由错误的函数
		'sp_error_throw_exception' => TRUE, //开启自定义异常处理机制
		'db' => array(  // 数据库连接配置
				'driver' => 'mysql',   // 驱动类型
				'host' => DATABASE_PATH, // 数据库地址
				'port' => DATABASE_PORT,        // 端口
				'login' => DATABASE_ACCOUNT,     // 用户名
				'password' => DATABASE_PWD,      // 密码
				'database' => DATABASE_NAME, // 库名称
		),
		'launch' => array( // 加入挂靠点，以便开始使用Url_ReWrite的功能
				'router_prefilter' => array(
						array('spUrlRewrite', 'setReWrite'),  // 对路由进行挂靠，处理转向地址
				),
				'function_url' => array(
						array("spUrlRewrite", "getReWrite"),  // 对spUrl进行挂靠，让spUrl可以进行Url_ReWrite地址的生成
				),
		),
		'ext' => array(
	 	 	// 以下是Url_ReWrite的设置
	 		'spUrlRewrite' => array(
				'hide_default' => false, // 隐藏默认的main/index名称，但这前提是需要隐藏的默认动作是无GET参数的
	 			'args_path_info' => false, // 地址参数是否使用path_info的方式，默认否
	 			'sep' => '-',
				'suffix' => '.html', // 生成地址的结尾符
				'map' => array(	
						'qrcode' => 'store@scanOrder',	
						'm_home' => 'site@home',
						'm_newsCate' => 'site@newsCate',
						'm_news' => 'site@news',
						'm_caseCate' => 'site@caseCate',
						'm_case' => 'site@cases',
						'm_productCate' => 'site@productCate',
						'm_product' => 'site@product',
						
						'm_album' => 'site@album',
						'm_photo' => 'site@photo',
						'm_column' => 'site@column',
						'm_columnInfo' => 'site@columnInfo',
						'm_page' => 'site@page',
						'm_reserve' => 'site@reserve',
						'm_message' => 'site@message',
				        'm_store_order' => 'store@orderList',
				        'm_dining_order' => 'dining@orderList',
				        'm_points_0rder'=>'points@myPointsOrder',
				        'm_rob_order'=>'rob@myRobOrder',
				        'm_group_order'=>'group@orderList',
				        'm_duo_order'=>'duo@orderList',
				        'm_bargain_order'=>'bargain@orderList'
				    
				),
				'args' => array(
				)
			),
	 ),
		// 视图配置
		'view' => array(
				'enabled' => TRUE, 		// 开启视图
				'config' =>array(
						'template_dir' => APP_PATH.'/template', 		// 模板目录
						'compile_dir' => APP_PATH.'/tmp',	 			// 编译目录
						'cache_dir' => APP_PATH.'/tmp',		 			// 缓存目录
						'left_delimiter' => '<!--{',  								// smarty左限定符
						'right_delimiter' => '}-->' 								// smarty右限定符
				),
				'debugging' => FALSE, 				// 是否开启视图调试功能，在部署模式下无法开启视图调试功能
				'engine_name' => 'Smarty', 			// 模板引擎的类名称，默认为Smarty
				'engine_path' => SP_PATH.'/Drivers/Smarty/Smarty.class.php', 	// 模板引擎主类路径
				'auto_display' => TRUE, 				// 是否使用自动输出模板功能
				'auto_display_sep' => '/', 				// 自动输出模板的拼装模式，/为按目录方式拼装，_为按下划线方式，以此类推
				'auto_display_suffix' => '.html', 	// 自动输出模板的后缀名
		)
);
// 当前模块附加的配置
$spConfig['controller_path'] = APP_PATH.'/controller/front';

// 载入SpeedPHP框架
require(SP_PATH."/SpeedPHP.php");

import(BASE_PATH."/include/chromephp.php");
import(BASE_PATH."/include/common.php");
import(BASE_PATH."/include/Log.php");
import(BASE_PATH."/include/UtilImage.php");

import(BASE_PATH."/include/wechatUtil/Curl.php");
import(BASE_PATH."/include/wechatUtil/Msg.php");
import(BASE_PATH. "/include/wechatUtil/MsgConstant.php");
import(BASE_PATH."/include/wechatUtil/AccessToken.php");
import(BASE_PATH."/include/wechatUtil/WeChatOAuth.php");//网页授权
import(BASE_PATH."/include/wechatUtil/UserAuth.php");//微信授权
import(BASE_PATH."/include/wechatUtil/JsSdk.php");//JsSdk

spRun();