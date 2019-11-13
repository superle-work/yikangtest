<?php
if (!class_exists('m_base_panel')) require 'model/base/table/m_base_panel.php';
/**
 * 提供相册管理服务
 * @name lib_panel.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author Lay
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-30
 */
class lib_panel extends spModel{
    private $mtb_panel;
    
    public function __construct(){
        parent::__construct();
        $this->mtb_panel = new m_base_panel();
    }
    
    /**
     * 查找所有功能
     * @param array $conditions
     * @param array $sort
     * @return array $result
     */
    public function findAllPanel($conditions, $sort){
        try{
            $result = $this->mtb_panel->findAll($conditions, $sort);
            if($result == true){
                return  common::errorArray(0, "查找成功", $result);
            }else{
                return  common::errorArray(1, "查找失败", $result);
            }
        }catch (Exception $ex){
            return  common::errorArray(1, "数据库操作失败", $ex);
        }
    }
    
    /**
     * 查找功能
     * @param array $conditions
     * @return array $result
     */
    public function findPanel($conditions){
        try{
            $result = $this->mtb_panel->find($conditions);
            if($result == true){
                return  common::errorArray(0, "查找成功", $result);
            }else{
                return  common::errorArray(1, "查找失败", $result);
            }
        }catch (Exception $ex){
            return  common::errorArray(1, "数据库操作失败", $ex);
        } 
    }
    
    /**
     * 添加
     * @param array $row
     * @return array $addId
     */
    public function addPanel($row){
        try{
            $row['add_time'] = date('Y-m-d H:i:s');
            $addId = $this->mtb_panel->create($row);
            if($addId == true){
                return  common::errorArray(0, "添加成功", $addId);
            }else{
                return  common::errorArray(1, "添加失败", $addId);
            }
        }catch (Exception $ex){
            return  common::errorArray(1, "数据库操作失败", $ex);
        }
    }
    
    /**
     * 更新
     * @param array $conditions
     * @param array $row
     * @return array $result
     */
    public function editPanel($conditions, $row){
        try{
            $result = $this->mtb_panel->update($conditions, $row);
            if($result == true){
                return  common::errorArray(0, "修改成功", $result);
            }else{
                return  common::errorArray(1, "修改失败", $result);
            }
        }catch (Exception $ex){
            return  common::errorArray(1, "数据库操作失败", $ex);
        }
    }
    
    /**
     * 删除
     * @param string $ids
     * @param string $field
     * @return array $result
     */
    public function deletePanel($ids, $field = 'id'){
        try{
            $sql = "DELETE FROM panel_label WHERE {$field} in ({$ids})";
            $result = $this->mtb_panel->runSql($sql);
            if($result == true){
                return  common::errorArray(0, "删除成功", $result);
            }else{
                return  common::errorArray(1, "删除失败", $result);
            }
        }catch (Exception $ex){return  common::errorArray(1, "数据库操作失败", $ex);}
    }

    /**
     * 分页
     * @param string $page
     * @param array $conditionList
     * @param string $sort
     * @param array $orList
     * @return array $result
     */
    public function pagingPanel($page, $conditionList,$sort='', $orList=null){
        $result = $this->mtb_panel->paging($page, $conditionList,$sort,$orList);
        return $result;
    }
}