<?php /* Smarty version Smarty-3.0.8, created on 2019-09-27 07:43:01
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/order/orderDetail.html" */ ?>
<?php /*%%SmartyHeaderCode:8686114025d8d4d055c5719-59308845%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1470fb2197f22204f12e338c7b2927c4f23557d9' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/order/orderDetail.html',
      1 => 1544605196,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8686114025d8d4d055c5719-59308845',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>订单详情</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/base.css">
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/order/orderDetail.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--主体内容-->
        <div id="content">
        	<div class="orderDetail">
        	<?php if ($_smarty_tpl->getVariable('from')->value==1){?>
        		<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']==0){?>
        		<div class="space"></div>
        		<div class="number-wrap2">
        			<span>您已拍下商品，请尽快支付~</span>
        		</div>
        		<div class="space"></div>
        		<?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['state']==1){?>
        		
        		<?php }else{ ?>
        		<div class="space"></div>
        		<div class="number-wrap">
        			<?php if ($_smarty_tpl->getVariable('clinicInfo')->value!=''){?>
        			<div>采样诊所 :&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('clinicInfo')->value['name'];?>
</div>
    				<?php }?>
    				
    				<?php if ($_smarty_tpl->getVariable('hospitalInfo')->value!=''){?>
        			<div>送检医院 :&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('hospitalInfo')->value['name'];?>
</div>
    				<?php }?>
        		</div>
        		<div class="space"></div>
        		<?php }?>
        	<?php }else{ ?>
        		<div class="number-wrap">
        			<?php if ($_smarty_tpl->getVariable('clinicInfo')->value!=''){?>
        			<div>采样诊所 :&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('clinicInfo')->value['name'];?>
</div>
    				<?php }?>
    				
    				<?php if ($_smarty_tpl->getVariable('hospitalInfo')->value!=''){?>
        			<div>送检医院 :&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('hospitalInfo')->value['name'];?>
</div>
    				<?php }?>
        		</div>
        		<div class="space"></div>
        	<?php }?>
        		
        		<div class="receiver-info">
        			<div class="checkName">
        				<?php echo $_smarty_tpl->getVariable('memberInfo')->value['name'];?>

        			</div>
        			<div class="phone">
        				出生年月日 :&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('memberInfo')->value['idCard'];?>
</div>
                    <div class="phone">
                       	手机号码 :&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('memberInfo')->value['phone'];?>

                    </div>
                </div>
                
                <div class="space"></div>
                
                
                
                <div class="goods-area">
                	<?php  $_smarty_tpl->tpl_vars['goods'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('orderInfo')->value['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['goods']->key => $_smarty_tpl->tpl_vars['goods']->value){
?>
                	<div class="goods-info">
	                	<a href="index.php?c=store&a=goodsDetail&id=<?php echo $_smarty_tpl->tpl_vars['goods']->value['gid'];?>
">
	                		<div class="goods">
	                			<div class="good-name">
	                				<div class="name"><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_name'];?>
</div>
	                				<div class="price">￥<?php echo $_smarty_tpl->tpl_vars['goods']->value['price'];?>
</div>
	                			</div>
	                			<div class="state">
	                				<?php if ($_smarty_tpl->getVariable('from')->value==1&&$_smarty_tpl->getVariable('orderInfo')->value['state']==1||$_smarty_tpl->getVariable('from')->value==2){?>
	                				<div class="state-type"></div>
	                				<?php }else{ ?>
	                				<div class="state-type">
	                					<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']==0){?><span class="state-0">待付款</span><?php }?>
					    				<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']==1){?><span class="state-1">待采样</span> <?php }?>
					    				<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']==2){?><span class="state-2">待送检</span><?php }?>
					    				<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']==3){?><span class="state-3">送检中</span><?php }?>
					    				<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']==4){?><span class="state-4">检测中</span><?php }?>
					    				<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']==5){?><span class="state-5">已完成</span><?php }?>
					    			</div>
					    			<?php }?>
	                				<div class="quantity">已售<?php echo $_smarty_tpl->tpl_vars['goods']->value['sale_quantity'];?>
</div>
	                			</div>
	                		</div>
	                	</a>
                	</div>
                	<div style="height:2px;background: #F5F5F5;"></div>
                	<?php }} ?>
                </div>
                
            <?php if ($_smarty_tpl->getVariable('from')->value==1){?>
                <div class="space"></div>
                
                <div class="order-number-area">
                	<span>订单编号:&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('orderInfo')->value['order_num'];?>
</span>
                	<span>下单时间:&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('orderInfo')->value['add_time'];?>
</span>
                	
                	<?php if ($_smarty_tpl->getVariable('orderInfo')->value['sample_time']!=''&&$_smarty_tpl->getVariable('orderInfo')->value['sample_time']!=null){?>
                	<span>采样时间:&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('orderInfo')->value['sample_time'];?>
</span>
                	<?php }?>
                	
                	<?php if ($_smarty_tpl->getVariable('orderInfo')->value['check_time']!=''&&$_smarty_tpl->getVariable('orderInfo')->value['check_time']!=null){?>
                	<span>送检时间:&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('orderInfo')->value['check_time'];?>
</span>
                	<?php }?>
                	
                	<?php if ($_smarty_tpl->getVariable('orderInfo')->value['end_time']!=''&&$_smarty_tpl->getVariable('orderInfo')->value['end_time']!=null){?>
                	<span>完成时间:&nbsp;&nbsp;<?php echo $_smarty_tpl->getVariable('orderInfo')->value['end_time'];?>
</span>
                	<?php }?>
                </div>
            <?php }else{ ?>
            	<div class="space2">订单信息</div>
                
                <div class="order-info-area">
                	<div class="order-info">
                		<div>
                			<span>姓名</span>
                			<span><?php echo $_smarty_tpl->getVariable('orderInfo')->value['nick_name'];?>
</span>
                		</div>
                		<div>
                			<span>手机号码</span>
                			<span><?php echo $_smarty_tpl->getVariable('orderInfo')->value['phone'];?>
</span>
                		</div>
                		<div>
                			<span>订单号</span>
                			<span><?php echo $_smarty_tpl->getVariable('orderInfo')->value['order_num'];?>
</span>
                		</div>
                		<div>
                			<span>下单时间</span>
                			<span><?php echo $_smarty_tpl->getVariable('orderInfo')->value['add_time'];?>
</span>
                		</div>
                	</div>
                	<div class="total_price">
                		<span>总金额</span>
                		<span>￥<?php echo $_smarty_tpl->getVariable('orderInfo')->value['total_price'];?>
</span>
                	</div>
                </div>
            <?php }?>
        	</div>
        </div>
        
    <?php if ($_smarty_tpl->getVariable('from')->value==1){?>
    	<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']==0){?>
    	<div class="last-area">
    		<button id="goPay" data-oid="<?php echo $_smarty_tpl->getVariable('orderInfo')->value['id'];?>
">立即支付</button>
    	</div>
   		<?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['state']==1){?>
        <div class="last-area">
        	<!--待采样时显示订单的二维码-->
    		<img src="index.php?c=store&a=qrcode&oid=<?php echo $_smarty_tpl->getVariable('orderInfo')->value['id'];?>
" alt="" />
    		<div class="tipInfo">体检前请出示二维码</div>
    	</div>
        <?php }else{ ?>
        
        <?php }?>
    <?php }elseif($_smarty_tpl->getVariable('from')->value==2){?>
    	
    <?php }else{ ?>
    	<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']==5){?>
    	<div class="report-area">
    		<?php if ($_smarty_tpl->getVariable('reportInfo')->value['imgInfo']!=''||$_smarty_tpl->getVariable('reportInfo')->value['imgInfo']!=null){?>
    		<?php  $_smarty_tpl->tpl_vars['img'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('reportInfo')->value['imgInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['img']->key => $_smarty_tpl->tpl_vars['img']->value){
?>
    		<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value['img_url'];?>
" alt="" />
    		<?php }} ?>
    		<?php }?>
    	</div>
    	<?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['state']==3||$_smarty_tpl->getVariable('orderInfo')->value['state']==4){?>
        <div class="last-area">
    		<button id="addReport" data-oid="<?php echo $_smarty_tpl->getVariable('orderInfo')->value['id'];?>
">上传报告</button>
    	</div>
    	<?php }else{ ?>
    	
        <?php }?>
    <?php }?>
    <br />
    <br />
    </div>
    <!--提示框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/order/orderDetail.js"></script>

</body>
</html>