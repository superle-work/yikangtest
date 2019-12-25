<?php
if(!class_exists('m_base_printer')) require 'model/base/table/m_base_printer.php';
/**
 * 数据库诊所表表模型
 * @name m_store_agent.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_printer extends spModel {

    private $m_base_printer;

    

    function __construct(){

        parent::__construct();

        $this->m_base_printer = new m_base_printer();

    }

	/**

	 * 分页查询用户

	 * @param array$page 分页信息

	 * @param array $conditionList 条件查询条件

	 * @param string $sort 排序字段及方式

	 * @return array $result

	 */

	public function pagingUsers($page, $conditionList, $sort = null){
		$result = $this->m_base_printer->paging($page, $conditionList,$sort);

		if($result['errorCode'] == 1){

		    $this->errorLog(__CLASS__, __FUNCTION__, $result['data']);

		}

		return $result;

	}

}