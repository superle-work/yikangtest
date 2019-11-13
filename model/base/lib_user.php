<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_user')) require 'model/base/table/m_base_user.php';
/**
 * 提供用户管理服务
 * @name lib_user.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class lib_user extends base_model{
    private $m_user;
    
    function __construct(){
        parent::__construct();
        $this->m_user = new m_base_user();
    }
    
	/**
	 * 用户添加
	 * @param array $userInfo
	 * @return array $result
	 */
	public function addUser($userInfo){
		try{
			$this->m_user->runSql("set names 'utf8mb4'");
			$addId = $this->m_user->create ( $userInfo );
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
	 * 用户登录
	 * @param array $row
	 * @return array $result
	 */
	public function userLogin($row){
		if($row['phone'] != '' && $row['phone'] != null){
			$loginInfo['phone']  =  $row['phone'];
		}else if($row['account'] != '' && $row['account'] != null){
			$loginInfo['account'] =  $row['account'];
		}else{
			return common::errorArray(1, "账号或者手机号至少传一个", false);
		}
		$loginInfoRow['password'] =  md5($loginInfo['password']);
		try{
			$result = $this->m_user->find($loginInfo);
			if(true == $result ){
				return common::errorArray(0, "登录成功", $result);
			}else{
				return common::errorArray(1, "密码错误", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取用户列表
	 * @param array $conditions
	 * @return array $result
	 */
	public function findUserList($conditions){
		try{
			$result = $this->m_user->findAll($conditions);
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
	 * 获取单条用户信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findUser($conditions = null, $sort = null, $fields = null){
		try{
			$result = $this->m_user->find($conditions, $sort, $fields);
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
	 * 用户信息修改
	 * @param array $condition
	 * @param array $userInfo
	 * @return array $result
	 */
	public function updateUser($condition,$userInfo){
		try{
			if($userInfo['password']){
				$userInfo['password'] = md5($userInfo['password']);
			}
			$result = $this->m_user->update ($condition,$userInfo );
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
	 * 给字段数值减少
	 * @param array $conditions
	 * @param string $field
	 * @param int $optval
	 * return $result;
	 */
	function decreaseField($conditions, $field, $optval = 1){
	    try{
	        $result = $this->m_user->decrField ($conditions, $field, $optval);
	        if(true == $result){
	            return common::errorArray(0, "修改成功", $result);
	        }else{
	            return common::errorArray(1, "修改失败", $result);
	        }
	    }catch (Exception $ex){return  common::errorArray(1, "数据库操作失败", $ex);}
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
	        $result = $this->m_user->incrField($conditions, $field, $optval);
	        if(true == $result){
	            return common::errorArray(0, "修改成功", $result);
	        }else{
	            return common::errorArray(1, "修改失败", $result);
	        }
	    }catch (Exception $ex){return  common::errorArray(1, "数据库操作失败", $ex);}
	}
	/**
	 * 增加消费总额
	 * @param int $userId
	 * @param float $fee
	 * @return array $result
	 */
	public function plusTotalFee($userId,$fee){
		try{
			$sql = "update base_user set total_fee = total_fee + $fee where id = $userId";
			$result = $this->m_user->runSql ($sql );
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
	 * 余额修改
	 * @param int $userId
	 * @param float $money
	 * @param string $type +余额增加 -余额减少
	 * @return array $result
	 */
	public function updateBalance($userId,$money,$type = '-'){
	    try{
	        $sql = "update base_user set balance = balance {$type} $money where id = $userId";
	        $result = $this->m_user->runSql ($sql );
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
	 * 用户删除
	 * @param stirng $ids
	 * @return array $result
	 */
	public function deleteUser($ids){
		try{
		    $sql = "delete from base_user where id in($ids)";
			$result = $this->m_user->runSql( $sql);
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
	 * 验证账号是否存在
	 * @param string $account
	 * @return array $result
	 */
	public function isAccountExist($account){
		$conditions = array( 'account' => $account );
		try{
			$result = $this->m_user->find($conditions);	
			if(true == $result ){
				return common::errorArray(0, "该账号已存在", array());
			}else{
				return common::errorArray(1, "该账号不存在", array());
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 验证opendId是否存在，即判断该用户是否已经生成用户
	 * @param string $openId
	 * @return array $result
	 */
	public function isOpenIdExist($openId){
		$conditions = array( 'open_id' => $openId );
		try{
			$result = $this->m_user->find($conditions);
			if(true == $result ){
				return common::errorArray(0, "该openId已存在", $result);
			}else{
				return common::errorArray(1, "该openId不存在", array());
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}

	/**
	 * 分页查询用户
	 * @param array$page 分页信息
	 * @param array $conditionList 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @return array $result
	 */
	public function pagingUser($page, $conditionList, $sort = null){
		$result = $this->m_user->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 清空粉丝
	 * @return array $result
	 */
	public function emptyFans(){
	    try{
	        $sql = "TRUNCATE TABLE base_user";
	        $result = $this->m_user->runSql($sql);
	         
	        if(true == $result ){
	            return common::errorArray(0, "清空粉丝成功", $result);
	        }else{
	            return common::errorArray(1, "清空粉丝失败", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return  common::errorArray(1, "数据库操作失败",$ex);
	    }
	}
	
	/**
	 * 绑定用户信息
	 * @param array $conditions
	 * @param array $userInfo
	 * @return array $result
	 */
	public function bindUser($conditions,$userInfo){
	    try{
	        //更新用户信息
	        $updateUser = $this->m_user->update($conditions,$userInfo);
	        if($updateUser){
	            $result = $this->m_user->find($conditions);
	        }
	        if($result){
	            return common::errorArray(0, "登录成功", $result);
	        }else{
	            return common::errorArray(1, "登录失败", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return  common::errorArray(1, "数据库操作失败",$ex);
	    }
	}
}