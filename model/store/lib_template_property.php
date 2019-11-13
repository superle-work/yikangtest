<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_property_template')) require "model/store/table/m_store_property_template.php";

/**
 * 提供模板属性属性管理服务
 * @name lib_template_property.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_template_property extends base_model{
    private $m_property_template;
    
    function __construct(){
        parent::__construct();
        $this->m_property_template = new m_store_property_template();
    }
    
	/**
	 * 模板属性添加
	 * @param array $templateInfo
	 * @return array $result
	 */
	public function addProperty($propertyInfo){
		try{
			$addId = $this->m_property_template->create ( $propertyInfo );
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
	 * 按模板属性名称分类
	 * @return array $result
	 */
	public function findNameListByProperty(){
	    try{
	        $sql = "SELECT name,group_concat(value) AS valueList,group_concat(id) AS idList  FROM store_property_template GROUP BY name order by sort asc";
	        $result = $this->m_property_template->findSql($sql);
	        if(true == $result ){
	            return common::errorArray(0, "查询成功", $result);
	        }else{
	            return common::errorArray(1, "查询失败", array());
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
     * 验证属性是否存在
     * @param string $name
     * @return array $result
     */
    public function isPropertyExist($name){
        $conditions = array( 'name' => $name );
        try{
            $result = $this->m_property_template->find($conditions);
            if(true == $result ){
                return common::errorArray(0, "该属性已存在", array());
            }else{
                return common::errorArray(1, "该属性不存在", array());
            }
        }catch (Exception $ex){
            $this->errorLog(__CLASS__, __FUNCTION__, $ex);
            return common::errorArray(1, "数据库操作失败", array());
        }
    }

	/**
	 * 模板属性信息修改
	 * @param array $templateInfo
	 * @param array $condition
	 * @return array $result
	 */
	public function updateProperty($condition,$propertyInfo){
		try{
			$result = $this->m_property_template->update ($condition,$propertyInfo );
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
	 * 获取单个模板属性信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findProperty($conditions){
		try{
			$result = $this->m_property_template->find($conditions);
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
	 * 获取单个模板属性信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findAllProperty($conditions){
		try{
			$result = $this->m_property_template->findAll($conditions);
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
	 * 分页查询属性
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @return $result
	 */
	public function pagingProperty($page, $conditions, $sort = null, $keywords = null){
		$pageIndex=$page['pageIndex'];
		$pageSize=$page['pageSize'];
		$m = ($pageIndex -1 ) * $pageSize;
		$n = $pageIndex * $pageSize;
		
		if(empty($sort)){
			$sortstr = "";
		}
		else if(is_array($sort)){
			if(count($sort) == 0){
				$sortstr = "";
			}
			else{
				$sortstr = "ORDER BY";
				foreach ($sort as $key => $sortitem){
					$sortarray[] = " {$sortitem['field']} {$sortitem['orderby']}";
				}
				$sortstr = $sortstr.join(',', $sortarray);
			}
		}
		else if(is_string($sort)){
			$sortstr = "ORDER BY ".$sort;
		}
		if(empty($conditions)&&(empty($keywords))){
			$sql = "SELECT distinct(name) FROM store_property_template  {$sortstr}";
			$sqlLimit = "{$sql}  LIMIT {$m},{$n} ";
		}
		else {
			//循环conditions数组，生成执行查询操作的sql语句
			$where = "";
			if (is_array ( $conditions ) || is_array($keywords)) {
				$join = array ();

				if (!empty($conditions)) {
					foreach ( $conditions as $key => $condition ) {
						//检测具体条件是否为数组，如果是则拆分条件并用OR连接，两边加上括号
						if(is_array($condition)){
							$join2=array();
							foreach ($condition as $key2 => $value){
								$value=$this->escape($value);
								$join2[]="{$key} = {$value}";
							}
							$join[] = '('.join( " and ", $join2 ).')';
						}
						else{
							//如果具体条件不是数组，则过滤字符串之后直接赋值
							$condition = $this->escape ( $condition );
							$join [] = "{$key} = {$condition}";
						}
					}
				}

				//模糊查询条件
				if(!empty($keywords)){
					foreach ($keywords as $key3 => $keyword){
						$join [] = $key3." LIKE CONCAT('%','$keyword','%')";
					}
				}
				//将所有的条件用AND连接起来
				$where = "WHERE " . join ( " AND ", $join );
			} else {
				if (null != $conditions)
					$where = "WHERE " . $conditions;
			}
			//根据$sort的值 选择要排序的字段
			$sql = "SELECT distinct(name) FROM store_property_template {$where} {$sortstr} ";
			$sqlLimit = "{$sql}  LIMIT {$m},{$n} ";
		}
		//查询数据库
		try {
			$result ['propertyList'] = $this->m_property_template->findSql($sqlLimit);
			$sql = "SELECT count(*) as total_record_num  from ( {$sql} ) as count_table";
			$totalCount = $this->m_property_template->findSql($sql);
			$result['pageInfo']['current_page']=$pageIndex;
			$result['pageInfo']['first_page'] = 1;
			if($pageIndex ==1){
				$result['pageInfo']['prev_page'] = 1;
			}else{
				$result['pageInfo']['prev_page'] = $pageIndex - 1;
			}
			$result['pageInfo']['next_page']= $pageIndex + 1;
			$result['pageInfo']['last_page']=1;
			$result['pageInfo']['total_count'] = $totalCount[0]['total_record_num'];
			if($result['pageInfo']['total_count'] % $pageSize ){
				$result['pageInfo']['total_page'] = intval($result['pageInfo']['total_count'] / $pageSize) + 1;
			}else{
				$result['pageInfo']['total_page'] = $result['pageInfo']['total_count'] / $pageSize;
			}
			$result['pageInfo']['page_size']=$pageSize;
		} catch (Exception $ex) {
			$result ["errorCode"] = 2;
			$result ["errorInfo"] = '数据库操作失败';
			$result ["result"] = array (
					"isSuccess" => FALSE
			);
			return $result;
		}
		
		if($result === FALSE) { // 如果数据库查无数据
			$errorCode = 1;
			$errorInfo = '获取分页数据失败';
			$result['isSuccess'] = FALSE;
		} else {
			$errorCode = 0;
			$errorInfo = '获取分页数据成功';
			$result['isSuccess'] = TRUE;
		}
		if(errorCode == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result);
		}
		return common::errorArray($errorCode, $errorInfo, $result);
	}

	
	/**
	 * 删除模板属性 真删
	 * @param array $conditions
	 * @return array $result
	 */
	public function deleteProperty($conditions){
		try{
			$result = $this->m_property_template->delete ( $conditions);
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
	 * 分组获取属性列表
	 * @param string $ptids
	 * @return array $result
	 */
	public function findGroupPropertyList($ptids){
	    try{
	        //获取指定模板对的公共属性
	        $sql = "SELECT id, name,
	        group_concat(value) AS valueList,
	        group_concat(id) AS idList
	        FROM store_property_template
	        where id in({$ptids}) GROUP BY name order by sort asc";
	        $resultList = $this->m_property_template->findSql($sql);
	        if(true == $resultList ){
	            //获取指定模板对的公共属性
	            foreach ($resultList as &$per){
	                $valueList  = explode(',', $per['valueList'] );
	                $idList = explode(',', $per['idList'] );
	                $property['name'] = $per['name'];
	                $list = array();
	                for($i = 0 ; $i < count($valueList);$i++){
	                    $list[] = array(
	                        "id" => $idList[$i],
	                        "value" => $valueList[$i]
	                    );
	                }
	                $property['list'] = $list;
	                $allPropertyList[] = $property;
	            }
	            return common::errorArray(0, "查找成功", $allPropertyList);
	        }else{
	            return common::errorArray(1, "查找失败", $resultList);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(2, "数据库操作失败", $resultList);
	    }
	}
}