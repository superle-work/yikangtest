<?php /* Smarty version Smarty-3.0.8, created on 2019-09-26 11:27:43
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/goods/goodsDetail.html" */ ?>
<?php /*%%SmartyHeaderCode:16704004905d8c302fe83260-71509113%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb11235067341ffb546876635a76d6e26e87b9f7' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/goods/goodsDetail.html',
      1 => 1542616979,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16704004905d8c302fe83260-71509113',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>商品详情</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @JS_PATH;?>
/public/swiper/css/swiper.min.css">
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/goods/goodsDetail.css">
</head>

<body >
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--页面头部-->
        <!--主体内容-->
        <div id="content" class="content-goodsDetail">
            <!--商品详情-->
            <!--商品介绍-->
            <div class="goods-instroduce">
            	<!--幻灯片轮播-->
	            <div class="swiper-container">
	                <div class="swiper-wrapper">
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
	                	<span class="name"><?php echo $_smarty_tpl->getVariable('goodsInfo')->value['name'];?>
</span>
	                </div>
	                <div class="price">
	                	<div>
	                		<span class="symbol">￥</span><span class="price-value"><?php echo $_smarty_tpl->getVariable('goodsInfo')->value['price'];?>
</span>
	                	</div>
	                	<div class="sale-quantity">已售<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['sale_quantity'];?>
</div>
	                </div>
	            </div>
	        </div>
	        
	        <div class="space"></div>
	        <!--商品详情-->
	        <div class="goods-desc">
	        	<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['detail_desc'];?>

	        </div>
		</div>
        <!--页面脚部-->
        <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
        <!--底部导航-->
        <div class="bottom-nav">
            <div class="but-area">
            	<div class="left-area" data-user="<?php echo $_SESSION['user']['id'];?>
">
                	<div class="favorite" data-id="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['id'];?>
" style="cursor:pointer;">
                		<?php if ($_smarty_tpl->getVariable('isFavorite')->value){?>
                		<i class="icon iconfont selected">&#xe6b0;</i>
                		<div class="my-favorite">已收藏</div>
	                	<?php }else{ ?>
	                	<i class="icon iconfont">&#xe61b;</i>
	                	<div class="my-favorite">收藏</div>
                		<?php }?>            	
                	</div>
                	<div class="share" style="cursor:pointer;">
	                	<i class="icon iconfont">&#xe627;</i>
	                	<div class="my-share">分享</div>
                	</div>
                	<div class="cartList" style="cursor:pointer;">
	                	<i class="icon iconfont">&#xe646;</i>
	                	<div class="my-carlist">购物车</div>
	                	<span class="cartList-num"><span class="number"><?php echo $_smarty_tpl->getVariable('cartCount')->value;?>
</span></span>
                	</div>
            	</div>
                           	            	
                <div class="right-area">
                	<button class="add-cart" data-account="<?php echo $_SESSION['user']['account'];?>
" data-user="<?php echo $_SESSION['user']['id'];?>
" data-id="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['id'];?>
" data-type="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['good_type'];?>
" >加入购物车</button><button class="buy-now" data-account="<?php echo $_SESSION['user']['account'];?>
" data-user="<?php echo $_SESSION['user']['id'];?>
" data-id="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['id'];?>
">立即购买</button>
                </div>
            </div>
        </div>
    </div>
    <!--提示框-->
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script src="<?php echo @JS_PATH;?>
/public/swiper/js/swiper.jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/jquery.bootpag/jquery.bootpag.min.js"></script>
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/wechat/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/goods/goodsDetail.js"></script>
	<!--弹框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    
    
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
	        jsApiList: ['checkJsApi', 'scanQRCode','openLocation','onMenuShareTimeline','onMenuShareAppMessage', 'hideMenuItems'
	            // 所有要调用的 API 都要加到这个列表中
	        ]
	    });
	    
	    var title = $(".goods-info .desc .name").text();
	    var link =  "<?php echo @ROOT_URL;?>
/index.php?c=store&a=index";
	    var imgUrl = "<?php echo @ROOT_URL;?>
/<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['img_url'];?>
";
	    var desc = '<?php echo $_SESSION['user']['nick_name'];?>
正在查看该商品，快一起来参与吧';
	    
	    wx.ready(function(){
	        // 在这里调用 API
	        //分享到朋友圈
	        wx.onMenuShareTimeline({
	            title: title+','+desc, // 分享标题
	            link: link, // 分享链接
	            imgUrl: imgUrl, // 分享图标
	            desc:desc,   // 分享描述
				success: function () {
					// 用户确认分享后执行的回调函数
					window.location.reload();
					/*
					$.ajax({
						url:"<?php echo @ROOT_URL;?>
/index.php?c=game&a=shareTimeLine",
						data:{},
						dataType:"json",
						success:function(json,statusText){
							window.location.href="./index.php?c=game&a=index&random="+Math.random();						
						}
					});
					*/
				}
	        });
	        //发送给朋友
	        wx.onMenuShareAppMessage({
	            title: title, // 分享标题
	            desc: desc, // 分享描述
	            link: link, // 分享链接
	            imgUrl: imgUrl, // 分享图标
	            type: 'link', // 分享类型,music、video或link，不填默认为link
	            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	            success: function () {
	            	window.location.reload();
	            	
	            	/*
	                $.ajax({
						url:"http://tlm.huimaibuy.com/index.php?c=game&a=shareTimeLine",
						data:{},
						dataType:"json",
						success:function(json,statusText){
							window.location.href="./index.php?c=game&a=index&random="+Math.random();
	
						}
					});
	                */
	            }
	        });
	        
	        //隐藏的菜单
	        wx.hideMenuItems({
	            menuList: ["menuItem:copyUrl",
			                //"menuItem:share:appMessage",
			                //"menuItem:share:timeline",
			                "menuItem:openWithQQBrowser",
			                "menuItem:openWithSafari",
			                "menuItem:share:qq",
			                "menuItem:favorite",
			                "menuItem:share:weiboApp",
			                "menuItem:share:email",
			                "menuItem:originPage"] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
	        });
	    });
    </script>
</body>
</html>