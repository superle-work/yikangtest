<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 17:10:54
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/fen.html" */ ?>
<?php /*%%SmartyHeaderCode:18880858875d89dd9e6d9814-63138095%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'af6c25532f9cbe6bc6997630d3ecb8d05c6df29a' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/fen.html',
      1 => 1536543280,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18880858875d89dd9e6d9814-63138095',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>分销中心</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/css/fen.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--主体内容-->
        <div id="content">
	        <!--页面头部-->
	        <div class="top-area">
			    <div class="head-image">
			    	<img src="<?php echo $_smarty_tpl->getVariable('fenInfo')->value['head_img_url'];?>
">
			    </div>
			    <div class="username">
			    	<?php echo $_smarty_tpl->getVariable('fenInfo')->value['name'];?>

			    </div>
			    <div class="money">
			    	<p>
			    		<span class="num" title="<?php echo $_smarty_tpl->getVariable('fenInfo')->value['my_fee'];?>
">
			    		<?php echo $_smarty_tpl->getVariable('fenInfo')->value['my_fee'];?>

			    		</span>
			    		<span class="unit">&nbsp;&nbsp;元</span>
			    	</p>
			    	<p class="unit2">佣金金额</p>
			    </div>
			</div>
			<div class="space"></div>
			
			<div class="cart-area">
			    <div class="info">
			        <a href="./index.php?c=fen&a=withdrawal&type=1&id=<?php echo $_smarty_tpl->getVariable('fenInfo')->value['id'];?>
">
			        	<div>
			        		<i class="icon iconfont check">&#xe63f;</i><span>提现</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		    </div>
			<div class="user-area">
			    <div class="info">
			        <a href="./index.php?c=fen&a=incomeCount">
			        	<div>
			        		<i class="icon iconfont like">&#xe649;</i><span>收益统计</span>
			        	</div>
			        	<div>
			        		<i class="icon iconfont arrow">&#xe618;</i>
			        	</div>
			        </a>
			    </div>
		   </div>
        </div>
    </div>
    <!--提示框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/js/fen.js"></script>

</body>
</html>