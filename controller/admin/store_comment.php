<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_comment')) require 'model/store/lib_comment.php';
/**
 * 评价管理
 * @name store_comment.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class store_comment extends admin_controller{
	private $lib_comment;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_comment = new lib_comment();
	}
	
	/**
	 * 评价列表页面
	 */
	function commentList(){
	    $this->getSetMenu($this);//设置侧边栏导航
	    $this->log(__CLASS__, __FUNCTION__, "评价列表页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/comment/commentList.html");
	}
	
	/**
	 * 评价详情页面
	 */
	function commentDetail(){
		$this->getMenu($this);//设置页面公共数据
		$id = $this->spArgs('id');
		$result = $this->lib_comment->findComment(array('id' => $id));
		$this->commentInfo = $result['data'];
		if($result['data']['has_image'] == 1){
			$result = $this->lib_comment->getCommentImageList(array('comment_id' => $id));
			$this->imageList = $result['data'];
		}
		$this->log(__CLASS__, __FUNCTION__, "评价详情页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/comment/commentDetail.html");
	}
	
	/**
	 * 单个回复评价
	 */
	function replyComment(){
		$cid = $this->spArgs('id');
		$reply = $this->spArgs('reply');
		$result = $this->lib_comment->replyComment($cid, $reply, $_SESSION['admin']['account']);
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "单个回复评价", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 统一回复评价
	 */
	function replyCommentBatch(){
		$cids = $this->spArgs('ids');
		$reply = $this->spArgs('reply');
		$result = $this->lib_comment->replyCommentBatch($cids, $reply, $_SESSION['admin']['account']);
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "统一回复评价", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 单个删除评价
	 */
	function deleteComment(){
		$id = $this->spArgs('id');
		$result = $this->lib_comment->deleteComment(array('id' => $id));
		$this->log(__CLASS__, __FUNCTION__, "单个删除评价", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 批量删除评价
	 */
	function deleteCommentBatch(){
		$ids = $this->spArgs('ids');
		$result = $this->lib_comment->deleteCommentBatch($ids);
		$this->log(__CLASS__, __FUNCTION__, "批量删除评价", 1, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 分页查询评价
	 */
	function pagingComment(){
	    $page = $this->getPageInfo($this);
	    $keyValueList = array('nick_name'=>'like','goods_name'=>'like','is_anonym'=>'=','is_reply'=>'=','has_image'=>'=','level'=>'=','from_add_time'=>'>=','to_add_time'=>'<=');
	    $conditionList = $this->getPagingList($this, $keyValueList);
		$sort = "is_reply asc,add_time desc";
		$result = $this->lib_comment->pagingComment($page, $conditionList, $sort);
		echo json_encode($result);
	}
	
	/**
	 * 添加评论
	 */
	function addComment(){
		$this->getMenu($this);
		$this->gid = $this->spArgs('gid');
		$this->log(__CLASS__, __FUNCTION__, "评价详情页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/comment/addComment.html");
	}
	
	/**
     * 添加教练
     */
    function insertComment(){
        //验证图片能否上传
        if($_FILES) $verify = UtilImage::verifyImage();
        if($verify['errorCode'] == 1) exit(json_encode($verify));
        $commentInfo = $this->getArgsList($this, array(nick_name,score,content,is_anonym,gid,sublist));
        $resultHead = UtilImage::uploadPhotoJust('imgurl', 'upload/image/store/comment/');
        $commentInfo['head_img_url'] = $resultHead;
		//判断是否上传商品照片
		if($commentInfo['sublist']){
	        $commentInfo['image_list'] = array();
	        $img_num_info = json_decode($commentInfo['sublist'],true);
	        if(count($img_num_info) != 0){
	            foreach($img_num_info as $value){
	                $imageResult = UtilImage::uploadPhoto($value['file_name'], 'upload/image/store/comment/',300,300);
	                array_push($commentInfo['image_list'],array($imageResult['url'],$imageResult['thumb']));
	            }
	        }
	    }
		//获取订单及商品信息
		if(!class_exists('lib_goods')) require 'model/store/lib_goods.php';
		$lib_goods = new lib_goods();
		$goodsInfo = $lib_goods->findGoods($commentInfo['gid']);
		$commentInfo['goods_name'] = $goodsInfo['data']['name'];	
        $result = $this->lib_comment->addComment($commentInfo);
	    if($result['errorCode'] == 0){//添加评价成功
//	        $lib_order->updateOrder(array('id'=> $commentInfo['oid']), array('is_comment'=>1));
			if($commentInfo['image_list']){
				foreach($commentInfo['image_list'] as &$value){
				    array_unshift($value, $result['data']);
				}
				$this->lib_comment->updateComment(array('id'=>$result['data']), array('has_image'=>1));
			    $this->lib_comment->addCommentImage($commentInfo['image_list']);
			}	

		}
        $this->log(__CLASS__, __FUNCTION__, "添加评论信息", 0, 'add');
        echo json_encode($result);
    }
}