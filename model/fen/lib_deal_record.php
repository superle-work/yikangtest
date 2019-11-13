<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_fen_deal_record')) require "model/fen/table/m_fen_deal_record.php";

/**
 * 订单交易成功的记录
 * @name lib_cart_data.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_deal_record extends base_model{
    private $m_deal_record;
    public $tableConfigObj;//配置表对象

    function __construct(){
        parent::__construct();
        $this->m_deal_record = new m_fen_deal_record();
        if(!class_exists('utilConfig')) include 'include/UtilConfig.php';
        $this->tableConfigObj = new UtilConfig('fen_config');
    }
    
	/**
	 * 添加订单记录
	 * @param array $dealInfo 订单记录
	 * @return array
	 */
	function addRecord($dealInfo){
		try{
			$dealInfo['add_time'] = common::getTime();
			$addId = $this->m_deal_record->create ( $dealInfo );
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
	 * 更新交易成功记录
	 * @param array $conditions
	 * @param array $row
	 * @return array
	 */
	function updateDealRecord($conditions, $row){
		try{
			if($row['is_check']){
				$row['check_time'] = common::getTime();
			}
			$result = $this->m_deal_record->update ($conditions,$row );
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", "");
		}
	}
	
	/**
	 * 单个删除交易成功记录
	 * @param array $conditions
	 * @return array
	 */
	function deleteDealRecord($conditions){
		try{
			$result = $this->m_deal_record->delete ( $conditions);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 批量删除交易成功记录
	 * @param string $ids 如 1,2,3,4
	 * @return array
	 */
	function deleteDealRecordBatch($ids){
		try{
			$sql = "DELETE FROM fen_deal_record WHERE id in({$ids})";
			$result = $this->m_deal_record->runSql($sql);
			if(true == $result){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 查看总共所属的模块
	 * @return array
	 */
	function getDistinctModule(){
	    try{
	        $sql = "select distinct module from fen_deal_record";
	        $result = $this->m_deal_record->findSql($sql);
	        if($result){
	            return common::errorArray(0, "查找成功", $result);
	        }else{
	            return common::errorArray(1, "查找为空", $result);
	        }
	    }catch (Exception $ex){
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败",$ex);
	    }
	}
	
	/**
	 * 查看单个交易成功记录
	 * @param array $condition
	 * @return array
	 */
	function findDealRecord($condition){
		try{
			$result = $this->m_deal_record->find($condition);
			if($result){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 *分页查询交易成功记录
	 * @param array $page
	 * @param array $conditionList
	 * @param string $sort
	 * @return array
	 */
	function pagingDealRecord($page, $conditionList,$sort){
		$result = $this->m_deal_record->paging($page, $conditionList,$sort);
		if($result['errorCode'] == 1){
		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);
		}
		return $result;
	}

	/**
	 * 分发分销佣金
	 * @param string $module
	 * @param array $orderInfo
	 * @param type $userInfo
	 */
	function getDistributorRank($module, $orderInfo, $userInfo){
	    if($orderInfo == '' || $module == '') return false;
	    //是否分销商
        if(!class_exists(UtilConfig)) include 'include/UtilConfig.php';
        $baseConfig = new UtilConfig('base_config');
	    if(!class_exists('lib_distributor')) include 'model/fen/lib_distributor.php';
	    $lib_distributor = new lib_distributor();
		$fenConfig = new UtilConfig('fen_config');
        $storeConfig = new UtilConfig("{$module}_config");
        $isFenConfig = $fenConfig->findConfigKeyValue();
        $isBaseFenConfig = $baseConfig->findConfigKeyValue('change');
        $isStoreFenConfig = $storeConfig->findConfigKeyValue();
		if($isBaseFenConfig['data']['plat_is_fen'] != 1 && $isStoreFenConfig['data']['is_fen'] != 1){
			return false;
		}
		
        //分销商处理
        $result = $this->distributorManage($userInfo);
        if($result){
            $distributor = $result;
        }else{
            return false;
        }
	    
	    $lib_goods = $this->getModuleGoods($module);//获取产品对象
	    $moduleText = $this->getModule($module);//获取模块名称
        
        //是否开启分销,为分销商增加佣金
        if($isBaseFenConfig['data']['plat_is_fen'] == 1 && $isStoreFenConfig['data']['is_fen'] == 1){
            if($distributor['errorCode'] == 0){
                //分销佣金模式
                $self_rank_ratio = 0;//一级分销佣金
                $parent_rank_ratio = 0;//二级分销佣金
                $grand_rank_ratio = 0;//三级分销佣金
                if($isFenConfig['data']['cash_type'] == 0){//按订单消费额
                    $price = $module != 'trip' ? $orderInfo['data']['total_price'] : $orderInfo['data']['full_total_price'];
                    $self_rank_ratio = $isFenConfig['data']['self_rank_ratio'] * $price;
                    $parent_rank_ratio = $isFenConfig['data']['parent_rank_ratio'] * $price;
                    $grand_rank_ratio = $isFenConfig['data']['grand_rank_ratio'] * $price;
                    
                    //构建goods_list
                    if($module == 'group' || $module == 'bargain'){
                        $gid = $orderInfo['data']['goods_id'];
                        $goods_info = $lib_goods->findGoods(array('id' => $gid));
                        $price = $module == 'group' ? $orderInfo['data']['price'] : $goods_info['data']['ori_price'] - $orderInfo['data']['bargain_money'];
                        $count = $module == 'group' ? $orderInfo['data']['goods_count'] : 1;
                    }else if($module == 'trip'){
                        $gid = $orderInfo['data']['gid'];
                        $goods_info = $lib_goods->findGoods(array('id' => $gid));
                        $totalPrice = $orderInfo['data']['goods_list']['joiner_adult_counts'] * $orderInfo['data']['goods_list']['a_adult_price'] + $orderInfo['data']['goods_list']['joiner_child_counts'] * $orderInfo['data']['goods_list']['a_child_price'];
                        $count = $orderInfo['data']['goods_list']['joiner_counts'];
                        $price = printf("%.2f",$totalPrice/$count);
                    }else if($module == 'hotel'){
                        $gid = $orderInfo['data']['rid'];
                        $goods_info = $lib_goods->findGoods(array('id' => $gid));
                        $count = $orderInfo['data']['date_count'] * $orderInfo['data']['goods_count'];
                    }else if($module == 'rob'){
                        $gid = $orderInfo['data']['grid'];
                        $goods_info = $lib_goods->findGoods(array('id' => $gid));
                        $count = $orderInfo['data']['goods_info']->goods_count;
                    }
                    $orderInfo['data']['goods_list']['gid'][0] = $gid;
                    $orderInfo['data']['goods_list']['goods_name'][0] = $goods_info['data']['name'];
                    $orderInfo['data']['goods_list']['count'][0] = $count ? $count : 1;
                    $orderInfo['data']['goods_list']['thumb'][0] = $goods_info['data']['thumb'];
                    $orderInfo['data']['goods_list']['price'][0] = $price ? $price : $goods_info['data']['price'];
                    
                }else if($isFenConfig['data']['cash_type'] == 1){//按商品系数
                    if($module == 'store' || $module == 'dining'){//百货商城、订餐商城
                        foreach ($orderInfo['data']['goods_list'] as $goods){
                            if($module == 'store'){
                                $gid = $goods['gid'];
                                $totalPrice = $goods['price']*$goods['count'];
                            }else if($module == 'dining'){
                                $gid = $goods->gid;
                                $totalPrice = $goods->price * $goods->count;
                            }
                            $conditions = array('id' => $gid);
                            $goods_info = $lib_goods->findGoods($conditions);
                            $self_rank_ratio += $goods_info['data']['self_fee_ratio'] * $totalPrice;
                            $parent_rank_ratio += $goods_info['data']['parent_fee_ratio'] * $totalPrice;
                            $grand_rank_ratio += $goods_info['data']['grand_fee_ratio'] * $totalPrice;
                        }
                    }elseif($module == 'trip' || $module == 'group' || $module == 'hotel' || $module == 'bargain' || $module = 'rob'){//旅游商城、酒店、砍价、团购、抢购
                        if($module == 'group' || $module == 'bargain'){
                            $gid = $orderInfo['data']['goods_id']; 
                            $goods_info = $lib_goods->findGoods(array('id' => $gid));
                            $totalPrice = $module == 'group' ? $orderInfo['data']['price']*$orderInfo['data']['goods_count'] : $goods_info['data']['ori_price'] - $orderInfo['data']['bargain_money'];
                            $price = $module == 'group' ? $orderInfo['data']['price'] : $goods_info['data']['ori_price'] - $orderInfo['data']['bargain_money'];
                            $count = $module == 'group' ? $orderInfo['data']['goods_count'] : 1;

                        }else if($module == 'trip'){
                            $gid = $orderInfo['data']['gid'];
                            $goods_info = $lib_goods->findGoods(array('id' => $gid));
                            $totalPrice = $orderInfo['data']['goods_list']['joiner_adult_counts'] * $orderInfo['data']['goods_list']['a_adult_price'] + $orderInfo['data']['goods_list']['joiner_child_counts'] * $orderInfo['data']['goods_list']['a_child_price'];
                            $count = $orderInfo['data']['goods_list']['joiner_counts'];
                            $price = sprintf("%.2f",$totalPrice/$count);
                        }else if($module == 'hotel'){
                            $gid = $orderInfo['data']['rid'];
                            $goods_info = $lib_goods->findGoods(array('id' => $gid));
                            $totalPrice = $goods_info['data']['price'] * $orderInfo['data']['date_count'] * $orderInfo['data']['goods_count'];
                            $count = $orderInfo['data']['date_count'] * $orderInfo['data']['goods_count'];
                        }else if($module == 'rob'){
                            $gid = $orderInfo['data']['grid'];
                            $goods_info = $lib_goods->findGoods(array('id' => $gid));
                            $totalPrice = $orderInfo['data']['goods_info']->price * $orderInfo['data']['goods_info']->goods_count;
                            $count = $orderInfo['data']['goods_info']->goods_count;
                        }
                        //构建goods_list
                        $orderInfo['data']['goods_list'][0]['gid'] = $gid;
                        $orderInfo['data']['goods_list'][0]['goods_name'] = $goods_info['data']['name'];
                        $orderInfo['data']['goods_list'][0]['count'] = $count ? $count : 1;
                        $orderInfo['data']['goods_list'][0]['thumb'] = $goods_info['data']['thumb'];
                        $orderInfo['data']['goods_list'][0]['price'] = $price ? $price : $goods_info['data']['price'];
                        
                        
                        $self_rank_ratio += $goods_info['data']['self_fee_ratio'] * $totalPrice;
                        $parent_rank_ratio += $goods_info['data']['parent_fee_ratio'] * $totalPrice;
                        $grand_rank_ratio += $goods_info['data']['grand_fee_ratio'] * $totalPrice;
                    }
                }
                try {
	                //一级分销佣金,一级维护销售额
	                if($distributor['data']['parent_id']){
	                    $conditions = array('id'=>$distributor['data']['parent_id']);
	                    $parent_distributor = $lib_distributor->findDistributor($conditions);
	                    if($parent_distributor['data']['is_use'] == 1){
    	                    $row = array(
    	                        'fir_fee'=>$parent_distributor['data']['fir_fee'] + $self_rank_ratio,
    	                        'total_fee'=>$parent_distributor['data']['total_fee'] + $self_rank_ratio,
    	                        'my_fee'=>$parent_distributor['data']['my_fee'] + $self_rank_ratio,
    	                        'total_sales_fee'=>$parent_distributor['data']['total_sales_fee'] + $orderInfo['data']['total_price']
    	                    );
    	                    $result = $lib_distributor->updateDistributor($conditions, $row);
    	                    if($result['errorCode'] != 0){return common::errorArray(1, '一级佣金更新失败', $result);}
	                    }
	                }
	                //上级分销佣金
	                if($distributor['data']['grand_id']){
	                    $conditions = array('id'=>$distributor['data']['grand_id']);
	                    $grand_distributor = $lib_distributor->findDistributor($conditions);
	                    if($grand_distributor['data']['is_use'] == 1){
    	                    $row = array(
    	                        'sec_fee'=>$grand_distributor['data']['sec_fee'] + $parent_rank_ratio,
    	                        'total_fee'=>$grand_distributor['data']['total_fee'] + $parent_rank_ratio,
    	                        'my_fee'=>$grand_distributor['data']['my_fee'] + $parent_rank_ratio,
    	                        'total_sales_fee'=>$grand_distributor['data']['total_sales_fee'] + $orderInfo['data']['total_price']
    	                    );
    	                    $result = $lib_distributor->updateDistributor($conditions, $row);
    	                    if($result['errorCode'] != 0){return common::errorArray(1, '上级佣金更新失败', $result);}
	                    }
	                }
	                //上上分销佣金
	                if($distributor['data']['grand_grand_id']){
	                    $conditions = array('id'=>$distributor['data']['grand_grand_id']);
	                    $grand_grand_distributor = $lib_distributor->findDistributor($conditions);
	                    if($grand_grand_distributor['data']['is_use'] == 1){
    	                    $row = array(
    	                        'thr_fee'=>$grand_grand_distributor['data']['thr_fee'] + $grand_rank_ratio,
    	                        'total_fee'=>$grand_grand_distributor['data']['total_fee'] + $grand_rank_ratio,
    	                        'my_fee'=>$grand_grand_distributor['data']['my_fee'] + $grand_rank_ratio,
    	                        'total_sales_fee'=>$grand_grand_distributor['data']['my_fee'] + $orderInfo['data']['total_price']
    	                    );
    	                    $result = $lib_distributor->updateDistributor($conditions, $row);
    	                    if($result['errorCode'] != 0){return common::errorArray(1, '上上级佣金更新失败', $result);}
	                    }
	                }
	                //插入交易记录
	                if(!class_exists('lib_deal_record')) include 'model/fen/lib_deal_record.php';
	                $lib_deal_record = new lib_deal_record();
	                if(!class_exists('lib_deal_goods')) include 'model/fen/lib_deal_goods.php';
	                $lib_deal_goods = new lib_deal_goods();
	                
	                $dealInfo = array(
	                    'oid' => $orderInfo['data']['id'],
	                    'order_num' => $orderInfo['data']['order_num'],
	                    'money' => $module != 'trip' ? $orderInfo['data']['total_price'] : $orderInfo['data']['full_total_price'],
	                    'distributor_name' => $distributor['data']['name'],
	                    'distributor_id' => $distributor['data']['id'],
	                    'nick_name' => $orderInfo['data']['nick_name'],
	                    'account' => $orderInfo['data']['account'],
	                    'address_text' => $orderInfo['data']['address_text'],
	                    'pay_method' => $orderInfo['data']['pay_method'],
	                    'self_fee' => $self_rank_ratio,
	                    'parent_fee' =>$parent_rank_ratio,
	                    'grand_fee' => $grand_rank_ratio,
	                    'add_time' => common::getTime(),
	                    'module' => $module,
	                );
	                $recordResult = $lib_deal_record->addRecord($dealInfo);
	                if($recordResult['errorCode'] != 0)return $result;
	                
	                $goodsList = (array)$orderInfo['data']['goods_list'];
	                
	                //添加交易产品记录
	                foreach ($goodsList as $goods){
	                    $goods_info = $lib_goods->findGoods($goods['gid']);
	                    
    	                $dealInfo = array(
    	                    'drid'=>$recordResult['data'],
    	                    'gid'=>$goods['gid'],
    	                    'goods_name'=>$goods['goods_name'],
    	                    'count'=>$goods['count'],
    	                    'thumb'=>$goods['thumb'],
    	                    'price'=>$goods['price'],
    	                    'module'=>$moduleText
    	                );
    	                $result = $lib_deal_goods->addGoods($dealInfo);
    	                if($result['errorCode'] != 0) return $result;
	                }
	                return common::errorArray(0, '佣金更新成功', true);
                }catch(Exception $ex){
                    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
                    return common::errorArray(1, "数据库操作失败",$ex);
                }
            }
        }
	}
	
	/**
	 * 分销商处理
	 * @param array $userInfo
	 */
	function distributorManage ($userInfo){
	    if(!class_exists('lib_distributor')) include 'model/fen/lib_distributor.php';
	    $lib_distributor = new lib_distributor();
        //成为分销商的要求
        if(!class_exists(UtilConfig)) include 'include/UtilConfig.php';
        $fenConfig = new UtilConfig('fen_config');
        $config = $fenConfig->findConfigKeyValue();
        $distributor = $lib_distributor->findDistributor(array('user_id'=>$userInfo['data']['id']));
	    if($userInfo['data']['type'] != 3){    //非分销商
            if($config['data']['distribute_fee'] != 0){     //消费额   > 成为分销商的最低消费额，当为0时关注即为分销商
                if($userInfo['data']['total_fee'] >= $config['data']['distribute_fee']){  //达到成为分销商的最低条件: 添加分销表记录
                    $applyInfo = array(
                        'name'=>$userInfo['data']['nick_name'],
                        'phone'=>$userInfo['data']['phone'],
                        'user_id'=>$userInfo['data']['id'],
                        'nick_name'=>$userInfo['data']['nick_name'],
                        'level'=>1,
                    );
                    try {
                        $result = $lib_distributor->addDistributor($applyInfo);     //添加分销商信息成功：维护  base_user 表的  type
                        if($result['errorCode'] == 0){
                            if(!class_exists('lib_user')) include 'model/base/lib_user.php';
                            $lib_user = new lib_user();
                            $lib_user->updateUser(array('id' => $userInfo['data']['id']), array('type' => 3));
                            $distributor = $lib_distributor->findDistributor(array('id'=>$result['data']));
                            return $distributor;
                        }else{
                            return common::errorArray(1, '插入失败', $result);
                        }
                    }catch (Exception $ex){
                        return common::errorArray(1, '数据库操作失败', $ex);
                    }
                }else{//无法成为分销商
                   return false; 
                }
            }else{
                return $distributor;
            }
	    }else{
	        if($distributor['data']['level'] == 0){    //下线（非分销商）
	            if($userInfo['data']['total_fee'] >= $config['data']['distribute_fee']){
	                try {
	                    $result = $lib_distributor->updateDistributor(array('user_id'=>$userInfo['data']['id']),array('level'=>1));
	                    if($result['errorCode'] == 0){
	                        $distributor = $lib_distributor->findDistributor(array('user_id'=>$userInfo['data']['id']));
	                        return $distributor;
	                    }else{
	                        return common::errorArray(1, '更新失败', $result);
	                    }
	                }catch (Exception $ex){
	                    return common::errorArray(1, '数据库操作失败', $ex);
	                }
	            }else{
	                return $distributor;
	            }
	        }else{
	            return $distributor;
	        }
	    }
	}
	
	/**
	 * 标识模块产品
	 * @param $module
	 * @return goods_obj $lib_goods
	 */
	function getModuleGoods($module){
	    if(!$module) return false;
	    switch ($module){
	        case 'store':
	            if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';
	            $lib_goods = new lib_goods();
	            break;
	        case 'trip':
	            if(!class_exists('lib_trip_goods')) include 'model/trip/lib_trip_goods.php';
	            $lib_goods = new lib_trip_goods();
	            break;
	        case 'duo':
	            if(!class_exists('lib_duo_goods')) include 'model/duo/lib_duo_goods.php';
	            $lib_goods = new lib_duo_goods();
	            break;
	        case 'rob':
	            if(!class_exists('lib_rob_goods')) include 'model/rob/lib_rob_goods.php';
	            $lib_goods = new lib_rob_goods();
	            break;
	        case 'group':
	            if(!class_exists('lib_group_goods')) include 'model/group/lib_group_goods.php';
	            $lib_goods = new lib_group_goods();
	            break;
	        case 'hotel':
	            if(!class_exists('lib_hotel_room')) include 'model/hotel/lib_hotel_room.php';
	            $lib_goods = new lib_hotel_room();
	            break;
	        case 'dining':
	            if(!class_exists('lib_dining_goods')) include 'model/dining/lib_dining_goods.php';
	            $lib_goods = new lib_dining_goods();
	            break;
	        case 'bargain':
	            if(!class_exists('lib_bargain_goods')) include 'model/bargain/lib_bargain_goods.php';
	            $lib_goods = new lib_bargain_goods();
	            break;
	    }
	    return $lib_goods;
	}
	
	/**
	 * 获取模块名称
	 * @param string $module
	 * @return string $module
	 */
	function getModule($module){
	    switch($module){
	        case 'store':
	            $moduleString = '百货商城';
	            break;
	        case 'duo':
	            $moduleString = '夺宝商城';
	            break;
	        case 'group':
	            $moduleString = '团购商城';
	            break;
	        case 'dining':
	            $moduleString = '订餐商城';
	            break;
	        case 'hotel':
	            $moduleString = '微酒店';
	            break;
	        case 'rob':
	            $moduleString = '抢购商城';
	            break;
	        case 'trip':
	            $moduleString = '旅游商城';
	            break;
	        case 'bargain':
	            $moduleString = '砍价商城';
	            break;
	        default:
	            $moduleString = '其它商城';
	            break;
	    }
	    return $moduleString;
	}
	
	/**
	 *分页查询交易统计记录
	 * @param array $page
	 * @param array $conditionList
	 * @param array $moneyList
	 * @param string $sort 
	 * @param string $group
	 * @return array
	 */
	function pagingdealAnalyse($page,$conditionList,$timeList,$sort){
	    //通用条件
	    $where = $this->pagingWhere($conditionList);
	    $where = rtrim($where,"AND");
	    if(count($conditionList) > 0){
	        $where = "where".$where;
	    }
	    //时间非通用条件
	    $time = $this->pagingWhere($timeList);
	    $time = rtrim($time,"AND");
	    if(count($timeList) > 0){
	        $time = "where".$time;
	    }
	    $sql = <<<EOT
    	    select @rownum:=@rownum+1 AS rownum,a.add_time,a.name,a.id,a.total_sales_fee,ifnull(b.num,0) as ch_num,ifnull(c.num,0) as ch_ch_num,ifnull(d.num,0) as ch_ch_ch_num,ifnull(g.money,0) as ch_fee,ifnull(j.money,0) as ch_ch_fee,ifnull(m.money,0) as ch_ch_ch_fee from
    	        (select @rownum:=0,id,add_time,total_sales_fee,name from fen_distributor {$where} {$sort}) as a 
    	        left join (select parent_id,count(1) as num from fen_distributor {$time} group by parent_id) as b on a.id = b.parent_id
    	        left join (select grand_id,count(1) as num from fen_distributor {$time} group by grand_id) as c on a.id = c.grand_id
    	        left join (select grand_grand_id,count(1) as num from fen_distributor {$time} group by grand_grand_id) as d on a.id = d.grand_grand_id
                left join (select sum(f.money) as money,e.parent_id from (select id,parent_id from fen_distributor) as e 
        	               left join fen_deal_record as f on e.id = f.distributor_id {$time} group by e.parent_id)as g on a.id = g.parent_id
                left join (select sum(i.money) as money,h.grand_id from (select id,grand_id from fen_distributor) as h 
        	               left join fen_deal_record as i on h.id = i.distributor_id {$time} group by h.grand_id)as j on a.id = j.grand_id
                left join (select sum(l.money) as money,k.grand_grand_id from (select id,grand_grand_id from fen_distributor) as k 
        	               left join fen_deal_record as l on k.id = l.distributor_id {$time} group by k.grand_grand_id)as m on a.id = m.grand_grand_id
EOT;
	    $result = $this->m_deal_record->pagingCommon($page, $sql, $sort = null);
        if($result['errorCode'] == 1){
            $this->errorLog(__CLASS__,__FUNCTION__,$result);
        }
        return $result;
	}
	
	/**
	 *分页查询客户列表记录
	 * @param array $page
	 * @param array $conditionList
	 * @param array $moneyList
	 * @param string $sort 
	 * @param string $group
	 * @return array
	 */
	function pagingDealAccount($page,$conditionList,$moneyList = array(),$sort,$group = ''){
		$page['pageIndex'] ? $pageIndex = $page['pageIndex'] : $pageIndex = 1;
		$page['pageSize'] ? $pageSize = $page['pageSize'] : $pageSize = 10;
		
		//分组
		if(null != $group && '' != $group){
			$group = "GROUP BY {$group}";
		}
		//排序
		if(null != $sort && '' != $sort){
			$sort = "ORDER BY {$sort}";
		}
		//和连接条件
		$whereString = "";
		if(count($conditionList) > 0){
			foreach ($conditionList as $condition){
				if('like' == $condition['operator']){
					$whereString .= " {$condition['field']} like '%{$condition['value']}%' AND";
				}else if('in' == $condition['operator']){
					$whereString .= " {$condition['field']} in ({$condition['value']}) AND";
				}else{
					$value = $this->m_deal_record->escape($condition['value']);
					$whereString .= " {$condition['field']} {$condition['operator']} {$value} AND";
				}
			}
			$whereString = rtrim($whereString,"AND");
			$sql = "SELECT @rownum:=@rownum+1 AS rownum,nick_name,account,total_money,times from (SELECT @rownum:=0,nick_name,account,sum(money) AS total_money,count(order_num) AS times FROM fen_deal_record WHERE{$whereString} {$group} {$sort} ) AS a";
		}else{
				$sql = "SELECT @rownum:=@rownum+1 AS rownum,nick_name,account,total_money,times from ( SELECT @rownum:=0,nick_name,account,sum(money) AS total_money,count(order_num) AS times FROM fen_deal_record {$group} {$sort} ) AS a";
		}
		
		//总金额连接条件
		$moneyString = "";
		//判断是否取money数组
		if(count($moneyList) > 0){
			foreach ($moneyList as $money){
				if('like' == $money['operator']){
					$moneyString .= " {$money['field']} like '%{$money['value']}%' AND";
				}else{
					$value = $this->m_deal_record->escape($money['value']);
					$moneyString .= " {$money['field']} {$money['operator']} {$value} AND";
				}
			}
			$moneyString = rtrim($moneyString,"AND");
			$sql = "SELECT rownum,nick_name,account,total_money,times from ( {$sql} ) AS b WHERE{$moneyString}";
		}
		
		//分页
		$m = ($pageIndex -1) * $pageSize;
		$n =  $pageSize;
		$sqlLimit =  "SELECT rownum,nick_name,account,total_money,times from ( {$sql} ) AS c LIMIT {$m}, {$n}";
		try {
			$result ['dataList'] = $this->m_deal_record->findSql($sqlLimit);
			$sql = "SELECT count(*) as total_record_num  from ( {$sql} ) as count_table";
			$count = $this->m_deal_record->findSql($sql);
			//如果之后1页，手动添加分页信息
			if($result['pageInfo']==NULL){
				$result['pageInfo']['current_page'] = $pageIndex;
				$result['pageInfo']['first_page'] = 1;
				$result['pageInfo']['prev_page']=$pageIndex - 1;
				$result['pageInfo']['next_page']=$pageIndex + 1;
				$result['pageInfo']['last_page']=ceil ($count[0]['total_record_num'] / $pageSize);
				$result['pageInfo']['total_count']= $count[0]['total_record_num'];
				$result['pageInfo']['total_page'] = ceil ($count[0]['total_record_num'] / $pageSize);
				$result['pageInfo']['page_size'] = $pageSize;
				$result['pageInfo']['all_pages'] = ceil ($count[0]['total_record_num'] / $pageSize);
			}
			return common::errorArray(0, "查询成功", $result);
		} catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 分销商个人数据分析
	 * @param array $info array(did,state,date)
	 * return array $result
	 */
	function dealPieChartData($info){
	    try{
	        $time = date('Y-m-d H:i:s',strtotime("-{$info['date']} days"));
	        $time = " where add_time <= '{$time}' ";
	        $where = " where id = {$info['did']} ";
	        if($info['state'] == 0){//统计人数
	            $sql = <<<EOT
    	    select a.add_time,a.name,a.id,(ifnull(b.num,0)+ifnull(c.num,0)+ifnull(d.num,0))as total_count,ifnull(b.num,0) as ch_count,ifnull(c.num,0) as ch_ch_count,ifnull(d.num,0) as ch_ch_ch_count from
    	        (select id,add_time,total_sales_fee,name from fen_distributor {$where}) as a
    	        left join (select parent_id,count(1) as num from fen_distributor {$time} group by parent_id) as b on a.id = b.parent_id
    	        left join (select grand_id,count(1) as num from fen_distributor {$time} group by grand_id) as c on a.id = c.grand_id
    	        left join (select grand_grand_id,count(1) as num from fen_distributor {$time} group by grand_grand_id) as d on a.id = d.grand_grand_id
EOT;
	        }else{//统计销售额
	            $sql = <<<EOT
    	    select a.add_time,a.name,a.id,(ifnull(g.money,0)+ifnull(j.money,0)+ifnull(m.money,0))as total_count,ifnull(g.money,0) as ch_count,ifnull(j.money,0) as ch_ch_count,ifnull(m.money,0) as ch_ch_ch_count from
    	        (select id,add_time,name from fen_distributor {$where}) as a
                left join (select sum(f.money) as money,e.parent_id from (select id,parent_id from fen_distributor) as e
        	               left join fen_deal_record as f on e.id = f.distributor_id {$time} group by e.parent_id)as g on a.id = g.parent_id
                left join (select sum(i.money) as money,h.grand_id from (select id,grand_id from fen_distributor) as h
        	               left join fen_deal_record as i on h.id = i.distributor_id {$time} group by h.grand_id)as j on a.id = j.grand_id
                left join (select sum(l.money) as money,k.grand_grand_id from (select id,grand_grand_id from fen_distributor) as k
        	               left join fen_deal_record as l on k.id = l.distributor_id {$time} group by k.grand_grand_id)as m on a.id = m.grand_grand_id
EOT;
	        }
	        $countResult = $this->m_deal_record->findSql($sql);
	        $countInfo = $countResult['0'];
	        $dataList = array(
	            array('name'=>'下一级分销商','y' => sprintf("%.4f",$countInfo['ch_count']/$countInfo['total_count'])*100,'num' => $countInfo['ch_count'],'color' => '#E72F2E'),
	            array('name'=>'下二级分销商','y' => sprintf("%.4f",$countInfo['ch_ch_count']/$countInfo['total_count'])*100,'num' => $countInfo['ch_ch_count'],'color' => '#08CD75'),
	            array('name'=>'下三级分销商','y' => sprintf("%.4f",$countInfo['ch_ch_ch_count']/$countInfo['total_count'])*100,'num' => $countInfo['ch_ch_ch_count'],'color' => '#05D3CF'),
	        );
	        return common::errorArray(0, '统计成功', $dataList);
	    } catch (Exception $ex) {
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return common::errorArray(1, "数据库操作失败", $ex);
		}
	}
	
	/**
	 * 三级分销发展趋势
	 * @param array $info array(did,state,date)
	 * return array $result
	 */
	function dealLineChartData($info){
	    try{
	        //年月日时间判断 5年 12个月 30天
	        $timeDetail = $this->getTimeInfo($info['date']);//获取时间详情
	        $timeList = $this->getTimeArray($timeDetail);//获取时间段
	        $time = " and add_time >= '{$timeDetail['start']}' ";
	        if($info['state'] == 0){//统计人数
	            $sql = <<<EOT
                select a.add_date,sum(a.parent_id) as ch_count,sum(a.grand_id) as ch_ch_count,sum(a.grand_grand_id) as ch_ch_ch_count from(                
                    select id,date_format(add_time,'{$timeDetail['format']}') as add_date,1 as parent_id,0 as grand_id,0 as grand_grand_id  from fen_distributor where parent_id = {$info['did']} {$time}
        	        union
        	        select id,date_format(add_time,'{$timeDetail['format']}') as add_date,0 as parent_id,1 as grand_id,0 as grand_grand_id from fen_distributor where grand_id = {$info['did']} {$time}
        	        union
        	        select id,date_format(add_time,'{$timeDetail['format']}') as add_date,0 as parent_id,0 as grand_id,1 as grand_grand_id from fen_distributor where grand_grand_id = {$info['did']} {$time}
                )as a group by a.add_date;
EOT;
	        }else{//统计销售额
	            $sql = <<<EOT
    	       select z.add_date,sum(ch_count) as ch_count,sum(z.ch_ch_count) as ch_ch_count,sum(z.ch_ch_ch_count) as ch_ch_ch_count from           
    	        (
        	        select add_time as add_date,sum(f.money) as ch_count,0 as ch_ch_count,0 as ch_ch_ch_count from (select id,parent_id from fen_distributor where parent_id = {$info['did']}) as e
            	               left join (select distributor_id,money,date_format(add_time,'{$timeDetail['format']}') as add_time from fen_deal_record) as f on e.id = f.distributor_id {$time} group by f.add_time,e.parent_id
            	    union
                    select add_time as add_date,0 as ch_count,sum(i.money) as ch_ch_count,0 as ch_ch_ch_count from (select id,grand_id from fen_distributor where grand_id = {$info['did']}) as h
            	               left join (select distributor_id,money,date_format(add_time,'{$timeDetail['format']}') as add_time from fen_deal_record) as i on h.id = i.distributor_id {$time} group by i.add_time,h.grand_id
                    union
                    select add_time as add_date,0 as ch_count,0 as ch_ch_count,sum(l.money) as ch_ch_ch_count from (select id,grand_grand_id from fen_distributor where grand_grand_id = {$info['did']}) as k
            	               left join (select distributor_id,money,date_format(add_time,'{$timeDetail['format']}') as add_time from fen_deal_record) as l on k.id = l.distributor_id {$time} group by l.add_time,k.grand_grand_id
        	    ) as z group by z.add_date
EOT;
	        }
	        $countList = $this->m_deal_record->findSql($sql);
	        foreach($timeList as $time_value){
	            foreach($countList as $count_key => $count_value){
	                if($time_value == $count_value['add_date']){
    	                $child[] = (int)$count_value['ch_count'];
    	                $child_child[] = (int)$count_value['ch_ch_count'];
    	                $child_child_child[] = (int)$count_value['ch_ch_ch_count'];
    	                continue 2;
	                }
	            }
	            $child[] = $child_child[] = $child_child_child[] = 0;
	        }
	        
	        $dataList = array(
	            array('name'=>'下一级分销商','data' => $child,'color' => '#E72F2E'),
	            array('name'=>'下二级分销商','data' => $child_child,'color' => '#08CD75'),
	            array('name'=>'下三级分销商','data' => $child_child_child,'color' => '#05D3CF'),
	        );
	        return common::errorArray(0, '统计成功', array('categories' => $timeList,'series' => $dataList));
	    } catch (Exception $ex) {
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 交易统计（针对下一级的发展人数，销售额统计）
	 * @param array $info array(state,date)
	 * return array $result
	 */
	function dealBarChartData($info){
	    try{
	        //年月日时间判断 5年 12个月 30天
	        $timeDetail = $this->getTimeInfo($info['date']);//获取时间详情
	        $timeList = $this->getTimeArray($timeDetail);//获取时间段
	        $time = " where add_time >= '{$timeDetail['start']}' ";
	        if($info['state'] == 0){//统计人数
	            $sql = <<<EOT
	            select c.* from (
	               select a.name,b.* from fen_distributor as a 
	               left join (select date_format(add_time,'{$timeDetail['format']}') as add_date,parent_id,count(parent_id) as num from fen_distributor {$time} group by parent_id) as b on a.id = b.parent_id
	            )as c where(
	               select count(1) from (
                       select d.name,e.* from fen_distributor as d 
                       left join (select date_format(add_time,'{$timeDetail['format']}') as add_date,parent_id,count(parent_id) as num from fen_distributor {$time} group by parent_id) as e on d.id = e.parent_id
                   )as f where f.add_date = c.add_date and f.num <= c.num
                ) <=3 and c.num > 0 order by c.num desc
EOT;
	        }else{//统计销售额
	            $sql = <<<EOT
	            select ifnull(d.name,'顶级分销商（自主）') as name,d.add_date,d.num from (
	               select c.name,b.add_date,sum(b.money) as num from fen_distributor as a 
	               left join (select date_format(add_time,'{$timeDetail['format']}') as add_date,distributor_id,money from fen_deal_record {$time}) as b on a.id = b.distributor_id
	               left join fen_distributor as c on a.parent_id = c.id group by b.distributor_id,b.add_date
	            )as d where(
	               select count(1) from (
                       select g.name,f.add_date,sum(f.money)as num from fen_distributor as e 
    	               left join (select date_format(add_time,'{$timeDetail['format']}') as add_date,distributor_id,money from fen_deal_record {$time}) as f on e.id = f.distributor_id
    	               left join fen_distributor as g on e.parent_id = g.id group by f.distributor_id,f.add_date
                   )as h where h.add_date = d.add_date and h.num <= d.num
                ) <=3 and d.num > 0 order by d.num desc
EOT;
	        }
	        $countList = $this->m_deal_record->findSql($sql);
	        $default_data = array('y' => 0,'title' => '--');
	        foreach($timeList as $time_value){
	            $option = array();
	            foreach($countList as $count_key => $count_value){
	                if($time_value == $count_value['add_date']){
	                    $option[] = array('y' => (int)$count_value['num'],'title' => $count_value['name']);
	                }
	            }
	            //判断是否有前三名，再分别放入每一名的人员中
	            for($i = 0;$i <= 2;$i++){
	                if($option[$i]){//存在前三名
	                    if($i == 0){
	                        $child[] = $option[$i];
	                    }elseif($i == 1){
	                        $child_child[] = $option[$i];
	                    }else{
	                        $child_child_child[] = $option[$i];
	                    }
	                }else{//不存在前三名
	                    if($i == 0){
	                        $child[] = $default_data;
	                    }elseif($i == 1){
	                        $child_child[] = $default_data;
	                    }else{
	                        $child_child_child[] = $default_data;
	                    }
	                }
	            }
	        }
	        
	        $dataList = array(
	            array('name'=>'第一名','data' => $child,'color' => '#E72F2E'),
	            array('name'=>'第二名','data' => $child_child,'color' => '#08CD75'),
	            array('name'=>'第三名','data' => $child_child_child,'color' => '#05D3CF'),
	        );
	        return common::errorArray(0, '统计成功', array('dateList' => $timeList,'dataList' => $dataList));
	    } catch (Exception $ex) {
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return common::errorArray(1, "数据库操作失败", $ex);
	    }
	}
	
	/**
	 * 获取时间段数组
	 * @param array $time array(count,type,str)
	 * @return array
	 */
	private function gettimeArray($time){
	    for($i = 0; $i < $time['count'] + 1; $i ++){
	        $date_list[$i] = date($time['str'],strtotime(($i -  $time['count'])." {$time['type']}"));
	    }
	    return $date_list;
	}
	
	/**
	 * 获取时间信息数组
	 * @param string $time
	 */
	private function getTimeInfo($time){
	    switch($time){
	        case 'day':
	            $timeInfo['count'] = 29;
	            $timeInfo['start'] = date('Y-m-d',strtotime("-{$timeInfo['count']} {$time}"));
	            $timeInfo['str'] = 'Y-m-d';
	            $timeInfo['format'] = '%Y-%m-%d';
	        break;
	        case 'month':
	            $timeInfo['count'] = 11;
	            $timeInfo['start'] = date('Y-m',strtotime("-{$timeInfo['count']} {$time}"));
	            $timeInfo['str'] = 'Y-m';
	            $timeInfo['format'] = '%Y-%m';
	        break;
	        case 'year':
	            $timeInfo['count'] = 4;
	            $timeInfo['start'] = date('Y',strtotime("-{$timeInfo['count']} {$time}"));
	            $timeInfo['str'] = 'Y';
	            $timeInfo['format'] = '%Y';
	        break;
	        default:
	            $timeInfo['count'] = 29;
	            $timeInfo['start'] = date('Y-m-d H:i:s',strtotime("-{$timeInfo['count']} {$time}"));
	            $timeInfo['str'] = 'Y-m-d';
	            $timeInfo['format'] = '%Y-%m-%d';
	            break;
	    }
	    $timeInfo['type'] = $time;
	    return $timeInfo;
	}
}

