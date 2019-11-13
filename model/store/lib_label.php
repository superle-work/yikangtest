<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_label')) require "model/store/table/m_store_label.php";
/**
 * 提供标签管理服务
 * @name lib_label.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_label extends base_model{
    private $m_label;
    
    function __construct(){
        parent::__construct();
        $this->m_label = new m_store_label();
    }
    
	/**
	 * 标签添加
	 * @param array $labelInfo
	 * @return array $result
	 */
	public function addLabel($labelInfo){
		try{
			$addId = $this->m_label->create ( $labelInfo );
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
	 * 标签信息修改
	 * @param array $labelInfo
	 * @param array $conditions
	 * @return $resultArray
	 */
	public function updateLabel($conditions,$labelInfo){
		try{
			$result = $this->m_label->update ($conditions,$labelInfo );
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
	 * 获取单个标签信息
	 * @param array $conditions
	 * @return array
	 */
	public function findLabel($conditions){
		try{
			$result = $this->m_label->find($conditions);
			if($result['gids']){
			    $sql = "SELECT * FROM store_goods WHERE id in({$result['gids']});";
			    if(!class_exists('m_store_goods')) include 'model/store/table/m_store_goods.php';
			    $m_store_goods = new m_store_goods();
                $result['goodsList'] = $m_store_goods->findSql($sql);
			}
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 *  获取标签列表
	 * @param array $conditions
	 * @param string $sort
	 * @param int $limit
	 * @return array $result
	 */
	public function findAllLabel($conditions,$sort = 'order_index asc',$limit = '' ){
		try{
			$result = $this->m_label->findAll($conditions,$sort );
			if(!class_exists('m_store_goods')) include 'model/store/table/m_store_goods.php';
			$m_store_goods = new m_store_goods();
			if(!class_exists('m_store_comment')) include 'model/store/table/m_store_comment.php';
			$m_store_comment = new m_store_comment();
			foreach ($result as &$per){
				$limit = $per['goods_num'];
			    if($per['gids']){
			    	if($limit !=''){
			    		$sql = "SELECT * FROM store_goods WHERE updown = 1 AND id in({$per['gids']}) order by sort_num asc LIMIT {$limit};";	
			    	}else{
			    		$sql = "SELECT * FROM store_goods WHERE updown = 1 AND id in({$per['gids']}) order by sort_num asc;";
			    	}
                    $per['goodsList'] = $m_store_goods->findSql($sql);
					foreach ($per['goodsList'] as &$comment){
						if($comment['id']){
							$sql = "SELECT count(*) AS good_comment FROM store_comment WHERE gid = {$comment['id']} AND score >=4;";
							$good_comment = $m_store_comment->findSql($sql);							
							$comment['good_comment'] = round($good_comment[0]['good_comment']/$comment['comment_count'],2)*100;			
						}
					}
			    }
			}
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除标签 真删
	 * @param array $conditions
	 * @return array $result
	 */
	public function deleteLabel($conditions){
		try{
			$result = $this->m_label->delete ( $conditions);
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

}