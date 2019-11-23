<?php /* Smarty version Smarty-3.0.8, created on 2019-11-13 09:14:11
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/clinic/bindUser.html" */ ?>
<?php /*%%SmartyHeaderCode:94405dcbc963de8c99-17451073%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f4702c275704a3e065295c46e9d520f56aae04cc' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/clinic/bindUser.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '94405dcbc963de8c99-17451073',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/css/clinic/bindUser.css" />
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/jquery.bootpag/jquery.bootpag.min.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/clinic/bindUser.js"></script>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">添加工作人员</h4>
</div>
<div class="modal-body">
    <div id="myContent">
        <div class="search-param-panel">
            <form class="search-param-form" id="search-param-form">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="70px">
                            <label for="nick_name">用户昵称:&nbsp;</label>
                        </td>
                        <td width="140px">
                            <input id="nick_name" name="nick_name" type="text" class="input-normal search"  />
                        </td>
                        <td width="70px">
                            <label for="nick_name">手机号:&nbsp;</label>
                        </td>
                        <td width="140px">
                            <input id="phone" name="phone" type="text" class="input-normal search"  />
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
        <div class="table-content" id = "list_container">
            <table id="list-table" class="list-table table-hover table-bordered">
                <tbody>
                </tbody>
            </table>
        </div>
        <!--分页-->
        <div id="page-selection"></div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-primary" id="bindSave">选择完成</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>