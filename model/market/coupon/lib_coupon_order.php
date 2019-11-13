<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_market_coupon_order')) require 'model/market/coupon/table/m_market_coupon_order.php';
if(!class_exists('m_market_user_coupon_order')) require 'model/market/coupon/table/m_market_user_coupon_order.php';
/**
 * 提供优惠券订单管理服务
 * @name lib_coupon_order.php
 * @package cwms
 * @category modle
 * @link http://www.changekeji.com
 * @author leon
 * @version 1.0
 * @since 2016-06-21
 */

class lib_coupon_order extends base_model{
    private $mtb_coupon_order;
    private $m_order;
    
    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        $this->mtb_coupon_order = new m_market_coupon_order();
        $this->m_order = new m_market_user_coupon_order();
    }
    
    /**
     * 添加订单
     * @param array $row
     */
    public function addOrderRecord($row){
        try {
            $row['add_time'] = common::getTime();
            $id = $this->mtb_coupon_order->create($row);
            if($id){
                return common::errorArray(0, "添加成功", $id);
            }else{
                return common::errorArray(1, "添加失败", $id);
            }
        }catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
    }
    
   /**
    * 删除订单
    * @param string $ids
    * @param string $field
    * @return array $result
    */
    public function deleteCouponOrder($ids, $field='id'){
        try{
            $sql = "DELETE FROM market_coupon_order where {$field} in($ids)";
            $result = $this->mtb_coupon_order->runSql($sql);
            if(true == $result ){
                return common::errorArray(0, "删除成功", $result);
            }else{
                return common::errorArray(1, "删除失败", $result);
            }
        }catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
    } 
    
   /**
    * 删除订单
    * @param string $ids
    * @param string $field
    * @return array $result
    */
    public function deleteCouponUserOrder($ids, $field='id'){
        try{
            $sql = "DELETE FROM market_coupon_user_order where {$field} in($ids)";
            $result = $this->mtb_coupon_order->runSql($sql);
            if(true == $result ){
                return common::errorArray(0, "删除成功", $result);
            }else{
                return common::errorArray(1, "删除失败", $result);
            }
        }catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
    } 
    
    /**
     * 分页显示订单
     * @param array $page
     * @param array $conditionList
     * @param string $sort
     * @return array $result
     */
    public function pagingOrder($page, $conditionList, $sort='add_time desc'){
        $result = $this->mtb_coupon_order->paging($page, $conditionList,$sort);
        if($result['errorCode'] == 1){
            $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
        }
        return $result;
    }
    
    
    //////////////////////////////////购买优惠券操作区域////////////////////////////////////////

    /**
     * 删除订单
     * @param string $ids
     * @param string $field
     * @return array $result
     */
    public function deleteUserCouponOrder($ids, $field='id'){
        try{
            $sql = "DELETE FROM market_coupon_user_order where {$field} in($ids)";
            $result = $this->mtb_coupon_order->runSql($sql);
            if(true == $result ){
                return common::errorArray(0, "删除成功", $result);
            }else{
                return common::errorArray(1, "删除失败", $result);
            }
        }catch (Exception $ex){
            $this->errorLog(__CLASS__, __FUNCTION__, $ex);
            return  common::errorArray(2, "数据库操作失败", $ex);
        }
    }
    
    /**
     * 查找购买订单
     * @param string $conditions
     * @param string $field
     * @return array $result
     */
    public function findUserCouponOrder($conditions){
        try{
            $result = $this->m_order->find($conditions);
            if(true == $result ){
                return common::errorArray(0, "删除成功", $result);
            }else{
                return common::errorArray(1, "删除失败", $result);
            }
        }catch (Exception $ex){
            $this->errorLog(__CLASS__, __FUNCTION__, $ex);
            return  common::errorArray(2, "数据库操作失败", $ex);
        }
    }
    
    /**
     * 更新购买订单
     * @param string $conditions
     * @param string $field
     * @return array $result
     */
    public function updateUserCouponOrder($conditions, $row){
        try{
            $result = $this->m_order->update($conditions, $row);
            if(true == $result ){
                return common::errorArray(0, "删除成功", $result);
            }else{
                return common::errorArray(1, "删除失败", $result);
            }
        }catch (Exception $ex){
            $this->errorLog(__CLASS__, __FUNCTION__, $ex);
            return  common::errorArray(2, "数据库操作失败", $ex);
        }
    }
    
    /**
     * 分页显示购买订单
     * @param array $page
     * @param array $conditionList
     * @param string $sort
     * @return array $result
     */
    public function pagingUserOrder($page, $conditionList, $sort='add_time desc'){
        $result = $this->m_order->paging($page, $conditionList,$sort);
        if($result['errorCode'] == 1){
            $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
        }
        return $result;
    }
    
    
    /**
     * 连表查询分页显示购买订单
     * @param array $page
     * @param array $conditionList
     * @param string $sort
     * @return array $result
     */
    public function pagingUserJoinOrder($page, $conditionList, $sort='add_time desc'){
        $table = array(
            	 	'left'=>"market_coupon_user_order",
             	"right"=>"market_coupon",
             	"left_on"=>"coupon_id",
             	"right_on"=>"id",
            		 "fieldList"=>array(
                 			"valid_start",
                 			"valid_end",
                 			"end_date",
                 		)
             );
        $result = $this->m_order->pagingJoin($table, $page, $conditionList,$sort);
        if($result['errorCode'] == 1){
            $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
        }
        return $result;
    }
}