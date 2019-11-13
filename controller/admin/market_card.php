<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_card')) require_once 'model/market/card/lib_card.php';
/**
 * 微信会员卡
 * @name market_card.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author jeky
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-09-01
 */
class  market_card extends admin_controller{
	private $lib_card;
	
	/**
	 * 构造函数
	 */
 	 function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
		$this->lib_card = new lib_card();
	 }
  
	  /**
	  * 会员卡活动列表页面
	  */
      function cardList(){
          $this->getSetMenu($this);
          $this->log(__CLASS__, __FUNCTION__, "会员卡活动列表页面", 1, 'view');
          $this->display("../template/admin/{$this->theme}/market/card/page/cardList.html");
      }

      /**
       * 编辑会员卡页面
       */
       function addCard(){
          $this->getMenu($this);
          $this->log(__CLASS__, __FUNCTION__, "编辑会员卡页面", 1, 'view');
          $this->display("../template/admin/{$this->theme}/market/card/page/addCard.html");
       }

      /**
       * 编辑会员卡页面
       */
      function editCard(){
        $this->getMenu($this);
        //会员卡信息
        $result = $this->lib_card->findCard(array('id' => $this->spArgs('id')));
        $this->card= $result['data'];
        $this->log(__CLASS__, __FUNCTION__, "编辑会员卡页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/market/card/page/editCard.html");
      }
      
      /**
       * 会员卡活动列表页面
       */
      function cardLevelList(){
      	$this->getMenu($this);
      	//会员卡信息
        $result = $this->lib_card->findCard(array('id' => $this->spArgs('id')));
        $this->card= $result['data'];
      	//会员卡等级列表
      	$result = $this->lib_card->findAllCardLevel(array('card_id' => $this->spArgs('id')));
      	$this->cardLevelList= $result['data'];
      	$this->log(__CLASS__, __FUNCTION__, "会员卡活动列表页面", 1, 'view');
      	$this->display("../template/admin/{$this->theme}/market/card/page/cardLevelList.html");
      }
      
      /**
       * 编辑会员卡等级页面
       */
      function addCardLevel(){
      	$this->getMenu($this);
      	//会员卡信息
      	$result = $this->lib_card->findCard(array('id' => $this->spArgs('id')));
      	$this->card= $result['data'];
      	$this->log(__CLASS__, __FUNCTION__, "编辑会员卡等级页面", 1, 'view');
      	$this->display("../template/admin/{$this->theme}/market/card/page/addCardLevel.html");
      }
      
      /**
       * 编辑会员卡等级页面
       */
      function editCardLevel(){
      	$this->getMenu($this);
      	//会员卡信息
      	$result = $this->lib_card->findCard(array('id' => $this->spArgs('card_id')));
      	$this->card= $result['data'];
      	//会员卡等级信息
      	$result = $this->lib_card->findCardLevel(array('id' => $this->spArgs('id')));
      	$this->cardLevel= $result['data'];
      	$this->log(__CLASS__, __FUNCTION__, "编辑会员卡等级页面", 1, 'view');
      	$this->display("../template/admin/{$this->theme}/market/card/page/editCardLevel.html");
      }

      /**
       * 添加会员卡活动
       */
      function insertCard(){
        //验证图片能否上传
        if($_FILES) $verify = UtilImage::verifyImage();
        if($verify['errorCode'] == 1) exit(json_encode($verify));
        $row = $this->getArgsList($this, array('name','address','num_length','prefix','start_date','end_date','valid_start','valid_end','is_use','notice','phone'));
        //插入图片
        $resultImg = UtilImage::uploadPhotoJust('imgurl','upload/image/card/cardImage/' . date('Ymd'));//upload img;
        if($resultImg){
            $row['logo'] = $resultImg;
        }
        $result = $this->lib_card-> addCard($row);
        $this->log(__CLASS__, __FUNCTION__, "添加会员卡活动", 0, 'add');
        echo json_encode($result);
      }

      /**
       * 修改会员卡基本信息
       */
      function updateCard(){
         //验证图片能否上传
         if($_FILES) $verify = UtilImage::verifyImage();
         if($verify['errorCode'] == 1) exit(json_encode($verify));
         $condition = array('id' => $this->spArgs('id'));
         $cardInfo = $this->getArgsList($this, array('name','address','num_length','prefix','start_date','end_date','valid_start','valid_end','is_use','notice','phone'));
         //更新图片
         $resultImg = UtilImage::uploadPhotoJust('imgurl','upload/image/card/cardImage/' . date('Ymd'));//upload img;
         if($resultImg){
            $cardInfo['logo'] = $resultImg;
            unlink($this->spArgs('prevurl'));//delete url
         }
         $result = $this->lib_card->updateCard( $condition, $cardInfo);
         $this->log(__CLASS__, __FUNCTION__, "修改会员卡基本信息", 0, 'edit');
         echo json_encode($result);
      }

      /**
        * 开启关闭会员卡
        */
      function useCard(){
         $condition = array('id' => $this->spArgs('id'));
         $result = $this->lib_card->updateCard($condition, array('is_use'=>$this->spArgs('is_use')));
         //日志记录
         $this->log(__CLASS__, __FUNCTION__, "开启关闭会员卡", 0, 'edit');
         echo json_encode($result);
      }

      /**
       * 修改会员卡等级基本信息
       */
      function updateCardLevel(){
        //验证图片能否上传
        if($_FILES) $verify = UtilImage::verifyImage();
        if($verify['errorCode'] == 1) exit(json_encode($verify));
        $condition = array('id' => $this->spArgs('id'));
        $levelInfo = $this->getArgsList($this, array('level_name','level','color','threshold','discount'));
        //更新图片
        $resultImg = UtilImage::uploadPhotoJust('imgurl','upload/image/card/cardlevel/' . date('Ymd'));//upload img;
        if($resultImg){
                $levelInfo['image'] = $resultImg;
                unlink($this->spArgs('prevurl'));//delete url
        }
        $result = $this->lib_card->updateCardLevel( $condition, $levelInfo);
        $this->log(__CLASS__, __FUNCTION__, "修改会员卡等级基本信息", 0, 'edit');
        echo json_encode($result);

      }

      /**
       * 删除会员卡
       */
      function deleteCard(){
        $result = $this->lib_card-> deleteCard($this->spArgs('ids'));
        $this->log(__CLASS__, __FUNCTION__, "删除会员卡", 0, 'del');
        echo json_encode($result);
      }

      /**
       * 删除会员卡等级
       */
      function deleteCardLevel(){
        $result = $this->lib_card-> deleteCardLevel($this->spArgs('ids'));
        $this->log(__CLASS__, __FUNCTION__, "删除会员卡等级", 0, 'del');
        echo json_encode($result);
      }

      /**
       * 添加会员卡等级
       */
      function insertCardLevel(){
        //验证图片能否上传
        if($_FILES) $verify = UtilImage::verifyImage();
        if($verify['errorCode'] == 1) exit(json_encode($verify));
        $row = $this->getArgsList($this, array('card_id','level','level_name','color','threshold','discount'));
        //更新图片
        $resultImg = UtilImage::uploadPhotoJust('imgurl','upload/image/card/cardlevel/' . date('Ymd'));//upload img;
        if($resultImg){
            $row['image'] = $resultImg;
        }
        $result = $this->lib_card-> addCardLevel($row);
        $this->log(__CLASS__, __FUNCTION__, "添加会员卡等级", 0, 'add');
        echo json_encode($result);
      }
      
      /**
       * 会员卡持有者列表界面
       */
      function ownCardUserList(){
          $this->getMenu($this);
          $this->clid = $this->spArgs('id');
          $this->log(__CLASS__, __FUNCTION__, "会员卡持有者列表界面", 1, 'view');
          $this->display("../template/admin/{$this->theme}/market/card/page/ownCardUserList.html");
      }
      
      /**
       * 查询会员卡会员列表
       */
      function pagingCardLevelUser(){
          $page = $this->getPageInfo($this);
          $clid = $this->spArgs('clid');
          $conditionList = array();//默认无条件 必须有
          if("" != $this->spArgs('name') && null != $this->spArgs('name')){
              array_push($conditionList,  array("field" => "lf_name","operator" => "like","value" => $this->spArgs('name')));
          }
          if("" != $this->spArgs('is_use') && null != $this->spArgs('is_use')){
              array_push($conditionList,  array("field" => "lf_is_use","operator" => "=","value" => $this->spArgs('is_use')));
          }
          $conditionList[] = array("field" => "lf_level_id","operator" => "=","value" => $clid);
          $sort = "left_table.add_time desc";
          $result = $this->lib_card->pagingCardOwner($page, $conditionList, $sort);
          echo json_encode($result);
      }
      

      /**
       * 分页查询会员卡活动
       */
      function pagingCard(){
        $page = $this->getPageInfo($this);
        $conditionList = array();//默认无条件 必须有
        if("" != $this->spArgs('name') && null != $this->spArgs('name')){
            array_push($conditionList,  array("field" => "name","operator" => "like","value" => $this->spArgs('name')));
        }
        if("" != $this->spArgs('is_use') && null != $this->spArgs('is_use')){
            array_push($conditionList,  array("field" => "is_use","operator" => "=","value" => $this->spArgs('is_use')));
        }
        
        $sort = "is_use desc,add_time desc,end_date desc";
        $result = $this->lib_card->pagingCard($page, $conditionList, $sort);
        echo json_encode($result);
      }
  
}