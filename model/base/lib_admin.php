<?php

if(!class_exists('base_model')) require 'include/base/model/base_model.php';

if(!class_exists('m_base_admin')) require 'model/base/table/m_base_admin.php';

if(!class_exists('m_base_user')) require 'model/base/table/m_base_user.php';
/**

 * 提供管理员管理服务

 * @name lib_admin.php

 * @package cws

 * @category model

 * @link http://www.chanekeji.com

 * @author jianfang

 * @version 2.0

 * @copyright CHANGE INC

 * @since 2016-08-02

 */

class lib_admin extends base_model{

    private $m_admin;
    private $m_user;
    

    function __construct(){

        parent::__construct();

        $this->m_admin = new m_base_admin();
        $this->m_user = new m_base_user();

    }

    

	/**

	 * 添加管理员

	 * @param array $adminInfo

	 * @return array $result

	 */

	public function addAdmin($adminInfo){

		try{

			$isExistitArray = $this->isAccountExist($adminInfo['account']);

			if($isExistitArray['errorCode'] == 0){

				return  common::errorArray(1, "该账户已存在", array());

			}else{

				$adminInfo['add_time'] = common::getTime();

				$adminInfo['password'] = md5($adminInfo['password']);

				$data=$this->m_user->find(array('id' => $adminInfo['userid']));

				$adminInfo['open_id']=$data['open_id'];

				$addId = $this->m_admin->create ( $adminInfo );

				if($addId == true){

					return  common::errorArray(0, "添加成功", $addId);

				}else{

					return  common::errorArray(1, "添加失败", false);

				}

			}

		}catch (Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

		    return  common::errorArray(1, "数据库操作失败",$ex);

		}

	}

	

	/**

	 * 管理员登录

	 * @param array $loginInfo

	 * @return array $result

	 */

	public function adminLogin($loginInfo){

		$loginInfo = array(

				"account" => $loginInfo['account'],

				"password" => md5($loginInfo['password']),

		);

		try{

			$result = $this->m_admin->find($loginInfo);

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

	
	public function pagingUser($page, $conditionList, $sort = null){

		$result = $this->m_user->paging($page, $conditionList,$sort);

		if($result['errorCode'] == 1){

		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);

		}

		return $result;

	}

	/**

	 * 查询管理员

	 * @param array $condition

	 * @return array $result

	 */

	public function findAdmin($condition){

		try{

			$result = $this->m_admin->find($condition);

			if($result){

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

	 * 获取管理员列表

	 * @param array $conditions

	 * @return array $result

	 */

	public function findAllAdmin($conditions){

		try{

			$result = $this->m_admin->findAll($conditions);

			return common::errorArray(0, "查找成功", $result);

		}catch (Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

		    return  common::errorArray(1, "数据库操作失败",$ex);

		}

	}

	

	/**

	 * 管理员信息修改

	 * @param array $condition

	 * @param array $adminInfo

	 * @return $result

	 */

	public function updateAdmin($condition,$adminInfo){

		try{

			if($adminInfo['password']){

				$adminInfo['password'] = md5($adminInfo['password']);

			}

			$result = $this->m_admin->update ($condition,$adminInfo );

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

	 * 用户删除 真删

	 * @param array $conditions

	 * @return array $result

	 */

	public function deleteAdmin($conditions){

		try{

			$result = $this->m_admin->delete ( $conditions);

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

	 * 验证账号是否存在

	 * @param string $account

	 * @return array $result

	 */

	public function isAccountExist($account){

		$conditions = array( 'account' => $account );

		try{

			$result = $this->m_admin->find($conditions);

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

	

}