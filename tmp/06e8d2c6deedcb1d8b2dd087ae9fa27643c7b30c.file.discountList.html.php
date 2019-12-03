<?php /* Smarty version Smarty-3.0.8, created on 2019-11-30 06:50:24
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/discount/discountList.html" */ ?>
<?php /*%%SmartyHeaderCode:322075de211308fe2a6-97424782%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '06e8d2c6deedcb1d8b2dd087ae9fa27643c7b30c' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/discount/discountList.html',
      1 => 1575096620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '322075de211308fe2a6-97424782',
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
/store/css/discount/discountList.css" />

<body>

<!---------------- 代理列表 ------------------->



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
</i><?php echo $_SESSION['currentMenu']['menuTitle'];?>


                <div class="operation-div">

                    <a class="btn btn-success" class="addGoods" href="admin.php?c=store_discount&a=addDiscount">添加</a>

                </div>

            </h3>

            <div class="inner-section row">

                <div id="content">

                    <div class="search-param-panel">

                        <form class="search-param-form" id="search-param-form">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                <tr>

                                    <td width="80px">

                                        <label for="user_type">用户角色:&nbsp;</label>

                                    </td>

                                    <td width="140px">
                                        <select class="select-normal"  id="user_type" name="user_type">
                                            <option value="">全部</option>
                                            <option value="0">普通用户</option>
                                            <option value="1">诊所用户</option>
                                            <option value="2">医院用户</option>
                                            <option value="3">物流人员</option>
                                        </select>

                                    </td>

                                    <td>

                                        <div class="list-action-area">

                                            <a href="javascript:;" class="search-button btn btn-primary btn-sm">查询</a>

                                        </div>

                                    </td>

                                </tr>

                            </table>

                        </form>

                    </div>

                    <div class="list-title">

                        <div class="list-title-panel">

                            <span class="glyphicon glyphicon-list"></span>列表</span>

                        </div>

                    </div>

                    <div class="union-operate">

                    	<a href="javascript:;" class="btn btn-default btn-sm delete-batch">批量删除</a>

                    </div>

                    <div class="table-content" id = "list_container">

                        <table id="list-table" class="list-table table-hover table-bordered">

                            <tbody>

                            </tbody>

                        </table>

                    </div>

                    <!--分页-->

                    <div id="page-selection"></div>



                    <div class="fn-clear"></div>



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

<script src="<?php echo @BASE_PATH;?>
/js/public/My97DatePicker/WdatePicker.js"></script>

<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/jquery.bootpag/jquery.bootpag.min.js"></script>

<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/discount/discountList.js"></script>

</body>

</html>