<?php /* Smarty version Smarty-3.0.8, created on 2019-11-29 06:32:41
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/goods/goodsDetail.html" */ ?>
<?php /*%%SmartyHeaderCode:110515de0bb89360383-30700593%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '29226408ff7ff22a6db920061030fc51d41b08de' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/goods/goodsDetail.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '110515de0bb89360383-30700593',
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
<!---------------- 商品详情 ------------------->

<!-- 页头 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!-- jsfiles -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<link href="<?php echo @BASE_PATH;?>
/include/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/include/umeditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo @BASE_PATH;?>
/include/umeditor/umeditor.min.js"></script>
<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/include/umeditor/lang/zh-cn/zh-cn.js"></script>
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
</i>商品详情</h3>
            <div class="inner-section row">
                <div class="items add-div">
                    <form id="myForm" action="#" method="post" enctype="multipart/form-data">
                        <div class="item table-responsive">
                            <table class="table table-striped table-hover table-bordered table-base">
                                <tbody>
                                    <tr>
                                        <td>商品名称<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['name'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>商品图片<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <img src="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['thumb'];?>
" height="30">
                                        </td>
                                        <td></td>
                                    </tr>
									<tr>
                                        <td>商品编号<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['number'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>商品销量<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['sale_quantity'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>状态<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php if ($_smarty_tpl->getVariable('goodsInfo')->value['updown']=='0'){?>
			                       			已下架
			                       			<?php }elseif($_smarty_tpl->getVariable('goodsInfo')->value['updown']=='1'){?>
			                       			已上架
			                       			<?php }?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>商品价格<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['price'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>首页推荐<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php if ($_smarty_tpl->getVariable('goodsInfo')->value['recommend']=='0'){?>
			                       			不推荐
			                       			<?php }elseif($_smarty_tpl->getVariable('goodsInfo')->value['recommend']=='1'){?>
			                       			推荐
			                       			<?php }?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>商品类别<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php if ($_smarty_tpl->getVariable('goodsInfo')->value['good_type']=='1'){?>
			                       			本地医院
			                       			<?php }elseif($_smarty_tpl->getVariable('goodsInfo')->value['good_type']=='2'){?>
			                       			第三方医院
			                       			<?php }?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>时长<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['time_length'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>适用<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                            <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['apply'];?>

                                        </td>
                                        <td></td>
                                    </tr>
			                        <tr>
			                            <td>详情描述<label class="must-tag">&nbsp;&nbsp;</label></td>
			                            <td>
			                                <div>
			                                	<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['detail_desc'];?>

			                                </div>
			                            </td>
			                            <td></td>
			                        </tr>
		                       		<tr>
		                       			<td>添加时间<label class="must-tag">&nbsp;&nbsp;</label></td>
		                       			<td>
		                       			<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['add_time'];?>

		                       			</td>
		                       			<td></td>
		                       		</tr>
                                    <tr>
                                        <td>创建者<label class="must-tag">&nbsp;&nbsp;</label></td>
                                        <td>
                                        <?php echo $_smarty_tpl->getVariable('goodsInfo')->value['creator'];?>

                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                            <input type="hidden" name="id" value="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['id'];?>
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