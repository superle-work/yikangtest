<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 17:06:47
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/userCenter.html" */ ?>
<?php /*%%SmartyHeaderCode:10774330405d89dca778d5b6-24601315%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a0867fc85f5482e7ffb8571319fcf0d416875c39' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/user/userCenter.html',
      1 => 1540882581,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10774330405d89dca778d5b6-24601315',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>我的</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/user/userCenter.css">
</head>

<body>
	<div id="container">
        <!--主体内容-->
        <div id="content">
	        <!--页面头部-->
	        <div class="top-area">
			    <div class="head-image">
			    	<img src="<?php echo $_smarty_tpl->getVariable('userInfo')->value['head_img_url'];?>
">
			    	<div><?php echo $_smarty_tpl->getVariable('userInfo')->value['nick_name'];?>
</div>
			    </div>
			</div>
			<div class="space"></div>
            <!--订单功能区-->
            <div class="order-area">
				<div class="cf all-order">
				 	<a href="javascript:;" class="left">
				 	 	<span>我的订单</span>
				 	</a>
			        <a href="./index.php?c=store&a=orderList" class="right">
			            <span>查看全部订单</span> <i class="icon iconfont">&#xe618;</i>
			        </a>
			    </div>
			    <div class="quick-btn cf">
			        <a class="order-btn order-btn-1" href="javascript:;" data-href="./index.php?c=store&a=orderList&state=0">
			            <p class="num"><i class="icon iconfont">&#xea8f;</i></p>
			            <p class="btn-text">待付款</p>
			        </a>
			        <a class="order-btn order-btn-2" href="javascript:;" data-href="./index.php?c=store&a=orderList&state=1">
			            <p class="num"><i class="icon iconfont">&#xe656;</i></p>
			            <p class="btn-text">待采样</p>
			        </a>
			        <a class="order-btn order-btn-3" href="javascript:;" data-href="./index.php?c=store&a=orderList&state=2">
			            <p class="num"><i class="icon iconfont">&#xe73d;</i></p>
			            <p class="btn-text">待送检</p>
			        </a>
			        <a class="order-btn order-btn-4" href="javascript:;" data-href="./index.php?c=store&a=orderList&state=3">
			            <p class="num"><i class="icon iconfont">&#xe63d;</i></p>
			            <p class="btn-text">送检中</p>
			        </a>
			        <a class="order-btn order-btn-5" href="javascript:;" data-href="./index.php?c=store&a=orderList&state=4">
			            <p class="num"><i class="icon iconfont">&#xe663;</i></p>
			            <p class="btn-text">检测中</p>
			        </a>
			    </div>
			</div>
			<div class="space"></div>
			
			<!--根据条件显示物流中心、医院中心、诊所中心-->
			<?php if ($_smarty_tpl->getVariable('type')->value==3){?>
			<div class="cart-area">
			    <div class="info">
			        <a href="./index.php?c=store&a=logistics">
			        	<div>
			        		<i class="icon iconfont">&#xe6b8;</i><span>物流中心</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		    </div>
		    <div class="space"></div>
		    <?php }elseif($_smarty_tpl->getVariable('type')->value==2){?>
		    <div class="cart-area">
			    <div class="info">
			        <a href="./index.php?c=store&a=hospitalCenter">
			        	<div>
			        		<i class="icon iconfont hospital">&#xe6c2;</i><span>医院中心</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		    </div>
		    <div class="space"></div>
		    <?php }elseif($_smarty_tpl->getVariable('type')->value==1){?>
		    <div class="cart-area">
			    <div class="info">
			        <a href="./index.php?c=store&a=clinicCenter">
			        	<div>
			        		<i class="icon iconfont clinic">&#xe6c2;</i><span>诊所中心</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		    </div>
		    <div class="space"></div>
		    <?php }else{ ?>
		    
		    <?php }?>
			<div class="cart-area">
			    <div class="info">
			        <a href="./index.php?c=store&a=checkMember">
			        	<div>
			        		<i class="icon iconfont check">&#xe630;</i><span>体检人员信息</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		    </div>
			<div class="user-area">
			    <div class="info">
			        <a href="./index.php?c=store&a=favorite">
			        	<div>
			        		<i class="icon iconfont like">&#xe6b0;</i><span>我的收藏</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		    </div>
		    <div class="space"></div>
		    <div class="cart-area">
			    <div class="info">
			        <a href="./index.php?c=fen&a=fenCenter">
			        	<div>
			        		<i class="icon iconfont check">&#xe675;</i><span>分销商中心</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		    </div>
		    <div class="user-area">
			    <div class="info">
			        <a href="./index.php?c=fen&a=promotionPage">
			        	<div>
			        		<i class="icon iconfont">&#xe60c;</i><span>推广二维码</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		    </div>
			<div class="user-area">
			    <div class="info">
			        <a href="./index.php?c=fen&a=cashRecordPage">
			        	<div>
			        		<i class="icon iconfont">&#xe652;</i><span>收支明细</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		   </div>
        </div>
        <br />
        <br />
        <br />
        <br />
        <br />
        <!--页面脚部-->
        
        <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/bottomNav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    </div>    
    <!--提示框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/user/userCenter.js"></script>

</body>
</html>