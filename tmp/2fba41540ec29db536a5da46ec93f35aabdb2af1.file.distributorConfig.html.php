<?php /* Smarty version Smarty-3.0.8, created on 2019-09-26 16:32:08
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/fen/page/distributor/distributorConfig.html" */ ?>
<?php /*%%SmartyHeaderCode:12033991725d8c77881da057-18348140%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2fba41540ec29db536a5da46ec93f35aabdb2af1' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/fen/page/distributor/distributorConfig.html',
      1 => 1535701988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12033991725d8c77881da057-18348140',
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
/fen/css/distributor/distributorConfig.css">
<body>
<!---------------- 分销商配置页面 ------------------->

<!-- 页头 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!-- 主体 -->
<div class="content container-fluid<?php if ($_SESSION['admin']['left_bar_scale']=='1'){?> scale<?php }?>">
    <div class="row">
        <div class="col-md-2 left-section">
            <!-- 左导航菜单 -->
            <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/menu.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

        </div>
        <div class="col-md-10 right-section">
            <!--内容区域-->
            <h3><i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i><?php echo $_SESSION['currentMenu']['menuTitle'];?>


            </h3>
            <div class="inner-section row">

                <div class="item table-responsive">
                    <form action="#" method="post" enctype="multipart/form-data">
                        <div id="normal-config" class="item table-responsive">
                            <table class="table table-striped table-hover table-bordered table-base">
                                <tbody>
                                <tr>
                                    <th style="width: 140px;">名称</th>
                                    <th>内容</th>
                                    <th>提示</th>
                                </tr>
                                <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('configInfo')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
                                <tr>
                                    <td><?php echo $_smarty_tpl->tpl_vars['item']->value['label'];?>
</td>
                                    <td>
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value['type']=='radio'){?>
                                        <label class="radio-inline myradio">
                                            <input type="radio" name="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_key'];?>
"  value="1" <?php if ($_smarty_tpl->tpl_vars['item']->value['item_value']=='1'){?> checked="true" <?php }?>>&nbsp; 是
                                        </label>
                                        <label class="radio-inline myradio">
                                            <input type="radio" name="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_key'];?>
"  value="0" <?php if ($_smarty_tpl->tpl_vars['item']->value['item_value']=='0'){?> checked="true" <?php }?>>&nbsp; 否
                                        </label>
                                        <?php }elseif($_smarty_tpl->tpl_vars['item']->value['type']=='select'){?>
                                        <select name="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_key'];?>
">
                                            <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['item']->value['box']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_value'];?>
"<?php if ($_smarty_tpl->tpl_vars['item']->value['item_value']==$_smarty_tpl->tpl_vars['value']->value){?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</option>
                                            <?php }} ?>
                                        </select>
                                        <?php }elseif($_smarty_tpl->tpl_vars['item']->value['type']=='text'){?>
                                        <input type="text" name="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_key'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_value'];?>
" size="80" <?php if ($_smarty_tpl->tpl_vars['item']->value['item_key']=='qq'){?>placeholder="形如 小美:123456,小丽:456789"<?php }?>>
                                        <?php }elseif($_smarty_tpl->tpl_vars['item']->value['type']=='image'){?>
                                        <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_value'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_key'];?>
">
                                        <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_value'];?>
" style="width: 240px;">&nbsp;&nbsp;<a class="btn btn-xs btn-success select-logo">选择</a>
                                        <?php }elseif($_smarty_tpl->tpl_vars['item']->value['type']=='file'){?>
                                        <input type="file" name="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_key'];?>
" size="18">
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value['item_value']){?><img src="<?php echo @THEMES_PATH;?>
/image/yes.gif"><?php }else{ ?><img src="<?php echo @THEMES_PATH;?>
/image/no.gif"><?php }?>
                                        <?php }elseif($_smarty_tpl->tpl_vars['item']->value['type']=='textarea'){?>
                                            <textarea name="<?php echo $_smarty_tpl->tpl_vars['item']->value['item_key'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['item_value'];?>
</textarea>
                                        <?php }?>
                                    </td>
                                    <td><span style="color:#999;"><?php echo $_smarty_tpl->tpl_vars['item']->value['tip'];?>
</span></td>
                                </tr>
                                <?php }} ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-primary submit" type="submit">提交</button>
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 总店门头照选择（Modal） 提示框-->
<div class="modal fade" id="selectLogoModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    选择总店门头照
                </h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-confirm">确认</button>
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">关闭</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div><!-- /.modal -->
<!-- 页脚 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<!-- jsfiles -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/fen/js/distributor/distributorConfig.js"></script>
</body>
</html>