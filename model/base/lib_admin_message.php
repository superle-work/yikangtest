<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_admin_message')) require 'model/base/table/m_base_admin_message.php';
/**
 * 提供管理员消息管理服务
 * @name lib_admin_message.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class lib_admin_message extends base_model{
    private $m_message;
    
    function __construct(){
        parent::__construct();
        $this->m_message = new m_base_admin_message();
    }
    
    /**
     * 发送消息
     * @param array $message title content type
     * @return array $result
     */
    public function sendMessage($message){        
        if(!class_exists('lib_admin')) include 'lib_admin.php';
        $lib_admin = new lib_admin();
        $adminResult = $lib_admin->getAdminList();
        $sendResult = true;
        if($adminResult['errorCode'] == 0){
            foreach ($adminResult['data'] as $admin){
                $addResult = $this->addMessage(array(
                    'admin_id' => $admin['id'],
                    'title' => $message['title'],
                    'content' => $message['content'],
                    'type' => $message['type'],
                    'is_read'=>0
                ));
                if($addResult['errorCode'] != 0){
                    $sendResult = false;
                }
            }
        }
        if($sendResult){
            return common::errorArray(0, "发送成功", true);
        }else {
            return common::errorArray(1, "发送错误", false);
        }
    }
    
	/**
	 * 添加消息
	 * @param array $row
	 * @return array $result
	 */
	public function addMessage($row){
		try{
		    $row['add_time'] = common::getTime();
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
	 * 获取管理员未读消息数量
	 * @param int $adminId
	 * @return array $result
	 */
	public function getNoReadMessageCount($adminId){
	    try{
	        $sql = "select count(*) as count from base_admin_message where admin_id = $adminId and is_read != 1";
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
	 *  获取消息表中消息来源模块
	 */
	public function findMessageField($field){
	    try{
	        $sql = "SELECT  DISTINCT {$field} FROM base_admin_message";
	        $result = $this->m_message ->findSql($sql);
	        if(true == $result){
	            return common::errorArray(0, "删除成功", $result);
	        }else{
	            return common::errorArray(1, "删除失败", $result);
	        }
	    }catch (Exception $ex){
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 置为已读
	 * @param array $conditions
	 * @return array $result
	 */
	public function getRead($conditions){
	    try{
	        $row = array('is_read'=>1);
	        $result = $this->m_message->update($conditions, $row);
	        if(true == $result){
	            return common::errorArray(0, "获取成功", $result);
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
	 * 查看多条消息
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
		    $sql = "delete from base_admin_message where id in($ids)";
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
	        $sql = "update base_admin_message set id_read = 1 where id in($ids)";
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