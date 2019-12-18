<?php /* Smarty version Smarty-3.0.8, created on 2019-12-17 03:27:28
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/base/printer/page/printerDetail.html" */ ?>
<?php /*%%SmartyHeaderCode:232595df84b201605e2-24725109%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c0da83534911ba579c168f2c8af84de0a0c25612' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/base/printer/page/printerDetail.html',
      1 => 1576553240,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '232595df84b201605e2-24725109',
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

<!---------------- 商品详情 ------------------->



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
</i>诊所详情</h3>

            <div class="inner-section row">

                <div class="items add-div">

                    <form id="myForm" action="#" method="post" enctype="multipart/form-data">

                        <div class="item table-responsive">

                            <table class="table table-striped table-hover table-bordered table-base">

                                <tbody>

                                    <tr>

                                        <td>打印机编号<label class="must-tag">&nbsp;&nbsp;</label></td>

                                        <td>

                                            <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['num'];?>


                                        </td>

                                        <td></td>

                                    </tr>

                                    <tr>

                                        <td>打印机所属区/县<label class="must-tag">&nbsp;&nbsp;</label></td>

                                        <td>

                                            <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['province'];?>
 <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['city'];?>
 <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['area'];?>


                                        </td>

                                        <td></td>

                                    </tr>

                                    <tr>

                                        <td>

                                        </td>

                                        <td>

                                            <input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['id'];?>
">

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

</body>

</html>