<?php /* Smarty version Smarty-3.0.8, created on 2019-11-23 00:59:56
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/clinic/editPrinter.html" */ ?>
<?php /*%%SmartyHeaderCode:35975dd8848c01b040-30966985%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '242858fa9d362ab98a68144809d836c844a2b346' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/clinic/editPrinter.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '35975dd8848c01b040-30966985',
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

<link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/css/clinic/editClinic.css" />

<body>

<!---------------- 编辑商品 ------------------->

<!-- 页头 -->

<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<!-- jsfiles -->

<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<link href="<?php echo @BASE_PATH;?>
/js/public/umeditor/themes/default/css/umeditor.min.css" type="text/css" rel="stylesheet">

<script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/third-party/template.min.js"></script>

<script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/umeditor.config.js"></script>

<script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/umeditor.min.js"></script>

<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/umeditor/lang/zh-cn/zh-cn.js"></script>

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
</i>编辑诊所</h3>

            <div class="inner-section row">

                <form role="form"  method="post" id="myForm"  enctype="multipart/form-data">

                    <table class="table table-striped table-hover table-bordered table-base">

                        <tr>

                            <td class="td1"><label for="name"><span class="must-tag">*</span>诊所名称</label></td>

                            <td class="td2"><input type="text" id="name" name="name" placeholder="诊所名称" value="<?php echo $_smarty_tpl->getVariable('goods')->value['name'];?>
"></td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label for="imgurl"><span class="must-tag">&nbsp;&nbsp;</span>主图更新</label></td>

                            <td class="td2">

                                <input type="hidden" name="prevurl" value="<?php echo $_smarty_tpl->getVariable('goods')->value['img_url'];?>
">

                                <input class="imgurl form-control"  accept="image/*" value="" title="支持jpg、jpeg、gif、png格式，文件小于5M" tabindex="3" type="file" id="imgurl"name="imgurl" size="3">

                                <img src="<?php echo $_smarty_tpl->getVariable('goods')->value['thumb'];?>
" style="height: 40px;"  title="缩略图" >

                                <!---是否重新上传图片标识 0未更新，1更新图片-->

                                <input type="hidden" id="imgFlag" name="imgFlag" value="0">

                            </td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label><span class="must-tag">&nbsp;&nbsp;</span>轮播图更新</label></td>

                            <td class="td2">

                                <div class="side-image-wrap">

                                    <input type="hidden" id="imgFlag1" name="imgFlag1" value="0" class="imgFlag">

                                    <input type="file" name="sideImg1" class="side-image form-control" accept="image/*" value="" title="支持jpg、jpeg、gif、png格式，文件小于5M">

                                    <?php if ($_smarty_tpl->getVariable('sideImg1')->value){?>

                                    <input type="hidden" name="prevSideImg1" value="<?php echo $_smarty_tpl->getVariable('sideImg1')->value['img_url'];?>
">

                                    <input type="hidden" name="sid1" value="<?php echo $_smarty_tpl->getVariable('sideImg1')->value['id'];?>
">

                                    <img src="<?php echo $_smarty_tpl->getVariable('sideImg1')->value['thumb'];?>
" class="thumb" style="height: 40px;"  title="缩略图" >

                                    <a class="delete-slide-image btn btn-danger btn-xs">删除</a>

                                    <?php }?>

                                </div>



                                <div class="side-image-wrap">

                                    <input type="hidden" id="imgFlag2" name="imgFlag2" value="0" class="imgFlag">

                                    <input type="file" name="sideImg2" class="side-image form-control"  accept="image/*" value="" title="支持jpg、jpeg、gif、png格式，文件小于5M">

                                    <?php if ($_smarty_tpl->getVariable('sideImg2')->value){?>

                                    <input type="hidden" name="prevSideImg2" value="<?php echo $_smarty_tpl->getVariable('sideImg2')->value['img_url'];?>
">

                                    <input type="hidden" name="sid2" value="<?php echo $_smarty_tpl->getVariable('sideImg2')->value['id'];?>
">

                                    <img src="<?php echo $_smarty_tpl->getVariable('sideImg2')->value['thumb'];?>
" class="thumb" style="height: 40px;"  title="缩略图" >

                                    <a class="delete-slide-image btn btn-danger btn-xs">删除</a>

                                    <?php }?>



                                </div>



                                <div class="side-image-wrap">

                                    <input type="hidden" id="imgFlag3" name="imgFlag3" value="0" class="imgFlag">

                                    <input type="file" name="sideImg3" class="side-image form-control"  accept="image/*" value="" title="支持jpg、jpeg、gif、png格式，文件小于5M">

                                    <?php if ($_smarty_tpl->getVariable('sideImg3')->value){?>

                                    <input type="hidden" name="prevSideImg3" value="<?php echo $_smarty_tpl->getVariable('sideImg3')->value['img_url'];?>
">

                                    <input type="hidden" name="sid3" value="<?php echo $_smarty_tpl->getVariable('sideImg3')->value['id'];?>
">

                                    <img src="<?php echo $_smarty_tpl->getVariable('sideImg3')->value['thumb'];?>
" class="thumb" style="height: 40px;"  title="缩略图" >

                                    <a class="delete-slide-image btn btn-danger btn-xs">删除</a>

                                    <?php }?>

                                </div>

                            </td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label for="phone"><span class="must-tag">*</span>电话</label></td>

                            <td class="td2"><input type="text" id="phone" name="phone" placeholder="诊所电话" value="<?php echo $_smarty_tpl->getVariable('goods')->value['phone'];?>
"></td>

                            <td class="td3"></td>

                        </tr>

                        <!--<tr>

                            <td class="td1"><label for="doctor_name"><span class="must-tag">*</span>医生账号名</label></td>

                            <td class="td2">

                                <input type="text" id="doctor_name" name="doctor_name" placeholder="医生账号名" value=""></td>

                            <td class="td3"></td>

                        </tr>  

                        <tr>

                            <td class="td1"><label for="doctor_pwd"><span class="must-tag">*</span>医生密码</label></td>

                            <td class="td2">

                                <input type="text" id="doctor_pwd" name="doctor_pwd" placeholder="医生密码" value=""></td>

                            <td class="td3"></td>

                        </tr>-->

                        

                        <tr>

                            <td class="td1"><label for="clinic_ratio"><span class="must-tag">*</span>诊所采样费用</label></td>

                            <td class="td2">

                                <input type="text" id="clinic_ratio" name="clinic_ratio" placeholder="诊所采样费用" value="<?php echo $_smarty_tpl->getVariable('goods')->value['clinic_ratio'];?>
"></td>

                            <td class="td3"></td>

                        </tr> 

                        <tr>
                            <td class="td1"><label for="sort_num"><span class="must-tag">*</span>排序系数</label></td>
                            <td class="td2">
                                <input type="text" id="sort_num" name="sort_num" placeholder="排序系数" value="<?php echo $_smarty_tpl->getVariable('goods')->value['sort_num'];?>
"></td>
                            <td class="td3"></td>
                        </tr>

                        <tr>

                            <td class="td1"><label for=""><span class="must-tag">*</span>诊所所在区/县</label></td>

                            <td class="td2">

                                <div class="area-info" data-province="<?php echo $_smarty_tpl->getVariable('goods')->value['province'];?>
" data-city="<?php echo $_smarty_tpl->getVariable('goods')->value['city'];?>
" data-area="<?php echo $_smarty_tpl->getVariable('goods')->value['area'];?>
">

		                    		<select id="province" name="province"></select> <select id="city" name="city"></select> <select id="area" name="area"></select>

		                    	</div>

                            </td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label for="where"><span class="must-tag">*</span>诊所位置</label></td>

                            <td class="td2">

                            	<div class="map-search-row">

                            		<input class="keyword" type="text" name="address" class="form-control" placeholder="请输入地址查找(或点击拾取)" value="<?php echo $_smarty_tpl->getVariable('goods')->value['address'];?>
"><a class="search-keyword btn btn-success btn-sm" >搜索</a>

                            	</div>

                            	<input type="hidden" name='longitude' id='longitude' value="<?php echo $_smarty_tpl->getVariable('goods')->value['longitude'];?>
">

                            	<input type="hidden" name='latitude' id='latitude' value="<?php echo $_smarty_tpl->getVariable('goods')->value['latitude'];?>
">

                            	<div class="where" id="allmap">

                            	</div>

                            	

                            </td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1">

                                <label for="detaildesc"><span class="must-tag">*</span>诊所描述</label>

                            </td>

                            <td class="td2">

                                <!-- umeditor -->

                                <script type="text/plain" id="detaildesc" name="detail_desc" style="width:780px;height:200px;"><?php echo $_smarty_tpl->getVariable('goods')->value['detail_desc'];?>
</script>

                                <script type="text/javascript">

                                    //实例化编辑器

                                    var um = UM.getEditor('detaildesc');

                                </script>

                                <!-- /umeditor -->

                            </td>

                            <td></td>

                        </tr>

                        <tr>

                            <td>



                            </td>

                            <td>

                                <input type="hidden" id='id' name="id" value="<?php echo $_smarty_tpl->getVariable('goods')->value['id'];?>
">

                                <button type="button" class="btn btn-success btn-md" id='save'>提交</button>

                                <button type="button" class="btn btn-default btn-md" id="back">返回</button>

                            </td>

                            <td></td>

                        </tr>

                    </table>



                </form>

            </div>

        </div>

    </div>

</div>





<!-- 页脚 -->

<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=E8585e29607347015477b67178b530ab"></script> 

<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.form.js"></script>

<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.js"></script>

<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.extend.js"></script>

<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/base/school/js/address.js"></script>

<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/clinic/editClinic.js"></script>

</body>

</html>



