<?php /* Smarty version Smarty-3.0.8, created on 2019-12-17 07:59:01
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/base/printer/page/editPrinter.html" */ ?>
<?php /*%%SmartyHeaderCode:269845df88ac5b675a0-70432189%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '22aa62ce3481bc08999fa3b028884bbc38a3a01e' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/base/printer/page/editPrinter.html',
      1 => 1576568216,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '269845df88ac5b675a0-70432189',
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
/base/printer/css/editPrinter.css" />

<body>

<!---------------- 编辑商品 ------------------->

<!-- 页头 -->

<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<!-- jsfiles -->

<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
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
</i>编辑打印机</h3>

            <div class="inner-section row">

                <form role="form"  method="post" id="myForm"  enctype="multipart/form-data">

                    <table class="table table-striped table-hover table-bordered table-base">

                        <tr>

                            <td class="td1"><label for="num"><span class="must-tag">*</span>打印机编号</label></td>

                            <td class="td2"><input type="text" id="num" name="num" placeholder="打印机编号" value="<?php echo $_smarty_tpl->getVariable('goods')->value['num'];?>
"></td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label for=""><span class="must-tag">*</span>诊所所在区/县</label></td>

                            <td class="td2">

                                <div class="area-info" data-province="<?php echo $_smarty_tpl->getVariable('goods')->value['province'];?>
" data-city="<?php echo $_smarty_tpl->getVariable('goods')->value['city'];?>
" data-area="<?php echo $_smarty_tpl->getVariable('goods')->value['area'];?>
">

		                    		<select id="province" name="province"></select> <select id="city" name="city"></select> <select id="area" name="area"></select>

		                    	</div>

                            </td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td>



                            </td>

                            <td>

                                <input type="hidden" id='id' name="id" value="<?php echo $_smarty_tpl->getVariable('goods')->value['id'];?>
">

                                <button type="button" class="btn btn-success btn-md" id='save'>提交</button>

                                <button type="button" class="btn btn-default btn-md" id="back">返回</button>

                            </td>

                            <td></td>

                        </tr>

                    </table>



                </form>

            </div>

        </div>

    </div>

</div>





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
/base/printer/js/editPrinter.js"></script>

</body>

</html>



