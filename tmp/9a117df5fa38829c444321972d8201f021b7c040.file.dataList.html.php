<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 18:12:56
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/weixin/data/page/dataList.html" */ ?>
<?php /*%%SmartyHeaderCode:21234429365d89ec289857f2-80130754%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9a117df5fa38829c444321972d8201f021b7c040' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/weixin/data/page/dataList.html',
      1 => 1535701988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21234429365d89ec289857f2-80130754',
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
/weixin/data/css/userDataList.css">
<body>

<!---------------- 销售额 报表统计------------------->
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

                <div class="operation-div">
                   
                </div>
            </h3>
            <div class="inner-section row">

                <!--关键指标-->
                <div class="key-index">
                    <ul class="clearfix">
                        <li class="key-index-item">
                            <div class="key-index-item-wrap">
                                <div class="count"><i class="icon iconfont">&#xe66c;</i><?php echo $_smarty_tpl->getVariable('keyIndex')->value['todayCount'];?>
</div>
                                <div class="text">今日关注量</div>
                            </div>
                        </li>
                        <li class="key-index-item">
                            <div class="key-index-item-wrap">
                                <div class="count"><i class="icon iconfont">&#xe66c;</i><?php echo $_smarty_tpl->getVariable('keyIndex')->value['totalCount'];?>
</div>
                                <div class="text">总关注量</div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!--折线图-->
                <div id="line-chart-area">
                    <div class="line-chart-area-wrap">
                        <!--查询参数-->
                        <div class="line-chart-top">
                            <h4>粉丝量增减趋势图</h4>
                            <div class="line-chart-search-area clearfix">
                                <a href="javascript:void(0);" class="search-option chart-range selected" data-dayCount="7">最近7日</a>
                                <span class="search-option search-option-left"><input id="startTime" name="startTime" type="text" class="input-normal search Wdate" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endTime\')||\'%y-%M-{%d-1}\'}',minDate:'#F{$dp.$D(\'endTime\',{d:-6})}'})" /></span>
                                <span>至</span>
                                <span class="search-option"><input id="endTime" name="endTime" type="text" class="input-normal search Wdate" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'startTime\',{d:+6})||\'%y-%M-{%d-1}\'}',minDate:'#F{$dp.$D(\'startTime\')}'})" /></span>
                                <span class="search-option"><select class="select-normal" id="userSource"><option value="99">用户来源</option><option value="0">其他</option><option value="39">查询微信公众帐号</option><option value="17">名片分享</option><option value="3">扫二维码</option><option value="35">代表搜号码</option><option value="43">图文页右上角菜单</option></select></span>
                                <a href="javascript:void(0);" class="search-button">查询</a>
                            </div>
                        </div>
                        <div class="line-chart-wrap">
                            <div class="line-chart" id="line-chart-1"></div>
                        </div>
                    </div>
                    <div class="line-chart-area-wrap">
                        <!--查询参数-->
                        <div class="line-chart-top">
                            <h4>累计粉丝量增减趋势图</h4>
                            <div class="line-chart-search-area clearfix">
                                <a href="javascript:void(0);" class="search-option chart-range selected" data-dayCount="7">最近7日</a>
                                <span class="search-option search-option-left"><input id="startDate" name="startDate" type="text" class="input-normal search Wdate" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'endDate\')||\'%y-%M-{%d-1}\'}',minDate:'#F{$dp.$D(\'endDate\',{d:-6})}'})" /></span>
                                <span>至</span>
                                <span class="search-option"><input id="endDate" name="endDate" type="text" class="input-normal search Wdate" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:'#F{$dp.$D(\'startDate\',{d:+6})||\'%y-%M-{%d-1}\'}',minDate:'#F{$dp.$D(\'startDate\')}'})" /></span>
                                <a href="javascript:void(0);" class="search-button">查询</a>
                            </div>
                        </div>
                        <div class="line-chart-wrap">
                            <div class="line-chart" id="line-chart-2"></div>
                        </div>
                    </div>
                </div>
                <!--饼图-->
                <div id="pie-chart-area">
                    <div class="pie-chart-top">
                        粉丝变化来源比例分析
                    </div>
                    <div class="pie-chart-wrap clearfix">
                        <!--预计交易 商品销量类别比例图-->
                        <!--交易完成 商品销量类别比例图-->
                        <div class="pie-chart pie-chart-left" id="pie-chart-left"></div>
                        <!--正在交易 商品销量类别比例图-->
                        <div class="pie-chart pie-chart-right" id="pie-chart-right"></div>
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
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/weixin/data/js/dataList.js"></script>

</body>
</html>