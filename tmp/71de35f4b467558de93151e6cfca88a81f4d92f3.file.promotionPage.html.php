<?php /* Smarty version Smarty-3.0.8, created on 2019-09-26 08:55:52
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/promotionPage.html" */ ?>
<?php /*%%SmartyHeaderCode:16774905545d8c0c98787f75-26187715%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '71de35f4b467558de93151e6cfca88a81f4d92f3' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/common/fen/page/promotionPage.html',
      1 => 1536730950,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16774905545d8c0c98787f75-26187715',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title>推广二维码</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/css/promotionPage.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--主体内容-->
        <div id="content">
            <!--二级码图片-->
            <?php if ($_smarty_tpl->getVariable('wechatCode')->value!=''){?>
            <div class="code">
            	<img class="code-img" src="<?php echo $_smarty_tpl->getVariable('wechatCode')->value;?>
">
            	<!--<img class="head-img" src="<?php echo $_smarty_tpl->getVariable('userInfo')->value['head_img_url'];?>
"-->
            </div>
            <?php }else{ ?>
            <div class="code-tip-info">
            	<div>您没有分销二维码 暂时无法开启分销之旅</div>
            </div>
            <?php }?>          
        </div>
        <input type="hidden" id='did' value="<?php echo $_smarty_tpl->getVariable('distributorInfo')->value['id'];?>
" />
        <input type="hidden" id='title' value="<?php echo $_smarty_tpl->getVariable('userInfo')->value['nick_name'];?>
的推广二维码" />
    </div>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/fen/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/common/fen/js/promotionPage.js"></script>
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/wechat/jweixin-1.0.0.js"></script>
<script>
	var did = $('#did').val();
	var img_url = $('#content #head-img').attr('src');
	var tgcode = $('#title').val();
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
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','hideMenuItems'
            // 所有要调用的 API 都要加到这个列表中
        ]
      });
    var title = tgcode;
    var link =  '<?php echo @ROOT_URL;?>
/index.php?c=fen&a=promotionPage&did='+did;
    var imgUrl = img_url;
    wx.ready(function () {
        // 在这里调用 API
        //分享到朋友圈
        wx.onMenuShareTimeline({
            title: title, // 分享标题
            link: link, // 分享链接
            imgUrl: imgUrl, // 分享图标
			success: function () {		
			},
			cancel: function () {
				// 用户取消分享后执行的回调函数

			}
        });
        //发送给朋友
        wx.onMenuShareAppMessage({
            title: title, // 分享标题
            desc: '', // 分享描述
            link: link, // 分享链接
            imgUrl: imgUrl, // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {              
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.hideMenuItems({
            menuList: ["menuItem:copyUrl",
                //"menuItem:share:appMessage",
                "menuItem:openWithQQBrowser",
                "menuItem:openWithSafari",
                "menuItem:share:qq",
                "menuItem:favorite",
                "menuItem:share:weiboApp",
                "menuItem:share:email",
                "menuItem:originPage"] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
            /**
             传播类
             发送给朋友: "menuItem:share:appMessage"
             分享到朋友圈: "menuItem:share:timeline"
             分享到QQ: "menuItem:share:qq"
             分享到Weibo: "menuItem:share:weiboApp"
             收藏: "menuItem:favorite"
             分享到FB: "menuItem:share:facebook"
             分享到 QQ 空间/menuItem:share:QZone

             保护类
             编辑标签: "menuItem:editTag"
             删除: "menuItem:delete"
             复制链接: "menuItem:copyUrl"
             原网页: "menuItem:originPage"
             阅读模式: "menuItem:readMode"
             在QQ浏览器中打开: "menuItem:openWithQQBrowser"
             在Safari中打开: "menuItem:openWithSafari"
             邮件: "menuItem:share:email"
             一些特殊公众号: "menuItem:share:brand"**/

        });

    });
</script>
</body>
</html>