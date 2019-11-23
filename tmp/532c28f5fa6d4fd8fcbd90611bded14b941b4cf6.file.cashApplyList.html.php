<?php /* Smarty version Smarty-3.0.8, created on 2019-11-14 01:07:02
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/fen/page/cashApply/cashApplyList.html" */ ?>
<?php /*%%SmartyHeaderCode:87485dcca8b6274048-85405638%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '532c28f5fa6d4fd8fcbd90611bded14b941b4cf6' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/fen/page/cashApply/cashApplyList.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '87485dcca8b6274048-85405638',
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
/fen/css/cashApply/cashApplyList.css">
<body>
<!---------------- 分销商申请列表 ------------------->

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
                <!--查询区域-->
                <div class="search-param-panel">
                    <table  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="80px">
                                <label for="tx_type">账户类型:&nbsp;</label>
                            </td>
                            <td width="140px">
                                <select class="select-normal" id="tx_type" name="tx_type">
                                    <option value="" selected>全部</option>
                                    <option value="1">分销商</option>
                                    <option value="2">诊所中心</option>
                                    <option value="3">医院中心</option>
                                </select>
                            </td>
                            <td width="80px">
                                <label for="isCheck">审核状态:&nbsp;</label>
                            </td>
                            <td width="140px">
                                <select class="select-normal" id="isCheck" name="isCheck">
                                    <option value="" selected>全部</option>
                                    <option value="0">未审核</option>
                                    <option value="1">审核通过</option>
                                    <option value="2">审核不通过</option>
                                </select>
                            </td>
                            <td width="80px">
                                <label for="cash_static">提现状态:&nbsp;</label>
                            </td>
                            <td width="140px">
                                <select class="select-normal" id="cash_static" name="cash_static">
                                    <option value="" selected>全部</option>
                                    <option value="0">未打款</option>
                                    <option value="1">即将打款</option>
                                    <option value="2">打款中</option>
                                    <option value="3">打款成功</option>
                                    <option value="4">打款失败</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                        	<td width="80px">
                                <label for="low_money">提现金额:&nbsp;</label>
                            </td>
                            <td width="140px">
                                <input id="low_money" name="low_money" style="width:50px" type="text" class="input-normal search"  />至
                                <input id="up_money" name="up_money" style="width:50px" type="text" class="input-normal search"  />
                            </td>
                            <td width="80px">
                                <label for="startTime">申请时间:&nbsp;</label>
                            </td>
                            <td width="140px">
                                <input id="startTime" name="startTime" type="text" class="input-normal search Wdate" onclick="WdatePicker({maxDate:'#F{$dp.$D(\'endTime\')||\'new Date()\'}'})" />
                            </td>
                            <td width="40px">
                                <label for="endTime">至:</label>
                            </td>
                            <td width="140px">
                                <input id="endTime" name="endTime" type="text" class="input-normal search Wdate" onclick="WdatePicker({minDate:'#F{$dp.$D(\'startTime\')}',maxDate:new Date()})" />
                            </td>
                            <td>
                                <div class="list-action-area">
                                    <a href="javascript:;" class="search-button btn btn-primary btn-sm">查询</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="list-title">
                        <div class="list-title-panel">
                            <span class="glyphicon glyphicon-list"></span>列表</span>
                            <input type="hidden" name="ids" id="ids" value="">
                           <a href="javascript:;" class="btn btn-default btn-sm deletebatch" style="float:right">批量删除</a>
                        </div>
                </div>
                <div class="item table-responsive">
                    <table id="list-table" class="list-table table table-striped table-hover table-bordered table-base applylist">
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!--分页-->
                <div id="page-selection"></div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="session" value='<?php echo $_SESSION['role_permission'];?>
'>

<!-- 模态框（Modal） 操作确认提示框-->
<div class="modal fade" id="cashStaticModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    打款提示框
                </h4>
            </div>
            <div class="modal-body">
                <p class="text-danger"></p>
                <p class="text-button">
	                <button type="button" class="btn btn-danger cashing">打款中</button>
	                <button type="button" class="btn btn-success cashed">打款成功</button>
	            </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-exit" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="modal fade" id="myCheckModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    审核提示框
                </h4>
            </div>
            <div class="modal-body">
                <p class="text-danger"></p>
                <p class="text-button">
                 	<button type="button" class="btn btn-success">审核通过</button>
                	<button type="button" class="btn btn-danger btn-error">审核不通过</button>
            	</p>
            	<p class="text-info" style="display:none">
               		 原因：<input type="text" class="info"/><span style="color:#a94442;font-size:12px;">*审核不通过必须填写</span>
               	</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-exit" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
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
/fen/js/cashApply/cashApplyList.js"></script>
</body>
</html>