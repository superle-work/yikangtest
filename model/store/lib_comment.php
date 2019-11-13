<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_comment')) require "model/store/table/m_store_comment.php";
if(!class_exists('m_store_comment_image')) require "model/store/table/m_store_comment_image.php";

/**
 * 提供评价管理服务
 * @name lib_comment.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_comment extends base_model{
    private $m_comment;
    private $m_comment_image;
    
    function __construct(){
        parent::__construct();
        $this->m_comment = new m_store_comment();
        $this->m_comment_image = new m_store_comment_image();
    }
    
	/**
	 * 评价添加
	 * @param array $commentInfo
	 * @return array $addId
	 */
	public function addComment($commentInfo){
		try{
			//更新商品表评价信息
			if(!class_exists('m_store_goods')) include 'model/store/table/m_store_goods.php';
			$m_store_goods = new m_store_goods();
			$goodsInfo = $m_store_goods->find(array('id'=>$commentInfo['gid']));
			$newAvgScore = ($goodsInfo['comment_count'] * $goodsInfo['comment_score'] + $commentInfo['score']) 
												/ ($goodsInfo['comment_count'] + 1);//计算最新平均评价粉丝
			$newAvgScore = number_format($newAvgScore,1);//保留1位小数
			$m_store_goods->update(array('id'=>$commentInfo['gid']),
						array(
							'comment_count' => $goodsInfo['comment_count'] + 1,
							'comment_score' => $newAvgScore
								)
					);
			$commentInfo['goods_name'] = $goodsInfo['name'];
			$commentInfo['add_time'] = common::getTime();
			$addId = $this->m_comment->create ( $commentInfo );
			if($addId){
				return  common::errorArray(0, "添加成功", $addId);
			}else{
				return  common::errorArray(1, "添加失败",$addId);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 评价信息修改 包括修改评价
	 * @param array $conditions
	 * @param array $commentInfo
	 * @return array $result
	 */
	public function updateComment($conditions,$commentInfo){
		try{
			$result = $this->m_comment->update ($conditions,$commentInfo );
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 回复评价
	 * @param int $cid 评价id
	 * @param string $replyContent 回复内容
	 * @param string $account 管理员账户
	 * @return array $result
	 */	
	public function replyComment($cid,$replyContent,$account){
		try{
			$result = $this->m_comment->update (
					array('id'=>$cid),
					array(
						'is_reply'=>1,
						'reply_content'=>$replyContent,
						'reply_account'=>$account,
						'reply_time'=> common::getTime())//这里是最后删除回复时间
					);
			if(true == $result){
				return common::errorArray(0, "回复成功", $result);
			}else{
				return common::errorArray(1, "回复失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 统一回复评价
	 * @param string $cids 评价ids
	 * @param string $replyContent 回复内容
	 * @param string $account 管理员账户
	 * @return array $result
	 */
	public function replyCommentBatch($cids,$replyContent,$account){
		try{
			$time =  common::getTime();
			$sql = "UPDATE store_comment SET is_reply = 1, reply_content = '{$replyContent}',reply_account = '{$account}',reply_time = '{$time}' WHERE id IN({$cids})";
			$result = $this->m_comment->runSql ($sql);
			if(true == $result){
				return common::errorArray(0, "统一回复成功", $result);
			}else{
				return common::errorArray(1, "统一回复失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除评价回复
	 * @param int $id
	 * @return array $result
	 */
	public function deleteReply($id){
		try{
			$result = $this->m_comment->update (
					array('id'=>$id),
					array(
						'is_reply'=>0,
						'reply_content'=>'',
						'reply_account'=>'',
						'reply_time'=> ''
						)
			);
			if(true == $result){
				return common::errorArray(0, "删除回复成功", $result);
			}else{
				return common::errorArray(1, "删除回复失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取单个评价信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findComment($conditions){
		try{
			$result = $this->m_comment->find($conditions);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除评价 真删
	 * @param array $conditions
	 * @return array $result
	 */
	public function deleteComment($conditions){
		try{
			$result = $this->m_comment->delete ( $conditions);
			$this->deleteCommentImage(array('comment_id'=>$conditions['id']));
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 批量删除评价
	 * @param string $ids 1,2,3,4,5
	 * @return array $result
	 */
	public function deleteCommentBatch($ids){
		try{
			$sql = "DELETE FROM store_comment WHERE id in({$ids})";
			$this->deleteCommentImageBatch($ids);
			$result = $this->m_comment->runSql ( $sql);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 分页查询评价
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array $result
	 */
	public function pagingComment($page,$conditionList,$sort = ''){
		$result = $this->m_comment->paging($page,$conditionList,$sort );
		/* if($result['errorCode'] == 0){
			if(!class_exists('m_store_order')) include 'model/store/table/m_store_order.php';
			$m_store_order = new m_store_order();
			if(!class_exists('m_store_goods_property')) include 'model/store/table/m_store_goods_property.php';
			$m_store_goods_property = new m_store_goods_property();
			foreach ($result['data']['dataList'] as &$per){
				if($per['has_image']){
					$imgResult = $this->getCommentImageList(array('comment_id'=>$per['id']));
					$per['image_list'] = $imgResult['data'];
				}
				$orderInfo = $m_store_order->find(array('id'=>$per['oid']));
				//获取订单商品属性
				$goodsInfo = json_decode($orderInfo['goods_list'],true);
				foreach($goodsInfo as $good){
					if($good['gid'] == $per['gid']){
						$gpid = $good['gpid'];
						if($gpid != 0){
							$property = $m_store_goods_property->find(array('id'=>$gpid));
							$per['property_text'] = $property['property_text'];							
						}

					}
				}
			}
		} */
		if($result['errorCode'] == 0){
			foreach ($result['data']['dataList'] as &$per){
				if($per['has_image']){
					$imgResult = $this->getCommentImageList(array('comment_id'=>$per['id']));
					$per['image_list'] = $imgResult['data'];
				}
			}
		}
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 批量添加评价图片
	 * @param array $rowList
	 * @return array $result
	 */
	public function addCommentImage($rowList){
		try{
			$result = $this->m_comment_image->createBatch("store_comment_image","comment_id,img_url,thumb",$rowList);
			if(true == $result){
				return common::errorArray(0, "添加成功", $result);
			}else{
				return common::errorArray(1, "添加失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 删除评价图片
	 * @param array $condition  $condition['comment_id']
	 * @return array $result
	 */
	private function deleteCommentImage($condition){
		try{
			$commentImageList = $this->m_comment_image->findAll($condition);
			foreach ($commentImageList as $commentImage){
				@unlink($commentImage['img_url']);
				@unlink($commentImage['thumb']);
			}
			$result = $this->m_comment_image->delete ( $condition);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 批量删除评价图片
	 * @param string cids 1,2,3
	 * @return array $result
	 */
	private function deleteCommentImageBatch($cids){
		try{
			$idList = explode(',', $cids);
			foreach ($idList  as $id){
				$commentImageList = $this->m_comment_image->findAll(array('comment_id'=>$id));
				foreach ($commentImageList as $commentImage){
					@unlink($commentImage['img_url']);
					@unlink($commentImage['thumb']);
				}
			}
			$sql = "DELETE FROM store_comment_image WHERE comment_id in({$cids})";
			$result = $this->m_comment_image->runSql ( $sql);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取评价图片列表
	 * @param array $conditions
	 * @return array $result
	 */
	public function getCommentImageList($conditions){
		try{
			$result = $this->m_comment_image->findAll($conditions);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
}