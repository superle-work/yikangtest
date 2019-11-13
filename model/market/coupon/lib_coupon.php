 <?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_market_coupon')) require 'model/market/coupon/table/m_market_coupon.php';
if(!class_exists('m_market_coupon_share')) require 'model/market/coupon/table/m_market_coupon_share.php';
if(!class_exists('m_market_user_coupon')) require 'model/market/coupon/table/m_market_user_coupon.php';
/**
 * 提供优惠券管理服务
 * @name lib_coupon.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author leon
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_coupon extends base_model{
    private $mtb_coupon;
    private $mtb_coupon_share;
    private $mtb_user_coupon;
    public $fileConfigObj;//文件配置对象
    
    public function __construct(){
        parent::__construct();
        $this->mtb_coupon = new m_market_coupon();
        $this->mtb_coupon_share = new m_market_coupon_share();
        $this->mtb_user_coupon = new m_market_user_coupon();
        if(!class_exists('UtilJson')) include 'include/UtilJson.php';
        $this->fileConfigObj = new UtilJson('model/market/coupon/coupon.json');
    }
    
	/**
	 * 添加优惠券
	 * @param array $couponInfo
	 * @return array $result
	 */
	function addCoupon($couponInfo){
		try {
			$couponInfo['add_time'] = common::getTime();
			$id = $this->mtb_coupon->create($couponInfo);
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
	 * 查看某个优惠券
	 * @param array $condition
	 * @return array $result
	 */
	function findCoupon($condition){
		try {
			$result = $this->mtb_coupon->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看用户的优惠券
	 * @param array $condition
	 * @return array $result
	 */
	function findUserCoupon($condition){
		try {
			$result = $this->mtb_user_coupon->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看用户的优惠券
	 * @param array $id
	 * @return array $result
	 */
	function findUserPayCoupon($id){
		try {
		    $sql = "SELECT c.*,u.color as user_color,u.id as uid,u.inventory,u.coupon_used FROM market_coupon_user u LEFT JOIN market_coupon c ON u.coupon_id = c.id WHERE u.id = {$id}";
			$result = $this->mtb_coupon->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result[0]);
			}else{
				return common::errorArray(1, "查找为空", $result[0]);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看用户的优惠券
	 * @param array $id
	 * @return array $result
	 */
	function findUserCouponOrder($id){
		try {
		    $sql = "SELECT * FROM market_coupon_user_order WHERE id = {$id}";
			$result = $this->mtb_coupon->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看所有可以参加优惠券活动
	 * @return array
	 */
	function findAllUseCoupon(){
		try {
			$curDate = common::getTime();
			$sql = "SELECT * FROM market_coupon WHERE start_date  <=  '{$curDate}' AND end_date  >= '{$curDate}' AND is_use = 1";
			$result = $this->mtb_coupon->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看所有可以购买的优惠券
	 * @return array
	 */
	function findAllUsePayCoupon($conditions = null){
		try {
			$curDate = common::getTime();
			if($conditions){
			    $sql = "SELECT * FROM market_coupon WHERE id = {$conditions} AND use_type = 1 AND start_date  <=  '{$curDate}' AND end_date  >= '{$curDate}' AND valid_end >= '{$curDate}' AND is_use = 1";
			}else{
    			$sql = "SELECT * FROM market_coupon WHERE use_type = 1 AND start_date  <=  '{$curDate}' AND end_date  >= '{$curDate}' AND valid_end >= '{$curDate}' AND is_use = 1";
			}
			$result = $this->mtb_coupon->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除优惠券
	 * @param string $ids
	 * @return array $result
	 */
	function deleteCoupon($ids){
		try {
			$sql = "DELETE FROM market_coupon where id in ({$ids})";
			$this->deleteUserCoupon($ids);
			$result = $this->mtb_coupon->runSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 更新优惠券
	 * @param array $condition
	 * @param array $couponInfo
	 * @return array
	 */
	function updateCoupon($condition,$couponInfo){
		try {
			$result = $this->mtb_coupon->update($condition,$couponInfo);
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
	 * 分页查询优惠券
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 */
	function pagingCoupon($page,$conditionList,$sort){
		$result = $this->mtb_coupon->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	//用户优惠券
	
	/**
	 * 添加用户优惠券
	 * @param array $userInfo
	 * @param 优惠券id $couponId
	 * @return array $result
	 */
	function addUserCoupon($userInfo,$couponId){
		try {
			$couponInfoResult = $this->findCoupon(array('id'=>$couponId,'is_use'=>1));
			
			//验证优惠券是否有发放的优惠券
			if($couponInfoResult['errorCode'] != 0){
				return common::errorArray(1, "没有可发放的优惠券", false);
			}
			//验证优惠券活动有效期
			$curDate = common::getTime();
			if($curDate < $couponInfoResult['data']['start_date'] || $curDate > $couponInfoResult['data']['end_date']){
				return common::errorArray(1, "该活动未开始或已过期", false);
			}
			//验证优惠券是否发完了
			if($couponInfoResult['data']['spare_quantity'] <= 0){
				return common::errorArray(1, "优惠券已经发完", false);
			}
			$userCount = 0;//用户获取该优惠券的个数
			$countResult = $this->findUserCoupon(array("user_id"=>$userInfo['user_id'], "coupon_id"=>$couponId));
			
			if($countResult['errorCode'] == 0){
				$userCount = $countResult['data']['inventory'] + $countResult['data']['coupon_used'];
			}
			//验证用户是否超过领取个数限制
			if($userCount >= $couponInfoResult['data']['user_limit']){//超过了
				$shareCount = 0;//用户分享次数
				$countResult = $this->getShareCount($userInfo['user_id'], $couponId);
				//次数大于0时 分享功能启用
				if($couponInfoResult['data']['share_times'] > 0){
				    if($countResult['errorCode'] == 0){
                        $shareCount = $countResult['data'][0]['count'];
                    }
                    if($countResult['data'][0]['is_use'] == 0 && $shareCount >= $couponInfoResult['data']['share_times']){//未使用 分享次数也到了
                        //优惠券领取数量加1 优惠券剩余数量减1
                        $newGetQuantity = $couponInfoResult['data']['get_quantity'] + 1;
                        $newSpareQuantity = $couponInfoResult['data']['spare_quantity'] - 1;
                        $this->updateCoupon(array('id'=>$couponId), array('get_quantity'=>$newGetQuantity,"spare_quantity"=>$newSpareQuantity));
                        //设置分享记录已经使用
                        $this->updateShare(array('user_id'=>$userInfo['user_id'],'coupon_id'=>$couponId), array('is_use'=>1));
                    }else if($countResult['data'][0]['is_use'] == 0 && $shareCount < $couponInfoResult['data']['share_times']){//未使用 分享次数没到
                        $times = $couponInfoResult['data']['share_times'] - $shareCount;
                        return common::errorArray(1, "您再分享{$times}次，还可以再次领取", false);
                    }else{//使用过了
                        return common::errorArray(1, "超出领取数量限制", false);
                    }
				}else{//使用过了
                    return common::errorArray(1, "超出领取数量限制", false);
                }

			}else{//没超过
				//优惠券领取数量加1 优惠券剩余数量减1
				$newGetQuantity = $couponInfoResult['data']['get_quantity'] + 1;
				$newSpareQuantity = $couponInfoResult['data']['spare_quantity'] - 1;
				$this->updateCoupon(array('id'=>$couponId), array('get_quantity'=>$newGetQuantity,"spare_quantity"=>$newSpareQuantity));
			}
			//用户是否已经领取过相同的优惠券
			$sql = "SELECT * FROM market_coupon_user WHERE user_id = {$userInfo['user_id']} AND coupon_id = {$couponId}";
			$result = $this->mtb_user_coupon->findSql($sql);
			if(count($result)>0){
			    $result = $this->mtb_user_coupon->update(array('user_id'=>$userInfo['user_id'],'coupon_id'=>$couponId), array('inventory'=>$result[0]['inventory'] + 1));
			    if($result == true){
			        return common::errorArray(0, "添加成功", $id);
			    }else{
			        return common::errorArray(1, "添加失败", $id);
			    }
			}else{
    			$couponInfo['add_time'] = date("Y-m-d H:i:s",time());
    			$couponInfo['user_id'] = $userInfo['user_id'];
    			$couponInfo['nick_name'] = $userInfo['nick_name'];
    			$couponInfo['coupon_id'] = $couponId;
    			$couponInfo['coupon_num'] = "NO" . date("YmdHis") . rand(0,99);
    			$couponInfo['use_scene'] = $couponInfoResult['data']['use_scene'];
    			$couponInfo['color'] = $couponInfoResult['data']['color'];
    			$couponInfo['name'] = $couponInfoResult['data']['name'];
    			$couponInfo['description'] = $couponInfoResult['data']['description'];
    			$couponInfo['type'] = $couponInfoResult['data']['type'];
    			$couponInfo['value'] = $couponInfoResult['data']['value'];
    			$couponInfo['condition_value'] = $couponInfoResult['data']['condition_value'];
    			$couponInfo['discount'] = $couponInfoResult['data']['discount'];
    			$couponInfo['valid_start'] = $couponInfoResult['data']['valid_start'];
    			$couponInfo['valid_end'] = $couponInfoResult['data']['valid_end'];
    			$couponInfo['inventory'] = 1;
    			$id = $this->mtb_user_coupon->create($couponInfo);
    			if($id){
    				return common::errorArray(0, "添加成功", $id);
    			}else{
    				return common::errorArray(1, "添加失败", $id);
    			}
			}
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
			$result = $this->mtb_user_coupon->update($condition,$couponInfo);
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
	 * 使用优惠券
	 * @param int $id 用户优惠券表主键id
	 * @return array|Ambigous <multitype:, array>
	 */
	function useUserCoupon($id){
		$userCouponResult = $this->findUserCoupon(array('id'=>$id));
		if($userCouponResult['errorCode'] == 0){
// 			if($userCouponResult['data']['is_use'] == 1){
// 				return common::errorArray(1, "优惠券已使用", false);
// 			}
			$currentDate = date("Y-m-d",time());
			if($currentDate < $userCouponResult['data']['valid_start'] && $currentDate > $userCouponResult['data']['valid_end']){
				return common::errorArray(1, "优惠券不在有效期内".$userCouponResult['data']['valid_start'].'<'.$currentDate.'<'.$userCouponResult['data']['valid_end'], false);
			}
			if($userCouponResult['data']['inventory'] < 1){
			    return common::errorArray(1, "优惠券数量不足", false);
			}
			$result = $this->updateUserCoupon(array('id'=>$id), array(
// 			    'is_use'=>1,
			    'use_time'=>date('Y-m-d H:i:s',time()),
			    'inventory'=>$userCouponResult['data']['inventory'] - 1,
			    'coupon_used'=>$userCouponResult['data']['coupon_used'] + 1
			));
			return $result;
		}else{
			return common::errorArray(1, "获取优惠券错误", false);
		}
	}
	
	/**
	 * 获取用户领取的优惠券个数
	 * @param int $userId
	 * @param int $couponId
	 * @return array
	 */
	function getUserCouponCount($userId,$couponId){
		try {
			$sql = "SELECT COUNT(*) AS count FROM market_coupon_user WHERE user_id = {$userId} AND coupon_id = {$couponId}";
			ChromePhp::info($sql);
			$result = $this->mtb_user_coupon->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看所有用户优惠券
	 * @param array $condition
	 * @param string $sort
	 * @return array
	 */
	function findAllUserCoupon($condition,$sort = ""){
		try {
			$result = $this->mtb_user_coupon->findAll($condition,$sort);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取可以在商城使用的优惠券列表
	 * @param int $userId
	 * @param float $totalPrice
	 * @return array
	 */
	function findAllMallCoupon($userId,$totalPrice = 0){
		try {
			$currentDate = date("Y-m-d",time());
			$sql = "select * from market_coupon_user where user_id = {$userId} and inventory >= 1 and (use_scene = 1 or use_scene = 3)  and condition_value <= {$totalPrice} and valid_start <='{$currentDate}' and valid_end >= '{$currentDate}' AND (type = 1 or type = 3)";
			$result = $this->mtb_user_coupon->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查询所有没有被使用的优惠券
	 * @param int $couponId
	 * @return array
	 */	
	function findUserCouponSp($couponId){
		try {
			$sql = "SELECT * FROM market_coupon_user WHERE coupon_id = {$couponId} AND inventory >= 1";
			$result = $this->mtb_user_coupon->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	/**
	 * 查询所有未被使用的积分优惠券
	 * @param array $condition
	 * @param string $sort
	 * @param string $field
	 * @param int $limit
	 * @return array $result
	 */
	function findAllPointsCoupon($condition, $sort='add_time desc', $field = null, $limit = null){
	    try {
	        $now = date('Y-m-d H:i:s', time());
	        $condition .= " points IS NOT NULL AND points > 0 
	                       AND valid_end >= '{$now}'
	                       AND valid_start <= '{$now}'
	                       AND start_date <= '{$now}'
	                       AND end_date >= '{$now}'
	                       AND spare_quantity > 0
	                       AND is_use = 1";
	        $result = $this->mtb_coupon->findAll($condition, $sort, $field, $limit);
	        if($result){
	            return common::errorArray(0, "查找成功", $result);
	        }else{
	            return common::errorArray(1, "查找为空", $result);
	        }
	    }catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除用户优惠券
	 * @param array $ids 优惠券ids
	 * @return array
	 */
	private function deleteUserCoupon($ids){
		try {
			$sql = "DELETE FROM market_coupon_user WHERE coupon_id IN({$ids})";
			$result = $this->mtb_user_coupon->runSql($sql);
			if($result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "无删除项目", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 分页查询用户优惠券
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return Ambigous <multitype:, array>
	 */
	function pagingUserCoupon($page, $conditionList,$sort){
	    $table = array(
	            	 	'left'=>"market_coupon_user",
	             	"right"=>"market_coupon",
	             	"left_on"=>"coupon_id",
	             	"right_on"=>"id",
	            		 "fieldList"=>array("use_type","sale_value")
	    );
		$result = $this->mtb_user_coupon->pagingJoin($table, $page, $conditionList,$sort);
		return $result;
	}
	
	//优惠券分享
	
	/**
	 * 添加优惠券用户分享
	 * @param array $couponInfo
	 * @return array
	 */
	function addUserShare($couponInfo){
		try {
			$couponInfo['add_time'] = date("Y-m-d H:i:s",time());
			$couponInfo['ip'] = common::getRealIp();
			$couponInfo['device'] = common::detectDevice();
			$id = $this->mtb_coupon_share->create($couponInfo);
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
	 * 获取分享记录
	 * @param int $userId
	 * @param int $couponId
	 * @return array
	 */
	function getShareCount($userId,$couponId){
		try {
			$sql = "SELECT COUNT(*)  AS count,is_use FROM market_coupon_share WHERE user_id = {$userId} AND coupon_id = {$couponId}";
			$result = $this->mtb_coupon_share->findSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 分页查询用户优惠券分享
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 */
	function pagingShare($page, $conditionList,$sort){
		$result = $this->mtb_coupon_share->paging($page, $conditionList,$sort);
		return $result;
	}
	
	/**
	 * 修改用户分享
	 * @param array $condition
	 * @param array $couponInfo
	 * @return array
	 */
	function updateShare($condition,$couponInfo){
		try {
			$result = $this->mtb_coupon_share->update($condition,$couponInfo);
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
	 * 添加优惠券订单
	 */
	function addCouponOrder ($order_info){
	    if(!class_exists('m_market_user_coupon_order')) include 'model/market/coupon/table/m_market_user_coupon_order.php';
	    $m_order = new m_market_user_coupon_order();
	    $findCoupon = $this->findCoupon(array('id'=>$order_info['coupon_id']));
	    //验证优惠券库存
	    if($findCoupon['data']['spare_quantity'] < $order_info['coupon_num']){
	        return  common::errorArray(1, "优惠券数量不足", false);
	    }
	    //验证活动是否结束
	    $data = time();
	    $end_data = strtotime($findCoupon['data']['end_date']);
	    if($data > $end_data){
	        return common::errorArray(1, "活动已结束，无法购买！", false);
	    }
	    //验证优惠券是否开放
	    if($findCoupon['data']['is_use'] == 0){
	        return common::errorArray(1, "商家已关闭该优惠券，无法购买！", false);
	    }
	    //验证购买类型
	    if($findCoupon['data']['use_type'] == 0){
	        return common::errorArray(1, "商家已修改该优惠券为免费领取，请及时关注商家动态！", false);
	    }
	    $order_info['total_money'] = $order_info['coupon_num'] * $order_info['money'];
	    $order_info['order_num'] = $this->generateOrderNum();
	    $order_info['add_time'] = date('Y-m-d H:i:s', time());
	    try{
	        $result = $m_order->create($order_info);
	        if(true == $result){
	            return common::errorArray(0, "添加成功", $result);
	        }else{
	            return common::errorArray(1, "添加失败", $result);
	        }
	    }catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(2, "数据库操作失败", $ex);
		}
	}

	/**
	 * 生成订单号
	 * @return string
	 */
	private function generateOrderNum(){
	    $num = 6;
	    if($num <  2){
	        $num = 2;
	    }else if($num > 16){
	        $num = 8;
	    }
	    $date = date ( 'Ymd', time () );
	    switch ($num){
	        case 2:
	            $bit = 99;
	            break;
	        case 3:
	            $bit = 999;
	            break;
	        case 4:
	            $bit = 9999;
	            break;
	        case 5:
	            $bit = 99999;
	            break;
	        case 6:
	            $bit = 999999;
	            break;
	        case 7:
	            $bit = 9999999;
	            break;
	        case 8:
	            $bit = 99999999;
	            break;
	        default:
	            $bit = 999999;
	            $num = 6;
	    }
	    $serialNumber = sprintf("%0{$num}s", rand(1,$bit));
	    return "KFT" . $date . $serialNumber;
	}
	
}
