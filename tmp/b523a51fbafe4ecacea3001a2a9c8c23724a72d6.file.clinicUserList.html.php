<?php /* Smarty version Smarty-3.0.8, created on 2019-09-11 16:24:19
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/clinic/clinicUserList.html" */ ?>
<?php /*%%SmartyHeaderCode:6241233825d78af33e0afd6-96552804%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b523a51fbafe4ecacea3001a2a9c8c23724a72d6' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/store/page/clinic/clinicUserList.html',
      1 => 1543830969,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6241233825d78af33e0afd6-96552804',
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

<!---------------- 商品列表 ------------------->



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

            <h3>

                <i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i>工作人员列表

                <div class="operation-div">

                	<input type="hidden" name="uid" id="uid" value="" data-id="<?php echo $_smarty_tpl->getVariable('id')->value;?>
">

                	<a data-toggle="modal" class="btn btn-primary btn-sm select-goods" href="./admin.php?c=store_clinic&a=showBindUser" data-target="#bindLabelDialog">添加用户</a>

                	

                    <a class="btn btn-default" href="admin.php?c=store_clinic&a=clinicList&mid=31">返回</a>

                </div>

            </h3>

            <div class="inner-section row">

                <div id="content">

                    <div class="list-title">

                        <div class="list-title-panel">

                            <span class="glyphicon glyphicon-list"></span>列表</span>

                        </div>

                    </div>

                    <div class="table-content">

                        <table class="list-table table-hover table-bordered">

                            <tbody>

                            	<tr>

                            		<th width="12%">序号</th>

                            		<th width="12%">头像</th>

                            		<th width="13%">昵称</th>

                            		<th width="13%">手机号</th>

                            		<th width="12%">真实姓名</th>

                            		<th width="13%">邮箱</th>

                            		<th width="13%">地址</th>

                            		<th width="12%">操作</th>

                            	</tr>

                            	

                            	<?php if ($_smarty_tpl->getVariable('userInfo')->value){?>

                        		<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('userInfo')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
                                <tr>

                                    <td><span><?php echo $_smarty_tpl->tpl_vars['k']->value+1;?>
</span></td>

                                    <td><img height="40" src="<?php echo $_smarty_tpl->tpl_vars['val']->value['head_img_url'];?>
"></td>

                                    <td><?php echo $_smarty_tpl->tpl_vars['val']->value['nick_name'];?>
</td>

                                    <td><?php echo $_smarty_tpl->tpl_vars['val']->value['phone'];?>
</td>

                                    <td><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</td>

                                    <td><?php echo $_smarty_tpl->tpl_vars['val']->value['email'];?>
</td>

                                    <td>

                                        <?php if ($_smarty_tpl->tpl_vars['val']->value['address']){?>

                                        <?php echo $_smarty_tpl->tpl_vars['val']->value['address'];?>


                                        <?php }else{ ?>

                                        <?php echo $_smarty_tpl->tpl_vars['val']->value['country'];?>
 <?php echo $_smarty_tpl->tpl_vars['val']->value['province'];?>
 <?php echo $_smarty_tpl->tpl_vars['val']->value['city'];?>


                                        <?php }?>

                                    </td>

                                    <td>

                                        <a href="javascript:;" class="btn btn-default btn-xs delete" data-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
">删除</a>

                                    </td>

                                </tr>
                                <?php }} ?>

                                <?php }else{ ?>

                               	<tr>

                               		<td colspan="8" align="center" style="color:red;font-size:15px;">无数据，请添加人员！</td>

                               	</tr>

                               <?php }?>

                            </tbody>

                        </table>

                    </div>

                    <!--分页-->

                    <!--<div id="page-selection"></div>



                    <div class="fn-clear"></div>-->



                </div>

            </div>

        </div>

    </div>

</div>



<!--选择用户-->

<div class="modal fade" id="bindLabelDialog"  tabindex="-1" role="dialog"

     aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">





        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div><!-- /.modal -->



<!-- 页脚 -->

<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/footer.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>



<!-- jsfiles -->

<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

<script src="<?php echo @BASE_PATH;?>
/js/public/My97DatePicker/WdatePicker.js"></script>

<script type="text/javascript" src="<?php echo @BASE_PATH;?>
/js/public/jquery.bootpag/jquery.bootpag.min.js"></script>



<script>

	$(".delete").click(function(){

		var id=$("#uid").attr("data-id");

		var uid=$(this).attr("data-id");

		if(id && uid){

			$.ajax({

				type:'post',

				url:'admin.php?c=store_clinic&a=deleteUser',

				data:{

					'id':id,

					'uid':uid,

				},

				dataType:'json',

				success:function(res){

					if(res.errorCode==0){

						location.href="admin.php?c=store_clinic&a=showUser&id="+res.data.id+"&uid="+res.data.uid;

					}

				}

			})

		}

	})

	

</script>

</body>

</html>