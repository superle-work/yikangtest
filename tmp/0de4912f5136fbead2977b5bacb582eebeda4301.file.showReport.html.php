<?php /* Smarty version Smarty-3.0.8, created on 2019-09-28 16:10:23
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/order/showReport.html" */ ?>
<?php /*%%SmartyHeaderCode:8416466545d8f156f841b86-83283583%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0de4912f5136fbead2977b5bacb582eebeda4301' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/order/showReport.html',
      1 => 1568187782,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8416466545d8f156f841b86-83283583',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/order/showReport.css">
    <title>查看报告</title>
</head>
<body>
    <div id="content">
    	<div class="top-area">
    		<textarea name="report_desc" id="report_desc" ><?php echo $_smarty_tpl->getVariable('reportInfo')->value['report_desc'];?>
</textarea>
    	</div>
    	
    	<div class="img-area">
    		<div class="image-list">								
				<?php if ($_smarty_tpl->getVariable('reportInfo')->value['imgInfo']!=''||$_smarty_tpl->getVariable('reportInfo')->value['imgInfo']!=null){?>
	    		<?php  $_smarty_tpl->tpl_vars['img'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('reportInfo')->value['imgInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['img']->key => $_smarty_tpl->tpl_vars['img']->value){
?>
	    		<img src="<?php echo $_smarty_tpl->tpl_vars['img']->value['img_url'];?>
" alt="" class="showImage"/>
	    		<?php }} ?>
	    		<?php }?>
			</div>
    	</div>
    </div>
<!--提示框-->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script src="<?php echo @JS_PATH;?>
/public/form/jquery.form.js"></script>

<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/wechat/jweixin-1.0.0.js"></script>

<script>
    /*
     **** 微信分享
    */ 
    wx.config({
        debug: false,
        appId: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['appId'];?>
',
        timestamp: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['timestamp'];?>
',
        nonceStr: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['nonceStr'];?>
',
        signature: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['signature'];?>
',
        jsApiList: ['checkJsApi','previewImage'
            // 所有要调用的 API 都要加到这个列表中
        ]
    });
    
    wx.ready(function () {
     // 在这里调用 API
        wx.checkJsApi({
            jsApiList : ['previewImage'],
            success : function(res) {

            }
        });

        $(".showImage").click(function(){
            var src="http://yikang.chuyuanshengtai.com/"+$(this).attr("src");
            var srcList=[];
            $(".showImage").each(function(k,v){
                srcList.push("http://yikang.chuyuanshengtai.com/"+$(v).attr("src"));
            })
            wx.previewImage({
                current: src,
                urls:srcList
            });
        })
    })
    
</script>
</body>
</html>