<?php /* Smarty version Smarty-3.0.8, created on 2019-11-13 03:11:04
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/category1/categoryList.html" */ ?>
<?php /*%%SmartyHeaderCode:52275dcb74485c2dc5-65525882%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a2ccb9fbb981e2dd0c6d402ce38c511b4fdc4a4' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/category1/categoryList.html',
      1 => 1537878954,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '52275dcb74485c2dc5-65525882',
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
/store/css/category/categoryList.css">
<body>
<!---------------- 商品分类 ------------------->

<!-- 页头 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!-- jsfiles -->
<!-- javascript plugins-->
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/bootstrap/js/bootstrap.js"></script>
<!--复选框、单选按钮效果增强插件-->
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/common/js/global.js"></script>
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

            </h3>
            <div class="inner-section row">

                <div class="hb-toolbar cf">
                    <div class="toolbar-opt toolbar-left"><a href="./admin.php?c=store_category&a=addCategory&rank=1" class="addCatagory btn btn-success btn-sm">添加分类</a></div>
                    <div class="toolbar-opt toolbar-right hidden"><a href="javascript:;" class="cat-save btn btn-success btn-sm">保存更改</a></div>
                </div>
                <div class="table-content" id = "list_container">
                    <div class="operate-area">
                        <div class="select-opt"><span class="cat-checkbox checkbox-box"><span class="pseudo-checkbox"></span></span></div>
                        <div class="delete-opt"><a href="javascript:;" class="batch-delete" style="display: none;">批量删除</a></div>
                        <div class="show-hide-opt" style="display: none;"><a href="javascript:;" class="down-show">展开</a>|<a href="javascript:;" class="up-hide">收起</a></div>
                    </div>
                    <table id="list-table" class="list-table table-hover" width="100%" align="center">
                        <thead>
                        <th class="td-00">&nbsp;</th>
                        <th class="td-0">&nbsp;</th>
                        <th class="td-1">分类名称</th>
                        <th class="td-4">状态</th>
                        <th class="td-5">排序</th>
                        <th class="td-2">创建时间</th>
                        <th class="td-3">操作</th>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 页脚 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/<?php echo $_smarty_tpl->getVariable('cate')->value;?>
/categoryList.js"></script>
</body>
</html>



