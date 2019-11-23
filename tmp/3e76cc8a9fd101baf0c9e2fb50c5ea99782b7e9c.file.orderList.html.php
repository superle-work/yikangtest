<?php /* Smarty version Smarty-3.0.8, created on 2019-11-13 10:11:47
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/order/orderList.html" */ ?>
<?php /*%%SmartyHeaderCode:219175dcbd6e3ca7d34-42066598%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3e76cc8a9fd101baf0c9e2fb50c5ea99782b7e9c' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/order/orderList.html',
      1 => 1573638419,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '219175dcbd6e3ca7d34-42066598',
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
/store/css/order/orderList.css" />

<body>

<!---------------- 订单列表 ------------------->



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
</i><?php echo $_SESSION['currentMenu']['menuTitle'];?>


                <div class="operation-div">

                    <a href="javascript:;" id ='import_btn' class="btn btn-success">导出订单</a>

                </div>

            </h3>

            <div class="inner-section row">

                <div id="content">

                    <div class="search-param-panel">

                        <form class="search-param-form" id="search-param-form">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                <tr>

                                    <td width="80">

                                        <label for="phone">手机号:&nbsp;</label>

                                    </td>

                                    <td width="140">

                                        <input id="phone" name="phone" type="text" class="input-normal search"  />

                                    </td>
                                    <td width="80">

                                        <label for="ordernum">订单编号:&nbsp;</label>

                                    </td>

                                    <td width="140">

                                        <input id="ordernum" name="ordernum" type="text" class="input-normal search"  />

                                    </td>

                                    <td width="80">

                                        <label for="state">订单状态:&nbsp;</label>

                                    </td>

                                    <td width="140">

                                        <select id="state" name="state" class="select-normal">

                                            <option value="">全部</option>

                                            <option value="0">待付款</option>

                                            <option value="1">待采样</option>

                                            <option value="2">待送检 </option>

                                            <option value="3">送检中</option>

                                            <option value="4">检测中</option>

                                            <option value="5">已完成</option>

                                        </select>

                                    </td>

                                    <td width="80">

                                        <label for="userstate">用戶类型:&nbsp;</label>

                                    </td>

                                    <td width="140">

                                        <select id="userstate" name="userstate" class="select-normal">

                                            <option value="">全部</option>

                                            <option value="0">普通用戶</option>

                                            <option value="1">诊所用戶</option>

                                        </select>

                                    </td>

                                    <td width="80">

                                        <label for="clinic_name">诊所名称:&nbsp;</label>

                                    </td>

                                    <td width="140">

                                        <input id="clinic_name" name="clinic_name" type="text" class="input-normal search"  />

                                    </td>

                                    <td></td>

                                    <td></td>

                                </tr>

                                <tr>

                                    <td width="80">

                                        <label for="hospital_name">医院名称:&nbsp;</label>

                                    </td>

                                    <td width="140">

                                        <input id="hospital_name" name="hospital_name" type="text" class="input-normal search"  />

                                    </td>

                                   

                                    <td width="80">

                                        <label for="startTime">创建时间:&nbsp;</label>

                                    </td>

                                    <td>

                                        <input id="startTime" name="startTime" type="text" class="input-normal search Wdate" onclick="WdatePicker({dateFmt:'yyyy-MM-dd'})" />

                                    </td>

                                    <td width="40">

                                        <label for="endTime">至:</label>

                                    </td>

                                    <td  width="140">

                                        <input id="endTime" name="endTime" type="text" class="input-normal search Wdate" onclick="WdatePicker({dateFmt:'yyyy-MM-dd'})" />

                                    </td>

                                    

                                    <td>

                                        <div class="search-button">

                                            <div class="list-action-area">

                                                <a href="javascript:;" id ='search_btn' class="btn btn-primary btn-sm">查询</a>

                                            </div>

                                        </div>

                                    </td>

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

                    <div class="union-operate">

                    	<a href="javascript:;" class="btn btn-default btn-sm delete-batch">批量删除</a>

                    </div>

                    <div class="table-content" id = "list_container">

                        <table id="list-table" class="list-table table-hover table-bordered" cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

                            <tbody>

                            </tbody>

                        </table>

                    </div>

                    <!--分页信息-->

                    <div id="page-selection"></div>

                    <div class="fn-clear"></div>



                    <!-- 列表底部 -->

                    <div class="list-bottom">

                        <div class="list-bottom-panel">

                        </div>

                    </div>

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

<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/sco/sco.tooltip.js"></script>

<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/order/orderList.js"></script>

</body>

</html>

