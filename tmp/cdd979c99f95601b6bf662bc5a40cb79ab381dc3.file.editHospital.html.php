<?php /* Smarty version Smarty-3.0.8, created on 2019-09-28 14:52:32
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/hospital/editHospital.html" */ ?>
<?php /*%%SmartyHeaderCode:20225032765d8f03308cf949-49576042%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cdd979c99f95601b6bf662bc5a40cb79ab381dc3' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/hospital/editHospital.html',
      1 => 1537352298,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20225032765d8f03308cf949-49576042',
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
/store/css/hospital/editHospital.css" />
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
</i>编辑医院</h3>
            <div class="inner-section row">
                <form role="form"  method="post" id="myForm"  enctype="multipart/form-data">
                    <table class="table table-striped table-hover table-bordered table-base">
                        <tr>
                            <td class="td1"><label for="name"><span class="must-tag">*</span>医院名称</label></td>
                            <td class="td2"><input type="text" id="name" name="name" placeholder="医院名称" value="<?php echo $_smarty_tpl->getVariable('goods')->value['name'];?>
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
                            <td class="td1"><label for="phone"><span class="must-tag">*</span>电话</label></td>
                            <td class="td2"><input type="text" id="phone" name="phone" placeholder="医院电话" value="<?php echo $_smarty_tpl->getVariable('goods')->value['phone'];?>
"></td>
                            <td class="td3"></td>
                        </tr>
                        <tr>
                            <td class="td1"><label for="hospital_ratio"><span class="must-tag">*</span>医院费用比例</label></td>
                            <td class="td2">
                                <input type="text" id="hospital_ratio" name="hospital_ratio" placeholder="医院费用比例" value="<?php echo $_smarty_tpl->getVariable('goods')->value['hospital_ratio'];?>
"> %</td>
                            <td class="td3"></td>
                        </tr> 
                        <tr>
                            <td class="td1"><label for="where"><span class="must-tag">*</span>医院位置</label></td>
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
                                <label for="detaildesc"><span class="must-tag">*</span>医院描述</label>
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
                            <td></td>
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
/store/js/hospital/editHospital.js"></script>
</body>
</html>
