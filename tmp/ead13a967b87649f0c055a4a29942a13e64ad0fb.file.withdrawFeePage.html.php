<?php /* Smarty version Smarty-3.0.8, created on 2019-09-29 14:10:29
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/withdrawFeePage.html" */ ?>
<?php /*%%SmartyHeaderCode:2507356435d904ad5f1d809-64836633%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ead13a967b87649f0c055a4a29942a13e64ad0fb' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/withdrawFeePage.html',
      1 => 1536656818,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2507356435d904ad5f1d809-64836633',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>申请提现</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/css/withdrawFeePage.css">
</head>

<body>
    <!--主容器-->
    <div>
     
        <!--主体内容-->
        <div id="content">
            <div class="top-area">
                <div>账户金额（元）</div>
                <div class="myfee"><?php echo $_smarty_tpl->getVariable('my_fee')->value;?>
</div>
            </div>
            
            <div class="inp-area">
	        	<p class="withdrawal">输入提现金额</p>
		    	<i class="icon iconfont rmb">&#xe645;</i><input type="text" name="money" class="inp"/>
			</div>
			
			<div class="space"></div>
			
            <div class="choose-account-text">
            	<span>请选择收款账户</span>
                <a class="account-manage" href="./index.php?c=fen&a=accountList&id=<?php echo $_smarty_tpl->getVariable('id')->value;?>
&type=<?php echo $_smarty_tpl->getVariable('type')->value;?>
"><i class="icon iconfont">&#xe642;</i>账户管理</a>
            </div>

            <!--支付账号列表（支付宝、微信、银联）-->
            <div class="account-list">
                <?php if ($_smarty_tpl->getVariable('accountList')->value){?>
                <table>
                    <?php  $_smarty_tpl->tpl_vars['account'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('accountList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['account']->index=-1;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['account']->key => $_smarty_tpl->tpl_vars['account']->value){
 $_smarty_tpl->tpl_vars['account']->index++;
 $_smarty_tpl->tpl_vars['account']->first = $_smarty_tpl->tpl_vars['account']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['accountList']['first'] = $_smarty_tpl->tpl_vars['account']->first;
?>
                    <tr>
                        <td class="col-1">
                            <a href="javascript:;" class="item-option<?php if ($_smarty_tpl->tpl_vars['account']->value['is_default']=='1'||$_smarty_tpl->getVariable('smarty')->value['foreach']['accountList']['first']){?> checked<?php }?>" data-aid="<?php echo $_smarty_tpl->tpl_vars['account']->value['id'];?>
">
                                <i class="icon iconfont selected" <?php if ($_smarty_tpl->tpl_vars['account']->value['is_default']=='1'||$_smarty_tpl->getVariable('smarty')->value['foreach']['accountList']['first']){?>style="display:inline;"<?php }?>>&#xe648;</i>
                                <i class="icon iconfont un-selected"<?php if ($_smarty_tpl->tpl_vars['account']->value['is_default']=='0'&&!$_smarty_tpl->getVariable('smarty')->value['foreach']['accountList']['first']){?>style="display:inline;"<?php }?>>&#xe623;</i>
                            </a>
                        </td>
                        <td class="col-2">
                            <?php if ($_smarty_tpl->tpl_vars['account']->value['account_type']=='0'){?>支付宝
                            <?php }elseif($_smarty_tpl->tpl_vars['account']->value['account_type']=='1'){?>微信
                            <?php }elseif($_smarty_tpl->tpl_vars['account']->value['account_type']=='2'){?>银行卡<?php }?>
                        </td>
                        <td class="col-3"><?php echo $_smarty_tpl->tpl_vars['account']->value['account'];?>
</td>
                        <td class="col-4"><?php echo $_smarty_tpl->tpl_vars['account']->value['name'];?>
</td>
                    </tr>
                    <?php }} ?>
                </table>
                <?php }else{ ?>
                <p class="bg-danger no-data">还没有任何账户信息呢！</p>
                <?php }?>
            </div>
        </div>
        
        <div class="withdraw">
        	<input type="hidden" id="tx_uid" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
"/>
        	<input type="hidden" id="tx_type" value="<?php echo $_smarty_tpl->getVariable('type')->value;?>
"/>
            <a href="javascript:;" class="can-use">申请提现</a>
        </div>

        <!--页面脚部-->
        <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/bottomNav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
        <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    </div>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/js/withdrawFeePage.js"></script>
</body>
</html>