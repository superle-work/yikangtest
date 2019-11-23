<?php /* Smarty version Smarty-3.0.8, created on 2019-11-18 07:54:47
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/logistics/addLogistics.html" */ ?>
<?php /*%%SmartyHeaderCode:57915dd24e47b693c6-14207599%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '14a3fac5f16c76d92a54828cbfe4a73bff3dd3a4' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/logistics/addLogistics.html',
      1 => 1574063685,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '57915dd24e47b693c6-14207599',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html><html><!-- head --><?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/css/logistics/addLogistics.css" /><body><!---------------- 添加商品 -------------------><!-- 页头 --><?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><!-- jsfiles --><?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><link href="<?php echo @BASE_PATH;?>
/js/public/umeditor/themes/default/css/umeditor.min.css" type="text/css" rel="stylesheet"><script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/third-party/template.min.js"></script><script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/umeditor.config.js"></script><script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/umeditor.min.js"></script><script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/lang/zh-cn/zh-cn.js"></script><!-- 主体 --><div class="content container-fluid<?php if ($_smarty_tpl->getVariable('leftBarScale')->value=='1'){?> scale<?php }?>">    <div class="row">        <div class="col-md-2 left-section">            <!-- 左导航菜单 -->            <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/menu.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>        </div>        <div class="col-md-10 right-section">            <!--内容区域-->            <h3><i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i>添加工作人员</h3>            <div class="inner-section row">                <div class="items add-div">                    <form action="#" method="post" id="myForm"  enctype="multipart/form-data">                        <table class="table table-striped table-hover table-bordered table-base">                            <tr>                                <td class="td1"><label for="doctor_pwd"><span class="must-tag">*</span>工作人员</label></td>                                <td class="td2">                                    <div class="select-goods-area"><a data-toggle="modal" class="btn btn-primary btn-sm select-goods" href="./admin.php?c=store_logistics&a=showBindUser" data-target="#bindLabelDialog">选择用户</a></div>                                </td>                            </tr>                            <tr>                                <td class="td1">                                    <label>选择区域</label>                                </td>                                <td class="td2">                                    <div class="layui-form-item" id="addressDiv">                                        <label class="layui-form-label">管理区域</label>                                        <div class="layui-input-inline">                                            <select name="province" lay-filter="province" id="province">                                                <option ></option>                                            </select> 省                                        </div>                                        <div class="layui-input-inline">                                            <select name="city" lay-filter="city" id="city">                                                <option></option>                                            </select> 市                                        </div>                                        <div class="layui-input-inline">                                            <select name="area" lay-filter="area" id="area">                                                <option></option>                                            </select>                                        </div>                                    </div>                                </td>                            </tr>                            <tr>                                <td><input type="hidden" name="uid" id="uid" value=""></td>                                <td><button type="button" class="btn btn-success btn-md" id='save'>提交</button></td>                                <td></td>                            </tr>                        </table>                    </form>                </div>            </div>        </div>    </div></div><!--选择用户--><div class="modal fade" id="bindLabelDialog"  tabindex="-1" role="dialog"     aria-labelledby="myModalLabel" aria-hidden="true">    <div class="modal-dialog modal-lg">        <div class="modal-content">        </div><!-- /.modal-content -->    </div><!-- /.modal-dialog --></div><!-- /.modal --><!-- 页脚 --><?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/citys.js"></script><script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.form.js"></script><script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.js"></script><script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.extend.js"></script><script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/base/school/js/address.js"></script><script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/logistics/addLogistics.js"></script></body></html>