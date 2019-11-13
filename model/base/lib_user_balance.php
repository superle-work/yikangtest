<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_user_balance_pay')) require 'model/base/table/m_base_user_balance_pay.php';
if(!class_exists('m_base_user_balance_cost')) require 'model/base/table/m_base_user_balance_cost.php';
/**
 * 提供用户余额管理服务
 * @name lib_user_balance.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class lib_user_balance extends base_model{
    private $m_pay;
    private $m_cost;
    
    function __construct(){
        parent::__construct();
        $this->m_pay = new m_base_user_balance_pay();
        $this->m_cost = new m_base_user_balance_cost();
    }
	
	//**************************余额提现*******************************
	
	
	
	
	
    
    //**********************************余额充值*************************************
    
    /**
     * 余额充值
     * @param int $userId
     * @param float $money
     * @return array $result
     */
    public function addBalance($userId,$money){
        if(!class_exists('lib_user')) require 'lib_user.php';
        $lib_user = new lib_user();
        $result = $lib_user->updateBalance($userId, $money,'+');
        if($result['errorCode'] == 0){
            $this->addPay(array('user_id'=>$userId,'money'=>$money));
        }
        return $result;
    }
    
	/**
	 * 用户充值记录添加
	 * @param array $row
	 * @return array $result
	 */
	private function addPay($row){
		try{
		    $row['add_time'] = common::getTime();
			$addId = $this->m_pay->create ( $row );
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
	 * 查找充值记录
	 * @param array $conditions
	 * @return array $result
	 */
	public function findPay($conditions){
		try{
			$result = $this->m_pay->find($conditions);
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
	 * 充值记录删除 
	 * @param string $ids
	 * @return array $result
	 */
	public function deletePay($ids){
		try{
		    $sql = "delete from base_user_balance_pay where in in({$ids})";
			$result = $this->m_pay->runSql($sql);
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
	 * 分页查询充值记录
	 * @param array$page 分页信息
	 * @param array $conditionList 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return array $result
	 */
	public function pagingPay($page, $conditionList, $sort = null){
		$result = $this->m_pay->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	//**********************************余额消费*************************************
	
	/**
	 * 余额消费
	 * @param int $userId
	 * @param float $money
	 * @return array $result
	 */
	public function costBalance($userId,$money){
	    if(!class_exists('lib_user')) require 'lib_user.php';
	    $lib_user = new lib_user();
	    $result = $lib_user->updateBalance($userId, $money,'-');
	    if($result['errorCode'] == 0){
	        $this->addPay(array('user_id'=>$userId,'money'=>$money));
	    }
	    return $result;
	}
	
	/**
	 * 用户消费记录添加
	 * @param array $row
	 * @return array $result
	 */
	private function addCost($row){
	    try{
	        $row['add_time'] = common::getTime();
	        $addId = $this->m_cost->create ( $row );
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
	 * 查找消费记录
	 * @param array $conditions
	 * @return array $result
	 */
	public function findCost($conditions){
	    try{
	        $result = $this->m_cost->find($conditions);
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
	 * 消费记录删除
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteCost($ids){
	    try{
	        $sql = "delete from base_user_balance_cost where in in({$ids})";
	        $result = $this->m_cost->runSql($sql);
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
	 * 分页查询消费记录
	 * @param array$page 分页信息
	 * @param array $conditionList 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return array $result
	 */
	public function pagingCost($page, $conditionList, $sort = null){
	    $result = $this->m_cost->paging($page, $conditionList,$sort);
	    if($result['errorCode'] == 1){
	        $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
	    }
	    return $result;
	}
}