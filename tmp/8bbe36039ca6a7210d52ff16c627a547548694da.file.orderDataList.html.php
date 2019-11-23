<?php /* Smarty version Smarty-3.0.8, created on 2019-11-21 03:38:07
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/data/orderDataList.html" */ ?>
<?php /*%%SmartyHeaderCode:246455dd6069fc25625-31039191%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8bbe36039ca6a7210d52ff16c627a547548694da' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/data/orderDataList.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '246455dd6069fc25625-31039191',
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
/store/css/data/orderDataList.css">
<body>

<!---------------- 订单量 报表统计------------------->
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
        <div class="col-md-10 right-section"><!--
            <!--内容区域-->
            <h3><i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i><?php echo $_SESSION['currentMenu']['menuTitle'];?>


            </h3>
            <div class="inner-section row">
                <!--关键指标-->
                <div class="key-index">
                    <ul class="clearfix">
                        <li class="key-index-item">
                            <div class="key-index-item-wrap">
                                <div class="count"><i class="icon iconfont">&#xe65d;</i><?php echo (($tmp = @$_smarty_tpl->getVariable('keyIndex')->value['todayOrderCount'])===null||$tmp==='' ? "0" : $tmp);?>
</div>
                                <div class="text">今日订单数</div>
                            </div>
                        </li>
                        <li class="key-index-item">
                            <div class="key-index-item-wrap">
                                <div class="count"><i class="icon iconfont">&#xe65d;</i><?php echo (($tmp = @$_smarty_tpl->getVariable('keyIndex')->value['totalOrderCount'])===null||$tmp==='' ? "0" : $tmp);?>
</div>
                                <div class="text">总订单数</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <!--折线图-->
                <div id="line-chart-area">
                    <!--查询参数-->
                    <div class="line-chart-top">
                        <h4>订单关键指标趋势图</h4>
                        <div class="line-chart-search-area clearfix">
                            <a href="javascript:void(0);" class="chart-range selected" data-dayCount="7">最近7日</a>
                            <a href="javascript:void(0);" class="chart-range" data-dayCount="15">最近15日</a>
                            <a href="javascript:void(0);" class="chart-range" data-dayCount="30">最近30日</a>
                        </div>
                    </div>
                    <div class="line-chart-wrap">
                        <div class="line-chart" id="line-chart-1"></div>
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
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/highchart/highcharts-4.1.9.js"></script>
<script src="<?php echo @BASE_PATH;?>
/js/public/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/data/orderDataList.js"></script>

</body>
</html>