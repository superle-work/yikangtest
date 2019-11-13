<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_user_message')) require 'model/base/table/m_base_user_message.php';
/**
 * 提供用户消息管理服务
 * @name lib_user_message.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class lib_user_message extends base_model{
    private $m_message;
    
    function __construct(){
        parent::__construct();
        $this->m_message = new m_base_user_message();
    }
    
    /**
     * 发送消息
     * @param array $message user_id title content type
     * @return array $result
     */
    public function sendMessage($message){
        $addResult = $this->addMessage(array(
                    'user_id' => $message['user_id'],
                    'nick_name' => $message['nick_name'],
                    'title' => $message['title'],
                    'content' => $message['content'],
                    'type' => $message['type'],
                    'is_read'=>0,
                    'add_time' =>date('Y-m-d',time())
                ));
         if($addResult['errorCode'] != 0){
            return common::errorArray(0, "发送成功", true);
         }else{
             return common::errorArray(1, "发送错误", false);
         }
    }
    
	/**
	 * 添加消息
	 * @param array $row
	 * @return array $result
	 */
	private function addMessage($row){
		try{
			$addId = $this->m_message->create ( $row );
			if($addId == true){
				return  common::errorArray(0, "添加成功", $addId);
			}else{
				return  common::errorArray(1, "添加失败", $addId);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取用户未读消息数量
	 * @param int $userId
	 * @return array $result
	 */
	public function getNoReadMessageCount($userId){
	    try{
	        $sql = "select count(*) as count from base_user_message where user_id = $userId and is_read != 1";
	        $result = $this->m_message->findSql($sql);
	        if(true == $result){
	            return common::errorArray(0, "获取成功", $result[0]['count']);
	        }else{
	            return common::errorArray(1, "获取失败", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return  common::errorArray(1, "数据库操作失败",$ex);
	    }
	}
	
	/**
	 * 查看消息
	 * @param array $condition
	 * @return array $result
	 */
	public function findMessage($condition){
		try{
			$result = $this->m_message->find($condition);
			if($result){
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
	 * 查看全部消息
	 * @param array $condition
	 * @return array $result
	 */
	public function findAllMessage($condition){
		try{
			$result = $this->m_message->findAll($condition);
			if($result){
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
	 * 修改消息
	 * @param array $conditions
	 * @param array $adminInfo
	 * @return $result
	 */
	public function updateMessage($conditions,$adminInfo){
		try{
			$result = $this->m_message->update ($conditions,$adminInfo );
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
	 * 删除消息
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteMessage($ids){
		try{
		    $sql = "delete from base_user_message where id in($ids)";
			$result = $this->m_message->runSql($sql);
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
	 * 标记消息已读
	 * @param string $ids
	 * @return array $result
	 */
	public function tagMessageRead($ids){
	    try{
	        $sql = "update base_user_message set id_read = 1 where id in($ids)";
	        $result = $this->m_message->runSql($sql);
	        if(true == $result){
	            return common::errorArray(0, "标记成功", $result);
	        }else{
	            return common::errorArray(1, "标记失败", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return  common::errorArray(1, "数据库操作失败",$ex);
	    }
	}
		
	/**
	 * 分页查询消息
	 * @param array$page 分页信息
	 * @param array $conditionList 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return array $result
	 */
	public function pagingMessage($page, $conditionList, $sort = null){
	    $result = $this->m_message->paging($page, $conditionList,$sort);
	    if($result['errorCode'] == 1){
	        $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
	    }
	    return $result;
	}
	
}