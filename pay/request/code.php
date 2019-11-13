<?php
include_once  $_SERVER['DOCUMENT_ROOT']. "/include/wechatUtil/Curl.php";
include_once  $_SERVER['DOCUMENT_ROOT']."/include/wechatUtil/Msg.php";
include_once  $_SERVER['DOCUMENT_ROOT']."/include/wechatUtil/MsgConstant.php";
include_once  $_SERVER['DOCUMENT_ROOT']."/include/wechatUtil/AccessToken.php";
include_once  $_SERVER['DOCUMENT_ROOT']."/include/wechatUtil/WeChatOAuth.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/wechatConfig.php";//导入微信配置

error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );

$oid = $_GET['oid'];
$module = $_GET['module'];
WeChatOAuth::getCode("pay/request/{$module}.php",$oid);
