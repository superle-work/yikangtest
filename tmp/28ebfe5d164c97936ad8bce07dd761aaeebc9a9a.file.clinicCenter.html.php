<?php /* Smarty version Smarty-3.0.8, created on 2019-09-27 09:06:57
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/clinic/clinicCenter.html" */ ?>
<?php /*%%SmartyHeaderCode:5189124285d8d60b17a1c17-66189285%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '28ebfe5d164c97936ad8bce07dd761aaeebc9a9a' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/clinic/clinicCenter.html',
      1 => 1540878725,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5189124285d8d60b17a1c17-66189285',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>诊所中心</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/clinic/clinicCenter.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">	
        <div id="content">
            <div class="top-area">
            	<div class="name">( <?php echo $_smarty_tpl->getVariable('clinicInfo')->value['name'];?>
 )</div>
            	<div class="user">账户金额&nbsp;&nbsp;(元)</div>
            	<div class="money">
            		<div class="blank"></div>
            		<div class="num" title="<?php echo $_smarty_tpl->getVariable('clinicInfo')->value['money'];?>
"><?php echo $_smarty_tpl->getVariable('clinicInfo')->value['money'];?>
</div>
            		<a href="index.php?c=fen&a=withdrawal&type=2&id=<?php echo $_smarty_tpl->getVariable('clinicInfo')->value['id'];?>
">金额可提现 <i class="icon iconfont right">&#xe618;</i></a>
            	</div>
            </div>
            <div class="info-area">
            	<span class="tip">*</span>ID <?php echo $_smarty_tpl->getVariable('clinicInfo')->value['clinicId'];?>

            </div>
            <div class="order-area">
            	<div id="scanQRCode" class="sao" data-id="<?php echo $_smarty_tpl->getVariable('clinicInfo')->value['id'];?>
">
            		<i class="icon iconfont">&#xe63b;</i>
            		<p>扫一扫</p>
            	</div>
            	<div class="clear" data-id="<?php echo $_smarty_tpl->getVariable('clinicInfo')->value['id'];?>
">
            		<i class="icon iconfont">&#xe640;</i>
            		<p>核销记录</p>
            	</div>
            </div>
        </div>
    </div>
    <!--提示框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/jquery.bootpag/jquery.bootpag.min.js"></script>
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/jquery.cookie/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/wechat/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/clinic/clinicCenter.js"></script>
    
    <script>
    	/*
	     **** 微信分享
	     */
	    wx.config({
	        debug: false,
	        appId: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['appId'];?>
',
	        timestamp: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['timestamp'];?>
',
	        nonceStr: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['nonceStr'];?>
',
	        signature: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['signature'];?>
',
	        jsApiList: ['checkJsApi', 'scanQRCode'
	            // 所有要调用的 API 都要加到这个列表中
	        ]
	    });
	    
	    wx.ready(function () {
	      	// 在这里调用 API
	      	wx.checkJsApi({
	           	jsApiList : ['scanQRCode'],
	           	success : function(res) {
	
	           	}
	      	});
	        
	        //点击按钮扫描二维码
	        document.querySelector('#scanQRCode').onclick = function() {
	            wx.scanQRCode({
	                needResult : 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
	                scanType : [ "qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
	                success : function(res) {
	                    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
	                    window.location.href = result;//因为我这边是扫描后有个链接，然后跳转到该页面
	                }
	            });
	        };
	        
	    });
    </script>
</body>
</html>