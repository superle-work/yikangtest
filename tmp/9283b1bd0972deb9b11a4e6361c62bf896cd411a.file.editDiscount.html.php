<?php /* Smarty version Smarty-3.0.8, created on 2019-11-30 07:03:42
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/discount/editDiscount.html" */ ?>
<?php /*%%SmartyHeaderCode:214735de2144ed1e464-70784841%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9283b1bd0972deb9b11a4e6361c62bf896cd411a' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/discount/editDiscount.html',
      1 => 1575097421,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '214735de2144ed1e464-70784841',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html><html><!-- head --><?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/css/discount/editDiscount.css" /><body><!---------------- 编辑商品 -------------------><!-- 页头 --><?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><!-- jsfiles --><?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><link href="<?php echo @BASE_PATH;?>
/js/public/umeditor/themes/default/css/umeditor.min.css" type="text/css" rel="stylesheet"><script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/third-party/template.min.js"></script><script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/umeditor.config.js"></script><script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/umeditor.min.js"></script><script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/lang/zh-cn/zh-cn.js"></script><!-- 主体 --><div class="content container-fluid<?php if ($_smarty_tpl->getVariable('leftBarScale')->value=='1'){?> scale<?php }?>">    <div class="row">        <div class="col-md-2 left-section">            <!-- 左导航菜单 -->            <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/menu.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>        </div>        <div class="col-md-10 right-section">            <!--内容区域-->            <h3><i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i>编辑角色折扣</h3>            <div class="inner-section row">                <form role="form"  method="post" id="myForm"  enctype="multipart/form-data">                    <table class="table table-striped table-hover table-bordered table-base">                        <tr>                            <td class="td1"><label for=""><span class="must-tag">*</span>用户角色</label></td>                            <td class="td2">                                <div class="area-info">                                    <select id="user_type" name="user_type" disabled>                                        <option value="0" <?php if ($_smarty_tpl->getVariable('goods')->value['good_type']==0){?>selected<?php }?> >普通用户</option>                                        <option value="1" <?php if ($_smarty_tpl->getVariable('goods')->value['good_type']==1){?>selected<?php }?> >诊所用户</option>                                        <option value="2" <?php if ($_smarty_tpl->getVariable('goods')->value['good_type']==2){?>selected<?php }?> >医院用户</option>                                        <option value="3" <?php if ($_smarty_tpl->getVariable('goods')->value['good_type']==3){?>selected<?php }?> >物流人员</option>                                    </select>                                </div>                            </td>                            <td class="td3"></td>                        </tr>                        <tr>                            <td class="td1"><label for="discount"><span class="must-tag">*</span>折扣</label></td>                            <td class="td2">                                <input type="text" id="discount" name="discount" value="<?php echo $_smarty_tpl->getVariable('goods')->value['discount'];?>
"></td>                            <td class="td3"></td>                        </tr>                        <tr>                            <td class="td1"><label for="blood_fee">采血费</label></td>                            <td class="td2"><input type="text" id="blood_fee" name="blood_fee" value="<?php echo $_smarty_tpl->getVariable('goods')->value['blood_fee'];?>
" placeholder="采血费"></td>                            <td class="td3"></td>                        </tr>                        <tr>                            <td class="td1"><label for="transport_fee">运输费</label></td>                            <td class="td2"><input type="text" id="transport_fee" name="transport_fee" value="<?php echo $_smarty_tpl->getVariable('goods')->value['transport_fee'];?>
" placeholder="运输费"></td>                            <td class="td3"></td>                        </tr>                        <tr>                            <td>                            </td>                            <td>                                <input type="hidden" id='id' name="id" value="<?php echo $_smarty_tpl->getVariable('goods')->value['id'];?>
">                                <button type="button" class="btn btn-success btn-md" id='save'>提交</button>                                <button type="button" class="btn btn-default btn-md" id="back">返回</button>                            </td>                            <td></td>                        </tr>                    </table>                </form>            </div>        </div>    </div></div><!--选择用户--><div class="modal fade" id="bindLabelDialog"  tabindex="-1" role="dialog"     aria-labelledby="myModalLabel" aria-hidden="true">    <div class="modal-dialog modal-lg">        <div class="modal-content">        </div><!-- /.modal-content -->    </div><!-- /.modal-dialog --></div><!-- /.modal --><!-- 页脚 --><?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?><script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=E8585e29607347015477b67178b530ab"></script> <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.form.js"></script><script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.js"></script><script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.extend.js"></script><script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/base/school/js/address.js"></script><script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/discount/editDiscount.js"></script></body></html>