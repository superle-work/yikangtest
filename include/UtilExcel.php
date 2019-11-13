<?php
require_once 'include/PHPExcel.php';
require_once 'include/PHPExcel/Reader/Excel2007.php';
require_once 'include/PHPExcel/Reader/Excel5.php';
include_once 'include/PHPExcel/IOFactory.php';
/**
 * Excel处理
 * @name UtilArray
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class UtilExcel{
/**
	 * 导出表格
	 * @param array $oriData 表格数据
	 * @param array $fieldList 列头信息
	 	 $fieldList = array(
					array('key'=>'goods_name','name'=>'商品名','width'=>25),
					array('key'=>'property','name'=>'属性','width'=>50),
					array('key'=>'count','name'=>'数量','width'=>20),
					array('key'=>'price','name'=>'单价','width'=>20),
					array('key'=>'ori_price','name'=>'原价','width'=>20)
				);
	 */
	function exportExcel($oriData,$fieldList,$fileName = 'file'){
		$headArr = array();
		for($j = 0;$j < count($fieldList);$j++){
			array_push($headArr, $fieldList[$j]['name']);
		}
		$data = array();
		for($i = 0;$i < count($oriData);$i++){
			for($j = 0;$j < count($fieldList);$j++){
				$data[$i][$j] = " ".$oriData[$i][$fieldList[$j]['key']];
			}
		}
		$this->getExcel($headArr,$data,$fieldList,$fileName);
	}
	
	/**
	 * 设置列头样式
	 * @param object $objPHPExcel
	 * @param array $fieldList
	 */
	private function setStyle($objPHPExcel,$fieldList){
		$count = count($fieldList);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true)
		->setName('微软雅黑')
		->setSize(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension(A)->setWidth(28);
		$objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setBold(true)
		->setName('微软雅黑')
		->setSize(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension(B)->setWidth(15);
		for($i = 0;$i < $count;$i++){
			$char = chr($i + 65);//65 A
			$objPHPExcel->getActiveSheet()->getStyle("{$char}1")->getFont()->setBold(true)
			->setName('微软雅黑')
			->setSize(12);
			//重命名表
			$objPHPExcel->getActiveSheet()->setTitle('Simple');
			//设置活动单指数到第一个表,所以Excel打开这是第一个表
			$objPHPExcel->getActiveSheet()->getColumnDimension("$char")->setWidth($fieldList[$i]['width']);
		}
	}
	
	private function getExcel($headArr,$data,$fieldList,$fileName){
		if(empty($data) || !is_array($data)){
			die("data must be a array");
		}
		$date = date("Y_m_d",time());
		$fileName = "{$fileName}_".date ( 'YmdHis', time () ).".xlsx";
		//创建新的PHPExcel对象
		$objPHPExcel = new PHPExcel();
		$objProps = $objPHPExcel->getProperties();
		//设置表头
		$key = ord("A");
		foreach($headArr as $v){
			$colum = chr($key);
			$objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
			$key += 1;
		}
		$column = 2;
		$objActSheet = $objPHPExcel->getActiveSheet();
		foreach($data as $key => $rows){ //行写入
			$span = ord("A");
			foreach($rows as $keyName=>$value){// 列写入
				$j = chr($span);
				$objActSheet->setCellValue($j.$column, $value);
				$span++;
			}
			$column++;
		}
		$fileName = iconv("utf-8", "gb2312", $fileName);
		//设置表样式
		$this->setStyle($objPHPExcel, $fieldList);
		//将输出重定向到一个客户端web浏览器(Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"$fileName\"");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	
}
/* End of file UtilExcel.php */
/* Location: ./include/UtilExcel.php */