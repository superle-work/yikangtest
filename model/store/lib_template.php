<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_template')) require "model/store/table/m_store_template.php";

/**
 * 提供模板管理服务
 * @name lib_template.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_template extends base_model{
    private $m_template;
    
    function __construct(){
        parent::__construct();
        $this->m_template = new m_store_template();
    }
    
	/**
	 * 模板添加
	 * @param array $templateInfo
	 * @return array $result
	 */
	public function addTemplate($templateInfo){
		try{
				$addId = $this->m_template->create ( $templateInfo );
				if($addId){
					return  common::errorArray(0, "添加成功", '');
				}else{
					return  common::errorArray(1, "添加失败", '');
				}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}

	/**
     * 验证模板是否存在
     * @param string $name
     * @return array $result
     */
    public function isTemplateExist($name){
        $conditions = array( 'name' => $name );
        try{
            $result = $this->m_template->find($conditions);
            if(true == $result ){
                return common::errorArray(0, "该模板已存在", $result);
            }else{
                return common::errorArray(1, "该模板不存在", $result);
            }
        }catch (Exception $ex){
            $this->errorLog(__CLASS__, __FUNCTION__, $ex);
            return common::errorArray(1, "数据库操作失败", array());
        }
    }
    
	/**
	 * 模板信息修改
	 * @param array $condition
	 * @param array $templateInfo
	 * @return array $result
	 */
	public function updateTemplate($condition,$templateInfo){
		try{
			$result = $this->m_template->update ($condition,$templateInfo );
			if(true == $result){
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
	 * 获取单个模板信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findTemplate($conditions){
		try{
			$result = $this->m_template->find($conditions);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "数据库操作失败", array());
		}
	}
	/**
	 * 获取指定模板信息
	 * @param string $ptids 
	 * @return array $result
	 */
	public function getTheSelectTemplate($ptids){
		try{
		    $sql = "SELECT name,group_concat(value) AS valueList,group_concat(id) AS idList  FROM (SELECT * from store_property_template where id in({$ptids}))as p GROUP BY name";
			$result = $this->m_template->findSql($sql);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "数据库操作失败", array());
		}
	}
	
	/**
	 * 获取所有模板信息
	 * @param array $conditions
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllTemplate($conditions,$sort = ''){
		try{
			$result = $this->m_template->findAll($conditions,$sort);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "数据库操作失败", array());
		}
	}
	
	/**
	 * 删除模板 真删
	 * @param $conditions
	 * @return array $result
	 */
	public function deleteTemplate($conditions){
		try{
			$result = $this->m_template->delete ( $conditions);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 批量删除订单
	 * @param string $tids 模板id 以逗号隔开
	 * @return array $result
	 */
	public function batchDeleteTemplate($tids){
		try{
// 			if(!class_exists('m_store_category')) include 'model/store/table/m_store_category.php';
// 			$mtb_category = new m_store_category();
// 			$sql = "SELECT * FROM `store_category` WHERE tid IN ({$tids})";
// 			$resultCate = $mtb_category->findSql($sql);
// 			if(true == $resultCate){
// 				return  common::errorArray(1, "所选中存在模板已被分类使用，无法删除", "");
// 			}
			$sql = "DELETE FROM `store_template` WHERE id IN ({$tids})";
			$result = $this->m_template->runSql($sql);
			return common::errorArray(0, "删除成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 分页查询属性
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @return multitype:boolean |multitype:number string Ambigous <boolean, multitype:boolean >
	 */
	public function pagingTemplate($page, $conditionList, $sort = null){
		$result = $this->m_template->paging($page, $conditionList,$sort);
		return $result;
	}
	
}