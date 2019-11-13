<?php /* Smarty version Smarty-3.0.8, created on 2019-09-30 19:33:04
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/goods/editGoods.html" */ ?>
<?php /*%%SmartyHeaderCode:3811612955d91e7f048fab2-47060543%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '05faa5e778441c11fffe4739f919cbaf22547dd9' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/goods/editGoods.html',
      1 => 1542339512,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3811612955d91e7f048fab2-47060543',
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
/store/css/goods/editGoods.css" />

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
</i>编辑商品</h3>

            <div class="inner-section row">

                <form role="form"  method="post" id="myForm"  enctype="multipart/form-data">

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

                            	</div>

                            </td>

                            <td class="td3"></td>

                        </tr>

                    	<tr>

                            <td class="td1"><label for="good_type"><span class="must-tag">*</span>商品类别</label></td>

                            <td class="td2">

                            	<select name="good_type" id="good_type">

                            		<option value="">--请选择--</option>

                            		<option value="1" <?php if ($_smarty_tpl->getVariable('goods')->value['good_type']==1){?>selected<?php }?> >本地医院</option>

                            		<option value="2" <?php if ($_smarty_tpl->getVariable('goods')->value['good_type']==2){?>selected<?php }?> >第三方医院</option>

                            	</select>

                            </td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label><span class="must-tag">*</span>商品名称</label></td>

                            <td class="td2"><input type="text" value="<?php echo $_smarty_tpl->getVariable('goods')->value['name'];?>
" id="name" name="name" placeholder="商品名称"></td>

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

                            <td class="td1"><label for="ori_price"><span class="must-tag">*</span>原价</label></td>

                            <td class="td2"><input type="text" id="ori_price" name="ori_price" placeholder="原价" value="<?php echo $_smarty_tpl->getVariable('goods')->value['ori_price'];?>
"></td>

                            <td class="td3"></td>

                        </tr>
                        <tr>
                            <td class="td1"><label><span class="must-tag">*</span>优惠比例</label></td>

                            <td class="td2">
                                <input type="text" id="discount" name="discount" placeholder="如填80，即按照原价的80%计算优惠价" value="<?php echo $_smarty_tpl->getVariable('goods')->value['discount'];?>
">%
                            </td>

                            <td class="td3"></td>
                        </tr>

                        <tr>

                            <td class="td1"><label for="price"><span class="must-tag">*</span>优惠价</label></td>

                            <td class="td2"><input type="text" id="price" name="price" placeholder="优惠价" value="<?php echo $_smarty_tpl->getVariable('goods')->value['price'];?>
"></td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label for="sample_vessel"><span class="must-tag">*</span>采样容器</label></td>

                            <td class="td2"><input type="text" id="sample_vessel" name="sample_vessel" placeholder="采样容器" value="<?php echo $_smarty_tpl->getVariable('goods')->value['sample_vessel'];?>
"></td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label for="time_length"><span class="must-tag">*</span>时长</label></td>

                            <td class="td2"><input type="text" id="time_length" name="time_length" placeholder="时长" value="<?php echo $_smarty_tpl->getVariable('goods')->value['time_length'];?>
"></td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label for="apply"><span class="must-tag">*</span>适用</label></td>

                            <td class="td2"><input type="text" id="apply" name="apply" placeholder="适用" value="<?php echo $_smarty_tpl->getVariable('goods')->value['apply'];?>
"></td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label for="salequantity"><span class="must-tag">*</span>商品销量</label></td>

                            <td class="td2"><input type="text" id="salequantity" name="sale_quantity"

                                                   placeholder="商品销量" value="<?php echo $_smarty_tpl->getVariable('goods')->value['sale_quantity'];?>
"></td>

                            <td class="td3"></td>

                        </tr>
                        
                        <tr>
                            <td class="td1"><label for="sort_num"><span class="must-tag">*</span>排序系数</label></td>

                            <td class="td2"><input type="text" id="sort_num" name="sort_num" placeholder="排序系数" value="<?php echo $_smarty_tpl->getVariable('goods')->value['sort_num'];?>
"></td>

                            <td class="td3"></td>
                        </tr>

                        <tr>

                            <td class="td1"><label><span class="must-tag">*</span>商品状态</label></td>

                            <td class="td2">

                                <label> <input type="radio" name="updown" value="1" <?php if ($_smarty_tpl->getVariable('goods')->value['updown']=='1'){?>checked<?php }?>> 上架</label>

                                <label> <input type="radio" name="updown" value="0"  <?php if ($_smarty_tpl->getVariable('goods')->value['updown']=='0'){?>checked<?php }?>>下架</label>

                            </td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1"><label><span class="must-tag">*</span>首页推荐</label></td>

                            <td class="td2">

                                <label> <input type="radio" name="recommend" value="0" <?php if ($_smarty_tpl->getVariable('goods')->value['recommend']=='0'){?>checked<?php }?>> 不推荐</label>

                                <label> <input type="radio" name="recommend" value="1"  <?php if ($_smarty_tpl->getVariable('goods')->value['recommend']=='1'){?>checked<?php }?>>推荐</label>

                            </td>

                            <td class="td3"></td>

                        </tr>

                        <tr>

                            <td class="td1">

                                <label for="detaildesc"><span class="must-tag">*</span>详情描述</label>

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

                                <input type="hidden" id='addGroups' name="add_groups" value="">

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



<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.form.js"></script>

<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.js"></script>

<script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/form/jquery.validate.extend.js"></script>

<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/goods/editGoods.js"></script>

</body>

</html>



