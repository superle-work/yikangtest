<?php /* Smarty version Smarty-3.0.8, created on 2019-12-25 08:58:53
         compiled from "./template/admin/default/common/page/dialog.html" */ ?>
<?php /*%%SmartyHeaderCode:310515e0324cd435604-03178815%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b38a891cea17540a02e0bb73fccc47e751499043' => 
    array (
      0 => './template/admin/default/common/page/dialog.html',
      1 => 1575337095,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '310515e0324cd435604-03178815',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!-- 模态框（Modal） 提示框-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    提示框
                </h4>
            </div>
            <div class="modal-body">
                恭喜你，操作成功！
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">关闭</button>

            </div>
        </div><!-- /.modal-content -->
    </div>
</div><!-- /.modal -->

<!-- 模态框（Modal） 操作确认提示框-->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    确认提示框
                </h4>
            </div>
            <div class="modal-body">
                <p class="text-danger">确认删除吗？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-confirm">确认</button>
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div><!-- /.modal -->

<!-- 模态框（Modal） 操作确认提示框-->
<div class="modal fade" id="myConfirmModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    确认提示框
                </h4>
            </div>
            <div class="modal-body">
                <p class="text-danger">确认删除吗？</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-confirm">确认</button>
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div><!-- /.modal -->
<!-- 模态框（Modal）操作持续中转圈提示 -->
<div class="modal fade" id="loading" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div style="z-index: 9999;height: 100%;margin: 0 auto;text-align: center;line-height: 600px;">
        <img height="60px" src="<?php echo @THEMES_PATH;?>
/image/loading.gif">
    </div>
</div><!-- /.modal -->
