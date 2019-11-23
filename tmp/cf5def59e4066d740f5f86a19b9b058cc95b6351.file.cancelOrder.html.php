<?php /* Smarty version Smarty-3.0.8, created on 2019-11-13 07:50:50
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/clinic/cancelOrder.html" */ ?>
<?php /*%%SmartyHeaderCode:223495dcbb5dab5f146-82965183%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cf5def59e4066d740f5f86a19b9b058cc95b6351' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/clinic/cancelOrder.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '223495dcbb5dab5f146-82965183',
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
/store/css/clinic/cancelOrder.css" />
<body>
<!---------------- 核销记录 ------------------->

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
                <i class="icon iconfont">&#xe697;</i>核销记录
                <div class="operation-div">
                    <a class="btn btn-default" href="javascript:history.go(-1);">返回</a>
                </div>
            </h3>
            <div class="inner-section row">
                <div id="content">
                    <div class="search-param-panel">
                        <form class="search-param-form" id="search-param-form">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                	<td width="80px">
                                        <label for="user_name">核销人员:&nbsp;</label>
                                    </td>
                                    <td width="140px">
                                        <input id="user_name" name="user_name" type="text" class="input-normal search"  placeholder="核销人员名称"/>
                                    </td>
                                    
                                    <td width="80px">
                                        <label for="order_num">订单号:&nbsp;</label>
                                    </td>
                                    <td width="140px">
                                        <input id="order_num" name="order_num" type="text" class="input-normal search"  />
                                    </td>
                                    <td width="80px">
                                        <label for="state">订单状态:&nbsp;</label>
                                    </td>
                                    <td width="140px">
                                        <select name="state" id="state" class="select-normal">
                                        	<option value="">全部</option>
                                        	<option value="2">待送检</option>
                                        	<option value="3">送检中</option>
                                        	<option value="4">检测中</option>
                                        	<option value="5">已完成</option>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                	<td width="80px">
                                        <label for="startTime">下单时间:&nbsp;</label>
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
                                        	<input type="hidden" id="id" value="<?php echo $_smarty_tpl->getVariable('id')->value;?>
"/>
                                            <a href="javascript:;" class="search-button btn btn-primary btn-sm">查询</a>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="list-title">
                        <div class="list-title-panel">
                            <span class="glyphicon glyphicon-list"></span>列表</span>
                        </div>
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
/store/js/clinic/cancelOrder.js"></script>
</body>
</html>