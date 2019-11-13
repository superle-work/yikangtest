<?php /* Smarty version Smarty-3.0.8, created on 2019-09-11 16:16:32
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/clinic/clinicList.html" */ ?>
<?php /*%%SmartyHeaderCode:11327642265d78ad605a0f01-21035981%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '28426c685de751b93fef3cc080e2438fc426df82' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/clinic/clinicList.html',
      1 => 1540952280,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11327642265d78ad605a0f01-21035981',
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
/store/css/clinic/clinicList.css" />

<body>

<!---------------- 诊所列表 ------------------->



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

                    <a class="btn btn-success" class="addGoods" href="admin.php?c=store_clinic&a=addClinic">添加</a>

                </div>

            </h3>

            <div class="inner-section row">

                <div id="content">

                    <div class="search-param-panel">

                        <form class="search-param-form" id="search-param-form">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                <tr>

                                    <td width="80px">

                                        <label for="goodsname">诊所名称:&nbsp;</label>

                                    </td>

                                    <td width="140px">

                                        <input id="goodsname" name="goodsname" type="text" class="input-normal search"  />

                                    </td>

                                    <td width="80px">

                                        <label for="startTime">创建时间:&nbsp;</label>

                                    </td>

                                    <td width="140px">

                                        <input id="startTime" name="startTime" type="text" class="input-normal search Wdate" onclick="WdatePicker({dateFmt:'yyyy-MM-dd'})" />

                                    </td>

                                    <td width="40px">

                                        <label for="endTime">至:</label>

                                    </td>

                                    <td width="140px">

                                        <input id="endTime" name="endTime" type="text" class="input-normal search Wdate" onclick="WdatePicker({dateFmt:'yyyy-MM-dd'})" />

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



<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title" id="myModalLabel">模态框（Modal）标题</h4>

            </div>

            <div class="modal-body">按下 ESC 按钮退出。</div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>

                <button type="button" class="btn btn-primary">提交更改</button>

            </div>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

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
/store/js/clinic/clinicList.js"></script>

</body>

</html>