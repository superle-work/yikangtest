<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_fen_distributor')) require "model/fen/table/m_fen_distributor.php";

/**
 * 提供管理员管理服务
 * @name lib_distributor.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */

class lib_distributor extends base_model{
    private $m_distributor;
    public $tableConfigObj;//配置表对象
    
    function __construct(){
        parent::__construct();
        $this->m_distributor = new m_fen_distributor();
        if(!class_exists('utilConfig')) include 'include/UtilConfig.php';
        $this->tableConfigObj = new UtilConfig('fen_config');
    }
    
	/**
	 * 添加分销商
	 * @param array $applyInfo 申请记录
	 * @return array
	 */
    function addDistributor($applyInfo){
		try{
			$applyInfo['add_time'] = common::getTime();
			$this->m_distributor->runSql("set names 'utf8mb4'");
			$addId = $this->m_distributor->create ( $applyInfo );
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
	 * 更新分销商
	 * @param array $conditions
	 * @param array $row
	 * @return array
	 */
	function updateDistributor($conditions, $row){
		try{
			$result = $this->m_distributor->update ($conditions,$row);
			if(true == $result){
			    if($row['profits']){//是否有分红
					$this->m_distributor->update($conditions,"total_profits=total_profits+{$row['profits']}");
			    }
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 单个删除分销商
	 * @param array $conditions
	 * @return array
	 */
	function deleteDistributor($conditions){
		try{
			$result = $this->m_distributor->delete ( $conditions);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 批量删除分销商
	 * @param string $ids 如 1,2,3,4
	 * @return array
	 */
	function deleteDistributorBatch($ids){
		try{
			$sql = "DELETE FROM fen_distributor WHERE id in({$ids})";
			$result = $this->m_distributor->runSql($sql);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看单个分销商
	 * @param array $condition
	 * @return array
	 */
	function findDistributor($conditions){
		try{
			$result = $this->m_distributor->find($conditions);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查看单个分销商及其个人信息
	 * @param array $condition
	 * @return array
	 */
	function findDistributorJoinUser($conditions){
		try{
		    $sql = "select a.*,b.head_img_url from fen_distributor a left join base_user b on a.user_id = b.id where a.id = {$conditions['id']}";
			$result = $this->m_distributor->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result[0]);
			}else{
				return common::errorArray(1, "查找为空", $result[0]);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查找多条分销商信息
	 * @param unknown_type $conditions
	 * @return array
	 */
	function findAllDistributor($conditions){
		try{
			$result = $this->m_distributor->findAll($conditions);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查找三级分销商信息
	 */
	function findThrDistributor($id){
		try{
			$sql = " SELECT *,'一级' as rank_name  FROM fen_distributor WHERE id = {$id} UNION SELECT *,'二级' as rank_name  FROM fen_distributor WHERE parent_id = {$id} UNION SELECT *,'三级' as rank_name  FROM fen_distributor WHERE grand_id ={$id}";
			$result = $this->m_distributor->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 维护分销商表数据（修改配置时）
	 * @param float $new_fee 新标准
	 * @param float $old_fee 旧标准
	 * @return array $result
	 */
	function maintenanceDistributorTable($new_fee,$old_fee){
	    try{
	        if($new_fee < $old_fee){
	            $add_time = common::getTime();
	            
	            $sql = <<<EOF
	            insert into fen_distributor(add_time,user_id,nick_name,name,phone) select '{$add_time}',id,nick_name,nick_name,phone from base_user where total_fee >= {$new_fee} and type != 3
EOF;
                $result = $this->m_distributor->runSql($sql);    
	            if(!class_exists('m_base_user')) include 'model/base/table/m_base_user.php';
	            $m_user = new m_base_user();
	            $addUser = $m_user->update("total_fee >= {$new_fee}","type = 3");
	        }
	        if($result){
	            return common::errorArray(0, "插入成功", $result);
	        }else{
	            return common::errorArray(1, "插入为空", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败",$ex);
	    }
	}
	/**
	 * 查找上下两级分销商信息
	 */
	function findUpDownDistributor($id,$parent_id,$grand_id,$grand_grand_id){
		try{
			$sql = <<<EOT
			(SELECT *,'上三级' as rank_name FROM fen_distributor WHERE id = {$grand_grand_id})
			UNION 
			(SELECT *,'上二级' as rank_name FROM fen_distributor WHERE id = {$grand_id})
			UNION 
			(SELECT *,'上一级' as rank_name FROM fen_distributor WHERE id = {$parent_id})
			UNION 
			(SELECT *,'本级' as rank_name  FROM fen_distributor WHERE id = {$id})
			UNION
			(SELECT *,'下一级' as rank_name  FROM fen_distributor WHERE parent_id = {$id} order by add_time desc) 
			UNION 
			(SELECT *,'下二级' as rank_name  FROM fen_distributor WHERE grand_id ={$id} order by add_time desc)
			UNION 
			(SELECT *,'下三级' as rank_name  FROM fen_distributor WHERE grand_grand_id ={$id} order by add_time desc)
EOT;
			$result = $this->m_distributor->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查找下级分销商信息(含下级的下级分销商数量)
	 */
	function findSecDistributor($id){
		try{
			$sql = "select a.*,b.num from (select * from fen_distributor where parent_id = {$id})as a left join (SELECT parent_id,count(*) AS num FROM fen_distributor WHERE grand_id ={$id} GROUP BY parent_id)as b on a.id = b.parent_id";
			$result = $this->m_distributor->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	/**
	 * 查看下级分销商数量
	 * @param int $id
	 * @return array
	 */
	function getNumOfDownDistributor($id = 0){
		try{
			$sql = "SELECT COUNT(*) AS num FROM fen_distributor WHERE parent_id = {$id}";
			$result = $this->m_distributor->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result[0]['num']);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
		
	}
	
	/**
	  *分页查询分销商
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array
	 */
	function pagingDistributor($page, $conditionList,$sort){
		$result = $this->m_distributor->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
}