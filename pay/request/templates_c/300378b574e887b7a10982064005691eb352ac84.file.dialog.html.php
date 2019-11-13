<?php /* Smarty version Smarty-3.0.8, created on 2018-09-20 04:34:13
         compiled from "../../template/front/common/dialog.html" */ ?>
<?php /*%%SmartyHeaderCode:98305ba30725a03f48-03412925%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '300378b574e887b7a10982064005691eb352ac84' => 
    array (
      0 => '../../template/front/common/dialog.html',
      1 => 1536231754,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '98305ba30725a03f48-03412925',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!--弹框的遮罩层-->
<div id="maskDialog"></div>
<!--其他遮罩层 如加载框-->
<div id="otherMaskDialog"></div>	
<!-- 加载中操作转圈提示 -->
<div id="loadingDialog" class="my-dialog">
	<div class="my-dialog-body">
		<img src="<?php echo @THEMES_PATH;?>
/image/loading.gif">
	</div>
</div>

<!--页面即将跳转提示框-->
<div id="skipDialog" class="my-dialog">
	<div class="my-dialog-body">
		<img src="<?php echo @THEMES_PATH;?>
/image/x-loading.gif">
		<div class="desc-text">页面即将跳转，请稍等...</div>
	</div>
</div>

<!--警告提示框-->
<div id="alertDialog" class="my-alert-dialog my-dialog">
	<div class="my-dialog-header">
		<i class="icon iconfont icon-close">&#xe63e;</i>
	</div>
	<div class="my-dialog-body">
		<div class="title-icon">
			<i class="icon iconfont">&#xe697;</i>
		</div>
		<div class="title-text">提示</div>
		<div class="desc-text">页面已损坏无法打开</div>
	</div>
	<div class="my-dialog-footer">
		<a class="btn-cancel-oper" href="javascript:;">知道了</a>
	</div>
</div>

<!--错误提示框-->
<div id="errorDialog" class="my-error-dialog my-dialog">
	<div class="my-dialog-header">
		<i class="icon iconfont icon-close">&#xe63e;</i>
	</div>
	<div class="my-dialog-body">
		<div class="title-icon">
			<i class="icon iconfont">&#xe699;</i>
		</div>
		<div class="title-text">错误</div>
		<div class="desc-text">加载失败</div>
	</div>
	<div class="my-dialog-footer">
		<a class="btn-cancel-oper" href="javascript:;">知道了</a>
	</div>
</div>

<!--普通的友情提示框-->
<div id="normalDialog" class="my-normal-dialog my-dialog">
	<div class="my-dialog-header">
		<i class="icon iconfont icon-close">&#xe63e;</i>
	</div>
	<div class="my-dialog-body">
		<div class="title-icon">
			<i class="icon iconfont">&#xe699;</i>
		</div>
		<div class="title-text">敬请期待</div>
		<div class="desc-text">稍后会联系您</div>
	</div>
	<div class="my-dialog-footer">
		<a class="btn-cancel-oper" href="javascript:;">知道了</a>
	</div>
</div>

<!--操作成功提示框-->
<div id="successDialog" class="my-success-dialog my-dialog">
	<div class="my-dialog-header">
		<i class="icon iconfont icon-close">&#xe63e;</i>
	</div>
	<div class="my-dialog-body">
		<div class="title-icon">
			<i class="icon iconfont">&#xe698;</i>
		</div>
		<div class="title-text">资料已经提交</div>
		<div class="desc-text">审核将在三个工作日之后</div>
	</div>
	<div class="my-dialog-footer">
		<a class="btn-cancel-oper" href="javascript:;">知道了</a>
	</div>
</div>

<!--危险操作确认提示框-->
<div id="dangerConfirmDialog" class="my-confirm-dialog my-dialog">
	<div class="my-dialog-header">
		<i class="icon iconfont icon-close">&#xe63e;</i>
	</div>
	<div class="my-dialog-body">
		<div class="title-icon">
			<i class="icon iconfont">&#xe69b;</i>
		</div>
		<div class="title-text">确认删除</div>
		<div class="desc-text">确认删除选中的项目吗？</div>
	</div>
	<div class="my-dialog-footer">
		<a class="btn-confirm-oper btn-left" href="javascript:;">删除</a>
		<a class="btn-cancel-oper btn-right" href="javascript:;">取消</a>
	</div>
</div>

<!--正常的友情确认提示框-->
<div id="normalConfirmDialog" class="my-confirm-dialog my-dialog">
	<div class="my-dialog-header">
		<i class="icon iconfont icon-close">&#xe63e;</i>
	</div>
	<div class="my-dialog-body">
		<div class="title-icon">
			<i class="icon iconfont">&#xe699;</i>
		</div>
		<div class="title-text">交卷提示</div>
		<div class="desc-text">答题完毕，确认现在提交试卷吗？</div>
	</div>
	<div class="my-dialog-footer">
		<a class="btn-confirm-oper btn-left" href="javascript:;">确定</a>
		<a class="btn-cancel-oper btn-right" href="javascript:;">取消</a>
	</div>
</div>


<!--操作成功后的确认提示框-->
<div id="successConfirmDialog" class="my-confirm-dialog my-dialog">
	<div class="my-dialog-header">
		<i class="icon iconfont icon-close">&#xe63e;</i>
	</div>
	<div class="my-dialog-body">
		<div class="title-icon">
			<i class="icon iconfont">&#xe696;</i>
		</div>
		<div class="title-text">付款成功</div>
		<div class="desc-text">恭喜您所购商品付款成功</div>
	</div>
	<div class="my-dialog-footer">
		<a class="btn-confirm-oper btn-left" href="javascript:;">确定</a>
		<a class="btn-cancel-oper btn-right" href="javascript:;">取消</a>
	</div>
</div>

<!--输入验证码确认提示框-->
<div id="codeConfirmDialog" class="my-confirm-dialog my-dialog">
	<div class="my-dialog-header">
		<i class="icon iconfont icon-close">&#xe63e;</i>
	</div>
	<div class="my-dialog-body">
		<div class="code-wrap">
			<input type="text" id="verifyCode" placeholder="请输入验证码">
			<img id="verifyCodeImg" src="">
		</div>
		<div class="title-text"></div>
		<div class="desc-text"></div>
	</div>
	<div class="my-dialog-footer">
		<a class="btn-confirm-oper btn-left" href="javascript:;">确定</a>
		<a class="btn-cancel-oper btn-right" href="javascript:;">取消</a>
	</div>
</div>

<!--输入退款原因确认提示框-->
<div id="reasonConfirmDialog" class="my-confirm-dialog my-dialog">
	<div class="my-dialog-header">
		<i class="icon iconfont icon-close">&#xe63e;</i>
	</div>
	<div class="my-dialog-body">
	    <div class="title-icon">
			<i class="icon iconfont">&#xe699;</i>
		</div>
	    <div class="title-text"></div>
		<div class="desc-text"></div>
		<div class="code-wrap">
			<input type="text" id="refund-reason" placeholder="请输入退款原因">
			<img id="verifyCodeImg" src="">
		</div>
	</div>
	<div class="my-dialog-footer">
		<a class="btn-confirm-oper btn-left" href="javascript:;">确定</a>
		<a class="btn-cancel-oper btn-right" href="javascript:;">取消</a>
	</div>
</div>