<?php /* Smarty version Smarty-3.0.8, created on 2019-09-11 17:03:59
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/fen/page/distributor/distributorList.html" */ ?>
<?php /*%%SmartyHeaderCode:16920967755d78b87f5b0979-77694263%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f2223543ab5190698c7258ecf69b4e4b2820b302' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/fen/page/distributor/distributorList.html',
      1 => 1537328552,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16920967755d78b87f5b0979-77694263',
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
/fen/css/distributor/distributorList.css">
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
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="80px">
                                <label for="name">分销商名称:&nbsp;</label>
                            </td>
                            <td width="140px">
                                <input id="name" name="name" type="text" class="input-normal search"  />
                            </td>
                            <td width="50px">
                                <label for="telephone">电话:&nbsp;</label>
                            </td>
                            <td width="140px">
                                <input id="telephone" name="telephone" type="text" class="input-normal search"  />
                            </td>
                            <td width="50px" hidden="hidden">
                                <label for="level">级别:&nbsp;</label>
                            </td>
                            <td width="140px" hidden="hidden">
                            	<select id="level" name="level" class="select-normal">
                            		<option value="">全部</option>
                            		<option value="1">初级</option>
                            		<option value="2">中级</option>
                            		<option value="3">高级</option>
                            	</select>
                            </td>
                            <td width="50px">
                                <label for="level">状态:&nbsp;</label>
                            </td>
                            <td width="140px">
                            	<select id="is_use" name="is_use" class="select-normal">
                            		<option value="">全部</option>
                            		<option value="1">有效</option>
                            		<option value="0">无效</option>
                            	</select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                        	<td width="80px">
                                <label for="low_fee">累计佣金:&nbsp;</label>
                            </td>
                            <td width="140px">
                                <input id="low_fee" name="low_fee" style="width:50px" type="text" class="input-normal search"  />至
                                <input id="up_fee" name="up_fee" style="width:50px" type="text" class="input-normal search"  />
                            </td>
                            <td width="80px">
                                <label for="startTime">添加时间:&nbsp;</label>
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
/fen/js/distributor/distributorList.js"></script>
</body>
</html>