<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_store_goods')) require "model/store/table/m_store_goods.php";
/**
 * 提供商城商品管理服务
 * @name lib_goods.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_goods extends base_model {
    private $m_goods;
    
    function __construct(){
        parent::__construct();
        $this->m_goods = new m_store_goods();
    }
    
	/**
	 * 添加商品
	 * @param array $goodsInfo
	 * @param array $userInfo
	 * @return array $result
	 */
	public function addGoods($goodsInfo){
		try {
			$addGoodsId = $this->m_goods->create($goodsInfo);
			if($addGoodsId == true){
				return  common::errorArray(0, "添加成功", array("gid" =>$addGoodsId));
			}else{
				return  common::errorArray(1, "添加失败,添加商品表失败", $addGoodsId);
			}
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(3, "添加失败,操作数据库失败", $ex);
		}
	}
	
	/**
	 * 根据传的cids查询商品
	 * @param string $cids 逗号隔开的cids
	 * @return array $result
	 */
	public function getGoodsByCate($cids){
		try {
			$sql = "SELECT * FROM store_goods WHERE updown = 1 AND (cids LIKE '{$cids},%' OR cids LIKE '{$cids}') ";
			$result = $this->m_goods->findSql($sql) ;
			return common::errorArray(0, "查找成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 根据传的cids和推荐查询商品
	 * @param string $recommend 是否推荐
	 * @param string $limit 前多少个,默认前16个
	 * @return array $result
	 */
	public function getGoodsRecommendCids($recommend = 0,$limit = 16){
		try {
			if($cids){
				$sql = "SELECT * FROM store_goods WHERE updown = 1 AND recommend = {$recommend} AND cids LIKE '{$cids}%'  order by sort_num asc limit 0,{$limit}";
			}else{
				$sql = "SELECT * FROM store_goods WHERE updown = 1 AND recommend = {$recommend}   order by sort_num asc limit 0,{$limit}";
			}
			$result = $this->m_goods->findSql($sql) ;
			return common::errorArray(0, "查找成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 根据ids查找所有商品
	 * @param string $ids
	 * @return array $result
	 */
	public function findGoodsIn($ids){
		try {
			$goodsList = array();
			$idList = explode(',', $ids);
			foreach ($idList as $id){
				$goods = $this->m_goods->find(array('id'=>$id));
				$goodsList[] = $goods;
			}
			if(count($goodsList) > 0 ){
				return common::errorArray(0, "查找成功", $goodsList);
			}else{
				return common::errorArray(1, "查找为空", $goodsList);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 判断类别是否被商品采用
	 * @param string $cid 列表id
	 * @return array $result
	 */
	public function isRelateGoodsCate($cid){
		try {
			$sql = "SELECT * FROM store_goods WHERE cids LIKE '{$cid},%' OR cids LIKE '%,{$cid}%' OR cids LIKE '%,{$cid}%,'";
			$result = $this->m_goods->findSql($sql) ;
			if ($result == true){
				return common::errorArray(0, "有关联此类别的商品", $result);
			}else{
				return common::errorArray(1, "没有关联此类别的商品", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 商品上下架
	 * @param array $condition
	 * @param array $row
	 * @return array $result
	 */
	public function updownGoods($condition,$row){
		try {
			$result = $this->m_goods->update ( $condition,$row);
			if ($result == true){
				return common::errorArray(0, "更新成功", $result);
			}else{
				return common::errorArray(1, "更新失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 商品虚拟删除
	 * @param array $condition
	 * @param array $delete
	 * @return array $result
	 */
	public function deleteGoods($condition,$delete){
		try {
			$result = $this->m_goods->update ( $condition,$delete);
			if ($result == true){
				return common::errorArray(0, "更新成功", $result);
			}else{
				return common::errorArray(1, "更新失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除商品
	 * @param int $gid
	 * @return array $result
	 */
	public function deleteComplete($gid){
		try {
			$result = $this->m_goods->deleteByPk($gid);
			if(!$result){
					return common::errorArray(1, "删除失败", $result);
			}
			return common::errorArray(0, "删除成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 更新商品
	 * @param array $conditions
	 * @param array $goodsInfo
	 * @return array $result
	 */
	public function updateGoods($conditions, $goodsInfo){
		try {
			$result = $this->m_goods->update($conditions, $goodsInfo);
			if( $result == true){
				return common::errorArray(0, "更新成功", $result);
			}else{
				return common::errorArray(1, "更新失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 生成商品编号
	 * @param string $code
	 * @return string
	 */
	private function generateGoodsNum($code){
		$sub_str = 'SP';//商品
		$date = date ( 'YmdHis', time () );
		return $sub_str.$date.$code;
	}
	
	/**
	 * 分页查询商品
	 * @param array$page 分页信息
	 * @param array $conditions 条件查询条件
	 * @param string $sort 排序字段及方式
	 * @param string $keywords 模糊查询
	 * @param datetime $createTime 创建时间
	 * @return array $result
	 */
	public function pagingGoods($page, $conditionList, $sort = null, $keywords = null,$createTime = null){
		$result = $this->m_goods->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}
	
	/**
	 * 根据id获取商品信息
	 * @param int $gid
	 * @return array $result
	 */
	function getGoods($gid){
		$condition = array('id' => $gid);
		try {
			$result = $this->m_goods->find($condition);
			if(true == $result){
				$goodsInfo = $result;
				return common::errorArray(0, "查询成功", $goodsInfo);
			}else {
				return common::errorArray(1, "查询失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查询满足条件的所有商品基本信息
	 * @param array $condition
	 * @return array
	 */
	function findAllGoods($condition,$sort = null,$fields = null ,$limit=null){
		try{
			$result = $this->m_goods->findAll($condition,$sort,$fields ,$limit);
			return common::errorArray(0, "查询成功", $result);
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "查询失败", $result);
		}
	}
	
	/**
	 * 根据id获取商品信息 只查goods表
	 * @param int $gid
	 * @return array
	 */
	function getGoodsBasic($gid){
		$condition = array('id' => $gid);
		try {
			$result = $this->m_goods->find($condition);
			if(true == $result){
				return common::errorArray(0, "查询成功", $result);
			}else {
				return common::errorArray(1, "查询失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(2, "数据库操作失败", $ex);
		}
	}

	/**
	 * 根据id获取商品信息 只查goods表
	 * @param int $gid
	 * @return array
	 */
	function findGoods($gid){
	    $condition = array('id' => $gid);
	    try {
	        $result = $this->m_goods->find($condition);
	        if(true == $result){
	            return common::errorArray(0, "查询成功", $result);
	        }else {
	            return common::errorArray(1, "查询失败", $result);
	        }
	    }catch(Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(2, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 添加商品辅图
	 * @param array $row
	 * @return array
	 */
	function addGoodsImage($row){
		if(!class_exists('m_store_goods_image')) include 'model/store/table/m_store_goods_image.php';
		$m_goods_image = new m_store_goods_image();
		try {
			$result = $m_goods_image->create($row);
			if(true == $result){
				return common::errorArray(0, "添加成功", $result);
			}else {
				return common::errorArray(1, "添加失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 更新商品辅图信息
	 * @param unknown_type $condition
	 * @param array $row
	 * @return array
	 */
	function updateGoodsImage($condition,$row){
		if(!class_exists('m_store_goods_image')) include 'model/store/table/m_store_goods_image.php';
		$m_goods_image = new m_store_goods_image();
		try {
			$result = $m_goods_image->update($condition,$row);
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else {
				return common::errorArray(1, "修改失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 根据gid获取商品辅图列表
	 * @param int $gid
	 * @return array
	 */
	function getGoodsImageList($gid){
		if(!class_exists('m_store_goods_image')) include 'model/store/table/m_store_goods_image.php';
		$m_goods_image = new m_store_goods_image();
		$condition = array('gid' => $gid);
		try {
			$result = $m_goods_image->findAll($condition);
			if(true == $result){
				return common::errorArray(0, "查询成功", $result);
			}else {
				return common::errorArray(1, "查询为空", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 删除商品附图
	 * @param array $condition
	 * @return array $result
	 */
	function deleteGoodsImage($condition){
		if(!class_exists('m_store_goods_image')) include 'model/store/table/m_store_goods_image.php';
		$m_goods_image = new m_store_goods_image();
		try {
			$result = $m_goods_image->delete($condition);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else {
				return common::errorArray(1, "删除失败", $result);
			}
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 批量删除商品
	 * @param string $gids
	 * @return array $result
	 */
	function batchDelete($gids){
		try{
			$sql = "SELECT * FROM `store_goods` WHERE id IN ({$gids})";
			$goodsResult = $this->m_goods->findSql($sql);
			if(!$goodsResult){
				return common::errorArray(1, "查出应删除记录失败", $goodsResult);
			}
			foreach($goodsResult as $goods){
				//删除商品图片
				$imgUrl = $goods['img_url'];
				if($imgUrl) @unlink($imgUrl);//delete url
				$thumbUrl = substr($imgUrl, 0,strripos($imgUrl,'.')) . "_thumb" .  substr($imgUrl, strripos($imgUrl,'.'));
				if($imgUrl) @unlink($thumbUrl);//delete thumb	
			}
			
			//删除相关商品辅图及数据库记录
			if(!class_exists('m_store_goods_image')) include 'model/store/table/m_store_goods_image.php';
		    $m_goods_image = new m_store_goods_image();
			$sql = "SELECT * FROM `store_goods_image` WHERE gid IN ({$gids})";
			$imageResult = $m_goods_image->findSql($sql);
			foreach($imageResult as $image){
				if($image['img_url']) @unlink($image['img_url']);
				if($image['thumb']) @unlink($image['thumb']);
			}
			$sql = "DELETE FROM `store_goods_image` WHERE gid IN ({$gids})";
			$imageResult = $m_goods_image->runSql($sql);
			if(true != $imageResult) return common::errorArray(1, "删除商品相关辅图失败", $imageResult);
			
			//删除商品
			$sql = "DELETE FROM `store_goods` WHERE id IN ({$gids})";
			$goodsResult = $this->m_goods->runSql($sql);
			if(true != $goodsResult){
				return common::errorArray(1, "删除失败", $goodsResult);
			}
			return common::errorArray(0, "删除成功", $goodsResult);
		}catch(Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	/**
	 * 模糊查询商品表
	 * @param string $name
	 * @return array $result
	 */
	public function searchGoods($name, $gids, $page, $sort = null){
	    $pageIndex=$page['pageIndex'];
	    $pageSize=$page['pageSize'];
	    $m = ($pageIndex -1 ) * $pageSize;
	    $n = $pageSize;
	    $sort = $sort?'ORDER BY '.$sort:'';
	    try{
	        if($name != null && $name != ''){
	        	//判断是否存在分类
	        	if($gids != null && $gids != ''){
	        		$sql = "SELECT * FROM store_goods WHERE updown = 1 AND (name LIKE '%{$name}%'  OR detail_desc LIKE '%{$name}%' ) AND id IN ({$gids}) {$sort} LIMIT {$m},{$n}";
		            //计算总记录数
		            $sqlRecord = "SELECT count(*) as num FROM store_goods WHERE updown = 1 AND (name LIKE '%{$name}%'  OR detail_desc LIKE '%{$name}%' ) AND id IN ({$gids})";
	        	}else{
	        		$sql = "SELECT * FROM store_goods WHERE updown = 1 AND (name LIKE '%{$name}%'  OR detail_desc LIKE '%{$name}%' ) {$sort} LIMIT {$m},{$n}";
		            //计算总记录数
		            $sqlRecord = "SELECT count(*) as num FROM store_goods WHERE updown = 1 AND (name LIKE '%{$name}%'  OR detail_desc LIKE '%{$name}%' )";
	        	}
//	            $sql = "SELECT * FROM store_goods WHERE updown = 1 AND (name LIKE '%{$name}%'  OR simple_desc LIKE '%{$name}%' ) {$sort} LIMIT {$m},{$n}";

//	            //计算总记录数

//	            $sqlRecord = "SELECT count(*) as num FROM store_goods WHERE updown = 1 AND (name LIKE '%{$name}%'  OR simple_desc LIKE '%{$name}%' )";
	        }else{
	        	//判断是否存在分类
	        	if($gids != null && $gids != ''){
	        		$sql = "SELECT * FROM store_goods WHERE updown = 1 AND id IN ({$gids}) {$sort} LIMIT {$m},{$n}";
		            //计算总记录数
		            $sqlRecord = "SELECT count(*) as num  FROM store_goods WHERE updown = 1 AND id IN ({$gids})";
	        	}else{
	        		$sql = "SELECT * FROM store_goods WHERE updown = 1 {$sort} LIMIT {$m},{$n}";
		            //计算总记录数
		            $sqlRecord = "SELECT count(*) as num  FROM store_goods WHERE updown = 1";
	        	}
//	            $sql = "SELECT * FROM store_goods WHERE updown = 1 {$sort} LIMIT {$m},{$n}";

//	            //计算总记录数

//	            $sqlRecord = "SELECT count(*) as num  FROM store_goods WHERE updown = 1";
	        }
	        $result['goodsList'] = spClass('m_store_goods')->findSql($sql);
			
	        $totalRecord= spClass('m_store_goods')->findSql($sqlRecord);
	        $result['pageInfo']['current_page']=$pageIndex;
	        $result['pageInfo']['first_page'] = 1;
	        if($pageIndex ==1){
	            $result['pageInfo']['prev_page'] = 1;
	        }else{
	            $result['pageInfo']['prev_page'] = $pageIndex - 1;
	        }
	        $result['pageInfo']['next_page']= $pageIndex + 1;
	        $result['pageInfo']['last_page']=1;
	        $result['pageInfo']['total_count'] = $totalRecord[0]['num'];
	        if($result['pageInfo']['total_count'] % $pageSize ){
	            $result['pageInfo']['total_page'] = (int) ($result['pageInfo']['total_count'] / $pageSize) + 1;
	        }else{
	            $result['pageInfo']['total_page'] = $result['pageInfo']['total_count'] / $pageSize;
	        }
	        $result['pageInfo']['page_size']=$pageSize;
	        return  common::errorArray(0, "查询成功", $result);
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}

	/**
	 * 累加商品浏览量（字段数量加1）
	 * @param int id
	 * @param string  total_view
	 * @return array  $result
	 */
	function addClick($id,$field = 'total_view'){
		try{
			$sql = "UPDATE store_goods SET {$field} = {$field} + 1  WHERE id = {$id}";
			$result = $this->runSql ($sql );
			if($result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}

}