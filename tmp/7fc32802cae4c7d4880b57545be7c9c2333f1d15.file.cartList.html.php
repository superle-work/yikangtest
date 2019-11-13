<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 17:06:59
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/cart/cartList.html" */ ?>
<?php /*%%SmartyHeaderCode:8740497185d89dcb39b19b4-30032671%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7fc32802cae4c7d4880b57545be7c9c2333f1d15' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/cart/cartList.html',
      1 => 1541821355,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8740497185d89dcb39b19b4-30032671',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>购物车</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/cart/cartList.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--页面头部-->
        <div class="top-area">
        	<div data-type="1" <?php if ($_smarty_tpl->getVariable('type')->value==1){?> class="cur" <?php }?> >
        		<span>本地医院</span>
        		<?php if ($_smarty_tpl->getVariable('type')->value==2){?>
        		<span class="num"><?php echo $_smarty_tpl->getVariable('num')->value;?>
</span>
        		<?php }?>
        	</div>
        	<div data-type="2" <?php if ($_smarty_tpl->getVariable('type')->value==2){?> class="cur" <?php }?> >
        		<span>第三方医院</span>
        		<?php if ($_smarty_tpl->getVariable('type')->value==1){?>
        		<span class="num"><?php echo $_smarty_tpl->getVariable('num')->value;?>
</span>
        		<?php }?>
        	</div>
        </div>
        <div class="space"></div>
        <!--主体内容-->
        <div id="content">
            <?php if ($_smarty_tpl->getVariable('cartList')->value){?>
            <div class="cart-top-area">
            	<div class="all-select">
            		<i class="icon iconfont location selected">&#xe648;</i>
            		<span>&nbsp;&nbsp;全选</span>
            	</div>
            	<div class="delete">
            		<a href="javascript:;" style="color:#7C7C7C;">删除</a>
            	</div>
            	
                <!--<a class="all-selected" id="selectAll" href="javascript:;">
                	<span class="selected">
	                    <i class="icon iconfont all-selected">&#xe648;</i>
	                    <i class="icon iconfont not-all-selected" style="display: none;">&#xe623;</i>
                    </span>
                    <span class="delete" style="display:none">
	                    <i class="icon iconfont all-delete" style="display: none;">&#xe616;</i>
	                    <i class="icon iconfont not-all-delete">&#xe618;</i>
                    </span>
                    <span class="select-all-text">全选</span>
                </a>-->
                <!--<a href="javascript:;" class="complete" style="display: none;">完成</a><a href="javascript:;" class="edit">编辑</a>-->
            </div>
            <!--购物车商品列表-->
            <div class="cart-goods-list">
            	<?php  $_smarty_tpl->tpl_vars['cart'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('cartList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cart']->key => $_smarty_tpl->tpl_vars['cart']->value){
?>
                <div class="goods-info">
                	<div class="goods">
                		<div class="good-sel selected" data-id="<?php echo $_smarty_tpl->tpl_vars['cart']->value['id'];?>
" data-gid="<?php echo $_smarty_tpl->tpl_vars['cart']->value['gid'];?>
">
                            <i class="icon iconfont">&#xe648;</i>
                        </div>
                        		
                		<a href="index.php?c=store&a=goodsDetail&id=<?php echo $_smarty_tpl->tpl_vars['cart']->value['gid'];?>
">
                			<div class="good-name">
                				<div class="name"><?php echo $_smarty_tpl->tpl_vars['cart']->value['goods_name'];?>
</div>
                				<div class="price">￥<?php echo $_smarty_tpl->tpl_vars['cart']->value['price'];?>
</div>
                			</div>
                		</a>
                		<div class="state">
            				<div class="state-type">
            					
			    			</div>
            				<div class="count">
            					<div class="num-area" data-id="<?php echo $_smarty_tpl->tpl_vars['cart']->value['id'];?>
">
                       				<button class="decCount" >-</button><input type="text" class="num" value="<?php echo $_smarty_tpl->tpl_vars['cart']->value['goods_count'];?>
"><button class="incCount" >+</button>
            					</div>
            				</div>
            			</div>
            		</div>
                </div>
                <div class="space2"></div>
                <?php }} ?>
            </div>
            <?php }else{ ?>
            <div class="empty-cart">
            	<i class="icon iconfont empty">&#xe650;</i>
                <p class="text-1">啊哦，购物车快饿瘪了T.T</p>
                <p class="text-2">快给我挑点宝贝吧</p>
                <p class="text-3"><a href="./index.php?c=store&a=index">去逛逛</a></p>
            </div>
            <?php }?>

        </div>

        <!--底部功能区-->
        <?php if ($_smarty_tpl->getVariable('cartList')->value){?>
        <div class="cart-bottom-area">
            <div class="row">
                <!--<a class="col-xs-4 col-sm-4 col-md-4 all-selected" id="selectAll" href="javascript:;">
                    <i class="icon iconfont all-selected">&#xe616;</i>
                    <i class="icon iconfont not-all-selected" style="display: none;">&#xe618;</i>
                    <span class="select-all-text">全选</span>
                </a>
                <a class="col-xs-4 col-sm-4 col-md-4" style="display: none;" id="selecAllDelete" href="javascript:;">
                    <i class="icon iconfont all-selected" style="display: none;">&#xe619;</i>
                    <i class="icon iconfont not-all-selected">&#xe618;</i>
                    <span class="select-all-text">全选</span>
                </a>-->
                <!--<a class="col-xs-4 col-sm-4 col-md-4" id="totalCounts"><span class="total-price-text">合计：<span class="total-price-value">0</span>元</span></a>-->
                
                <a class="col-xs-8 col-sm-8 col-md-8" id="totalPrice"><span class="totalCounts">共<span class="goods-count">0</span>件商品</span><span class="total-price-text">&nbsp;总金额：<i class="icon iconfont total-price">&#xe645;</i><span class="total-price-value">0</span></span></a>
                <a class="col-xs-4 col-sm-4 col-md-4 "id="balance" href="javascript:;"><span class="can-use" data-user="<?php echo $_SESSION['user']['id'];?>
">去结算</span></a>
            </div>
        </div>
        <?php }else{ ?>

        <?php }?>
        
    </div>
    
    <!--危险操作确认提示框-->
	<div id="deleteModal" class="my-confirm-dialog my-dialog">
		<div class="dialog-body">
			<div class="title-text">提示</div>
			<div class="desc-text">确认要删除此产品吗</div>
		</div>
		<div class="dialog-footer">
			<a class="cancel" href="javascript:;">取消</a>
			<a class="confirm" href="javascript:;">确认</a>
		</div>
	</div>
    <!--提示框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/cart/cartList.js"></script>
</body>
</html>