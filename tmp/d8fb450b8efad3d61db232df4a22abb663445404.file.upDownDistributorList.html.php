<?php /* Smarty version Smarty-3.0.8, created on 2019-09-28 15:21:10
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/fen/page/distributor/upDownDistributorList.html" */ ?>
<?php /*%%SmartyHeaderCode:12825382395d8f09e682eb05-94444360%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd8fb450b8efad3d61db232df4a22abb663445404' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/admin/default/fen/page/distributor/upDownDistributorList.html',
      1 => 1535701988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12825382395d8f09e682eb05-94444360',
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
/fen/css/distributor/upDownDistributorList.css">
<body>
<!---------------- 分销商申请列表 ------------------->

<!-- 页头 -->
<?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/header.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
<!-- 主体 -->
<div class="content container-fluid<?php if ($_SESSION['admin']['left_bar_scale']=='1'){?> scale<?php }?>">
    <div class="row">
        <div class="col-md-2 left-section">
            <!-- 左导航菜单 -->
            <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/admin/".($_smarty_tpl->getVariable('theme')->value)."/common/page/menu.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

        </div>
        <div class="col-md-10 right-section">
            <!--内容区域-->
            <h3> <i class="icon iconfont"><?php echo $_SESSION['currentMenu']['icon'];?>
</i>关联分销商
            <div class="operation-div">
                    <a class="btn btn-success" href="javascript:window.history.go(-1);">返回</a>
            </div>
            </h3>
            <div class="inner-section row">
                <div class="list-title">
                        <div class="list-title-panel">
                            <span class="glyphicon glyphicon-list"> 当前分销商：<?php echo $_smarty_tpl->getVariable('distributorInfo')->value['name'];?>
</span>
                        </div>
                </div>
                <div class="item table-responsive">
                    <table id="list-table" class="list-table table table-striped table-hover table-bordered table-base applylist">
                        <tbody>
                        <tr><th class="th1">序号</th><th class="th12">分销商名称</th><th class="th3">昵称</th><th class="th4">电话</th><th class="th5">销售总额</th><th class="th5">累计佣金</th><th class="th5">可提佣金</th><th class="th5">冻结佣金</th><th class="th5">一级佣金</th><th class="th5">二级佣金</th><th class="th5">三级佣金</th><th class="th6">关联级别</th><th class="th2" hidden="hidden">身份</th><th class="th2">状态</th><th class="th8">操作</th></tr>
                        	<?php  $_smarty_tpl->tpl_vars['distributorList'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('upDownDistributorList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['test']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['distributorList']->key => $_smarty_tpl->tpl_vars['distributorList']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['test']['iteration']++;
?>
                        	<tr>
                        		<td><?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['test']['iteration'];?>
</td>
                        		<td><a class="limitName" title="查看分销商详情" href="./admin.php?c=fen_distributor&a=distributorInfo&id=<?php echo $_smarty_tpl->tpl_vars['distributorList']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['name'];?>
</a></td>
                        		<td><a href="./admin.php?c=base_user&a=userDetail&id=<?php echo $_smarty_tpl->tpl_vars['distributorList']->value['user_id'];?>
" class="limitName" title="查看用户详情"><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['nick_name'];?>
</a></td>
                        		<td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['distributorList']->value['phone'])===null||$tmp==='' ? '--' : $tmp);?>
</td>
                        		<td><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['total_sales_fee'];?>
</td>
                        		<td><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['total_fee'];?>
</td>
                        		<td><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['my_fee'];?>
</td>
                        		<td><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['freeze_fee'];?>
</td>
                        		<td><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['fir_fee'];?>
</td>
                        		<td><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['sec_fee'];?>
</td>
                        		<td><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['thr_fee'];?>
</td>
                        		<td>
                        			<?php if ($_smarty_tpl->tpl_vars['distributorList']->value['rank_name']=='上三级'){?>
                        			<span style="color:#FF1493"><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['rank_name'];?>
</span>
                        			<?php }elseif($_smarty_tpl->tpl_vars['distributorList']->value['rank_name']=='上二级'){?>
                        			<span style="color:#FF69B4"><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['rank_name'];?>
</span>
                        			<?php }elseif($_smarty_tpl->tpl_vars['distributorList']->value['rank_name']=='上一级'){?>
                        			<span style="color:	#F08080"><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['rank_name'];?>
</span>
                        			<?php }elseif($_smarty_tpl->tpl_vars['distributorList']->value['rank_name']=='本级'){?>
                        			<span style="color:red"><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['rank_name'];?>
</span>
                        			<?php }elseif($_smarty_tpl->tpl_vars['distributorList']->value['rank_name']=='下一级'){?>
                        			<span style="color:#3CB371"><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['rank_name'];?>
</span>
                        			<?php }elseif($_smarty_tpl->tpl_vars['distributorList']->value['rank_name']=='下二级'){?>
                        			<span style="color:	#00FA9A"><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['rank_name'];?>
</span>
                        			<?php }elseif($_smarty_tpl->tpl_vars['distributorList']->value['rank_name']=='下三级'){?>
                        			<span style="color:#48D1CC"><?php echo $_smarty_tpl->tpl_vars['distributorList']->value['rank_name'];?>
</span>
                        			<?php }else{ ?>
                        			<?php echo $_smarty_tpl->tpl_vars['distributorList']->value['rank_name'];?>

                        			<?php }?>
                       			</td>
                       			<td hidden="hidden"><?php if ($_smarty_tpl->tpl_vars['distributorList']->value['level']==0){?><span class="text-danger">下线</span><?php }else{ ?><span class="text-success">分销商</span><?php }?></td>
                       			<td><?php if ($_smarty_tpl->tpl_vars['distributorList']->value['is_use']==0){?><span class="text-danger">无效</span><?php }else{ ?><span class="text-success">有效</span><?php }?></td>
		                        <td>
		                        	<?php if ($_smarty_tpl->tpl_vars['distributorList']->value['id']!=$_smarty_tpl->getVariable('distributorInfo')->value['id']){?>
		                        	<a href="./admin.php?c=fen_distributor&a=UpDownDistributorList&id=<?php echo $_smarty_tpl->tpl_vars['distributorList']->value['id'];?>
" class="btn btn-xs btn-primary search-parent">查看上下级</a>
		                        	<?php }?>
		                        	</td>
		                     </tr>
		                     <?php }} ?>
                        </tbody>
                    </table>
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
</body>
</html>