<?php /* Smarty version Smarty-3.0.8, created on 2019-11-12 08:14:02
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/base/admin/page/editAdmin.html" */ ?>
<?php /*%%SmartyHeaderCode:259715dca69cac04ae6-37076556%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9f7e75323cc545910cf4c7b1cb9255485648c5c7' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/base/admin/page/editAdmin.html',
      1 => 1535701986,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '259715dca69cac04ae6-37076556',
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
<body>
<!---------------- 管理员编辑 ------------------->

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
            <h3><i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i>编辑管理员</h3>
            <div class="inner-section row">

                <div class="items editNav-div">
                    <form  id="myForm" action="#" method="post" enctype="multipart/form-data">
                        <div id="inNav" class="item table-responsive">
                            <table class="table table-striped table-hover table-bordered table-base">
                                <tbody>
                                <tr>
                                    <td>账号<label class="must-tag">*</label></td>
                                    <td>
                                        &nbsp;<label><?php echo $_smarty_tpl->getVariable('admin')->value['account'];?>
</label>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>名称<label class="must-tag">&nbsp;&nbsp;</label></td>
                                    <td>
                                        <input type="text" name="admin_name" id="admin_name" value="<?php echo $_smarty_tpl->getVariable('admin')->value['admin_name'];?>
">
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>邮箱<label class="must-tag">&nbsp;</label></td>
                                    <td>
                                        <input type="text"  name="email" id="email" value="<?php echo $_smarty_tpl->getVariable('admin')->value['email'];?>
">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        <input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('admin')->value['id'];?>
">
                                        <input type="hidden" name="account" value="<?php echo $_smarty_tpl->getVariable('admin')->value['account'];?>
">
                                        <a class="btn btn-primary submit">提交</a>
                                        <a href="javascript:window.history.go(-1);" class="btn btn-default">返回</a>
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

<!-- 页脚 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<!-- jsfiles -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/base/admin/js/editAdmin.js"></script>
</body>
</html>