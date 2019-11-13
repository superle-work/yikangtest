<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_school')) require 'model/base/table/m_base_school.php';
/**
 * 提供学校位置服务
 * @name lib_school.php
 * @package fjwl
 * @category model
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2017-07-25
 */
class lib_school extends base_model{
	private $m_school;
	
	/**
	 * 构造函数
	 */
	function __construct(){
		$this->m_school = new m_base_school();
	}
	
	/**
	 * 添加学校位置
	 * @param array $info
	 * @return array $result
	 */
	public function addSchool($info){
		try{
			$addId = $this->m_school->create($info);
			if($addId){
				return  common::errorArray(0, "添加成功", $addId);
			}else{
				return  common::errorArray(1, "添加失败",false);
			}	
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查找学校位置列表
	 * @param array $condition
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllSchool($condition, $sort = null){
		try{
			$result = $this->m_school->findAll($condition, $sort);
			if($result){
				return  common::errorArray(0, "查询成功", $result);
			}else{
				return  common::errorArray(1, "未查到符合要求的学校", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 查找学校位置
	 * @param array $condition
	 * @return array $result
	 */
	public function findSchool($conditions, $sort = null, $fields = null){
		try{
			$result = $this->m_school->find($conditions, $sort, $fields);
			if($result){
				return  common::errorArray(0, "查询成功", $result);
			}else{
				return  common::errorArray(1, "查询失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 修改学校位置
	 * @param array $condition
	 * @param array $info
	 * @return array $result
	 */
	public function updateSchool($condition, $info){
		try{
			$result = $this->m_school->update($condition, $info);
			if($result){
				return  common::errorArray(0, "修改成功", $result);
			}else{
				return  common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 删除学校位置
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteSchool($ids,$field = 'id'){
		try{
			$result = $this->m_school->delete("{$field} in ({$ids})");
			if($result){
				return  common::errorArray(0, "删除成功", $result);
			}else{
				return  common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 分页查询学校位置
	 * @param array$page 分页信息
	 * @param array $conditionList 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return array $result
	 */
	public function pagingSchool($page, $conditionList, $sort = null){
		$result = $this->m_school->paging($page, $conditionList, $sort);
		return $result;
	}
}