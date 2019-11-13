<?php /* Smarty version Smarty-3.0.8, created on 2019-09-29 14:10:06
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/clinic/clinicDetail.html" */ ?>
<?php /*%%SmartyHeaderCode:5028361805d904abe00b434-17909139%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f2d442fc2d35be553665096957c3409cb861073d' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/clinic/clinicDetail.html',
      1 => 1541829856,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5028361805d904abe00b434-17909139',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>诊所详情</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @JS_PATH;?>
/public/swiper/css/swiper.min.css">
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/clinic/clinicDetail.css">
</head>

<body >
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--主体内容-->
        <div id="content" class="content-goodsDetail">
            <!--商品介绍-->
            <div class="goods-instroduce">
            	<!--幻灯片轮播-->
	            <div class="swiper-container">
	            	<div class="swiper-wrapper">
		                <div class="swiper-slide"><img class="slide-image" src="<?php echo $_smarty_tpl->getVariable('clinicList')->value['img_url'];?>
"></div>
	                    <?php  $_smarty_tpl->tpl_vars['side'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('sideList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['side']->key => $_smarty_tpl->tpl_vars['side']->value){
?>
	                    <div class="swiper-slide"><img class="slide-image" src="<?php echo $_smarty_tpl->tpl_vars['side']->value['img_url'];?>
"></div>
	                    <?php }} ?>
		            </div>
	                <!-- 如果需要分页器 -->
	                <?php if ($_smarty_tpl->getVariable('sideList')->value){?>
	                <div class="swiper-pagination"></div>
	                <?php }?>
	            </div>
	            <!--商品信息-->
	            <div class="goods-info">
	                <div class="desc">
	                	<span class="name"><?php echo $_smarty_tpl->getVariable('clinicList')->value['name'];?>
</span>
	                </div>
	                <div class="addrInfo" id="gps" style="padding-bottom:10px;">
	                    <i class="icon iconfont colo">&#xe639;</i><span class="address"> <?php echo $_smarty_tpl->getVariable('clinicList')->value['address'];?>
</span>
	                </div>
	                <div class="phoneInfo">
	                    <i class="icon iconfont colo">&#xe67f;</i><a href="tel:<?php echo $_smarty_tpl->getVariable('clinicList')->value['phone'];?>
"><span class="phone"> <?php echo $_smarty_tpl->getVariable('clinicList')->value['phone'];?>
</span></a>
	                </div>
	                <br />
	            </div>
	        </div>
	        <!--商品详情-->
	        <div class="goods-detail">
	        	<?php echo $_smarty_tpl->getVariable('clinicList')->value['detail_desc'];?>

	        </div>
		</div>
        <!--页面脚部-->
        <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
        <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/bottomNav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    </div>
    <!--提示框-->
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script src="<?php echo @JS_PATH;?>
/public/swiper/js/swiper.jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/jquery.bootpag/jquery.bootpag.min.js"></script>
	<!--弹框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/clinic/clinicDetail.js"></script>
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/wechat/jweixin-1.0.0.js"></script>

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
            jsApiList: ['checkJsApi','openLocation','onMenuShareTimeline', 'onMenuShareAppMessage', 'hideMenuItems'
                // 所有要调用的 API 都要加到这个列表中
            ]
        });
    	
    	wx.ready(function () {
		 // 在这里调用 API
	         wx.checkJsApi({
	              jsApiList : ['openLocation'],
	              success : function(res) {
	
	              }
	         });
	         
	         //获取商户地址经纬度
	         document.querySelector('#gps').onclick = function() {
	             wx.openLocation({
	                latitude: <?php echo $_smarty_tpl->getVariable('clinicList')->value['latitude'];?>
, // 纬度，浮点数，范围为90 ~ -90
	                longitude: <?php echo $_smarty_tpl->getVariable('clinicList')->value['longitude'];?>
, // 经度，浮点数，范围为180 ~ -180。
	                name: '', // 位置名
	                address: '', // 地址详情说明
	                scale: 20, // 地图缩放级别,整形值,范围从1~28。默认为最大
	                infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
	
	             });
	         }; 
	    })
    </script>
</body>
</html>