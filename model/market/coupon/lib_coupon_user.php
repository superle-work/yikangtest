<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_market_coupon_user')) require 'model/market/coupon/table/m_market_user_coupon.php';
/**
 * 提供优惠券管理服务
 * @name lib_coupon_user.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author leon
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 *
 */
class lib_coupon_user extends base_model{
    private $mtb_coupon_user;
    
    public function __construct(){
        parent::__construct();
        $this->mtb_coupon_user = new m_market_user_coupon;
    }
    
	/**
	 * 签到领取优惠券
	 * @param int $couponid
	 * @param int $userid
	 * @return array $result
	 */
	public function signGetCoupon($couponid,$userid){
	    if(!class_exists('m_market_coupon')) include 'model/market/coupon/table/m_market_coupon.php';
	    $mtb_coupon = new m_market_coupon();
	    if(!class_exists('m_base_user')) include 'model/base/table/m_base_user.php';
	    $mtb_user = new m_base_user();
		$sql = "SELECT * FROM market_coupon WHERE id={$couponid['id']} ";
		$tie = $mtb_coupon->findSql($sql);
		$couponInfo = $tie[0];//根据优惠券id查找优惠券所有信息
		$sql = "SELECT account FROM base_user WHERE id={$userid['id']} ";
		$tie = $mtb_user->findSql($sql);
		$account = $tie[0];//用户账号
		$couponInfo['add_time'] = date("Y-m-d", time());
		$userCouponInfo = array(
				'coupon_num' => $this->generateCouponNum(),
				'account' => $account['account'],
				'coupon_id' => $couponInfo['id'],
				'name' => $couponInfo['name'],
				'value' => $couponInfo['value'],
				'use_limit' => $couponInfo['use_limit'],
				'description' => $couponInfo['description'],
				'add_time' => $couponInfo['add_time'],
				'start_time' => $couponInfo['start_time'],
				'end_time' => $couponInfo['end_time'],
				'origin' => '0',
				'is_use' => '0'
		);
		try{
			$addId = $this->mtb_coupon_user->create ( $userCouponInfo );
			if($addId){
				$sql = "SELECT spare_quantity FROM market_coupon WHERE id={$couponid['id']} ";
				$tie = $mtb_coupon->findSql($sql);
				$spare_quantity = ($tie[0]['spare_quantity'])-1;
				$sql = "SELECT sign_quantity FROM market_coupon WHERE id={$couponid['id']} ";
				$tie = $mtb_coupon->findSql($sql);
				$sign_quantity = ($tie[0]['sign_quantity'])+1;
				$couponInfo = array(
						'spare_quantity' => $spare_quantity,
						'sign_quantity' => $sign_quantity
				);
				try{
					$addId = $mtb_coupon->update ($couponid, $couponInfo );
					if($addId){
						return  common::errorArray(
								0, "领取成功", $addId
						);
					}else{
						return  common::errorArray(
								1, "领取失败", $addId
						);
					}
				}catch (Exception $ex){
        		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
        		    return  common::errorArray(2, "数据库操作失败", $ex);
        		}
			}else{
				return  common::errorArray(
						1, "添加失败", $addId
				);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 普通方式领取
	 * @param int $couponid 优惠券id
	 * @param int $userid  用户id
	 * @return array $result
	 */
	public function commonGetCoupon($couponid,$userid){
	    if(!class_exists('m_market_coupon')) include 'model/market/coupon/table/m_market_coupon.php';
	    $mtb_coupon = new m_market_coupon();
		$sql = "SELECT spare_quantity FROM market_coupon WHERE id={$couponid['id']} ";
		$margin = $tie[0]['spare_quantity'] = $mtb_coupon->findSql($sql);//一种优惠券的余量
		if ($margin == 0){
			return common::errorArray(1,"优惠券已发放完毕", $margin);
		}else{
		    if(!class_exists('m_base_user')) include 'model/base/table/m_base_user.php';
		    $mtb_user = new m_base_user();
			$sql = "SELECT account FROM base_user WHERE id={$userid['id']} ";
			$tie = $mtb_user->findSql($sql);
			$account = $tie[0]['account'];//用户账号
			$sql = "SELECT COUNT(*) AS num FROM market_coupon_user WHERE coupon_id={$couponid['id']} and account='{$account}' ";
			$tie = $this->mtb_coupon_user->findSql($sql);
			$result = $tie[0]['num'];//用户领取一种优惠券的总数
			$sql = "SELECT toplimit FROM market_coupon WHERE id={$couponid['id']} ";
			$tie = $mtb_coupon->findSql($sql);
			$toplimit = $tie[0]['toplimit'];//一种优惠券的领取上限
			if($result >= $toplimit){
				return common::errorArray(1, "已达到领取上限", $toplimit);
			}else{
				$sql = "SELECT * FROM market_coupon WHERE id={$couponid['id']} ";
				$tie = $mtb_coupon->findSql($sql);
				$couponInfo = $tie[0];//根据优惠券id查找优惠券所有信息
				$sql = "SELECT account FROM base_user WHERE id={$userid['id']} ";
				$tie = $mtb_user->findSql($sql);
				$account = $tie[0];//用户账号
				$couponInfo['add_time'] = date("Y-m-d", time());
				$userCouponInfo = array(
						'coupon_num' => $this->generateCouponNum(),
						'account' => $account['account'],
						'coupon_id' => $couponInfo['id'],
						'name' => $couponInfo['name'],
						'value' => $couponInfo['value'],
						'use_limit' => $couponInfo['use_limit'],
						'description' => $couponInfo['description'],
						'add_time' => $couponInfo['add_time'],
						'start_time' => $couponInfo['start_time'],
						'end_time' => $couponInfo['end_time'],
						'origin' => '0',
						'is_use' => '0'
				);
				try{
					$addId = $this->mtb_coupon_user->create ( $userCouponInfo );
					if($addId){
						$sql = "SELECT spare_quantity FROM market_coupon WHERE id={$couponid['id']} ";
						$tie = $mtb_coupon->findSql($sql);
						$spare_quantity = ($tie[0]['spare_quantity'])-1;
						$sql = "SELECT issue_quantity FROM market_coupon WHERE id={$couponid['id']} ";
						$tie = $mtb_coupon->findSql($sql);
						$issue_quantity = ($tie[0]['issue_quantity'])+1;
						$couponInfo = array(
								'spare_quantity' => $spare_quantity,
								'issue_quantity' => $issue_quantity
						);
						try{
							$addId = $mtb_coupon->update ($couponid, $couponInfo );
							if($addId){
								return  common::errorArray(
										0, "领取成功", $addId
								);
							}else{
								return  common::errorArray(
										1, "领取失败", $addId
								);
							}
						}catch (Exception $ex){
                		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
                		    return  common::errorArray(2, "数据库操作失败", $ex);
                		}
					}else{
						return  common::errorArray(
								1, "添加用户优惠券表失败", $addId
						);
					}
				}catch (Exception $ex){
        		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
        		    return  common::errorArray(2, "数据库操作失败", $ex);
        		}
			}
		}
	}	
	
	/**
	 * 使用优惠券
	 * @param array $conditions
	 * @return array $result
	 */
	public function useCoupon($conditions){
		$sql = "SELECT is_use FROM market_coupon_user WHERE id={$conditions['id']} ";
		$tie = $this->mtb_coupon_user->findSql($sql);
		$result = $tie[0][is_use];
		if($result == 1){
			return common::errorArray(1, "已被使用", $result);
		}else{
			$sql = "SELECT start_time FROM market_coupon_user WHERE id={$conditions['id']} ";
			$tie = $this->mtb_coupon_user->findSql($sql);
			$result = $tie[0][start_time];
			if($result>date("Y-m-d", time())){
				return common::errorArray(1, "活动还未开始", $result);
			}else{
				$sql = "SELECT end_time FROM market_coupon_user WHERE id={$conditions['id']} ";
				$tie = $this->mtb_coupon_user->findSql($sql);
				$result = $tie[0][end_time];
				if($result<date("Y-m-d", time())){
					return common::errorArray(1, "已过使用期限", $result);
				}else{
					$useInfo['is_use'] = '1';
					$useInfo['use_time'] = date("Y-m-d", time());
					try{
						$result = $this->mtb_coupon_user->update ($conditions,$useInfo);
						if(true == $result){
							return common::errorArray(0, "使用成功", $result);
						}else{
							return common::errorArray(1, "使用失败", $result);
						}
					}catch (Exception $ex){
            		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
            		    return  common::errorArray(2, "数据库操作失败", $ex);
            		}
				}
			}
		}
	}

	/**
	 * 获取单个用户优惠券信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findUserCoupon($conditions){
		try{
			$result = $this->mtb_coupon_user->find($conditions);

			return common::errorArray(0, "查找成功", $result);

		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}

	/**
	 * 修改用户优惠券
	 * @param array $condition
	 * @param array $couponInfo
	 * @return array
	 */
	function updateUserCoupon($condition,$couponInfo){
	    try {
	        $result = $this->mtb_coupon_user->update($condition,$couponInfo);
	        if($result){
	            return common::errorArray(0, "修改成功", $result);
	        }else{
	            return common::errorArray(1, "修改失败", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return  common::errorArray(2, "数据库操作失败", $ex);
	    }
	}
	/**
	 * 获取用户优惠券列表
	 * @param array $conditions
	 * @return array $result
	 */
	public function findAllUserCoupon($conditions,$sort = ''){
		try{
			$result = $this->mtb_coupon_user->findAll($conditions,$sort);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 生成优惠券编号
	 */
	private function generateCouponNum(){
		$date = date ( 'Ymd', time ());
		$serialNumber = sprintf('%010s', rand(1,9999999999));
		return "CG" .  $serialNumber.$date;
	}
	
	/**
	 * 分页查询用户优惠券
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @return multitype:boolean |multitype:number string Ambigous <boolean, multitype:boolean >
	 */
	public function pagingUserCoupons($page, $conditions, $sort = null, $keywords = null,$addTime = null){
		$pageIndex=$page['pageIndex'];
		$pageSize=$page['pageSize'];
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
			$sql = "SELECT * FROM market_coupon_user {$sortstr}";
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
							$join[] = '('.join( " OR ", $join2 ).')';
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
	
				//时间段条件
				if(!empty($addTime)){
					if ($addTime['from']!='') {
						$join [] = "date_format(add_time,'%Y-%m-%d')>='".$addTime['from']."'";
					}
					if ($addTime['to']!='') {
						$join [] = "date_format(add_time,'%Y-%m-%d')<='".$addTime['to']."'";
					}
				}
	
				//将所有的条件用AND连接起来
				$where = "WHERE " . join ( " AND ", $join );
			} else {
				if (null != $conditions)
					$where = "WHERE " . $conditions;
			}
			//根据$sort的值 选择要排序的字段
			$sql = "SELECT * FROM market_coupon_user {$where} {$sortstr}";
		}
		//查询数据库
		try {
			$result ['userCouponsList'] = $this->mtb_coupon_user->spPager ( $pageIndex, $pageSize )->findSql($sql);
			$sql = "SELECT count(a.id) from ( {$sql} ) a";
			$count = $this->mtb_coupon_user->findSql($sql);
		} catch (Exception $ex) {
			$result ["errorCode"] = 1;
			$result ["errorInfo"] = '数据库操作失败';
			$result ["result"] = array (
					"isSuccess" => FALSE
			);
			$this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return $result;
		}
		//如果之后1页，手动添加分页信息
	
		$result['pageInfo']['current_page']=$pageIndex;
		$result['pageInfo']['first_page']=1;
		$result['pageInfo']['prev_page']=$pageIndex-1;
		$result['pageInfo']['next_page']=$pageIndex+1;
		$result['pageInfo']['last_page']=ceil ($count[0]['count(a.id)']/$pageSize);
		$result['pageInfo']['total_count']=$count[0]['count(a.id)'];
		$result['pageInfo']['total_page']=ceil ($count[0]['count(a.id)']/$pageSize);
		$result['pageInfo']['page_size']=$pageSize;
		$result['pageInfo']['all_pages']=ceil ($count[0]['count(a.id)']/$pageSize);
	
		if($result === FALSE) { // 如果数据库查无数据
			$errorCode = 1;
			$errorInfo = '获取分页数据失败';
			$result['isSuccess'] = FALSE;
		} else {
			$errorCode = 0;
			$errorInfo = '获取分页数据成功';
			$result['isSuccess'] = TRUE;
		}
	
		return common::errorArray(
				$errorCode, $errorInfo, $result
		);
	}
}

