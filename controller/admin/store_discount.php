<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_agent')) require 'model/store/lib_discount.php';
if(!class_exists('UtilConfig')) require 'include/UtilConfig.php';
/**
 *折扣管理
 * @name store_discount.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class store_discount extends admin_controller
{
    private $lib_discount;

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING);
        $this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
        $this->lib_discount = new lib_discount();
    }

    /**
     * 跳转添加折扣信息页面
     */
    function addDiscount()
    {
        $this->getMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转添加代理信息页面", 1, 'add');
        $this->display("../template/admin/{$this->theme}/store/page/discount/addDiscount.html");
    }

    /**
     * 折扣列表信息页面
     */
    function discountList()
    {
        $this->getSetMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "跳转代理列表信息页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/discount/discountList.html");
    }

    /**
     * 编辑折扣页面
     */
    function editDiscount()
    {
        $this->getMenu($this);
        $goodsResult = $this->lib_discount->getDiscount($this->spArgs('id'));
        $this->goods = $goodsResult['data'];
        $this->log(__CLASS__, __FUNCTION__, "编辑代理页面", 1, 'edit');
        $this->display("../template/admin/{$this->theme}/store/page/discount/editDiscount.html");
    }


    /**
     * 添加折扣
     */
    function insertDiscount()
    {
        $goodsInfo = $this->getArgsList($this, array(user_type, discount, blood_fee, transport_fee));
        $where['user_type'] = $goodsInfo['user_type'];
        //判断表中是否已有
        $result = $this->lib_discount->getDiscountInfo($where);
        if ($result['errorCode'] == 0) {
            echo json_encode(common::errorArray(1, '该角色已设置折扣', false));
            die;
        }

        $resultGoods = $this->lib_discount->addDiscount($goodsInfo);
        $this->log(__CLASS__, __FUNCTION__, "添加角色折扣", 0, 'add');
        echo json_encode($resultGoods);
    }

    /**
     * 分页查询折扣
     */
    function pagingDiscount()
    {
        $page = $this->getPageInfo($this);
        $keyValueList = array('user_type' => '=');
        $conditions = $this->getPagingList($this, $keyValueList);
        $sort = "id desc";

        $lib_agent = new lib_discount();
        $result = $lib_agent->pagingDiscount($page, $conditions, $sort);
        foreach($result['data']['dataList'] as $k => $v){
            if($v['user_type'] == 1){
                $result['data']['dataList'][$k]['user_type_des'] = '诊所用户';
            } elseif($v['user_type'] == 2){
                $result['data']['dataList'][$k]['user_type_des'] = '医院用户';
            }elseif($v['user_type'] == 3){
                $result['data']['dataList'][$k]['user_type_des'] = '物流人员';
            }else{
                $result['data']['dataList'][$k]['user_type_des'] = '普通用户';
            }
        }
        echo json_encode($result);
    }

    /**
     * 修改折扣
     */
    function updateDiscount()
    {
        $conditions = array('id' => $this->spArgs('id'));
        //更新基本信息
        $goodsInfo = $this->getArgsList($this, array(discount, blood_fee,transport_fee));

        $result = $this->lib_discount->updateDiscount($conditions, $goodsInfo);
        $this->log(__CLASS__, __FUNCTION__, "修改信息", 0, 'edit');
        echo json_encode($result);
    }

    /**
     * 根据id获取折扣信息
     */
    function getDiscountDetail()
    {
        $result = $this->lib_discount->getDiscount($this->spArgs('id'));
        $this->log(__CLASS__, __FUNCTION__, "根据id获取折扣信息", 0, 'view');
        echo json_encode($result);
    }

    /**
     * 删除折扣
     */
    function deleteAgent()
    {
        $id = $this->spArgs('id');
        $result = $this->lib_discount->deleteDiscount($id);
        if ($result['errorCode'] != 0) {
            echo json_encode($result);
            return;
        }
        $this->log(__CLASS__, __FUNCTION__, "删除折扣", 0, 'del');
        echo json_encode($result);
    }

    /**
     * 批量删除折扣
     */
    function batchDelete()
    {
        $gids = $this->spArgs('ids');
        $result = $this->lib_discount->batchDelete($gids);
        $this->log(__CLASS__, __FUNCTION__, "批量删除折扣", 0, 'del');
        echo json_encode($result);
    }

}