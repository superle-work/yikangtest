<?php /* Smarty version Smarty-3.0.8, created on 2019-09-24 17:07:28
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/goods/goodsCate.html" */ ?>
<?php /*%%SmartyHeaderCode:20883551925d89dcd09aeea8-04130674%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2bb0f0658c8b252b8e3da14897bc3277bed71078' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/goods/goodsCate.html',
      1 => 1543223420,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20883551925d89dcd09aeea8-04130674',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>

    <title>分类</title>
    <link rel="stylesheet"	href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/goods/goodsCate.css">
    <link rel="stylesheet"	href="<?php echo @JS_PATH;?>
/public/swiper/css/swiper.min.css">
</head>
<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
        <!--主体内容-->
        <div id="content" class="clearfix">
        	<!--搜索区-->
        	<div class="search-area">
        		<div class="search-cont">
        			<i class="iconfont">&#xe609;</i>
        			<input type="text" placeholder="搜索体检项目关键词" value="<?php echo $_smarty_tpl->getVariable('keywords')->value;?>
"/>
        		</div>
        		<div class="screening">
        			<input type="hidden" name="sort"/>
        			<span class="screen">筛选</span>
        			<i class="iconfont">&#xe610;</i>
        		</div>
        	</div>
        	
        	<div class="row goodCate-area">
	    		<div class="swiper-container swiper-container-horizontal swiper-container-free-mode">
					<div class="swiper-wrapper">
					    <div class="swiper-slide loan-list all <?php if (!$_smarty_tpl->getVariable('cid')->value){?>cur<?php }?>" data-id="">全部</div>				        					
					    <?php  $_smarty_tpl->tpl_vars['cate'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('cateList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cate']->key => $_smarty_tpl->tpl_vars['cate']->value){
?>
					    <div class="swiper-slide loan-list <?php if ($_smarty_tpl->tpl_vars['cate']->value['id']==$_smarty_tpl->getVariable('cid')->value){?>cur<?php }?>" data-id="<?php echo $_smarty_tpl->tpl_vars['cate']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['cate']->value['name'];?>
</div>				        
					    <?php }} ?>
					</div>
				</div>
        	</div>
        	<div class="row space"></div>
        	
        	<div class="recommend-goods-diplay-area" >
                <!--商品展示区域-->
                
            </div>
        	
        	<div class="get-more" data-start="1" data-num="<?php if ($_smarty_tpl->getVariable('cateLevel')->value==3){?>5<?php }elseif($_smarty_tpl->getVariable('cateLevel')->value==2){?>15<?php }else{ ?>10<?php }?>"></div>
        </div>
        <div style="height:50px;width:100%;"></div>
        <input id='cate-level' type="hidden" value="<?php echo $_smarty_tpl->getVariable('cateLevel')->value;?>
" />
        <input type="hidden" id="account" value="<?php echo $_SESSION['user']['account'];?>
"/>
        <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/bottomNav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
            <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    </div>
    <!-- 模态框（Modal） -->
	<div class="modal fade" id="sortModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <!--搜索区-->
	        	<div class="search-area">
	        		<div class="search-cont">
	        			<i class="iconfont">&#xe609;</i>
	        			<input type="text" placeholder="搜索体检项目关键词" />
	        		</div>
	        		<div class="screening">
	        			<input type="hidden" name="sort"/>
	        			<span class="screen">筛选</span>
	        			<i class="iconfont">&#xe610;</i>
	        		</div>
	        	</div>
            </div>
            <div class="modal-body">
            	<div class="sort-area">
            		<div class="time_list">
            			<span>时间</span>
            			<div class="time_but">
            				<button class="" data-sort="add_time asc">时间正序</button>
            				<button data-sort="add_time desc">时间倒序</button>
            			</div>
            		</div>
            		<div class="price_list">
            			<span>价格</span>
            			<div class="price_but">
            				<button class="" data-sort="price asc">价格正序</button>
            				<button data-sort="price desc">价格倒序</button>
            			</div>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <div class="reset">重置</div>
                <div class="finish">完成</div>
            </div>
        </div><!-- /.modal-content -->
	</div>


    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    
    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/jquery.cookie/jquery.cookie.js"></script>
    <script src="<?php echo @JS_PATH;?>
/public/swiper/js/swiper.jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/goods/goodsCate.js"></script>
</body>
</html>