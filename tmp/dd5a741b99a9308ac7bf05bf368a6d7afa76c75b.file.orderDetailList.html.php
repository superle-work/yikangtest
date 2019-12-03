<?php /* Smarty version Smarty-3.0.8, created on 2019-12-02 02:31:39
         compiled from "C:\phpStudy\PHPTutorial\WWW\yxj/template\../template/admin/default/store/page/order/orderDetailList.html" */ ?>
<?php /*%%SmartyHeaderCode:104095de4778b772530-42004294%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dd5a741b99a9308ac7bf05bf368a6d7afa76c75b' => 
    array (
      0 => 'C:\\phpStudy\\PHPTutorial\\WWW\\yxj/template\\../template/admin/default/store/page/order/orderDetailList.html',
      1 => 1573626494,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '104095de4778b772530-42004294',
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
/store/css/order/orderDetailList.css" />

<body>

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

            <h3><i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i>订单商品列表

                <div class="operation-div">

                    <a href="javascript:;" data-oid="<?php echo $_smarty_tpl->getVariable('orderInfo')->value['id'];?>
" id ='export_btn' class="btn btn-success">导出详情</a><a class="btn btn-default" href="javascript:window.history.go(-1);">返回</a>

                </div>

            </h3>

            <div class="inner-section row">

                <div id="content">

                    <div class="list-title">

                        <div class="list-title-panel">

                            <span class="glyphicon glyphicon-list"></span>列表</span>

                        </div>

                    </div>

                    <div class="table-content" id = "list_container">

                        <table id="list-table" class="list-table table-hover table-bordered" cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

                            <tbody>

                                <tr>

                                    <th class="th2" colspan="2">序号</th>

                                    <th class="th3">商品名称</th>

                                    <th class="th5">数量</th>

                                    <th class="th6">单价</th>

                                </tr>

                                <?php  $_smarty_tpl->tpl_vars['goods'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('orderInfo')->value['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['goodsList']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['goods']->key => $_smarty_tpl->tpl_vars['goods']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['goodsList']['iteration']++;
?>

                                <tr <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['goodsList']['iteration']%2==0){?>class="even"<?php }else{ ?>class="odd"<?php }?>>

                                    <td colspan="2"><?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['goodsList']['iteration'];?>
</td>

                                    <td><?php echo $_smarty_tpl->tpl_vars['goods']->value['goods_name'];?>
</td>

                                    <td><?php echo $_smarty_tpl->tpl_vars['goods']->value['count'];?>
</td>

                                    <td><?php echo $_smarty_tpl->tpl_vars['goods']->value['price'];?>
</td>

                                </tr>

                                <?php }} ?>

                                <tr>

									<td colspan="5" style="color: #FF5C00">

										<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']=='0'){?>待付款

                                        <?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['state']=='1'){?>待采样

                                        <?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['state']=='2'){?>待送检

                                        <?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['state']=='3'){?>送检中

                                        <?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['state']=='4'){?>检测中

                                        <?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['state']=='5'){?>已完成

                                        <?php }?>

                                   </td>

								</tr>

								<tr>

									<td colspan="2">推荐诊所</td>

									<td colspan="3">

										<?php if ($_smarty_tpl->getVariable('recommendClinic')->value){?>

										<?php echo $_smarty_tpl->getVariable('recommendClinic')->value;?>


										<?php }else{ ?>

										---

										<?php }?>

									</td>

								</tr>

								<tr>

								<?php if ($_smarty_tpl->getVariable('clinicInfo')->value){?>

								</tr>

									<td colspan="2">诊所详情</td>

									<td colspan="3">

										<span>诊所名称：<?php echo $_smarty_tpl->getVariable('clinicInfo')->value['name'];?>
</span>

										<span>核销人员名称：<?php echo $_smarty_tpl->getVariable('orderInfo')->value['clinic_worker_name'];?>
</span>

									</td>

                                </tr>

                                <?php }?>

                                

                                <?php if ($_smarty_tpl->getVariable('hospitalInfo')->value){?>

								</tr>

									<td colspan="2">医院详情</td>

									<td colspan="3">

										<span>医院名称：<?php echo $_smarty_tpl->getVariable('hospitalInfo')->value['name'];?>
</span>

										<span>核销人员名称：<?php echo $_smarty_tpl->getVariable('orderInfo')->value['hospital_worker_name'];?>
</span>

									</td>

                                </tr>

                                <?php }?>

                                <?php if ($_smarty_tpl->getVariable('orderInfo')->value['logistics_worker_name']){?>

                                <tr>

                                	<td colspan="2">物流详情</td>

									<td colspan="3">

										<span>核销人员名称：<?php echo $_smarty_tpl->getVariable('orderInfo')->value['logistics_worker_name'];?>
</span>

									</td>

                                </tr>

                                <?php }?>

                                <tr>

                                	<td colspan="5">

                                		<span>下单时间:<?php if ($_smarty_tpl->getVariable('orderInfo')->value['add_time']){?> <?php echo $_smarty_tpl->getVariable('orderInfo')->value['add_time'];?>
 <?php }else{ ?> --- <?php }?></span>

                                		<span>采样时间:<?php if ($_smarty_tpl->getVariable('orderInfo')->value['sample_time']){?> <?php echo $_smarty_tpl->getVariable('orderInfo')->value['sample_time'];?>
 <?php }else{ ?> --- <?php }?></span>

                                		<span>送检时间:<?php if ($_smarty_tpl->getVariable('orderInfo')->value['check_time']){?> <?php echo $_smarty_tpl->getVariable('orderInfo')->value['check_time'];?>
 <?php }else{ ?> --- <?php }?></span>

                                		<span>完成时间:<?php if ($_smarty_tpl->getVariable('orderInfo')->value['end_time']){?> <?php echo $_smarty_tpl->getVariable('orderInfo')->value['end_time'];?>
 <?php }else{ ?> --- <?php }?></span>

                                	</td>

                                </tr>

                                <tr>

                                    <td colspan="5">

                                        <span style="color: #FF5C00">订单金额：<?php echo $_smarty_tpl->getVariable('orderInfo')->value['total_price'];?>
</span>

                                        <span>支付方式：

                                            <?php if ($_smarty_tpl->getVariable('orderInfo')->value['pay_method']=='0'){?>无

                                            <?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['pay_method']==1){?>微信支付

                                            <?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['pay_method']==2){?>支付宝支付

                                            <?php }elseif($_smarty_tpl->getVariable('orderInfo')->value['pay_method']==3){?>货到付款

                                            <?php }?>

                                        </span>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

					<div class="print-button">

						<a href="javascript:window.print();" class="btn btn-sm btn-default">打印</a>

						<?php if ($_smarty_tpl->getVariable('orderInfo')->value['state']!='0'){?>

							<?php if ($_smarty_tpl->getVariable('config')->value['order_ticket_notify']==1){?>

								<a data-oid="<?php echo $_smarty_tpl->getVariable('orderInfo')->value['id'];?>
" data-key="<?php echo $_smarty_tpl->getVariable('config')->value['order_notify_key'];?>
" class="btn btn-sm btn-default send-ticket-notify hidden">小票打印</a>

							<?php }?>

						<?php }?>

					</div>

                    <div class="fn-clear"></div>



                    <!-- 列表底部 -->

                    <div class="list-bottom">

                        <div class="list-bottom-panel">

                        </div>

                    </div>

                </div>

                <!--分页-->

                <div id="page-selection"></div>

            </div>

        </div>

    </div>

</div>



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

<script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/admin/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/store/js/order/orderDetailList.js"></script>

</body>

</html>



