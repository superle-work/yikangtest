<?php
include_once 'include/UtilDate.php';
/**
 * 提供粉丝统计管理服务
 * @name lib_fans_data.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-05
 */
class lib_fans_data extends spModel{
	/**
	 * 获取用户数据关键指标
	 * @return array
	 */
	function getKeyIndexOfUser(){
		//加入缓存
		$fansCache = spAccess('r' , 'fansCache');
		if($fansCache){
			return common::errorArray(0, "获取成功", $fansCache);
		}
		$UtilDate = new UtilDate();
		$dateList= $UtilDate->getSerialDate(1,'asc',0);
		$from = $dateList[0];
		$to =$dateList[0];
		$rawResult = Statistics::getUserCumulate($from, $to);
		$lastCount = $rawResult['list'][0]['cumulate_user'];//截止昨日关注量
		$rawFansList = FansManage::getAllFansList();
		$totalCount = $rawFansList['total'];//总关注量
		$todayCount = $totalCount - $lastCount;
		$result =  array('todayCount'=>$todayCount,'totalCount'=>$totalCount);
		spAccess('w' , 'fansCache', $result, 3600);
		return common::errorArray(0, "获取成功",$result);
	}
	
	/**
	 * 获取用户增减数据
	 * @param int $dayCount
	 * @param string $from
	 * @param string $to
	 * @param int $userSource
	 * @param true $cache
	 * @return array
	 */
	function getUserSummary($dayCount,$from = '',$to = '',$userSource = 99,$cache){
		if($cache){
			//加入缓存
			$summaryCache = spAccess('r' , 'summaryCache');
			if($summaryCache){
				return common::errorArray(0, "获取数据", $summaryCache);
			}
		}
		$UtilDate = new UtilDate();
		if($dayCount > 0){//传入天数
			$result['xData']= $UtilDate->getSerialDate($dayCount,'asc',0);
			$from = $result['xData'][0];
			$to =$result['xData'][$dayCount - 1];
		}else{//传入日期段 时间跨度不能超过7天，且不包括当天数据
			$result['xData']= $UtilDate->getDataList($from, $to);
		}
		/**
		 * 用户来源
		 * 0代表其他（包括带参数二维码）3代表扫二维码
		 * 17代表名片分享 35代表搜号码（即微信添加朋友页的搜索）
		 * 39代表查询微信公众帐号 43代表图文页右上角菜单
		 * 99代表所有，默认是所有
		 */
		//读取微信数据
		$rawResult = Statistics::getUserSummary($from, $to);
		if(isset($rawResult['errcode'])){
			return common::errorArray(1, "接口错误", $rawResult);
		}
		$rawList = $rawResult['list'];
		$sourceList = array();
		$result['yData'][0]['name'] = "取消关注量";
		$result['yData'][0]['color'] = "#ff6666";
		$result['yData'][1]['name'] = "新增关注量";
		$result['yData'][1]['color'] = "green";
		if(null == $userSource || '' == $userSource){
			$userSource = 99;
		}
		if($userSource == 99){//累加所有的关注数
			//按时间分组
			for ($m = 0;$m < count($result['xData']);$m++){
				$allDateList[$m] = array();
				for ($i = 0;$i < count($rawList);$i++){
					$rawList[$i] = (array)$rawList[$i];
					if($rawList[$i]['ref_date'] == $result['xData'][$m]){
						array_push($allDateList[$m], $rawList[$i]);
					}
				}
			}
			//累加cancel_user和new_user
			for ($n = 0;$n < count($allDateList);$n++){
				if(count($allDateList[$n]) > 1){
					$cancelCount = 0;
					$newCount = 0;
					foreach ($allDateList[$n] as $per){
						$cancelCount += $per['cancel_user'];
						$newCount += $per['new_user'];
					}
					$allDateList[$n] = array(
							'cancel_user'=>$cancelCount,
							'new_user'=>$newCount,
							'ref_date'=>$allDateList[$n][0]['ref_date'],
							'user_source'=>99
					);
				}else{
					$allDateList[$n] = array(
							'cancel_user'=>$allDateList[$n][0]['cancel_user'],
							'new_user'=>$allDateList[$n][0]['new_user'],
							'ref_date'=>$allDateList[$n][0]['ref_date'],
							'user_source'=>99
					);
				}
			}
			//生成y轴数据
			foreach ($allDateList as $per){
				if(null == $per['cancel_user'] || '' == $per['cancel_user']){
					$result['yData'][0]['data'][] =0;
				}else{
					$result['yData'][0]['data'][] = $per['cancel_user'];
				}
				if(null == $per['new_user'] || '' == $per['new_user']){
					$result['yData'][1]['data'][] = 0;
				}else{
					$result['yData'][1]['data'][] = $per['new_user'];
				}
			}
		}else{//指定用户来源的数据
			foreach ($rawList as $per){
				$per = (array)$per;
				if($per['user_source'] == $userSource){
					$sourceList[$userSource][] = $per;
				}
			}
			for($i = 0;$i < count($result['xData']);$i++){
				$isIn = false;
				foreach ($sourceList[$userSource] as $per){
					$per = (array)$per;
					if($per['ref_date'] == $result['xData'][$i]){
						$isIn = true;
						$result['yData'][0]['data'][] = $per['cancel_user'];
						$result['yData'][1]['data'][] = $per['new_user'];
					}
				}
				if(!$isIn){//补全数据
					$result['yData'][0]['data'][] = 0;
					$result['yData'][1]['data'][] = 0;
				}
			}
		}
		if($cache){
			spAccess('w' , 'summaryCache', $result, 3600);
		}
		return common::errorArray(0, "获取数据", $result);
	}
	
