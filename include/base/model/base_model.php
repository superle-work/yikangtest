<?php
/**
 * base_model基类
 * @name base_model.php
 * @package cws
 * @category base
 * @link http://www.changekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class base_model extends spModel{
	/**
	 * 添加错误日志
	 * @param string $class 类名
	 * @param string $function 方法名
	 * @param string $info 详细信息
	 * @return boolean
	 */
	protected function errorLog($class,$function,$info){
		$row['class'] = $class;
		$row['function'] = $function;
		$row['info'] = $info;
		$row['account'] = $_SESSION['admin']['account'];
		$row['name'] = $_SESSION['admin']['name'];
		$row['ip'] = common::getRealIp();
		$row['province'] = $_SESSION['province'];
		$row['city'] = $_SESSION['city'];
		$row['add_time'] = date("Y-m-d H:i:s",time());
		if(!is_array($row))return FALSE;
		if(empty($row))return FALSE;
		foreach($row as $key => $value){
			$cols[] = $key;
			$vals[] = $this->escape($value);
		}
		$col = join(',', $cols);
		$val = join(',', $vals);
		//自动清除第999条错误日志之后的记录
		if(!class_exists('lib_log')) include 'model/base/lib_log.php';
		$lib_log = new lib_log();
		$lib_log->deleteSomeErrors();
		//插入记录
		$sql = "INSERT INTO base_error ({$col}) VALUES ({$val})";
		if( FALSE != $this->_db->exec($sql) ){ // 获取当前新增的ID
			if( $newinserid = $this->_db->newinsertid() ){
				return $newinserid;
			}else{
				$this->array_remove_value($row, "");//删除传入的空值元素
				return array_pop( $this->find($row, "{$this->pk} DESC",$this->pk) );
			}
		}
		return FALSE;
	}
}