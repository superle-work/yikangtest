<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_slide')) require 'model/base/table/m_base_slide.php';
/**
 * 提供幻灯片管理服务
 * @name lib_slide.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class lib_slide extends base_model{
    private $m_slide;
    
    function __construct(){
        parent::__construct();
        $this->m_slide = new m_base_slide();
    }
    
	/**
	 * 幻灯片添加
	 * @param array $slideInfo
	 * @return array $result
	 */
	public function addSlide($slideInfo){
		try{
		    $slideInfo['add_time'] = common::getTime();
			$addId = $this->m_slide->create ( $slideInfo );
			if($addId){
				return  common::errorArray(0, "添加成功", $addId);
			}else{
				return  common::errorArray(1, "添加失败", false);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 幻灯片信息修改
	 * @param array $conditions
	 * @param array $slideInfo
	 * @return array $result
	 */
	public function updateSlide($conditions,$slideInfo){
		try{
			$result = $this->m_slide->update ($conditions,$slideInfo );
			if(true == $result){
				return common::errorArray(0, "修改成功", $result);
			}else{
				return common::errorArray(1, "修改失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取单个幻灯片信息
	 * @param array $conditions
	 * @return array $result
	 */
	public function findSlide($conditions){
		try{
			$result = $this->m_slide->find($conditions);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取幻灯片列表
	 * @param array $conditions
	 * @param string $sort
	 * @return array
	 */
	public function findAllSlide($conditions = null,$sort = 'sort asc'){
		try{
			$result = $this->m_slide->findAll($conditions,$sort);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 删除幻灯片
	 * @param string $ids
	 * @return array $result
	 */
	public function deleteSlide($ids){
		try{
			$slideList = $this->m_slide->find($ids);
			$result = $this->m_slide->delete ($ids);
			if(true == $result){
			    //删除图片和缩略图
	//			    $slideList = $this->m_slide->delete($ids);
		        unlink($slideList['img_url']);
		        unlink($slideList['thumb']);
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "删除失败", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}

}