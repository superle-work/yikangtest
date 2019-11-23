<?php /* Smarty version Smarty-3.0.8, created on 2019-11-13 07:27:17
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/goods/goodsList.html" */ ?>
<?php /*%%SmartyHeaderCode:268915dcbb05558b328-67195331%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6d21ad7b7aeac78716304cbe5fd20867d55729bb' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/goods/goodsList.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '268915dcbb05558b328-67195331',
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
/store/css/goods/goodsList.css" />
<body>
<!---------------- 商品列表 ------------------->

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
                    <a class="btn btn-success" class="addGoods" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['spUrl'][0][0]->__template_spUrl(array('c'=>'store_goods','a'=>'selectCategory'),$_smarty_tpl);?>
">添加</a>
                </div>
            </h3>
            <div class="inner-section row">
                <div id="content">
                    <div class="search-param-panel">
                        <form class="search-param-form" id="search-param-form">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="80px">
                                        <label for="goodsname">商品名称:&nbsp;</label>
                                    </td>
                                    <td width="140px">
                                        <input id="goodsname" name="goodsname" type="text" class="input-normal search"  />
                                    </td>
                                    <td width="80px">
                                        <label for="recommend">首页推荐:&nbsp;</label>
                                    </td>
                                    <td width="140px">
                                        <select class="select-normal" id="recommend" name="recommend">
                                            <option value="">全部</option>
                                            <option value="1">推荐</option>
                                            <option value="0">不推荐</option>
                                        </select>
                                    </td>
                                    <td width="50px">
                                        <label for="updown">状态:&nbsp;</label>
                                    </td>
                                    <td width="140px">
                                        <select class="select-normal" id="updown" name="updown">
                                            <option value="">全部</option>
                                            <option value="1">上架</option>
                                            <option value="0">下架</option>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
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
/store/js/goods/goodsList.js"></script>
</body>
</html>