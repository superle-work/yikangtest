<?php /* Smarty version Smarty-3.0.8, created on 2019-11-20 06:37:36
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/goods/addGoods.html" */ ?>
<?php /*%%SmartyHeaderCode:266245dd4df3020df77-31496808%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f0dbef1b7fbcd9beec2ddce10c4b80bbb2afa0d1' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/goods/addGoods.html',
      1 => 1574231837,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '266245dd4df3020df77-31496808',
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
/store/css/goods/addGoods.css" />

<body>

<!---------------- 添加商品 ------------------->

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
</i>添加商品</h3>

            <div class="inner-section row">

                <div class="items add-div">

                    <form action="#" method="post" id="myForm"  enctype="multipart/form-data">

                        <table class="table table-striped table-hover table-bordered table-base">

                            <tr>

                                <td class="td1"><label>分类</label></td>

                                <td class="td2">

                                	<div class="category-list row">

                                		<?php  $_smarty_tpl->tpl_vars['cate'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('cateList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cate']->key => $_smarty_tpl->tpl_vars['cate']->value){
?>

                                			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 category"><?php echo $_smarty_tpl->tpl_vars['cate']->value['name'];?>
</div>

                                		<?php }} ?>

                                			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 category"><a href="javascript:window.history.go(-1)">修改</a></div>

                                	</div>

                                </td>

                                <td class="td3"></td>

                           	</tr>

                           	<tr>

                                <td class="td1"><label for="good_type"><span class="must-tag">*</span>商品类别</label></td>

                                <td class="td2">

                                	<select name="good_type" id="good_type">

                                		<option value="">--请选择--</option>

                                		<option value="1">本地医院</option>

                                		<option value="2">第三方医院</option>

                                	</select>

                                </td>

                                <td class="td3"></td>

                            </tr>

                            <tr>
                                <td class="td1">
                                    <label>选择区域</label>
                                </td>
                                <td class="td2">
                                    <div class="layui-form-item" id="addressDiv">
                                        <label class="layui-form-label">管理区域</label>
                                        <div class="layui-input-inline">
                                            <select name="province" lay-filter="province" id="province">
                                                <option ></option>
                                            </select> 省
                                        </div>
                                        <div class="layui-input-inline">
                                            <select name="city" lay-filter="city" id="city">
                                                <option></option>
                                            </select> 市
                                        </div>
                                        <div class="layui-input-inline">
                                            <select name="area" lay-filter="area" id="area">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>

                                <td class="td1"><label for="name"><span class="must-tag">*</span>商品名称</label></td>

                                <td class="td2"><input type="text" id="name" name="name" placeholder="商品名称"></td>

                                <td class="td3"></td>

                            </tr>

                            <!-- <tr>

                                <td class="td1"><label for="imgurl"><span class="must-tag">*</span>主图上传</label></td>

                                <td class="td2"><input class="imgurl form-control" title="支持jpg、jpeg、gif、png格式，文件小于5M" tabindex="3" type="file"  accept="image/*" id="imgurl" name="imgurl" size="3"></td>

                                <td class="td3"></td>

                            </tr> -->

                            <tr>

                                <td class="td1"><label>轮播图上传</label></td>

                                <td class="td2">

                                    <input class="side-image form-control" title="支持jpg、jpeg、gif、png格式，文件小于5M" type="file" name="sideImg1">

                                    <input class="side-image form-control" title="支持jpg、jpeg、gif、png格式，文件小于5M" type="file" name="sideImg2">

                                    <input class="side-image form-control" title="支持jpg、jpeg、gif、png格式，文件小于5M" type="file"  name="sideImg3">

                                </td>

                                <td class="td3"></td>

                            </tr>

                            <tr>

                                <td class="td1"><label for="ori_price"><span class="must-tag">*</span>原价</label></td>

                                <td class="td2">
                                    <input type="text" id="ori_price" name="ori_price" placeholder="原价">
                                </td>

                                <td class="td3"></td>

                            </tr>
                            <tr>

                                <td class="td1"><label><span class="must-tag">*</span>优惠比例</label></td>

                                <td class="td2">
                                    <input type="text" id="discount" name="discount" placeholder="如填80，即按照原价的80%计算优惠价" >%
                                </td>

                                <td class="td3"></td>

                            </tr>
                            <tr>

                                <td class="td1"><label for="price"><span class="must-tag">*</span>优惠价</label></td>

                                <td class="td2"><input type="text" id="price" name="price" placeholder="优惠价"></td>

                                <td class="td3"></td>

                            </tr>

                            <tr>

                                <td class="td1"><label for="sample_vessel"><span class="must-tag">*</span>采样容器</label></td>

                                <td class="td2"><input type="text" id="sample_vessel" name="sample_vessel" placeholder="采样容器"></td>

                                <td class="td3"></td>

                            </tr>

                            <tr>

                                <td class="td1"><label for="time_length"><span class="must-tag">*</span>时长</label></td>

                                <td class="td2"><input type="text" id="time_length" name="time_length" placeholder="时长"></td>

                                <td class="td3"></td>

                            </tr>

                            <tr>

                                <td class="td1"><label for="apply"><span class="must-tag">*</span>适用</label></td>

                                <td class="td2"><input type="text" id="apply" name="apply" placeholder="适用"></td>

                                <td class="td3"></td>

                            </tr>

                            <tr>
                                <td class="td1"><label for="sort_num"><span class="must-tag">*</span>排序系数</label></td>

                                <td class="td2"><input type="text" id="sort_num" name="sort_num" placeholder="排序系数"></td>

                                <td class="td3"></td>
                            </tr>

                            <tr>

                                <td class="td1"><label><span class="must-tag">*</span>商品状态</label></td>

                                <td class="td2">

                                    <label> <input type="radio" name="updown" value="1" checked> 上架</label>

                                    <label> <input type="radio" name="updown" value="2">下架</label>

                                </td>

                                <td class="td3"></td>

                            </tr>

                            <tr>

                                <td class="td1"><label><span class="must-tag">*</span>首页推荐</label></td>

                                <td class="td2">

                                    <label> <input type="radio" name="recommend" value="0" checked>不推荐</label>

                                    <label> <input type="radio" name="recommend" value="1">推荐</label>

                                </td>

                                <td class="td3"></td>

                            </tr>

                            <tr>

                                <td class="td1">

                                    <label for="detaildesc"><span class="must-tag">*</span>详情描述</label>

                                </td>

                                <td class="td2">

                                    <!-- umeditor -->

                                    <script type="text/plain" id="detaildesc" name="detail_desc" style="width:780px;height:200px;"></script>

                                    <script type="text/javascript">

                                        //实例化编辑器

                                        var um = UM.getEditor('detaildesc');

                                    </script>

                                    <!-- /umeditor -->

                                </td>

                                <td class="td3"></td>

                            </tr>

                            <tr>

                                <td>

									<input type="hidden" name="cids" id="cids" class="cids" value="<?php echo $_smarty_tpl->getVariable('cids')->value;?>
">

                                </td>

                                <td><button type="button" class="btn btn-success btn-md" id='save'>提交</button></td>

                                <td></td>

                            </tr>

                        </table>



                    </form>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- 页脚 -->

<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>



<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/citys.js"></script>

<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.form.js"></script>

<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.js"></script>

<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.extend.js"></script>

<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/goods/addGoods.js"></script>

</body>

</html>