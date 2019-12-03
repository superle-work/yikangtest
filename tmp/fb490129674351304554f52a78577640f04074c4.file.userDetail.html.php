<?php /* Smarty version Smarty-3.0.8, created on 2019-12-02 02:41:17
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/base/user/page/userDetail.html" */ ?>
<?php /*%%SmartyHeaderCode:179635de479cd462de5-12412049%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb490129674351304554f52a78577640f04074c4' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/base/user/page/userDetail.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '179635de479cd462de5-12412049',
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
<!---------------- 用户详情 ------------------->

<!-- 页头 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!-- jsfiles -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
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
</i>用户详情</h3>
            <div class="inner-section row">
                <div class="items add-div">
                    <form id="myForm" action="#" method="post" enctype="multipart/form-data">
                        <div class="item table-responsive">
                            <table class="table table-striped table-hover table-bordered table-base">
                                <tbody>
                                    <tr>
                                        <td>账号<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                        	<?php echo $_smarty_tpl->getVariable('userInfo')->value['account'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>头像<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <img height="30" src="<?php echo $_smarty_tpl->getVariable('userInfo')->value['head_img_url'];?>
">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>昵称<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                        	<?php echo $_smarty_tpl->getVariable('userInfo')->value['nick_name'];?>

                                        </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>性别<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php if ($_smarty_tpl->getVariable('userInfo')->value['sex']==1){?>男
                                        	<?php }elseif($_smarty_tpl->getVariable('userInfo')->value['sex']==2){?>女
                                        	<?php }elseif($_smarty_tpl->getVariable('userInfo')->value['sex']==0){?>未知
                                        	<?php }?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>地址<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                        	<?php echo $_smarty_tpl->getVariable('userInfo')->value['country'];?>
-<?php echo $_smarty_tpl->getVariable('userInfo')->value['province'];?>
--<?php echo $_smarty_tpl->getVariable('userInfo')->value['city'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>生日<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('userInfo')->value['birthday'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>关注时间<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('userInfo')->value['subscribe_time'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                     <tr>
                                        <td>是否关注<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php if ($_smarty_tpl->getVariable('userInfo')->value['subscribe']==1){?>已关注
                                        	<?php }elseif($_smarty_tpl->getVariable('userInfo')->value['subscribe']==1){?>未关注
                                        	<?php }?>
                                        </td>
                                        <td></td>
                                    </tr>
                                     <tr>
                                        <td>关注次数<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                        	<?php echo $_smarty_tpl->getVariable('userInfo')->value['subscribe_times'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr style="display:none;">
                                        <td>积分<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('userInfo')->value['points'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>手机号<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('userInfo')->value['phone'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td>最后登录时间<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('userInfo')->value['last_login'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>添加时间<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('userInfo')->value['add_time'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('userInfo')->value['id'];?>
">
                                            <a href="javascript:window.history.go(-1);" class="btn btn-default">返回</a>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 页脚 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
</body>
</html>