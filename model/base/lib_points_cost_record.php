<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_points_cost_record')) require "model/base/table/m_base_points_cost_record.php";
/**
 * 提供商城商品管理服务
 * @name lib_points_cost_record.php
 * @package fjwl
 * @category model
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2017-08-04
 */
class lib_points_cost_record extends base_model {
    private $m_points_cost_record;
    
    function __construct(){
        parent::__construct();
        $this->m_points_cost_record = new m_base_points_cost_record();
    }
    
	/**
     * 查找单个简单信息
     * @param array $conditoins
     * @param string $sort
     * @param string $fields
     * @return array $result
     */
    public function findCostRecord($conditoins,$sort = null,$fields = null){
        try {
            $result = $this->m_points_cost_record->find($conditoins,$sort,$fields);
            if($result){
                return common::errorArray(0, "查找成功", $result);
            }else{
                return common::errorArray(1, "查找失败", $result);
            }
        } catch (Exception $ex) {
            $this->errorLog(__CLASS__, __FUNCTION__, $ex);
            return common::errorArray(1, "数据库操作失败", $ex);
        }
    }
	
	/**
	 * 分页查询商品
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @param datetime $createTime 创建时间
	 * @return array $result
	 */
	public function pagingCostRecord($page, $conditionList, $sort = null, $orList = null){
		$result = $this->m_points_cost_record->paging($page, $conditionList,$sort,$orList);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 分页查询商品
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @param datetime $createTime 创建时间
	 * @return array $result
	 */
	public function pagingPointsCostJoin($page,$conditionList,$sort = null){
		$table = array(
		 	'left'=>"base_points_cost_record", 
		 	"right"=>"base_user", 
		 	"left_on"=>"user_id",
		 	"right_on"=>"id",
			"fieldList"=>array(
	 			"nick_name",
	 		)
		);
		$result = $this->m_points_cost_record->pagingJoin($table,$page,$conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 添加
	 * @param array $addInfo
	 */
	function insertCostRecord($addInfo){
		$addInfo['add_time'] = common::getTime();
        try {
            $result = $this->m_points_cost_record->create($addInfo);
            if($result){
                return common::errorArray(0, '添加成功', $result);
            }else{
                return common::errorArray(1, "添加失败", $result);
            }
        } catch (Exception $ex) {
            $this->errorLog(__CLASS__, __FUNCTION__, $ex);
            return common::errorArray(1, "数据库操作失败", $ex);
        }
	}
	
	/**
	 * 给字段数值增加
	 * @param array $conditions
	 * @param string $field
	 * @param int $optval
	 * return $result;
	 */
	function increaseField($conditions, $field, $optval = 1){
	    try{
	        $result = $this->m_points_cost_record->incrField($conditions, $field, $optval);
	        if(true == $result){
	            return common::errorArray(0, "修改成功", $result);
	        }else{
	            return common::errorArray(1, "修改失败", $result);
	        }
	    }catch (Exception $ex){return  common::errorArray(1, "数据库操作失败", $ex);}
	}
	
	/**
	 * 删除记录
	 * @param string $ids
	 * @param string $field
	 * @return array $result
	 */
	function deletePointsCost($ids,$field = "id"){
		try{
	        $result = $this->m_points_cost_record->delete("{$field} in ({$ids})");
	        if(true == $result){
	            return common::errorArray(0, "删除成功", $result);
	        }else{
	            return common::errorArray(1, "删除失败", $result);
	        }
	    }catch (Exception $ex){return  common::errorArray(1, "数据库操作失败", $ex);}
	}
}