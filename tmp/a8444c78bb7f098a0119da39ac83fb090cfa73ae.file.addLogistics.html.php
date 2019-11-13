<?php /* Smarty version Smarty-3.0.8, created on 2019-09-27 11:46:56
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/logistics/addLogistics.html" */ ?>
<?php /*%%SmartyHeaderCode:5929689835d8d86308a0674-49888783%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a8444c78bb7f098a0119da39ac83fb090cfa73ae' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/logistics/addLogistics.html',
      1 => 1537258800,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5929689835d8d86308a0674-49888783',
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
/store/css/logistics/addLogistics.css" />
<body>
<!---------------- 添加商品 ------------------->
<!-- 页头 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!-- jsfiles -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<link href="<?php echo @BASE_PATH;?>
/js/public/umeditor/themes/default/css/umeditor.min.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/third-party/template.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/umeditor.min.js"></script>
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/lang/zh-cn/zh-cn.js"></script>
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
</i>添加工作人员</h3>
            <div class="inner-section row">
                <div class="items add-div">
                    <form action="#" method="post" id="myForm"  enctype="multipart/form-data">
                        <table class="table table-striped table-hover table-bordered table-base">
                            <tr>
                                <td class="td1"><label for="doctor_pwd"><span class="must-tag">*</span>工作人员</label></td>
                                <td class="td2">
                                    <div class="select-goods-area"><a data-toggle="modal" class="btn btn-primary btn-sm select-goods" href="./admin.php?c=store_logistics&a=showBindUser" data-target="#bindLabelDialog">选择用户</a></div>
                                </td>
                                <td class="td3"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="uid" id="uid" value=""></td>
                                <td><button type="button" class="btn btn-success btn-md" id='save'>提交</button></td>
                                <td></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--选择用户-->
<div class="modal fade" id="bindLabelDialog"  tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- 页脚 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.extend.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/base/school/js/address.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/logistics/addLogistics.js"></script>
</body>
</html>