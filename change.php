<?php
// 后台模块程序入口文件

// 载入配置与定义文件
require("config.php");
//SpeedPhp配置
$spConfig = array(
		'mode'=>'debug',
		'default_controller' => 'config', 	// 默认的控制器名称
		'default_action' => 'configCenter',  		// 默认的动作名称
		'url_controller' => 'c',  				// 请求时使用的控制器变量标识
		'url_action' => 'a',  					// 请求时使用的动作变量标识
		'dispatcher_error' => "import(APP_PATH.'/template/front/404.html');exit();", // 定义处理路由错误的函数
		'sp_error_throw_exception' => TRUE, //开启自定义异常处理机制
		'db' => array(  // 数据库连接配置
				'driver' => 'mysql',   // 驱动类型
				'host' => DATABASE_PATH, // 数据库地址
				'port' => DATABASE_PORT,        // 端口
				'login' => DATABASE_ACCOUNT,     // 用户名
				'password' => DATABASE_PWD,      // 密码
				'database' => DATABASE_NAME, // 库名称
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
$spConfig['controller_path'] = APP_PATH.'/controller/change';

// 载入SpeedPHP框架
require(SP_PATH."/SpeedPHP.php");

import(BASE_PATH."/include/chromephp.php");
import(BASE_PATH."/include/common.php");
import(BASE_PATH."/include/UtilImage.php");

include_once 'include/wechatUtil/Curl.php';
include_once 'include/wechatUtil/Msg.php';
include_once 'include/wechatUtil/MsgConstant.php';
include_once 'include/wechatUtil/AccessToken.php';
include_once 'include/wechatUtil/FansManage.php';

spRun();