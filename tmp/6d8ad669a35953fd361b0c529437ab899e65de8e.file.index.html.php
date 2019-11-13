<?php /* Smarty version Smarty-3.0.8, created on 2019-09-26 15:58:49
         compiled from "/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/index.html" */ ?>
<?php /*%%SmartyHeaderCode:7642799475d8c6fb9a700e4-46325363%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6d8ad669a35953fd361b0c529437ab899e65de8e' => 
    array (
      0 => '/home/wwwroot/yikang.chuyuanshengtai.com/template/../template/front/store/ma17004/page/index.html',
      1 => 1541819766,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7642799475d8c6fb9a700e4-46325363',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html>
<html lang="zh-cn"> 
<head>
    <title>首页</title>
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/head.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <link rel="stylesheet"	href="<?php echo @JS_PATH;?>
/public/swiper/css/swiper.min.css">
    <link rel="stylesheet" href="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/css/index.css">
</head>

<body>
    <!--主容器-->
    <div id="container" class="container-fluid">
    	<div class="search-goods-area">
    		<div style="margin-left:15px;">
    			<i class="icon iconfont search_ico">&#xe601;</i>
    			<span><?php echo $_SESSION['area']['city'];?>
</span>
    		</div>
        	<div class="search">
            	<i class="icon iconfont search_ico">&#xe609;</i></span>
            	<input type="text" class="search-text" placeholder="搜索体检项目关键词">
            </div>
        </div>
        <!--页面头部-->
		<?php if ($_smarty_tpl->getVariable('slideList')->value){?>
        <!--幻灯片轮播-->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php  $_smarty_tpl->tpl_vars['slide'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('slideList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['slide']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['slide']->key => $_smarty_tpl->tpl_vars['slide']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['slide']['iteration']++;
?>
                <!--默认只展示前5张轮播图-->
                <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['slide']['iteration']<=5){?>
                <div class="swiper-slide"><a href="<?php echo $_smarty_tpl->tpl_vars['slide']->value['url'];?>
"><img class="slide-image" src="<?php echo $_smarty_tpl->tpl_vars['slide']->value['img_url'];?>
"></a></div>
                <?php }?>
                <?php }} ?>
            </div>
            <!-- 如果需要分页器 -->
            <div class="swiper-pagination"></div>
        </div>
		<?php }?>
		
		<div class="quick-area">
			<div class="quick-info">
                <a href="./index.php?c=store&a=goodsCate">
                    <div class="image product">
                    	<i class="icon iconfont show-icon1">&#xe65e;</i>
                    </div>
                    <div class="name">我要体检</div>
                </a>
            </div>
            <div class="quick-info">
                <a href="index.php?c=store&a=moreClinic">
                    <div class="image clinic">
                    	<i class="icon iconfont show-icon2">&#xe601;</i>
                    </div>
                    <div class="name">附近诊所</div>
                </a>
            </div>
            <div class="quick-info">
                <a href="index.php?c=store&a=orderList">
                    <div class="image order">
                    	<i class="icon iconfont show-icon3">&#xe667;</i>
                    </div>
                    <div class="name">订单查询</div>
                </a>
            </div>
            <div class="quick-info">
                <a href="index.php?c=fen&a=fenCenter">
                    <div class="image center">
                    	<i class="icon iconfont show-icon4">&#xe675;</i>
                    </div>
                    <div class="name">分销中心</div>
                </a>
            </div>
		</div>
		<div class="space"></div>
		
        <!--主体内容-->
        <div id="content">
            <!--商品分类-->
			<?php if ($_smarty_tpl->getVariable('categoryList')->value){?>
            <div class="label-display-area">
                <div class="row label-display">
                <?php  $_smarty_tpl->tpl_vars['cate'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('categoryList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['cateList']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cate']->key => $_smarty_tpl->tpl_vars['cate']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['cateList']['iteration']++;
?>
                    <!--默认只展示前8个标签-->
                    <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['cateList']['iteration']<=8){?>
                    <div class="col-xs-3 col-sm-3 col-md-3 item">
                        <a href="./index.php?c=store&a=goodsCate&cid=<?php echo $_smarty_tpl->tpl_vars['cate']->value['id'];?>
">
                            <div class="image"><img src="<?php echo $_smarty_tpl->tpl_vars['cate']->value['icon'];?>
"></div>
                            <div class="name"><?php echo $_smarty_tpl->tpl_vars['cate']->value['name'];?>
</div>
                        </a>
                    </div>
                    <?php }?>
                <?php }} ?>
                
                
	                <!--全部项目-->
	                <div class="col-xs-3 col-sm-3 col-md-3 item">
	                    <a href="./index.php?c=store&a=goodsCate">
	                        <div class="image">
	                        	<i class="icon iconfont all">&#xe70c;</i>
	                        </div>
	                        <div class="name">全部体检</div>
	                    </a>
	                </div>
                </div>
            </div>
			<?php }?>
			<!--商品消息和推荐商品-->
			
	    	<!--推荐商品展示区域-->
	    	<!--$recommendGoodsList-->
            <div class="recommend-goods-diplay-area hidden" >
                <div class="recommend-name-area" style="background:url(<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/image/0.png) no-repeat left center;"><span class="recommend-name">推荐商品</span><a class="more" href="./index.php?c=store&a=goodsSearch">查看全部<i class="icon iconfont">&#xe62d;</i></a></div>
                <!--推荐的商品展示区域-->
                <div class="row recommend-goods-area">
                    <?php  $_smarty_tpl->tpl_vars['goods'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('recommendGoodsList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['goodsList']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['goods']->key => $_smarty_tpl->tpl_vars['goods']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['goodsList']['iteration']++;
?>
                    <!--默认只展示前16个商品-->
                    <div class="col-xs-12 col-sm-12 col-md-12 item">
                        <div class="goods">
                            <a href="./index.php?c=store&a=goodsDetail&id=<?php echo $_smarty_tpl->tpl_vars['goods']->value['id'];?>
" class="goods-info" title="商品详情" data-id="<?php echo $_smarty_tpl->tpl_vars['goods']->value['id'];?>
">
                                <div class="image">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['goods']->value['img_url'];?>
">
                                </div>
                            </a>
                            <div class="goods-descript-info">
                            	<div class="name"><?php echo $_smarty_tpl->tpl_vars['goods']->value['name'];?>
</div>
	                            <div>
	                            	<span class="ori-price"><i class="icon iconfont">&#xe645;</i><?php echo $_smarty_tpl->tpl_vars['goods']->value['price'];?>
</span>
	                            	<span class="sale-quantity">已售：<?php echo $_smarty_tpl->tpl_vars['goods']->value['sale_quantity'];?>
</span>
	                            </div>
                            </div>
                        </div>
                    </div>
                    <?php }} ?>
                </div>
            </div>
            <div class="space"></div>
            
            <!--标签分类商品展示区域-->
            <div class="label-goods-diplay-area">
                <!--标签名称-->
                <div class="label-goods-foreach">
	                <div class="label-title-english">
	                	<span class="left">
	                		<span class="shuxian"></span>
	                		<span class="near">&nbsp;合作医院</span></span>
	                	<!--<a class="right" href="index.php?c=store&a=moreClinic">更多诊所  <i class="icon iconfont">&#xe619;</i></a>-->
	                </div>
	                
	                <!--诊所展示区-->
	                <div class="label-goods-display-item">
	                    <div class="row label-goods-area">
							<?php if ($_smarty_tpl->getVariable('labelList')->value){?>
	                        <?php  $_smarty_tpl->tpl_vars['goods'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('labelList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['goodsList']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['goods']->key => $_smarty_tpl->tpl_vars['goods']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['goodsList']['iteration']++;
?>
	                        <!--默认只展示前6个-->
	                        <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['goodsList']['iteration']<=6){?>
	                        <div class="col-xs-12 col-sm-12 col-md-12 item">
	                            <div class="goods goods-<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['goodsList']['iteration']%2;?>
">
	                                <a href="./index.php?c=store&a=clinicDetail&id=<?php echo $_smarty_tpl->tpl_vars['goods']->value['id'];?>
" class="goods-info" title="诊所详情" data-id="<?php echo $_smarty_tpl->tpl_vars['goods']->value['id'];?>
">
	                                    <div class="image">
	                                        <img alt="<?php echo $_smarty_tpl->tpl_vars['goods']->value['name'];?>
" src="<?php echo $_smarty_tpl->tpl_vars['goods']->value['img_url'];?>
">
	                                    </div>
	                                
		                                <div class="detail">
		                                	<div class="name"><?php echo $_smarty_tpl->tpl_vars['goods']->value['name'];?>
</div>
		                                    <div class="space2">
		                                    	<span class="addr"><i class="icon iconfont colo">&#xe639;</i><span class="address"><?php echo $_smarty_tpl->tpl_vars['goods']->value['address'];?>
</span></span><span class="distance"><?php echo $_smarty_tpl->tpl_vars['goods']->value['distance'];?>
</span>
		                                    </div>	
		                                    <div>
		                                    	<i class="icon iconfont colo">&#xe67f;</i><span class="phone"><?php echo $_smarty_tpl->tpl_vars['goods']->value['phone'];?>
</span>
		                                    </div>
		                                </div>
		                            </a>
	                            </div>
	                        </div>
	                        <div class="space3"></div>
	                        <?php }?>
	                        <?php }} ?>
							<?php }else{ ?>
							<p class="text-danger">没有查到记录</p>
							<?php }?>
	                    </div>
	                </div>
	            </div>
            </div>
        </div>
        <br />
        <br />
        <br />
        <br />
        <br />
        <!--页面脚部-->
        <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/bottomNav.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    </div>
    
    <!-- 模态框（Modal） 加入购物车弹出框,当商品为多规格 加载以下div-->
    <div class="modal fade" id="addCartModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fixed">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        <i class="icon iconfont">&#xe613;</i>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        <div class="cart-goods-info">
                            <img src="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['thumb'];?>
" style="height: 40px;">
                            <div class="cart-goods-text-info">
                                <div class="name"><?php echo $_smarty_tpl->getVariable('goodsInfo')->value['name'];?>
</div>
                                <div class="price">
                                    <i class="icon iconfont">&#xe604;</i>
                                    <span class="price-value" data-price="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['price'];?>
"><?php echo $_smarty_tpl->getVariable('goodsInfo')->value['price'];?>
</span><span class="unit-area">/<span class="unit">件</span></span>
                                    <span class="ori-price-info"<?php if ($_smarty_tpl->getVariable('goodsInfo')->value['standard']==1){?>style="display:none;"<?php }?>>原价<i class="icon iconfont">&#xe604;</i><span class="ori-price"><?php echo $_smarty_tpl->getVariable('goodsInfo')->value['ori_price'];?>
</span></span>
                                    <span class="inventory-area">(库存&nbsp;<span class="inventory"><?php echo $_smarty_tpl->getVariable('goodsInfo')->value['inventory'];?>
</span>)</span></div>
                            </div>
                        </div>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="property-group-area" data-id="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['id'];?>
">
                        <?php $_smarty_tpl->tpl_vars['k'] = new Smarty_variable(0, null, null);?>
                        <?php  $_smarty_tpl->tpl_vars['property'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('propertyGroups')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['propertyGroups']['iteration']=0;
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['property']->key => $_smarty_tpl->tpl_vars['property']->value){
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['propertyGroups']['iteration']++;
?>
                        <div class="goods-property-item" data-property-index="<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['propertyGroups']['iteration'];?>
">
                            <dl class="goods-prop clearfix">
                                <dt><span><?php echo $_smarty_tpl->tpl_vars['property']->value['name'];?>
</span></dt>
                                <dd>
                                    <ul data-name="<?php echo $_smarty_tpl->tpl_vars['property']->value['name'];?>
">
                                        <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['property']->value['valueList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
?>
                                        <li data-key="<?php echo $_smarty_tpl->getVariable('k')->value++;?>
" data-value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
">
                                            <a class="property-value" href="javascritp:;"><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</a>
                                            <i>已选中</i>
                                        </li>
                                        <?php }} ?>
                                    </ul>
                                </dd>
                            </dl>
                        </div>
                        <?php }} ?>
                    </div>

                    <div class="quantity-area">
                        <span class="quantity-title">数量</span>
                        <!--数量操作区域-->
                        <div class="quantity-operation-area">
                            <button class="quantity-minus"><i class="icon iconfont">&#xe611;</i></button>
                            <span class="quantity-area"><input type="text" class="quantity-value" value="1"></span>
                            <button class="quantity-plus"><i class="icon iconfont">&#xe610;</i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <a class="col-xs-6 col-sm-6 col-md-6 buy-now" href="javascritp:;"   data-id="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['id'];?>
"><i class="icon iconfont">&#xe60e;</i><span>立即购买</span></a>
                        <a class="col-xs-6 col-sm-6 col-md-6 add-cart" href="javascript:;"  data-id="<?php echo $_smarty_tpl->getVariable('goodsInfo')->value['id'];?>
"><i class="icon iconfont">&#xe609;</i><span>加入购物车</span></a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal -->
    
    <!--提示框-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/dialog.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用js文件-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/store/".($_smarty_tpl->getVariable('theme')->value)."/inner/jsfiles.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <!--公用的客服服务-->
    <?php $_template = new Smarty_Internal_Template((@TEMPLATE_PATH)."/front/common/service/page/service.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate(); $_template->rendered_content = null;?><?php unset($_template);?>
    <script src="<?php echo @JS_PATH;?>
/public/swiper/js/swiper.jquery.min.js"></script>

    <script type="text/javascript" src="<?php echo @JS_PATH;?>
/public/wechat/jweixin-1.0.0.js"></script>
    
    <?php if (!$_SESSION['area']['city']){?>
    <script>
    //获取城市定位
    wx.config({
        debug: false,
        appId: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['appId'];?>
',
        timestamp: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['timestamp'];?>
',
        nonceStr: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['nonceStr'];?>
',
        signature: '<?php echo $_smarty_tpl->getVariable('signPackage')->value['signature'];?>
',
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'getLocation',
        ]
    });
    wx.ready(function(){
        wx.checkJsApi({
            jsApiList: [
                'getNetworkType',
                'previewImage'
            ],
            success: function (res) {
            }
        });
        wx.getLocation({
            type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。    
                $.ajax({
                    url:"index.php?c=store&a=getLocation",
                    type:"post",
                    data:{"latitude":latitude,"longitude":longitude},
                    dataType:"json",
                    success:function(json,statusText){
                        if(json.errorCode == 0){
                            window.location.reload();                           
                        }
                    },
                    error:errorResponse
                });
            },
            cancel: function (res) {
                $("#content .get-more").text('获取位置失败');
            }
        });
    });
    </script>
    <?php }?>

    <script type="text/javascript" src="<?php echo @TEMPLATE_PATH;?>
/front/store/<?php echo $_smarty_tpl->getVariable('theme')->value;?>
/js/index.js"></script>
</body>
</html>