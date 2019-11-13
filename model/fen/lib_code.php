<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_fen_code')) require "model/fen/table/m_fen_code.php";

/**
 * 提供分销商二维码服务
 * @name lib_code.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class lib_code extends base_model{
    private $m_code;
    
    function __construct(){
        parent::__construct();
        $this->m_code = new m_fen_code();
    }
    
	/**
	 * 添加分销二维码
	 * @param array $codeInfo
	 * @param string $targetPath 格式 upload/image/store/headImage/
	 * @param string $headImgUrl
	 * @return array
	 */
	function getCode($codeInfo,$targetPath,$headImgUrl,$bgUrl){
		try{
		    $codeResult = $this->m_code->find($codeInfo);
		    if(!$codeResult){
		        $addId = $this->m_code->create ( $codeInfo );
		        if($addId == true){
		            include_once 'include/UtilQRcode.php';
		            $qrcode = new UtilQRcode();
		            $codeUrl = $qrcode->getParamQRcode($codeInfo['did'],2);
		            $this->m_code->update(array('id' => $addId), array('code' => $codeUrl));
					$targetPathName = $this->getContainQrcode($codeInfo,$targetPath,$headImgUrl,$bgUrl);
		            return  common::errorArray(0, "添加成功", $targetPathName['data']);
		        }else{
		            return  common::errorArray(1, "添加失败", $codeUrl);
		        }
		    }else{
		        return common::errorArray(0, "存在二维码", $codeResult['contain_qrcode']);
		    }
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 保存合成的分销商二维码
	 * @param array $condition
	 * @param array $row
	 * @return array $result
	 */
	function getContainQrcode($condition, $targetPath,$headImgUrl,$bgUrl){
	    $codeResult = $this->findCode($condition);
	    try{
	        if(empty($codeResult['data']['contain_qrcode'])){   //不存在组合图：生成组合图
	            if(!class_exists('mergeImg')) include 'include/mergeImg.php';
	            $targetPathName = mergeImg::getTargetPath($targetPath);
				$headImgUrlResult = mergeImg::mergeContainJpgImg($headImgUrl, $codeResult['data']['code'], array('x' => 70, 'y' => 70), array('x'=>200, 'y' => 200), array('x'=>60, 'y' => 60), $targetPathName);
				$targetPathName2 = mergeImg::getTargetPath($targetPath);
	            $result = mergeImg::mergeContainJpgImg($targetPathName, $bgUrl, array('x' => 220, 'y' => 416),null, array('x'=>200, 'y' => 200), $targetPathName2);			              	
	           	if($result){
	           		unlink($targetPathName);
			    	$resultUpdate = $this->m_code->update( $condition, array('contain_qrcode' => $targetPathName2));  //持久化路径到数据库
		            if($resultUpdate){
		                return common::errorArray(0, "添加成功", $targetPathName2);
		            }else{
		                return common::errorArray(1, "添加失败", $result);
		            }
			    }else{
			    	return common::errorArray(1, "生成图片", $result);
			    }
	        }else{
	            return common::errorArray(0, "存在合成二维码", $codeResult['data']['contain_qrcode']);
	        }
	    } catch (Exception $ex) {
	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);
	        return  common::errorArray(1, "数据库操作失败",$ex);
	    }
	}
	
	
	
	/**
	 * 添加分销二维码
	 * @param array $codeInfo
	 * @return array
	 */
//	function getCode($codeInfo){
//		try{
//		    $codeResult = $this->m_code->find($codeInfo);
//		    if(!$codeResult){
//		        $addId = $this->m_code->create ( $codeInfo );
//		        if($addId == true){
//		            include_once 'include/UtilQRcode.php';
//		            $qrcode = new UtilQRcode();
//		            $codeUrl = $qrcode->getParamQRcode($addId,2);
//		            $this->m_code->update(array('id' => $addId), array('code' => $codeUrl));
//		            return  common::errorArray(0, "添加成功", $codeUrl);
//		        }else{
//		            return  common::errorArray(1, "添加失败", $codeUrl);
//		        }
//		    }else{
//		        return common::errorArray(0, "存在二维码", $codeResult['code']);
//		    }
//		}catch (Exception $ex){
//		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
//		    return  common::errorArray(1, "数据库操作失败",$ex);
//		}
//	}
	
	/**
	 * 查看分销二维码
	 * @param array $condition
	 * @return array
	 */
	function findCode($condition){
		try{
			$result = $this->m_code->find($condition);
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
}