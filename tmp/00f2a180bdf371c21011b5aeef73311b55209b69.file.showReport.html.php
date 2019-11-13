<?php /* Smarty version Smarty-3.0.8, created on 2019-09-18 17:11:11
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/order/showReport.html" */ ?>
<?php /*%%SmartyHeaderCode:7502962745d81f4af2163a2-61397933%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '00f2a180bdf371c21011b5aeef73311b55209b69' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/order/showReport.html',
      1 => 1537857234,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7502962745d81f4af2163a2-61397933',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html>
<!-- head -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/css/order/showReport.css" />
<body>
<!---------------- 查看订单报告 ------------------->

<!-- 页头 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!-- 主体 -->
<div class="content container-fluid<?php if ($_smarty_tpl->getVariable('leftBarScale')->value=='1'){?> scale<?php }?>">
    <div class="row">
        <div class="col-md-2 left-section">
            <!-- 左导航菜单 -->
            <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/menu.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

        </div>
        <div class="col-md-10 right-section">
            <!--内容区域-->
            <h3>
                <i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i>查看订单报告
            </h3>
            <div class="inner-section row">
                <div id="content">
                    <div class="list-title">
                        <div class="list-title-panel">
                            <span class="glyphicon glyphicon-list"></span>列表</span>
                        </div>
                    </div>
                    <div class="table-content" id = "list_container">
                        <table id="list-table" class="list-table table-hover table-bordered" cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <th style="width:100px"></th>
                                    <th>报告详情</th>
                                </tr>
                                <tr>
                                    <td style="width:100px">报告描述</td>
                                    <td><?php echo $_smarty_tpl->getVariable('reportInfo')->value['report_desc'];?>
</td>
                                </tr>
                                <tr>
                                    <td style="width:100px">报告图片</td>
                                    <td class="image">
                                        <?php  $_smarty_tpl->tpl_vars['img'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('reportInfo')->value['img_str']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['img']->key => $_smarty_tpl->tpl_vars['img']->value){
?>
                                        <img src="<?php echo $_smarty_tpl->tpl_vars['img']->value['img_url'];?>
" alt="">
                                        <?php }} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="edit">
                                        <a href="admin.php?c=store_order&a=editReport&id=<?php echo $_smarty_tpl->getVariable('reportInfo')->value['id'];?>
" class="btn-primary btn-xs">编辑</a>
                                        <a href="javascript:history.go(-1);" class="btn-info btn-xs">返回</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
					
                    <div class="fn-clear"></div>

                    <!-- 列表底部 -->
                    <div class="list-bottom">
                        <div class="list-bottom-panel">
                        </div>
                    </div>
                </div>
                <!--分页-->
                <div id="page-selection"></div>
            </div>
        </div>
    </div>
</div>

<!-- 页脚 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<!-- jsfiles -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script src="<?php echo @BASE_PATH;?>
/js/public/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/jquery.bootpag/jquery.bootpag.min.js"></script>
</body>
</html>

