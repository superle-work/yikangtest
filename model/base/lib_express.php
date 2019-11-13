<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_express')) require 'model/base/table/m_base_express.php';
/**
 * 提供订单快递单号管理服务
 * @name lib_express.php
 * @package cws
 * @category modle
 * @link http://www.changekeji.com
 * @author Micheal
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-12-13
 **/
class lib_express extends base_model{
    private $m_base_express;

    /**
     * 构造函数
     */
    function __construct() {
        parent::__construct ();
        error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
        if(!$this->m_express) $this->m_express = new m_base_express();
    }
    /**
     * 增加快递公司信息
     * @param array $addInfo
	 * @return array $result
     */
    function addExpress($addInfo){
        try{
            $addId = $this->m_express->create ( $addInfo );
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
     * 删除快递公司
     *  @param string $ids
	 *  @return array $result
     */
    function deleteExpress($ids){
        try{
            $sql = "delete from base_express where id in($ids)";
            $result = $this->m_express->runSql( $sql);
            if($result){
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
     * 修改快递公司
     *  @param array $expressInfo
     *  @param array $condition
	 *  @return array $result
     */
    function updateExpress( $condition, $expressInfo ){
        try{
            $result = $this->m_express->update ($condition,$expressInfo );
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
	 * 获取快递信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findExpress($conditions = null){
		try{
			$result = $this->m_express->find($conditions);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取快递信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findALLExpress($conditions){
	    try{
	        $result = $this->m_express->findAll($conditions);
	        if(true == $result ){
	            return common::errorArray(0, "查找成功", $result);
	        }else{
	            return common::errorArray(1, "查找失败", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return  common::errorArray(1, "数据库操作失败",$ex);
	    }
	}
    
    /**
     * 分页查询物流信息
     * @param array$page 分页信息
     * @param array $conditionList 条件查询条件
     * @param string $sort 排序字段及方式
     * @return array $result
     */
    public function pagingExpress($page, $conditionList, $sort = null){
        $result = $this->m_express->paging($page, $conditionList,$sort);
        if($result['errorCode'] == 1){
            $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
        }
        return $result;
    }
}
