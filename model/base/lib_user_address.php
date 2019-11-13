<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_user_address')) require 'model/base/table/m_base_user_address.php';
/**
 * 提供用户管理服务
 * @name lib_user_address.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class lib_user_address extends base_model{
    private $m_address;
    
    function __construct(){
        parent::__construct();
        $this->m_address = new m_base_user_address();
    }
    
	/**
	 * 添加收货地址
	 * @param array $addressInfo
	 * @return array $result
	 */
	public function addAddress($addressInfo){
		try{
		    $addressInfo['add_time'] = common::getTime();
			$id = $this->m_address->create($addressInfo);
			if($id ){
				return common::errorArray(0, "添加成功", $id);
			}else{
				return common::errorArray(1, "添加失败", false);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	} 
	
	/**
	 * 删除收货地址
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteAddress($ids){
		try{
			$sql ="DELETE FROM base_user_address WHERE id in({$ids})";
			$result = $this->m_address->runSql ( $sql);
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
	 * 更新收货地址
	 * @param array $conditions
	 * @param array $addressInfo
	 * @return array $result
	 */
	public function updateAddress($conditions,$addressInfo){
		try{
			$result = $this->m_address->update ($conditions,$addressInfo );
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
	 * 获取收货地址信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function getAddressInfo($conditions){
		try{
			$result = $this->m_address->find($conditions);
			if($result){
				$result['address'] = json_decode($result['address']);
			}
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", false);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取收货地址列表
	 * @param array $conditions
	 * @return array $result
	 */
	public function getAddressList($conditions){
		try{
			$result = $this->m_address->findAll($conditions , "is_default desc");
			foreach ($result as &$per){
				$per['address'] = json_decode($per['address']);
			}
			if(true == $result ){
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
	 * 获取默认收货地址
	 * @param array $conditions
	 * @return array $result
	 */
	public function getDefaultAddress($conditions){
		try{
			$result = $this->m_address->find($conditions);
//			foreach ($result as &$per){
//				$per['address'] = json_decode($per['address']);
//			}
			$result['address'] = json_decode($result['address']);
			if(true == $result ){
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
	 * 清空粉丝地址
	 * @return array $result
	 */
	public function emptyAddress(){
	    try{
	        $sql = "TRUNCATE TABLE base_user_address";
	        $result = $this->m_address->runSql($sql);
	        if(true == $result ){
	            return common::errorArray(0, "清空粉丝地址成功", $result);
	        }else{
	            return common::errorArray(1, "清空粉丝地址失败", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return  common::errorArray(1, "数据库操作失败",$ex);
	    }
	} 
}