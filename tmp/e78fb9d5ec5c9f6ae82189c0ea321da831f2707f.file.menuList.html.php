<?php /* Smarty version Smarty-3.0.8, created on 2019-09-25 09:31:02
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/weixin/menu/page/menuList.html" */ ?>
<?php /*%%SmartyHeaderCode:18753589755d8ac356f19a76-74489974%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e78fb9d5ec5c9f6ae82189c0ea321da831f2707f' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/weixin/menu/page/menuList.html',
      1 => 1535701988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18753589755d8ac356f19a76-74489974',
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
/weixin/menu/css/menuList.css">
<body>
<!---------------- 自定义菜单 ------------------->

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
                <div class="menu-setting-area-warp" id="menuContainer">
                    <div class="menu-setting-area">
                        <p class="menu-setting-tips">可创建最多3个一级菜单，每个一级菜单下可创建最多5个二级菜单(菜单名称8个汉字或16个字母以内)。</p>
                        <div class="inner-menu-container-warp">
                            <div class="inner-menu-container clearfix">
                                <div class="inner-side">
                                    <div class="inner-menu-content">
                                        <div class="menu-manage">
                                            <div class="sub-title"><span>菜单管理</span><a href="javascript:;" class="add-menu" data-level="0" data-toggle="popover" data-trigger="hover" data-placement="top"data-content="添加"><i class="icon iconfont">&#xe61f;</i></a></div>
                                            <div class="menu-list-box">
                                                <?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('wechatMenuList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['menu']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['menu']->iteration=0;
 $_smarty_tpl->tpl_vars['menu']->index=-1;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['wechatMenuList']['total'] = $_smarty_tpl->tpl_vars['menu']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['wechatMenuList']['iteration']=0;
if ($_smarty_tpl->tpl_vars['menu']->total > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
 $_smarty_tpl->tpl_vars['menu']->iteration++;
 $_smarty_tpl->tpl_vars['menu']->index++;
 $_smarty_tpl->tpl_vars['menu']->first = $_smarty_tpl->tpl_vars['menu']->index === 0;
 $_smarty_tpl->tpl_vars['menu']->last = $_smarty_tpl->tpl_vars['menu']->iteration === $_smarty_tpl->tpl_vars['menu']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['wechatMenuList']['first'] = $_smarty_tpl->tpl_vars['menu']->first;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['wechatMenuList']['iteration']++;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['wechatMenuList']['last'] = $_smarty_tpl->tpl_vars['menu']->last;
?>
                                                <div class="inner-menu<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['wechatMenuList']['first']){?> inner-menu-first<?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['wechatMenuList']['last']){?> inner-menu-last<?php }?>">
                                                    <!--一级菜单-->
                                                    <div class="inner-menu-item menu-item-level-1">
                                                        <a href="javascript:;" class="edit-menu-name" data-level="1" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="重命名"><i class="icon iconfont">&#xe623;</i></a>
                                                        <a href="javascript:;" class="inner-menu-link" data-id="<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['wechatMenuList']['iteration'];?>
" data-pid="0" data-name="<?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
" data-type="<?php echo $_smarty_tpl->tpl_vars['menu']->value['type'];?>
" data-level="1" <?php if ($_smarty_tpl->tpl_vars['menu']->value['type']=='view'){?> data-url="<?php echo $_smarty_tpl->tpl_vars['menu']->value['url'];?>
" <?php }elseif($_smarty_tpl->tpl_vars['menu']->value['type']=='media_id'||$_smarty_tpl->tpl_vars['menu']->value['type']=='view_limited'){?> data-media_id="<?php echo $_smarty_tpl->tpl_vars['menu']->value['media_id'];?>
" <?php }elseif($_smarty_tpl->tpl_vars['menu']->value['type']=='miniprogram'){?> data-url="<?php echo $_smarty_tpl->tpl_vars['menu']->value['url'];?>
" data-appid="<?php echo $_smarty_tpl->tpl_vars['menu']->value['appid'];?>
" data-pagepath="<?php echo $_smarty_tpl->tpl_vars['menu']->value['pagepath'];?>
" <?php }else{ ?> data-key="<?php echo $_smarty_tpl->tpl_vars['menu']->value['key'];?>
"<?php }?>><span class="menu-name"><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</span></a>
                                                        <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['wechatMenuList']['total']>1){?>
                                                        <a href="javascript:;" class="menu-order menu-up"  data-level="1" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="上移"><i class="icon iconfont">&#xe621;</i></a>
                                                        <a href="javascript:;" class="menu-order menu-down" data-level="1" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="下移"><i class="icon iconfont">&#xe622;</i></a>
                                                        <?php }?>
                                                        <a href="javascript:;" class="add-menu" data-level="1" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="添加"><i class="icon iconfont">&#xe61f;</i></a>
                                                        <a href="javascript:;" class="delete-menu" data-level="1" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="确认删除吗?"><i class="icon iconfont">&#xe624;</i></a>
                                                    </div>
                                                    <?php  $_smarty_tpl->tpl_vars['subMenu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menu']->value['sub_button']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['subMenu']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['subMenu']->iteration=0;
 $_smarty_tpl->tpl_vars['subMenu']->index=-1;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['subMenuList']['total'] = $_smarty_tpl->tpl_vars['subMenu']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['subMenuList']['iteration']=0;
if ($_smarty_tpl->tpl_vars['subMenu']->total > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['subMenu']->key => $_smarty_tpl->tpl_vars['subMenu']->value){
 $_smarty_tpl->tpl_vars['subMenu']->iteration++;
 $_smarty_tpl->tpl_vars['subMenu']->index++;
 $_smarty_tpl->tpl_vars['subMenu']->first = $_smarty_tpl->tpl_vars['subMenu']->index === 0;
 $_smarty_tpl->tpl_vars['subMenu']->last = $_smarty_tpl->tpl_vars['subMenu']->iteration === $_smarty_tpl->tpl_vars['subMenu']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['subMenuList']['first'] = $_smarty_tpl->tpl_vars['subMenu']->first;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['subMenuList']['iteration']++;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['subMenuList']['last'] = $_smarty_tpl->tpl_vars['subMenu']->last;
?>
                                                    <!--二级菜单-->
                                                    <div class="inner-menu-item inner-subMenu-item menu-item-level-2 <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['subMenuList']['first']){?> inner-subMenu-item-first<?php }elseif($_smarty_tpl->getVariable('smarty')->value['foreach']['subMenuList']['last']){?> inner-subMenu-item-last<?php }?>">
                                                        <a href="javascript:;" class="edit-menu-name" data-level="2" data-toggle="popover" data-trigger="hover" data-placement="left" data-content="重命名"><i class="icon iconfont">&#xe623;</i></a>
                                                        <a href="javascript:;" class="inner-menu-link" data-id="<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['wechatMenuList']['iteration'];?>
<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['subMenuList']['iteration'];?>
" data-pid="<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['wechatMenuList']['iteration'];?>
" data-name="<?php echo $_smarty_tpl->tpl_vars['subMenu']->value['name'];?>
" data-type="<?php echo $_smarty_tpl->tpl_vars['subMenu']->value['type'];?>
" data-level="2" <?php if ($_smarty_tpl->tpl_vars['subMenu']->value['type']=='view'){?> data-url="<?php echo $_smarty_tpl->tpl_vars['subMenu']->value['url'];?>
" <?php }elseif($_smarty_tpl->tpl_vars['subMenu']->value['type']=='media_id'||$_smarty_tpl->tpl_vars['subMenu']->value['type']=='view_limited'){?> data-media_id="<?php echo $_smarty_tpl->tpl_vars['subMenu']->value['media_id'];?>
"<?php }elseif($_smarty_tpl->tpl_vars['subMenu']->value['type']=='miniprogram'){?> data-url="<?php echo $_smarty_tpl->tpl_vars['subMenu']->value['url'];?>
" data-appid="<?php echo $_smarty_tpl->tpl_vars['subMenu']->value['appid'];?>
" data-pagepath="<?php echo $_smarty_tpl->tpl_vars['subMenu']->value['pagepath'];?>
" <?php }else{ ?> data-key="<?php echo $_smarty_tpl->tpl_vars['subMenu']->value['key'];?>
"<?php }?>><i class="icon iconfont">&#xe620;</i><span class="menu-name"><?php echo $_smarty_tpl->tpl_vars['subMenu']->value['name'];?>
</span></a>
                                                        <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['subMenuList']['total']>1){?>
                                                        <a href="javascript:;" class="menu-order menu-up"  data-level="2" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="上移"><i class="icon iconfont">&#xe621;</i></a>
                                                        <a href="javascript:;" class="menu-order menu-down"  data-level="2"  data-toggle="popover" data-trigger="hover" data-placement="top" data-content="下移"><i class="icon iconfont">&#xe622;</i></a>
                                                        <?php }?>
                                                        <a href="javascript:;" class="delete-menu" data-level="2"  data-toggle="popover" data-trigger="hover" data-placement="left" data-content="确认删除吗?"><i class="icon iconfont">&#xe624;</i></a>
                                                    </div>
                                                    <?php }} ?>
                                                </div>
                                                <?php }} ?>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="inner-main">
                                    <div class="inner-menu-content">
                                        <div class="sub-title"><span class="menu-tip-info"></span></div>
                                        <!--一级菜单已经添加二级菜单的情况-->
                                        <div class="action_content init default jsMain" id="none" style="display: block;">
                                            <p class="action_tips">你可以点击左侧菜单或添加一个新菜单，然后设置菜单内容</p>
                                            <a href="javascript:" class="initialCreate" style="display: none;">
                                                <i class="icon_menu_action add"></i>
                                                <strong>添加二级菜单</strong>
                                            </a>
                                        </div>

                                        <!--菜单未设置任何内容时-->
                                        <div class="action_content init jsMain" style="display: none;" id="index">
                                            <p class="action_tips">请设置“公众号”菜单的内容</p>

                                            <a href="javascript:;" class="initialCreate" style="display: inline-block;"><i class="icon_menu_action add"></i><strong>添加二级菜单</strong></a>
                                            <a href="javascript:;" id="sendMsg" class="sendMsg"><i class="icon_menu_action send"></i><strong>发送信息</strong></a>
                                            <a href="javascript:;" class="skip-to-page"><i class="icon_menu_action url"></i><strong>跳转到网页</strong></a>
                                            <a href="javascript:;" id="addEvent" class="addEvent"><i class="icon_menu_action event"></i><strong>添加事件</strong></a>
                                            <a href="javascript:;" id="addMiniProgram" class="addMiniProgram"><i class="icon_menu_action miniprogram"></i><strong>跳转小程序</strong></a>
                                            
                                        </div>

                                        <!--菜单设置了发送消息的情况-->
                                        <div class="action_content sendMsg jsMain" id="message" style="display: none;">
                                            <p class="action_tips">订阅者点击该子菜单会收到以下消息</p>
                                            <div class="response-content selected">
                                                <ul class="response-content-nav">
                                                    <li class="selected"><a href="javascript:;"  data-media-type="news"><i class="icon iconfont">&#xe655;</i><span>图文</span></a></li>
                                                    <li><a href="javascript:;"  data-media-type="text" style="display: none;"><i class="icon iconfont">&#xe654;</i><span>文字</span></a></li>
                                                    <li><a href="javascript:;" data-media-type="image"><i class="icon iconfont">&#xe653;</i><span>图片</span></a></li>
                                                    <li><a href="javascript:;" data-media-type="voice"><i class="icon iconfont">&#xe651;</i><span>语音</span></a></li>
                                                    <li><a href="javascript:;" data-media-type="video"><i class="icon iconfont">&#xe652;</i><span>视频</span></a></li>
                                                </ul>
                                                <div class="tab-content-panel">
                                                    <div class="tab-content tab-content-news selected">
                                                        <div class="display-content display-content-image">
                                                            <div class="display-material-area">

                                                            </div>
                                                            <div class="no-content" style="display: block;">
                                                                <span class="create-access">
                                                                    <a href="javascript:;">
                                                                        <i class="icon iconfont">&#xe65a;</i>
                                                                        <strong>从素材库中选择</strong>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-content tab-content-text" style="display: none;">
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
                                                    <div class="tab-content tab-content-image">
                                                        <div class="display-content display-content-image">
                                                            <div class="display-material-area">

                                                            </div>
                                                            <div class="no-content" style="display: block;">
                                                                <span class="create-access">
                                                                    <a href="javascript:;">
                                                                        <i class="icon iconfont">&#xe65a;</i>
                                                                        <strong>从素材库中选择</strong>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-content tab-content-voice">
                                                        <div class="display-content display-content-voice">
                                                            <div class="display-material-area">

                                                            </div>
                                                            <div class="no-content" style="display: block;">
                                                                <span class="create-access">
                                                                    <a href="javascript:;">
                                                                        <i class="icon iconfont">&#xe65a;</i>
                                                                        <strong>从素材库中选择</strong>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-content tab-content-video">
                                                        <div class="display-content display-content-video">
                                                            <div class="display-material-area">

                                                            </div>
                                                            <div class="no-content"style="display:block;">
                                                                <span class="create-access">
                                                                    <a href="javascript:;">
                                                                        <i class="icon iconfont">&#xe65a;</i>
                                                                        <strong>从素材库中选择</strong>
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--菜单设置了链接地址的情况-->
                                        <div class="action_content sended jsMain" id="view" style="display: none;">

                                            <p class="action_tips">订阅者点击该子菜单会跳到以下链接</p>
                                            <div class="msg_wrp" id="viewDiv">http://www.changekeji.com</div>
                                            <p class="frm_tips" style="display: none;">来自<span class="js_name">素材库</span><span style="display:none;"> -《<span class="js_title"></span>》</span></p>

                                            <div class="btn_wrp">
                                                <a href="javascript:;" class="btn btn_default btn_editing" id="changeBt" style="display: inline-block;">修改</a>
                                            </div>
                                        </div>
                                        <!--设置url-->
                                        <div class="action_content url jsMain" id="url" style="display: none;">
                                            <form action="" id="urlForm" onsubmit="return false;">

                                                <p class="action_tips" id="urlTips">订阅者点击该子菜单会跳到以下链接，<a class="resetAction" href="javascript:void(0);" style="display: none;">重设菜单内容</a></p>
                                                <div class="frm_control_group">
                                                    <label class="frm_label">页面地址</label>
                                                    <div class="frm_controls">
                                                        <span class="frm_input_box">
                                                            <input type="text" class="frm_input" id="urlText" name="urlText">
                                                        </span>
                                                        <p class="frm_tips" id="js_urlTitle" style="display: none;">
                                                            来自<span class="js_name"></span><span style=""> -《<span class="js_title"></span>》</span>
                                                        </p>
                                                        <p class="frm_msg fail" style="display: none;" id="urlFail">
                                                            <span for="urlText" class="frm_msg_content" style="display: inline;">请输入正确的URL</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="frm_control_group btn_appmsg_wrap" style="display: none;">
                                                    <div class="frm_controls">
                                                        <a href="javascript:;" id="js_appmsgPop">从公众号图文消息中选择</a>
                                                        <p class="frm_msg fail" style="display: none;" id="urlUnSelect">
                                                            <span for="urlText" class="frm_msg_content" style="display: inline;">请选择一篇文章</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="tool_bar dn" style="display: none;">
                                                <a class="submit btn btn-success" type="submit" id="urlSave">保存</a>
                                                <a href="javascript:void(0);" class="btn btn_default" id="urlBack">返回</a>
                                            </div>
                                        </div>

                                        <!--设置Event 开发者专用中心-->
                                        <div class="action_content event jsMain" id="event" style="display: none;">
                                            <form action="" id="eventForm" onsubmit="return false;">
                                                <p class="action_tips" id="eventTips">此为开发者使用功能，切勿随意修改</p>
                                                <div class="frm_control_group">
                                                    <label class="frm_label">EventType</label>
                                                    <div class="frm_controls">
                                                        <span class="frm_input_box" style="border:none;padding:0;">
                                                            <select id="typeText">
                                                                <option value="click">点击推事件</option>
                                                                <option value="scancode_push">扫码推事件</option>
                                                                <option value="scancode_waitmsg">扫码推事件且弹出“消息接收中”提示框</option>
                                                                <option value="pic_sysphoto">弹出系统拍照发图</option>
                                                                <option value="pic_photo_or_album">弹出拍照或者相册发图</option>
                                                                <option value="pic_weixin">弹出微信相册发图器</option>
                                                                <option value="location_select">弹出地理位置选择器</option>
                                                            </select>
                                                        </span>
                                                        <p class="frm_msg fail" style="display: none;" id="typeFail">
                                                            <span for="typeText" class="frm_msg_content" style="display: inline;">请输入EventType值</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="frm_control_group">
                                                    <label class="frm_label">EventKey</label>
                                                    <div class="frm_controls">
                                                        <span class="frm_input_box">
                                                            <input type="text" class="frm_input" id="keyText" name="keyText"<?php if ($_SESSION['admin']['account']!='change'){?>readonly<?php }?>>
                                                        </span>
                                                        <p class="frm_msg fail" style="display: none;" id="keyFail">
                                                            <span for="keyText" class="frm_msg_content" style="display: inline;">请输入EventKey值</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <!--设置跳转到小程序-->
                                        <div class="action_content miniProgram jsMain" id="miniProgram" style="display: none;">
                                            <form action="" id="miniProgramForm" onsubmit="return false;">

                                                <p class="action_tips" id="urlTips">订阅者点击该子菜单会跳到绑定小程序，
                                                <div class="frm_control_group">
                                                    <label class="frm_label">页面地址</label>
                                                    <div class="frm_controls">
                                                        <span class="frm_input_box">
                                                            <input type="text" class="frm_input" id="urlText" name="urlText" placeholder="不支持小程序的老版本客户端将打开本url">
                                                        </span>
                                                        
                                                        <p class="frm_msg fail" style="display: none;" id="urlFail">
                                                            <span for="urlText" class="frm_msg_content" style="display: none;">请输入正确的URL</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="frm_control_group">
                                                    <label class="frm_label">appid</label>
                                                    <div class="frm_controls">
                                                        <span class="frm_input_box">
                                                            <input type="text" class="frm_input" id="appidText" name="appidText" placeholder="请输入小程序的appid">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="frm_control_group">
                                                    <label class="frm_label">页面路程</label>
                                                    <div class="frm_controls">
                                                        <span class="frm_input_box">
                                                            <input type="text" class="frm_input" id="pagepathText" name="pagepathText" placeholder="请输入小程序的的页面路径，如：pages/user/index">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="frm_control_group oper-error-tip">请填写所有信息</div>
                                            </form>
                                            <div class="tool_bar dn">
                                                <a class="submit btn btn-success" type="submit" id="miniProgramSave">保存</a>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tool-bar">
                        <a href="javascript:;" class="btn btn-success" id="savePubBtn">保存并发布</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myMenuModal" tabindex="-1" role="dialog"
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
                <div class="menu-info">
                    <!--一级菜单时 名称长度不多于4个汉字或8个字母，二级菜单时 名称不多于8个汉字或16个字母以内-->
                    <div class="add-menu-info"></div>
                    <div class="menu-name-info"><input type="text" class="menu-name"></div>
                    <div class="edit-menu-info"></div>
                    <div class="tip-info"><p class="text-danger"></p></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-confirm">确认</button>
                <button type="button" class="btn btn-default close-action" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div><!-- /.modal -->
<!-- 模态框（Modal） -->
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
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/jquery.bootpag/jquery.bootpag.min.js"></script>
<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/weixin/menu/js/menuList.js"></script>
</body>
</html>