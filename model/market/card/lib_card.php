<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
include_once 'model/market/card/table/market_cards.php';
include_once 'model/market/card/table/market_card_level.php';
include_once 'model/market/card/table/market_card_user.php';
/**
 * 提供会员卡管理服务
 * @name lib_card.php
 * @package cws
 * @category modle
 * @link http://www.changekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-31
 */
class lib_card extends base_model{
	/**
	 * 添加会员卡
	 * @param array $cardInfo
	 * @param array $levelList
	 * @return array $cardId
	 */
	function addCard($cardInfo,$levelList ){
		$m_card = new market_cards();
		try {
			$cardInfo['add_time'] = common::getTime();
			$cardId = $m_card->create($cardInfo);
			if($cardId){
				$m_card_level = new market_card_level();
				$m_card_level->createAll($levelList);
				return common::errorArray(0, "添加成功", $cardId);
			}else{
				return common::errorArray(1, "添加失败", $cardId);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看某个会员卡
	 * @param array $condition
	 * @return array $result
	 */
	function findCard($condition){
		$m_card = new market_cards();
		try {
			$result = $m_card->find($condition);
			if(!$result){
				return common::errorArray(1, "查找为空", $result);
			}
			$m_card_level = new market_card_level();
			$levelList = $m_card_level->findAll(array('card_id'=>$result['id']));
			$result['levelList'] = $levelList;
			return common::errorArray(0, "查找成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除会员卡
	 * @param string $ids
	 * @return array $result
	 */
	function deleteCard($ids){
		$m_card = new market_cards();
		try {
			$sql = "SELECT * FROM market_card where id in ({$ids})";
			$result = $m_card->findSql($sql);
			foreach ($result as $item){
				unlink($item['logo']);
			}
			$sql = "DELETE FROM market_card where id in({$ids})";
			$this->deleteCardLevel($ids,'card_id');
			$this->deleteUserCard($ids);//删除用户会员卡
			$result = $m_card->runSql($sql);
			if($result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "无删除项", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 更新会员卡
	 * @param array $condition
	 * @param array $cardInfo
	 * @return array $result
	 */
	function updateCard($condition,$cardInfo){
		$m_card = new market_cards();
		try {
			$result = $m_card->update($condition,$cardInfo);
			if($result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 分页查询会员卡
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array $result
	 */
	function pagingCard($page,$conditionList,$sort){
		$m_card = new market_cards();
		$result = $m_card->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 查看所有可以参加会员卡活动
	 * @return array $result 
	 */
	function findAllUseCard(){
		$m_card = new market_cards();
		try {
			$curDate = common::getTime();
			$sql = "SELECT * FROM market_card WHERE start_date  <=  '{$curDate}' AND end_date  >= '{$curDate}' AND is_use = 1";
			$result = $m_card->findSql($sql);
			foreach ($result as &$item){
				$m_card_level = new market_card_level();
				$levelList = $m_card_level->findAll(array('card_id'=>$item['id']));
				$item['levelList'] = $levelList;
			}
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 更新会员卡等级
	 * @param array $condition
	 * @param array $levelInfo
	 * @return array $result
	 */
	function updateCardLevel($condition,$levelInfo){
		$m_card_level = new market_card_level();
		try {
			$result = $m_card_level->update($condition,$levelInfo);
			if($result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查找单个会员卡等级
	 * @param array $condition
	 * @return array $result
	 */
	function findCardLevel($condition){
		$m_card_level = new market_card_level();
		try {
			$result = $m_card_level->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查找所有会员卡等级
	 * @param array $condition
	 * @return array $result
	 */
	function findAllCardLevel($condition){
		$m_card_level = new market_card_level();
		try {
			$result = $m_card_level->findAll($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 添加会员卡等级
	 * @param array $row
	 * @return array $result
	 */
	function addCardLevel($row){
		$m_card_level = new market_card_level();
		try {
			$result = $m_card_level->create($row);
			if($result){
				return common::errorArray(0, "添加成功", $result);
			}else{
				return common::errorArray(1, "添加失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除会员卡等级
	 * @param string $ids
	 * @param string $field id
	 * @return array $result
	 */
	function deleteCardLevel($ids,$field = 'id'){
		$m_card_level = new market_card_level();
		try {
			$sql = "SELECT * FROM market_card_level where {$field} in({$ids})";
			$result = $m_card_level->findSql($sql);
			foreach ($result as $item){
				if($item['image']){
					unlink($item['image']);
				}
			}
			$sql = "DELETE FROM market_card_level where {$field} in({$ids})";
			$result = $m_card_level->runSql($sql);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	//用户会员卡
	
	/**
	 * 查看用户的会员卡
	 * @param array $condition
	 * @return array $result
	 */
	function findUserCard($condition){
		$m_card = new market_card_user();
		try {
			$result = $m_card->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看用户的会员卡是否可用
	 * @param string $id
	 * @param string $filed
	 * @return array $result
	 */
	function findUserCardSql($filed = 'id',$id){
	    try {
	        $m_card = new market_card_user();
	        $currentDate = date("Y-m-d",time());
	        $sql = "select * from market_card_user where valid_start  <=  '{$currentDate}' AND valid_end  >= '{$currentDate}' and {$filed} = {$id}";
	        $result = $m_card->findSql($sql);
	        if($result){
	            return common::errorArray(0, "查找成功", $result);
	        }else{
	            return common::errorArray(1, "查找为空", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 添加用户会员卡
	 * @param array $userInfo
	 * @param int $cardId
	 * @param int $levelId
	 * @return array $id
	 */
	function addUserCard($userInfo,$cardId,$levelId){
		$m_card = new market_card_user();
		try {
			$cardInfoResult = $this->findCard(array('id'=>$cardId,'is_use'=>1));
			//验证会员卡是否有发放的会员卡
			if($cardInfoResult['errorCode'] != 0){
				return common::errorArray(1, "没有可领的会员卡", false);
			}
			//验证会员卡活动有效期
			$curDate = common::getTime();
			if($curDate < $cardInfoResult['data']['start_date'] || $curDate > $cardInfoResult['data']['end_date']){
				return common::errorArray(1, "该活动未开始或已过期", false);
			}
			//验证用户会员卡是否领取,限定不同商户，同个商户，会员卡只能领去一次
// 			$userCardResult = $this->findUserCard(array('user_id'=> $userInfo['id'],'card_name'=>$cardInfoResult['data']['name']));
// 			if($userCardResult['errorCode'] == 0){
// 				return common::errorArray(1, "您已经领取过该会员卡", false);
// 			}
			
			//不限定商户的，只要领取过会员卡就不会再领取
			$userCardResult = $this->findUserCard(array('user_id'=> $userInfo['id']));
			if($userCardResult['errorCode'] == 0){
				return common::errorArray(1, "您已经领取过该会员卡", false);
			}
			//生成编号
			$currentCount = $this->getCountUserCard($cardId);
			$m = 1;
			for($i = 0;$i < $cardInfoResult['data']['num_length'];$i++){
				$m *=   10;
			}
			$total = $m - 1;
			if($currentCount >= $total){
				return common::errorArray(1, "编号已溢出，无法生成编号", false);
			}
			$numString = sprintf("%0{$cardInfoResult['data']['num_length']}s",$currentCount + 1);
			$row['card_num'] = $cardInfoResult['data']['prefix'] .  $numString ;
			$row['user_id'] = $userInfo['id'];
			$row['nick_name'] = $userInfo['nick_name'];
			$row['address'] = $userInfo['address'];
			$row['card_id'] = $cardId;
			$row['card_name'] = $cardInfoResult['data']['name'];
			$row['logo'] = $cardInfoResult['data']['logo'];
			$row['valid_start'] = $cardInfoResult['data']['valid_start'];
			$row['valid_end'] = $cardInfoResult['data']['valid_end'];
			//获取level_id为1的level信息
			$firLevelResult = $this->findCardLevel(array('id'=>$levelId));
			$row['level_id'] = $levelId;
			$row['level'] = $firLevelResult['data']['level'];
			$row['level_name'] = $firLevelResult['data']['level_name'];
			$row['image'] = $firLevelResult['data']['image'];
			$row['color'] = $firLevelResult['data']['color'];
			$row['add_time'] = common::getTime();
			$id = $m_card->create($row);
			if($id){
				return common::errorArray(0, "添加成功", $id);
			}else{
				return common::errorArray(1, "添加失败", $id);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 获取用户会员卡个数
	 * @param int $cardId
	 * @return array $result
	 */
	private function getCountUserCard($cardId){
		$m_card = new market_card_user();
		try {
			$sql = "SELECT  COUNT(*) AS count FROM market_card_user WHERE card_id = {$cardId}";
			$result = $m_card->findSql($sql);
			if($result){
				return  $result[0]['count'];
			}else{
				return 0;
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return 0;
		}
	}
	
	/**
	 * 修改用户会员卡
	 * @param array $condition
	 * @param array $cardInfo
	 * @return array $result
	 */
	function updateUserCard($condition,$cardInfo){
		$m_card = new market_card_user();
		try {
			$result = $m_card->update($condition,$cardInfo);
			if($result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 *获取升级等级id
	 * @param float $currentTotalFee  当前消费总金额
	 * @param string $level 会员卡等级
	 * @return array $result
	 */
	function getUpLevelId($currentTotalFee,$level){
		$m_level = new market_card_level();
		try {
			$sql = "select * from market_card_level where threshold <= $currentTotalFee order by threshold desc limit 1";
			$result = $m_level->findSql($sql);
			if($result && $result[0]['level']!=$level){//有高的等级
				return common::errorArray(0, "可升级为{$result[0]['level']}级", $result[0]['id']);
			}else{//没有高的等级
				return common::errorArray(1, "不可升级", false);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 会员卡升级
	 * @param int $userCardId 用户会员卡id
	 * @param int $levelId 要升级的会员卡等级id
	 * @return array $result
	 */
	function upUserCard($userCardId,$levelId){
		$m_card = new market_card_user();
		$m_level = new market_card_level();
		try {
			$levelInfo = $m_level->find(array('id'=>$levelId));
			$cardInfo = array(
					'level' => $levelInfo['level'],
					'level_id' => $levelInfo['id'],
					'level_name' => $levelInfo['level_name'],
					'image' => $levelInfo['image'],
					'color' => $levelInfo['color']
			);
			$result = $m_card->update(array('id'=>$userCardId),$cardInfo);
			if($result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}

	/**
	 * 查看所有用户会员卡
	 * @param array $condition
	 * @param string $sort
	 * @return array $result
	 */
	function findAllUserCard($condition,$sort = ""){
		$m_card = new market_card_user();
		try {
			$result = $m_card->findAll($condition,$sort);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	/**
	 * 联表查询userCard
	 * @param string $l_table  左表名
	 * @param string $r_table  右表名
	 * @param string $onConditions 连接条件
	 * @param string $user_id  用户ID
	 * @return array $result
	 */
	function findAllUserCardBySql($l_table,$r_table,$onConditions,$user_id = null){
	    $m_card = new market_card_user();
	    try {
	        if($user_id){
	            $whereString ="where {$l_table}.user_id = {$user_id}";
	        }
	        $sql = "select {$l_table}.*,{$r_table}.* from {$l_table}  left join {$r_table} on {$onConditions} {$whereString}";
	        $result = $m_card->findSql($sql);
	        if($result){
	            return common::errorArray(0, "查找成功", $result);
	        }else{
	            return common::errorArray(1, "查找为空", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 删除用户会员卡
	 * @param array $ids 会员卡ids
	 * @return array $result
	 */
	private function deleteUserCard($ids){
		$m_card = new market_card_user();
		try {
			$sql = "DELETE FROM market_card_user WHERE card_id IN({$ids})";
			$result = $m_card->runSql($sql);
			if($result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "无删除项目", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);}
	}
	
	/**
	 * 分页查询会员卡
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array $result
	 */
	function pagingCardOwner($page, $conditionList,$sort){
	    $m_card = new market_card_user();
	    $table = array(
	       'left'=>"market_card_user", 
	       "right"=>"base_user", 
	       "left_on"=>"user_id",
	       "right_on"=>"id",
	       "fieldList"=>array(
	       "head_img_url",
	       "email",
	       "phone",
	       "name"
			)
	    );
	    $result = $m_card->pagingJoin($table, $page, $conditionList,$sort);
	    if($result['errorCode'] == 1){
	        $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
	    }
	    return $result;
	}
	
	/**
	 * 分页查询用户会员卡
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array $result
	 */
	function pagingUserCard($page, $conditionList,$sort){
		$m_card = new market_card_user();
		$result = $m_card->paging( $page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
}