	/**
	 * 判断日期是否在数据里
	 * @param string $date
	 * @param array $rawList
	 * @return multitype:string number |multitype:number boolean
	 */
	private function isDataIn($date,$rawList){
		for ($i = 0;$i < count($rawList);$i++){
			$rawList[$i] = (array)$rawList[$i];
			if($rawList[$i]['ref_date'] == $date){
				return array('index'=>$i,'result'=>ture);
			}
		}
		return array('index'=>0,'result'=>false);
	}
	
	/**
	 * 获取累计用户数据
	 * @param int $dayCount
	 * @param string $from
	 * @param string $to
	 * @return array
	 */
	function getUserCumulate($dayCount,$from = '',$to = '',$cache){
		if($cache){
			//加入缓存
			$cumulateCache = spAccess('r' , 'cumulateCache');
			if($cumulateCache){
				return common::errorArray(0, "获取数据", $cumulateCache);
			}
		}
		$UtilDate = new UtilDate();
		if($dayCount > 0){//传入天数
			$result['xData']= $UtilDate->getSerialDate($dayCount,'asc',0);
			$from = $result['xData'][0];
			$to =$result['xData'][$dayCount - 1];
		}else{//传入日期段 时间跨度不能超过7天，且不包括当天数据
			$result['xData']= $UtilDate->getDataList($from, $to);
		}
		$rawResult = Statistics::getUserCumulate($from, $to);
		$result['yData'][0]['name'] = "粉丝量";
		$result['yData'][0]['color'] = "green";
		foreach ($rawResult['list'] as $per){
			$result['yData'][0]['data'][] = $per['cumulate_user'];
		}
		if(isset($result['errcode'])){
			return common::errorArray(1, "接口错误", $rawResult);
		}
		if($cache){
			spAccess('w' , 'cumulateCache', $result, 3600);
		}
		return common::errorArray(0, "获取数据成功", $result);
	}
	
	/**
	 * 获取粉丝来源途径占比
	 * @param int $dayCount
	 * @param string $from
	 * @param string $to
	 * @return array
	 */
	function getUserSourceRate($dayCount,$from = '',$to = '',$cache){
		if($cache){
			//加入缓存
			$rateCache = spAccess('r' , 'rateCache');
			if($rateCache){
				return common::errorArray(0, "获取数据", $rateCache);
			}
		}
		$UtilDate = new UtilDate();
		if($dayCount > 0){//传入天数
			$dateList = $UtilDate->getSerialDate($dayCount,'asc',0);
			$from = $dateList[0];
			$to = $dateList[$dayCount - 1];
		}
		/**
		 * 用户来源
		 * 0代表其他（包括带参数二维码）3代表扫二维码
		 * 17代表名片分享 35代表搜号码（即微信添加朋友页的搜索）
		 * 39代表查询微信公众帐号 43代表图文页右上角菜单
		 * 99代表所有，默认是所有
		 */
		//读取微信数据
		$rawResult = Statistics::getUserSummary($from, $to);
		if(isset($rawResult['errcode'])){
			return common::errorArray(1, "接口错误", $rawResult);
		}
		$userSourceList = array(
					array('name'=>"其他",'value'=>0),
					array('name'=>"扫二维码",'value'=>3),
					array('name'=>"名片分享",'value'=>17),
					array('name'=>"搜号码",'value'=>35),
					array('name'=>"查询公众号",'value'=>39),
					array('name'=>"图文右上角",'value'=>43),
				);
		$rawList = $rawResult['list'];
		//按用户来源分组
		for ($i = 0;$i < count($userSourceList);$i++){
			$allSourceList[$i] = array();
			for ($j= 0;$j< count($rawList);$j++){
				$rawList[$j] = (array)$rawList[$j];
				if($rawList[$j]['user_source'] == $userSourceList[$i]['value']){
					array_push($allSourceList[$i], $rawList[$j]);
				}
			}
		}
		//按来源类型累加
		foreach ($allSourceList as &$per){
			if(count($per) > 1){
				$cancelCount = 0;
				$newCount = 0;
				for($i = 0;$i < count($per);$i++){
					$cancelCount += $per[$i]['cancel_user'];
					$newCount += $per[$i]['new_user'];
				}
				$per = array('cancel_user'=>$cancelCount,"new_user"=>$newCount);
			}else if(count($per) == 1){
				$per = array('cancel_user'=>$per[0]['cancel_user'],"new_user"=>$per[0]['new_user']);
			}else{
				$per = array('cancel_user'=>0,"new_user"=>0);
			}
		}
		//计算总数
		$totalCancel = 0;
		$totalNew= 0;
		foreach ($allSourceList as $per){
			$totalCancel += $per['cancel_user'];
			$totalNew += $per['new_user'];
		}
		//计算比例
		$cancelDataList = array();
		$newDataList = array();
		for ($i = 0 ;$i < count($allSourceList);$i++){
			$allSourceList[$i]['cancelRate'] = round($allSourceList[$i]['cancel_user'] / $totalCancel ,2);
			$allSourceList[$i]['newRate'] = round($allSourceList[$i]['new_user'] / $totalNew ,2);
			array_push($cancelDataList, array("{$userSourceList[$i]['name']}",$allSourceList[$i]['cancelRate']));
			array_push($newDataList, array("{$userSourceList[$i]['name']}",$allSourceList[$i]['newRate']));
		}
		$result = array('cancelDataList'=>$cancelDataList,"newDataList"=>$newDataList);
		if($cache){
			spAccess('w' , 'rateCache', $result, 3600);
		}
		return common::errorArray(0, "获取数据成功", $result);
	}
	
}
