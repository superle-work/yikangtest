<?php /* Smarty version Smarty-3.0.8, created on 2019-09-26 14:14:06
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/base/slide/page/slideList.html" */ ?>
<?php /*%%SmartyHeaderCode:16437301215d8c572e359870-13887170%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '84bd73d6dde0ac722ad5b15031f4066b9fff2af3' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/base/slide/page/slideList.html',
      1 => 1535701986,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16437301215d8c572e359870-13887170',
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
/base/slide/css/slideList.css">
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
                    <a class="btn btn-success addSlide" href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['spUrl'][0][0]->__template_spUrl(array('c'=>'base_slide','a'=>'addSlide','type'=>$_smarty_tpl->getVariable('type')->value),$_smarty_tpl);?>
">添加</a>
                </div>
            </h3>
            <div class="inner-section row">
                <div id="content">
                    <div class="list-title">
                        <div class="list-title-panel">
                            <span class="glyphicon glyphicon-list"></span>列表</span>
                        </div>

                    </div>
                    <div class="table-content" id = "list_container">
                        <table id="list-table" class="list-table table-hover table-bordered" cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
                            <tbody>
                            <tr>
                                <th>名称</th>
                                <th>缩略图</th>
                                <th>链接地址</th>
                                <th>打开方式</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            <?php if ($_smarty_tpl->getVariable('slideList')->value){?>
                            <?php  $_smarty_tpl->tpl_vars['slide'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('slideList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['slide']->key => $_smarty_tpl->tpl_vars['slide']->value){
?>
                            <tr>
                                <td><?php echo $_smarty_tpl->tpl_vars['slide']->value['name'];?>
</td>
                                <td><img src="<?php echo $_smarty_tpl->tpl_vars['slide']->value['thumb'];?>
" height="50"></td>
                                <td><?php if ($_smarty_tpl->tpl_vars['slide']->value['url']){?><?php echo $_smarty_tpl->tpl_vars['slide']->value['url'];?>
<?php }else{ ?>无<?php }?></td>
                                <td><?php if ($_smarty_tpl->tpl_vars['slide']->value['target']=='_blank'){?>新开窗口<?php }else{ ?>本页刷新<?php }?></td>
                                <td><?php echo $_smarty_tpl->tpl_vars['slide']->value['sort'];?>
</td>
                                <td><a class="btn btn-xs btn-primary" href="./admin.php?c=base_slide&a=editSlide&id=<?php echo $_smarty_tpl->tpl_vars['slide']->value['id'];?>
" target="_self">编辑</a><a class="btn btn-xs btn-default deleteSlide" sid="<?php echo $_smarty_tpl->tpl_vars['slide']->value['id'];?>
">删除</a></td>
                            </tr>
                            <?php }} ?>
                            <?php }else{ ?>
                            <tr><td colspan="6"><p class="text-danger">暂无数据。</p></td></tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>

                    <div class="fn-clear"></div>
                    <!-- 列表底部 -->
                    <div class="list-bottom">
                        <div class="list-bottom-panel">
                        </div>
                    </div>

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
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/base/slide/js/slideList.js"></script>
</body>
</html>