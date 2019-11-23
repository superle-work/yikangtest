<?php /* Smarty version Smarty-3.0.8, created on 2019-11-15 06:32:25
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/base/admin/page/messageList.html" */ ?>
<?php /*%%SmartyHeaderCode:272475dce46794538f1-50727413%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1b2d5055fe13a749d8eb46aed5a557d40f568682' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/base/admin/page/messageList.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '272475dce46794538f1-50727413',
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
<body>
<!---------------- 产品列表 ------------------->

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
                </div>
            </h3>
            <div class="inner-section row">
                <!--查询区域-->
                <div class="search-param-panel">
                     	<form class="search-param-form" id="search-param-form">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                               	  <td width="80">
                                        <label for="title">标题名称:&nbsp;</label>
                                    </td>
                                    <td width="140">
                                        <input id="title" name="title" type="text" class="input-normal search"  />
                                  </td>
                                  <td width="80">
                                      <label for="source">来源:&nbsp;</label>
                                  </td>
                                  <td width="140">
                                      <select id="source" name="source" class="select-normal">
                                          <option value="">全部</option>
                                          <?php  $_smarty_tpl->tpl_vars['source'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('sources')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['source']->key => $_smarty_tpl->tpl_vars['source']->value){
?>
                                          <option value="<?php echo $_smarty_tpl->tpl_vars['source']->value['source'];?>
"><?php echo $_smarty_tpl->tpl_vars['source']->value['source'];?>
</option>
                                          <?php }} ?>
                                      </select>
                                  </td>
                                  <td width="80">
                                      <label for="type">消息类型:&nbsp;</label>
                                  </td>
                                  <td width="140">
                                        <input id="type" name="type" type="text" class="input-normal search"  />
                                  </td>
                                  <td width="80">
                                      <label for="is_read">是否已读:&nbsp;</label>
                                  </td>
                                  <td width="140">
                                      <select id="is_read" name="is_read" class="select-normal">
                                          <option value="">全部</option>
                                          <option value="0">未读</option>
                                          <option value="1">已读</option>
                                      </select>
                                  </td>
                                  <td></td>
                                  <td></td>
                              </tr>
                              <tr>
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
                <div class="item table-responsive">
                    <table id="list-table" class="productList-table table table-striped table-hover table-bordered table-base">
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
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/sco/sco.tooltip.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/base/admin/js/messageList.js"></script>
</body>
</html>