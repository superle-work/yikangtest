<?php /* Smarty version Smarty-3.0.8, created on 2019-09-11 17:04:17
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/weixin/response/page/responsePage.html" */ ?>
<?php /*%%SmartyHeaderCode:1577469165d78b891901dd3-07120232%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '45ff0e4a52f02219c4074fe5d29b701978430aa2' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/weixin/response/page/responsePage.html',
      1 => 1535701988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1577469165d78b891901dd3-07120232',
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
<link rel="stylesheet" href="<?php echo @BASE_PATH;?>
/js/public/jquery.qqFace/css/qqFace.css">
<link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/weixin/response/css/responsePage.css">
<body<?php if ($_SESSION['admin']['account']!='change'){?> oncontextmenu="return false"  oncopy="return false" oncut="return false"<?php }?>>
<!---------------- 智能回复 ------------------->

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

                <input id="navFlag" type="hidden" value="<?php echo $_smarty_tpl->getVariable('navFlag')->value;?>
">
            </h3>
            <div class="response-text-wrap">
                <!--自动回复功能是否开启-->
                <?php if ($_smarty_tpl->getVariable('isUse')->value){?>
                <span class="lock-icon unlock"><i class="icon iconfont">&#xe650;</i></span>
                <h4 class="title">已开启智能回复设置</h4>
                <p class="desc">通过编辑内容或关键词规则，快速进行自动回复设置。</p>
                <div class="oper"><a class="btn btn-danger btn-md set-use" data-isuse="0">停用</a></div>
                <?php }else{ ?>
                <span class="lock-icon lock"><i class="icon iconfont">&#xe64f;</i></span>
                <h4 class="title">已关闭智能回复设置</h4>
                <p class="desc">通过编辑内容或关键词规则，快速进行自动回复设置。</p>
                <div class="oper"><a class="btn btn-success btn-md set-use" data-isuse="1">启用</a></div>
                <?php }?>
            </div>
            <?php if ($_smarty_tpl->getVariable('isUse')->value){?>
            <div class="inner-section row">
                <div class="response-type">
                    <ul class="clearfix">
                        <li id="attentionMessage" class="selected"><a href="javascript:" data-use_scene="0" data-id="<?php echo $_smarty_tpl->getVariable('attentionMessage')->value['id'];?>
" data-is_use="<?php echo $_smarty_tpl->getVariable('attentionMessage')->value['is_use'];?>
">被关注自动回复</a></li>
                        <li id="allMessage"><a href="javascript:" data-use_scene="1" data-id="<?php echo $_smarty_tpl->getVariable('allMessage')->value['id'];?>
" data-is_use="<?php echo $_smarty_tpl->getVariable('allMessage')->value['is_use'];?>
">消息统一自动回复</a></li>
                        <li id="keywordsMessage" class="last-type"><a href="javascript:" data-use_scene="2">关键词自动回复</a></li>
                    </ul>
                    <span class="response-set-way response-way-0"><a target="_blank" href="http://kf.qq.com/faq/120322fu63YV130422aEv6nq.html">如何设置被关注自动回复<i class="icon iconfont">&#xe616;</i></a></span>
                    <span class="response-set-way response-way-1"><a target="_blank" href="http://kf.qq.com/faq/120322fu63YV130422q6FBrm.html">如何设置消息自动回复<i class="icon iconfont">&#xe616;</i></a></span>
                    <span class="response-set-way response-way-2"><a target="_blank" href="http://kf.qq.com/faq/120322fu63YV130422rYNjYB.html">如何设置关键词自动回复<i class="icon iconfont">&#xe616;</i></a></span>
                </div>
                <!--回复内容操作区域-->
                <div id="responseContentWrap">
                    <!--被关注自动回复区域-->
                    <div class="response-content response-content-0 selected">
                        <ul class="response-content-nav">
                            <li<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']==''||$_smarty_tpl->getVariable('attentionMessage')->value['type']=='text'){?> class="selected"<?php }?>><a href="javascript:;"  data-media-type="text"><i class="icon iconfont">&#xe654;</i><span>文字</span></a></li>
                            <li<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='image'){?> class="selected"<?php }?>><a href="javascript:;" data-media-type="image"><i class="icon iconfont">&#xe653;</i><span>图片</span></a></li>
                            <li<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='voice'){?> class="selected"<?php }?>><a href="javascript:;" data-media-type="voice"><i class="icon iconfont">&#xe651;</i><span>语音</span></a></li>
                            <li<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='video'){?> class="selected"<?php }?>><a href="javascript:;" data-media-type="video"><i class="icon iconfont">&#xe652;</i><span>视频</span></a></li>
                            <li<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='my_news'){?> class="selected"<?php }?>><a href="javascript:;" data-media-type="my_news"><i class="icon iconfont">&#xe655;</i><span>自定义图文</span></a></li>
                        </ul>
                        <div class="tab-content-panel">
                            <div class="tab-content tab-content-text <?php if ($_smarty_tpl->getVariable('attentionMessage')->value==''||$_smarty_tpl->getVariable('attentionMessage')->value['type']=='text'){?>selected<?php }?>">
                                <div class="real-content"><?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='text'){?><?php echo $_smarty_tpl->getVariable('attentionMessage')->value['content'];?>
<?php }?></div>
                                <div class="display-content display-content-text" contenteditable="true"></div>
                                <div class="editor-toolbar clearfix">
                                    <a href="javascript:;" class="icon-emotion"><i class="icon iconfont">&#xe657;</i></a>
                                    <a href="javascript:;" class="icon-link"><i class="icon iconfont">&#xe658;</i></a>
                                    <p class="editor-tip">共可以输入<em>600</em>字</p>
                                    <div class="link-oper-area">
                                        <span class="hook"><span class="hook_dec hook_top"></span><span class="hook_dec hook_btm"></span></span>
                                        <div class="link-name-area">链接名称：<input type="text" placeholder="最多输入30字" class="link-name"></div>
                                        <div class="link-url-area">链接地址：<input type="text" class="link-url" placeholder="请输入正确的链接地址"></div>
                                        <div class="tip-info"></div>
                                        <div class="link-btn-area">
                                            <a class="btn btn-sm btn-success link-add" href="javascript:;">添加</a>
                                            <a class="btn btn-sm btn-default link-cancel" href="javascript:;">取消</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content tab-content-image <?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='image'){?>selected<?php }?>">
                                <div class="display-content display-content-image">
                                    <div class="display-material-area"<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='image'){?>style="display:block;"<?php }?>>
                                        <?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='image'){?>
                                        <img src="<?php echo $_smarty_tpl->getVariable('attentionMessage')->value['url'];?>
" data-media_id="<?php echo $_smarty_tpl->getVariable('attentionMessage')->value['media_id'];?>
"><a href="javascript:;" class="material-remove">删除</a>
                                        <?php }?>
                                    </div>

                                    <div class="no-content"<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']!='image'){?>style="display:block;"<?php }?>>
                                        <span class="create-access">
                                            <a href="javascript:;">
                                                <i class="icon iconfont">&#xe65a;</i>
                                                <strong>从素材库中选择</strong>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content tab-content-voice <?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='voice'){?>selected<?php }?>">
                                <div class="display-content display-content-voice">
                                    <div class="display-material-area"<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='voice'){?>style="display:block;"<?php }?>>
                                        <?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='voice'){?>
                                        <div class="voice-wrap" data-media_id="<?php echo $_smarty_tpl->getVariable('attentionMessage')->value['media_id'];?>
">
                                            <span class="voice-icon"><i class="icon iconfont">&#xe64c;</i></span>
                                            <span class="voice-name"></span>
                                        </div>
                                        <a href="javascript:;" class="material-remove">删除</a>
                                        <?php }?>
                                    </div>

                                    <div class="no-content"<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']!='voice'){?>style="display:block;"<?php }?>>
                                        <span class="create-access">
                                            <a href="javascript:;">
                                                <i class="icon iconfont">&#xe65a;</i>
                                                <strong>从素材库中选择</strong>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content tab-content-video <?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='video'){?>selected<?php }?>">
                                <div class="display-content display-content-video">
                                    <div class="display-material-area"<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='video'){?>style="display:block;"<?php }?>>
                                        <?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='video'){?>
                                        <div class="video-wrap" data-media_id="<?php echo $_smarty_tpl->getVariable('attentionMessage')->value['media_id'];?>
">
                                            <span class="video-icon"><i class="icon iconfont">&#xe64d;</i></span>
                                            <span class="video-name"></span>
                                        </div>
                                        <a href="javascript:;" class="material-remove">删除</a>
                                        <?php }?>
                                    </div>

                                    <div class="no-content"<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']!='video'){?>style="display:block;"<?php }?>>
                                            <span class="create-access">
                                                <a href="javascript:;">
                                                    <i class="icon iconfont">&#xe65a;</i>
                                                    <strong>从素材库中选择</strong>
                                                </a>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <!--自定义图文-->
                            <div class="tab-content tab-content-my_news <?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='my_news'){?>selected<?php }?>">
                                <div class="display-content display-content-my_news">
                                	<?php if ($_smarty_tpl->getVariable('attentionMessage')->value['type']=='my_news'&&$_smarty_tpl->getVariable('attentionMessage')->value['content']!=''){?>
                                		<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = json_decode($_smarty_tpl->getVariable('attentionMessage')->value['content']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
                                		<div class="item">
	                                		<div class="title-area"><input type="text" class="title" value="<?php echo $_smarty_tpl->getVariable('item')->value->title;?>
" placeholder="请输入标题"></div>          								
											<div class="description-area"><textarea class="description" placeholder="请输入描述"><?php echo $_smarty_tpl->getVariable('item')->value->description;?>
</textarea></div>
											<div class="url-area"><input type="text" class="url" value="<?php echo $_smarty_tpl->getVariable('item')->value->url;?>
" placeholder="请输入图文链接地址"></div>
											<div class="picUrl-area">
												<div class="image-wrap">
													<img src="<?php echo $_smarty_tpl->getVariable('item')->value->picUrl;?>
">
												</div>
											</div>
											<div class="oper-area">
												<a class="item-upload-image" href="javascript:;" title="上传图片"><i class="icon iconfont">&#xe691;</i></a>
												<a class="item-delete" href="javascript:;" title="删除图文"><i class="icon iconfont">&#xe624;</i></a>
												<a class="item-add" href="javascript:;" title="添加图文"><i class="icon iconfont">&#xe65a;</i></a>
											</div>
										</div>
                                		<?php }} ?>
                                	<?php }elseif(($_smarty_tpl->getVariable('attentionMessage')->value['type']!='my_news')||($_smarty_tpl->getVariable('attentionMessage')->value['type']=='my_news'&&$_smarty_tpl->getVariable('attentionMessage')->value['content']=='')){?>
          							<div class="item">
										<div class="title-area"><input type="text" class="title" placeholder="请输入标题"></div>          								
										<div class="description-area"><textarea class="description" placeholder="请输入描述"></textarea></div>
										<div class="url-area"><input type="text" class="url" placeholder="请输入图文链接地址"></div>
										<div class="picUrl-area">
											<div class="image-wrap"></div>
										</div>
										<div class="oper-area">
											<a class="item-upload-image" href="javascript:;" title="上传图片"><i class="icon iconfont">&#xe691;</i></a>
											<a class="item-delete" href="javascript:;" title="删除图文"><i class="icon iconfont">&#xe624;</i></a>
											<a class="item-add" href="javascript:;" title="添加图文"><i class="icon iconfont">&#xe65a;</i></a>
										</div>
          							</div>
          							
          							<?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--消息自动回复区域-->
                    <div class="response-content response-content-1">
                        <ul class="response-content-nav">
                            <li<?php if ($_smarty_tpl->getVariable('allMessage')->value==''||$_smarty_tpl->getVariable('allMessage')->value['type']=='text'){?> class="selected"<?php }?>><a href="javascript:;" data-media-type="text"><i class="icon iconfont">&#xe654;</i><span>文字</span></a></li>
                            <li<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='image'){?> class="selected"<?php }?>><a href="javascript:;" data-media-type="image"><i class="icon iconfont">&#xe653;</i><span>图片</span></a></li>
                            <li<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='voice'){?> class="selected"<?php }?>><a href="javascript:;" data-media-type="voice"><i class="icon iconfont">&#xe651;</i><span>语音</span></a></li>
                            <li<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='video'){?> class="selected"<?php }?>><a href="javascript:;" data-media-type="video"><i class="icon iconfont">&#xe652;</i><span>视频</span></a></li>
                            <li<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='my_news'){?> class="selected"<?php }?>><a href="javascript:;" data-media-type="my_news"><i class="icon iconfont">&#xe655;</i><span>自定义图文</span></a></li>
                        </ul>
                        <div class="tab-content-panel">
                            <div class="tab-content tab-content-text <?php if ($_smarty_tpl->getVariable('allMessage')->value==''||$_smarty_tpl->getVariable('allMessage')->value['type']=='text'){?>selected<?php }?>">
                                <div class="real-content"><?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='text'){?><?php echo $_smarty_tpl->getVariable('allMessage')->value['content'];?>
<?php }?></div>
                                <div class="display-content display-content-text" contenteditable="true"></div>
                                <div class="editor-toolbar clearfix">
                                    <a href="javascript:;" class="icon-emotion"><i class="icon iconfont">&#xe657;</i></a>
                                    <a href="javascript:;" class="icon-link"><i class="icon iconfont">&#xe658;</i></a>
                                    <p class="editor-tip">共可以输入<em>600</em>字</p>
                                    <div class="link-oper-area">
                                        <span class="hook"><span class="hook_dec hook_top"></span><span class="hook_dec hook_btm"></span></span>
                                        <div class="link-name-area">链接名称：<input type="text" placeholder="最多输入30字" class="link-name"></div>
                                        <div class="link-url-area">链接地址：<input type="text" class="link-url" placeholder="请输入正确的链接地址"></div>
                                        <div class="tip-info"></div>
                                        <div class="link-btn-area">
                                            <a class="btn btn-sm btn-success link-add" href="javascript:;">添加</a>
                                            <a class="btn btn-sm btn-default link-cancel" href="javascript:;">取消</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content tab-content-image <?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='image'){?>selected<?php }?>">

                                <div class="display-content display-content-image">
                                    <div class="display-material-area"<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='image'){?>style="display:block;"<?php }?>>
                                        <?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='image'){?>
                                        <img src="<?php echo $_smarty_tpl->getVariable('allMessage')->value['url'];?>
" data-media_id="<?php echo $_smarty_tpl->getVariable('allMessage')->value['media_id'];?>
"><a href="javascript:;" class="material-remove">删除</a>
                                        <?php }?>
                                    </div>

                                    <div class="no-content"<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']!='image'){?>style="display:block;"<?php }?>>
                                        <span class="create-access">
                                            <a href="javascript:;">
                                                <i class="icon iconfont">&#xe65a;</i>
                                                <strong>从素材库中选择</strong>
                                            </a>
                                        </span>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-content tab-content-voice <?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='voice'){?>selected<?php }?>">
                                <div class="display-content display-content-voice">
                                    <div class="display-material-area"<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='voice'){?>style="display:block;"<?php }?>>
                                        <?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='voice'){?>
                                        <div class="voice-wrap" data-media_id="<?php echo $_smarty_tpl->getVariable('allMessage')->value['media_id'];?>
">
                                            <span class="voice-icon"><i class="icon iconfont">&#xe64c;</i></span>
                                            <span class="voice-name"></span>
                                        </div>
                                        <a href="javascript:;" class="material-remove">删除</a>
                                        <?php }?>
                                    </div>

                                    <div class="no-content"<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']!='voice'){?>style="display:block;"<?php }?>>
                                        <span class="create-access">
                                            <a href="javascript:;">
                                                <i class="icon iconfont">&#xe65a;</i>
                                                <strong>从素材库中选择</strong>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content tab-content-video <?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='video'){?>selected<?php }?>">
                                <div class="display-content display-content-video">
                                    <div class="display-material-area"<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='video'){?>style="display:block;"<?php }?>>
                                        <?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='video'){?>
                                        <div class="video-wrap" data-media_id="<?php echo $_smarty_tpl->getVariable('allMessage')->value['media_id'];?>
">
                                            <span class="video-icon"><i class="icon iconfont">&#xe64d;</i></span>
                                            <span class="video-name"></span>
                                        </div>
                                        <a href="javascript:;" class="material-remove">删除</a>
                                        <?php }?>
                                    </div>

                                    <div class="no-content"<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']!='video'){?>style="display:block;"<?php }?>>
                                            <span class="create-access">
                                                <a href="javascript:;">
                                                    <i class="icon iconfont">&#xe65a;</i>
                                                    <strong>从素材库中选择</strong>
                                                </a>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <!--自定义图文-->
                            <div class="tab-content tab-content-my_news <?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='my_news'){?>selected<?php }?>">
                                <div class="display-content display-content-my_news">
                                	<?php if ($_smarty_tpl->getVariable('allMessage')->value['type']=='my_news'&&$_smarty_tpl->getVariable('allMessage')->value['content']!=''){?>
                                		<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = json_decode($_smarty_tpl->getVariable('allMessage')->value['content']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
                                		<div class="item">
	                                		<div class="title-area"><input type="text" class="title" value="<?php echo $_smarty_tpl->getVariable('item')->value->title;?>
" placeholder="请输入标题"></div>          								
											<div class="description-area"><textarea class="description" placeholder="请输入描述"><?php echo $_smarty_tpl->getVariable('item')->value->description;?>
</textarea></div>
											<div class="url-area"><input type="text" class="url" value="<?php echo $_smarty_tpl->getVariable('item')->value->url;?>
" placeholder="请输入图文链接地址"></div>
											<div class="picUrl-area">
												<div class="image-wrap">
													<img src="<?php echo $_smarty_tpl->getVariable('item')->value->picUrl;?>
">
												</div>
											</div>
											<div class="oper-area">
												<a class="item-upload-image" href="javascript:;" title="上传图片"><i class="icon iconfont">&#xe691;</i></a>
												<a class="item-delete" href="javascript:;" title="删除图文"><i class="icon iconfont">&#xe624;</i></a>
												<a class="item-add" href="javascript:;" title="添加图文"><i class="icon iconfont">&#xe65a;</i></a>
											</div>
										</div>
                                		<?php }} ?>
                                	<?php }elseif(($_smarty_tpl->getVariable('allMessage')->value['type']!='my_news')||($_smarty_tpl->getVariable('allMessage')->value['type']=='my_news'&&$_smarty_tpl->getVariable('allMessage')->value['content']=='')){?>
          							<div class="item">
										<div class="title-area"><input type="text" class="title" placeholder="请输入标题"></div>          								
										<div class="description-area"><textarea class="description" placeholder="请输入描述"></textarea></div>
										<div class="url-area"><input type="text" class="url" placeholder="请输入图文链接地址"></div>
										<div class="picUrl-area">
											<div class="image-wrap"></div>
										</div>
										<div class="oper-area">
											<a class="item-upload-image" href="javascript:;" title="上传图片"><i class="icon iconfont">&#xe691;</i></a>
											<a class="item-delete" href="javascript:;" title="删除图文"><i class="icon iconfont">&#xe624;</i></a>
											<a class="item-add" href="javascript:;" title="添加图文"><i class="icon iconfont">&#xe65a;</i></a>
										</div>
          							</div>
          							
          							<?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--关键词自动回复区域-->
                    <div class="response-content response-content-2">
                        <div class="btn-rule-add-wrap">
                            <a href="javascript:;" class="btn btn-success btn-sm btn-ruld-add"><i class="icon iconfont">&#xe61f;</i>添加规则</a>
                        </div>

                        <!--添加规则-->
                        <div class="keywords-response rule-add-wrap keywords-rule-wrap">
                            <div class="rule-top">新规则</div>
                            <div class="rule-wrap">
                                <div class="rule-info-area rule-name-area"><span class="label-text"><i class="icon iconfont">&#xe620;</i>规则名</span><input type="text" class="rule-name" placeholder="规则名最多60个字"></div>
                                <div class="rule-info-area rule-keywords-area"><span class="label-text"><i class="icon iconfont">&#xe620;</i>关键字</span><input type="text" class="rule-keywords" placeholder="多个关键词之间以英文竖线“|”隔开,每个关键词少于30个字"></div>
                                <div class="rule-info-area rule-response-type-area clearfix">
                                    <span class="label-text rule-content-area-text"><i class="icon iconfont">&#xe620;</i>回复</span>
                                    <ul>
                                        <li class="selected"><a href="javascript:;"  data-media-type="text"><i class="icon iconfont">&#xe654;</i><span>文字</span></a></li>
                                        <li><a href="javascript:;" data-media-type="image"><i class="icon iconfont">&#xe653;</i><span>图片</span></a></li>
                                        <li><a href="javascript:;" data-media-type="voice"><i class="icon iconfont">&#xe651;</i><span>语音</span></a></li>
                                        <li><a href="javascript:;" data-media-type="video"><i class="icon iconfont">&#xe652;</i><span>视频</span></a></li>
                                        <li><a href="javascript:;" data-media-type="news"><i class="icon iconfont">&#xe655;</i><span>图文</span></a></li>
                                        <li><a href="javascript:;" data-media-type="my_news"><i class="icon iconfont">&#xe655;</i><span>自定义图文</span></a></li>
                                    </ul>
                                </div>
                                <!--新规则内容-->
                                <div class="rule-info-area rule-content-area">
                                    <!--文本内容 默认显示-->
                                    <div class="tab-content tab-content-text selected" data-media-type="text">
                                        <div class="display-content display-content-text" contenteditable="true"></div>
                                        <div class="editor-toolbar clearfix">
                                            <a href="javascript:;" class="icon-emotion"><i class="icon iconfont">&#xe657;</i></a>
                                            <a href="javascript:;" class="icon-link"><i class="icon iconfont">&#xe658;</i></a>
                                            <p class="editor-tip">共可以输入<em>600</em>字</p>
                                            <div class="link-oper-area">
                                                <span class="hook"><span class="hook_dec hook_top"></span><span class="hook_dec hook_btm"></span></span>
                                                <div class="link-name-area">链接名称：<input type="text" placeholder="最多输入30字" class="link-name"></div>
                                                <div class="link-url-area">链接地址：<input type="text" class="link-url" placeholder="请输入正确的链接地址"></div>
                                                <div class="tip-info"></div>
                                                <div class="link-btn-area">
                                                    <a class="btn btn-sm btn-success link-add" href="javascript:;">添加</a>
                                                    <a class="btn btn-sm btn-default link-cancel" href="javascript:;">取消</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-content display-material-area" data-media-type="">
                                        <!--素材库中选择内容-->

                                    </div>
                                    <!--自定义图文-->
		                            <div class="tab-content tab-content-my_news" data-media_type="my_news">
		                                <div class="display-content display-content-my_news">
		          							<div class="item">
												<div class="title-area"><input type="text" class="title" placeholder="请输入标题"></div>          								
												<div class="description-area"><textarea class="description" placeholder="请输入描述"></textarea></div>
												<div class="url-area"><input type="text" class="url" placeholder="请输入图文链接地址"></div>
												<div class="picUrl-area">
													<div class="image-wrap"></div>
												</div>
												<div class="oper-area">
													<a class="item-upload-image" href="javascript:;" title="上传图片"><i class="icon iconfont">&#xe691;</i></a>
													<a class="item-delete" href="javascript:;" title="删除图文"><i class="icon iconfont">&#xe624;</i></a>
													<a class="item-add" href="javascript:;" title="添加图文"><i class="icon iconfont">&#xe65a;</i></a>
												</div>
		          							</div>
		                                </div>
		                            </div>
                                </div>
                            </div>
                            <div class="rule-bottom"><a class="btn btn-success btn-sm btn-rule-save" href="javascript:;">保存</a><a class="btn btn-default btn-sm btn-rule-remove" href="javascript:;">删除</a></div>
                        </div>
                        <!--已有规则-->
                        <div class="keywords-response keywords-response-wrap"<?php if ($_smarty_tpl->getVariable('keywordsMessage')->value){?> style="display:block;"<?php }?>>
                            <?php  $_smarty_tpl->tpl_vars['message'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('keywordsMessage')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['keywordsMessage']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['message']->key => $_smarty_tpl->tpl_vars['message']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['keywordsMessage']['iteration']++;
?>
                            <div class="keywords-rule keywords-rule-wrap" data-id="<?php echo $_smarty_tpl->tpl_vars['message']->value['id'];?>
" data-message_id="<?php echo $_smarty_tpl->tpl_vars['message']->value['message_id'];?>
">
                                <div class="rule-top">规则<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['keywordsMessage']['iteration'];?>
：<?php echo $_smarty_tpl->tpl_vars['message']->value['rule_name'];?>
</div>
                                <div class="rule-wrap">
                                    <div class="rule-info-area rule-name-area"><span class="label-text"><i class="icon iconfont">&#xe620;</i>规则名</span><input type="text" class="rule-name" placeholder="规则名最多60个字" value="<?php echo $_smarty_tpl->tpl_vars['message']->value['rule_name'];?>
"></div>
                                    <div class="rule-info-area rule-keywords-area"><span class="label-text"><i class="icon iconfont">&#xe620;</i>关键字</span><input type="text" class="rule-keywords" placeholder="多个关键词之间以英文竖线“|”隔开，每个关键词少于30个字"value="<?php  $_smarty_tpl->tpl_vars['keywords'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['message']->value['keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['keywords']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['keywords']->iteration=0;
if ($_smarty_tpl->tpl_vars['keywords']->total > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['keywords']->key => $_smarty_tpl->tpl_vars['keywords']->value){
 $_smarty_tpl->tpl_vars['keywords']->iteration++;
 $_smarty_tpl->tpl_vars['keywords']->last = $_smarty_tpl->tpl_vars['keywords']->iteration === $_smarty_tpl->tpl_vars['keywords']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['keywords']['last'] = $_smarty_tpl->tpl_vars['keywords']->last;
?><?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['keywords']['last']==false){?>|<?php }?><?php }} ?>"></div>
                                    <div class="rule-info-area rule-response-type-area clearfix">
                                        <span class="label-text rule-content-area-text"><i class="icon iconfont">&#xe620;</i>回复</span>
                                        <ul>
                                            <li <?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='text'){?>class="selected"<?php }?>><a href="javascript:;"  data-media-type="text"><i class="icon iconfont">&#xe654;</i><span>文字</span></a></li>
                                            <li <?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='image'){?>class="selected"<?php }?>><a href="javascript:;" data-media-type="image"><i class="icon iconfont">&#xe653;</i><span>图片</span></a></li>
                                            <li <?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='voice'){?>class="selected"<?php }?>><a href="javascript:;" data-media-type="voice"><i class="icon iconfont">&#xe651;</i><span>语音</span></a></li>
                                            <li <?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='video'){?>class="selected"<?php }?>><a href="javascript:;" data-media-type="video"><i class="icon iconfont">&#xe652;</i><span>视频</span></a></li>
                                            <li <?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='news'){?>class="selected"<?php }?>><a href="javascript:;" data-media-type="news"><i class="icon iconfont">&#xe655;</i><span>图文</span></a></li>
                                            <li <?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='my_news'){?>class="selected"<?php }?>><a href="javascript:;" data-media-type="my_news"><i class="icon iconfont">&#xe655;</i><span>自定义图文</span></a></li>
                                        </ul>
                                    </div>
                                    <!--规则内容-->
                                    <div class="rule-info-area rule-content-area">
                                        <!--文本内容 默认显示-->
                                        <div class="tab-content tab-content-text<?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='text'){?> selected<?php }?>" data-media-type="text">
                                            <div class="real-content"><?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='text'){?><?php echo $_smarty_tpl->tpl_vars['message']->value['content'];?>
<?php }?></div>
                                            <div class="display-content display-content-text" contenteditable="true"></div>
                                            <div class="editor-toolbar clearfix">
                                                <a href="javascript:;" class="icon-emotion"><i class="icon iconfont">&#xe657;</i></a>
                                                <a href="javascript:;" class="icon-link"><i class="icon iconfont">&#xe658;</i></a>
                                                <p class="editor-tip">共可以输入<em>600</em>字</p>
                                                <div class="link-oper-area">
                                                    <span class="hook"><span class="hook_dec hook_top"></span><span class="hook_dec hook_btm"></span></span>
                                                    <div class="link-name-area">链接名称：<input type="text" placeholder="最多输入30字" class="link-name"></div>
                                                    <div class="link-url-area">链接地址：<input type="text" class="link-url" placeholder="请输入正确的链接地址"></div>
                                                    <div class="tip-info"></div>
                                                    <div class="link-btn-area">
                                                        <a class="btn btn-sm btn-success link-add" href="javascript:;">添加</a>
                                                        <a class="btn btn-sm btn-default link-cancel" href="javascript:;">取消</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content display-material-area<?php if ($_smarty_tpl->tpl_vars['message']->value['type']!='text'){?> selected<?php }?>" data-media-type="<?php echo $_smarty_tpl->tpl_vars['message']->value['type'];?>
">
                                            <!--素材库中选择内容-->
                                            <?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='image'){?>
                                             <img src="<?php echo $_smarty_tpl->tpl_vars['message']->value['url'];?>
" data-media_id="<?php echo $_smarty_tpl->tpl_vars['message']->value['media_id'];?>
"><a href="javascript:;" class="material-remove">删除</a>
                                            <?php }elseif($_smarty_tpl->tpl_vars['message']->value['type']=='voice'){?>
                                            <div class="voice-wrap" data-media_id="<?php echo $_smarty_tpl->tpl_vars['message']->value['media_id'];?>
">
                                                <span class="voice-icon"><i class="icon iconfont">&#xe64c;</i></span><span class="voice-name"><?php echo $_smarty_tpl->tpl_vars['message']->value['content'];?>
</span>
                                            </div>
                                            <a href="javascript:;" class="material-remove">删除</a>
                                            <?php }elseif($_smarty_tpl->tpl_vars['message']->value['type']=='video'){?>
                                            <div class="video-wrap" data-media_id="<?php echo $_smarty_tpl->tpl_vars['message']->value['media_id'];?>
"><span class="video-icon"><i class="icon iconfont">&#xe64d;</i></span><span class="video-name"><?php echo $_smarty_tpl->tpl_vars['message']->value['content'];?>
</span></div>
                                            <?php }elseif($_smarty_tpl->tpl_vars['message']->value['type']=='news'){?>
                                            <div class="news-wrap" data-media_id="<?php echo $_smarty_tpl->tpl_vars['message']->value['media_id'];?>
">
                                            <?php echo $_smarty_tpl->tpl_vars['message']->value['content'];?>

                                            <a href="javascript:;" class="material-remove">删除</a>
                                            </div>
                                            <?php }?>

                                        </div>
                                        <!--自定义图文-->
			                            <div class="tab-content tab-content-my_news <?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='my_news'){?>selected<?php }?>" data-media_type="<?php echo $_smarty_tpl->tpl_vars['message']->value['type'];?>
">
			                                <div class="display-content display-content-my_news">
			                                	<?php if ($_smarty_tpl->tpl_vars['message']->value['type']=='my_news'&&$_smarty_tpl->tpl_vars['message']->value['content']!=''){?>
			                                		<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = json_decode($_smarty_tpl->tpl_vars['message']->value['content']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
?>
			                                		<div class="item">
				                                		<div class="title-area"><input type="text" class="title" value="<?php echo $_smarty_tpl->getVariable('item')->value->title;?>
" placeholder="请输入标题"></div>          								
														<div class="description-area"><textarea class="description" placeholder="请输入描述"><?php echo $_smarty_tpl->getVariable('item')->value->description;?>
</textarea></div>
														<div class="url-area"><input type="text" class="url" value="<?php echo $_smarty_tpl->getVariable('item')->value->url;?>
" placeholder="请输入图文链接地址"></div>
														<div class="picUrl-area">
															<div class="image-wrap">
																<img src="<?php echo $_smarty_tpl->getVariable('item')->value->picUrl;?>
">
															</div>
														</div>
														<div class="oper-area">
															<a class="item-upload-image" href="javascript:;" title="上传图片"><i class="icon iconfont">&#xe691;</i></a>
															<a class="item-delete" href="javascript:;" title="删除图文"><i class="icon iconfont">&#xe624;</i></a>
															<a class="item-add" href="javascript:;" title="添加图文"><i class="icon iconfont">&#xe65a;</i></a>
														</div>
													</div>
			                                		<?php }} ?>
			                                	<?php }elseif(($_smarty_tpl->tpl_vars['message']->value['type']!='my_news')||($_smarty_tpl->tpl_vars['message']->value['type']=='my_news'&&$_smarty_tpl->tpl_vars['message']->value['content']=='')){?>
			          							<div class="item">
													<div class="title-area"><input type="text" class="title" placeholder="请输入标题"></div>          								
													<div class="description-area"><textarea class="description" placeholder="请输入描述"></textarea></div>
													<div class="url-area"><input type="text" class="url" placeholder="请输入图文链接地址"></div>
													<div class="picUrl-area">
														<div class="image-wrap"></div>
													</div>
													<div class="oper-area">
														<a class="item-upload-image" href="javascript:;" title="上传图片"><i class="icon iconfont">&#xe691;</i></a>
														<a class="item-delete" href="javascript:;" title="删除图文"><i class="icon iconfont">&#xe624;</i></a>
														<a class="item-add" href="javascript:;" title="添加图文"><i class="icon iconfont">&#xe65a;</i></a>
													</div>
			          							</div>
			          							
			          							<?php }?>
			                                </div>
			                            </div>
                                    </div>
                                </div>
                                <div class="rule-bottom"><a class="btn btn-success btn-sm btn-rule-update" href="javascript:;">保存</a><?php if ($_smarty_tpl->tpl_vars['message']->value['is_use']=='1'){?><a class="btn btn-danger btn-sm keywords-response-set-use" data-is_use="0" href="javascript:;">停用</a><?php }else{ ?><a href="javascript:;" data-is_use="1" class="btn btn-success btn-sm keywords-response-set-use">启用</a><?php }?><a class="btn btn-default btn-sm btn-rule-delete" href="javascript:;">删除</a></div>
                            </div>
                            <?php }} ?>
                        </div>
                        <?php if (!$_smarty_tpl->getVariable('keywordsMessage')->value){?>
                        <div class="keywords-response no-keywords-response">
                            暂无创建规则
                        </div>
                        <?php }?>
                    </div>
                    <!--回复操作按钮-->
                    <div class="response-toolbar">
                        <div class="response-toolbar-oper response-toolbar-0 selected">
                            <a href="javascript:;" class="btn btn-success btn-md response-save">保存</a>
                            <a href="javascript:;" <?php if ($_smarty_tpl->getVariable('attentionMessage')->value['is_use']=='0'){?>style="display:inline-block;"<?php }?> class="btn btn-success btn-md response-use" data-is_use="1">启用</a>
                            <a href="javascript:;" <?php if ($_smarty_tpl->getVariable('attentionMessage')->value['is_use']=='1'){?>style="display:inline-block;"<?php }?> class="btn btn-danger btn-md response-no-use" data-is_use="0">停用</a>
                        </div>
                        <div class="response-toolbar-oper response-toolbar-1">
                            <a href="javascript:;" class="btn btn-success btn-md response-save">保存</a>
                            <a href="javascript:;" <?php if ($_smarty_tpl->getVariable('allMessage')->value['is_use']=='0'){?>style="display:inline-block;"<?php }?> class="btn btn-success btn-md response-use" data-is_use="1">启用</a>
                            <a href="javascript:;" <?php if ($_smarty_tpl->getVariable('allMessage')->value['is_use']=='1'){?>style="display:inline-block;"<?php }?> class="btn btn-danger btn-md response-no-use" data-is_use="0">停用</a>
                        </div>

                    </div>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
</div>
<form action="#" method="post" id="myForm" enctype="multipart/form-data" class="hidden">
     <input class="upload-image" title="支持jpg、jpeg、gif、png格式，文件小于5M" tabindex="3" type="file" accept="image/*" id="imgurl" name="imgurl" size="3">
     <input type="hidden" name="prevUrl" id="prevUrl" value=""/>
</form>
<!-- 页脚 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!--打开素材库，从中选择特定素材-->
<div class="modal fade" id="materialModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title">
                    确认提示框
                </h4>
            </div>
            <div class="modal-body">
                <!--素材列表-->
                <div class="materail-list">

                </div>
                <!--分页-->
                <div id="page-selection"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-confirm no-operate">确认</button>
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<!-- jsfiles -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/jquery.qqFace/js/jquery.qqFace.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/weixin/response/js/responsePage.js"></script>
</body>
</html>