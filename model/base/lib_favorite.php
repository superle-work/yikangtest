<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_favorite')) require 'model/base/table/m_base_favorite.php';
/**
 * 提供收藏管理服务
 * @name lib_favorite.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class lib_favorite extends base_model{
    private $m_favorite;
    
    /**
     * 构造函数
     */
    function __construct(){
        $this->m_favorite = new m_base_favorite();
    }
    
	/**
	 * 收藏添加
	 * @param array $favoriteInfo
	 * @return array
	 */
	public function addFavorite($favoriteInfo){
		try{
			$result = $this->m_favorite->find(array('user_id'=>$favoriteInfo['user_id'],'goods_id'=>$favoriteInfo['goods_id']));
				if($result){
					$result = $this->findCount($favoriteInfo['user_id']);
					if($result['data'] > 0 && $result['data'] < 20){
						$favoriteInfo['add_time'] = common::getTime();
						$addId = $this->m_favorite->create ( $favoriteInfo );
						if($addId){
							return  common::errorArray(0, "添加成功", $addId);
						}else{
							return  common::errorArray(1, "添加失败",$addId);
						}
					}else{
						return  common::errorArray(1, "收藏夹已满",$addId);
					}
				}else{
					return  common::errorArray(1, "该商品已收藏",$addId);
				}
				
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取用户收藏夹个数
	 * @param int $userId
	 * @return array
	 */
	public  function findCount($userId){
		try{
			$sql = "SELECT COUNT(*) as num FROM base_favorite WHERE user_id = {$userId}";
			$result = $this->m_favorite->findSql ( $sql );
			if($result){
				return  common::errorArray(0, "查找成功", $result[0]['num']);
			}else{
				return  common::errorArray(1, "查找失败",$result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 收藏信息修改
	 * @param array $favoriteInfo
	 * @param array $conditions
	 * @return array $result
	 */
	public function updateFavorite($conditions,$favoriteInfo){
		try{
			$result = $this->m_favorite->update ($conditions,$favoriteInfo );
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取单个收藏信息
	 * @param int $id 收藏夹id
	 * @param string $joinTable 连接查询的表名 如base_mall
	 * @return array $result
	 */
	public function findFavorite($id,$joinTable){
		try{
		    $sql = "SELECT a.*,b.* FROM (SELECT * FROM base_favorite WHERE id = $id) AS a LEFT JOIN $joinTable AS b ON a.thing_id = b.id";
		    echo $sql;
		    $result = $this->m_favorite->findSql($sql);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result[0]);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取用户所有收藏
	 * @param int $userId
	 * @param string $joinTable 连接查询的表名 如base_mall
	 * @return array
	 */
	public function findAllFavorite($userId,$joinTable){
		try{
			$sql = "SELECT a.*,b.* FROM (SELECT * FROM base_favorite WHERE user_id = $userId ORDER BY add_time DESC) AS a LEFT JOIN $joinTable AS b ON a.thing_id = b.id";
			$result = $this->m_favorite->findSql($sql);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 批量删除收藏
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteFavorite($ids){
		try{
			$sql = "DELETE FROM base_favorite WHERE id in({$ids})";
			$result = $this->m_favorite->runSql ( $sql);
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
	 * 批量删除收藏
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteFavoriteGoods($conditions){
		try{
			$result = $this->m_favorite->delete ($conditions);
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
	 * 模糊查询商品表
	 * @param string $name
	 * @return array $result
	 */
	public function searchFavorite($user_id, $page, $sort = null){
	    $pageIndex=$page['pageIndex'];
	    $pageSize=$page['pageSize'];
	    $m = ($pageIndex -1 ) * $pageSize;
	    $n = $pageIndex * $pageSize;
	    $sort = $sort?'ORDER BY '.$sort:'';
	    if(!class_exists('m_store_goods')) include 'model/store/table/m_store_goods.php';
	    $m_store_goods = new m_store_goods();
	    //获取收藏商品的id
	    $condition = array('user_id'=>$user_id,'type'=>0);
	    $favoriteInfo =$this->m_favorite->findAll($condition);
	    if($favoriteInfo){
	        $gids=array();
	        foreach($favoriteInfo as $favorite){
	            array_push($gids,$favorite['thing_id']);
	        }
	        $gids = implode(',',$gids);
	        try{
	             
	            if($gids != null && $gids != ''){
	                $sql = "SELECT * FROM store_goods WHERE updown = 1 AND id IN ({$gids}) {$sort} LIMIT {$m},{$n}";
	                //计算总记录数
	                $sqlRecord = "SELECT count(*) as num FROM store_goods WHERE updown = 1 AND id IN ({$gids})";
	                $result['goodsList'] = $m_store_goods->findSql($sql);
	                if(!class_exists('m_store_comment')) include 'model/store/table/m_store_comment.php';
	                $m_store_comment = new m_store_comment();
	                //获取好评数百分比
	                foreach($result['goodsList'] as &$comment){
	                    $sql = "SELECT count(*) AS good_comment FROM store_comment WHERE gid = {$comment['id']} AND score >=4;";
	                    $good_comment = $m_store_comment->findSql($sql);
	                    $comment['good_comment'] = round($good_comment[0]['good_comment']/$comment['comment_count'],2)*100;
	
	                }
	                $totalRecord= $m_store_goods->findSql($sqlRecord);
	                $result['pageInfo']['current_page']=$pageIndex;
	                $result['pageInfo']['first_page'] = 1;
	                if($pageIndex ==1){
	                    $result['pageInfo']['prev_page'] = 1;
	                }else{
	                    $result['pageInfo']['prev_page'] = $pageIndex - 1;
	                }
	                $result['pageInfo']['next_page']= $pageIndex + 1;
	                $result['pageInfo']['last_page']=1;
	                $result['pageInfo']['total_count'] = $totalRecord[0]['num'];
	                if($result['pageInfo']['total_count'] % $pageSize ){
	                    $result['pageInfo']['total_page'] = (int) ($result['pageInfo']['total_count'] / $pageSize) + 1;
	                }else{
	                    $result['pageInfo']['total_page'] = $result['pageInfo']['total_count'] / $pageSize;
	                }
	                $result['pageInfo']['page_size']=$pageSize;
	                return  common::errorArray(0, "查询成功", $result);
	            }
	        }catch (Exception $ex){
	            $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	            return common::errorArray(1, "数据库操作失败", $ex);
	        }
	    }else{
	        $result['goodsList'] = array();
	        return  common::errorArray(0, "查询成功", $result);
	    }
	
	}
}