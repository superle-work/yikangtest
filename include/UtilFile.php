<?php
/**
 * 文件服务
 * @name UtilFile
 * @package cerp
 * @category include
 * @link http://www.changekeji.com
 * @author jianfang
 * @version 1.0
 * @since 2015-11-27
 */
class UtilFile{
	private $fileType;
	
	private $imageTypeView = array('jpg','jpeg','gif','png','bmp');
	private $imageType = array('jpg','jpeg','gif','png','bmp','psd','cdr','ai');
	private $officeType = array('ppt','pptx','doc','docx','xls','xlsx','pdf');
	private $packageType = array('zip','rar');
	private $soundType = array('mp3','wma','flac');
	private $videoType = array('mp4','avi','rmvb','flv','mkv','swf');
	
	private $imageLimit = 10485760;//10*1024*1025 10M
	private $officeLimit = 10485760;//10*1024*1025 10M
	private $packageLimit = 10485760;//10*1024*1025 10M
	private $souncLimit = 10485760;//10*1024*1025 10M
	private $videoLimit = 10485760;//10*1024*1025 10M
	
	function __construct(){
		$this->fileType = array_merge($this->imageType,$this->officeType,$this->packageType,$this->soundType,$this->soundType);
	}
	
	/**
	 * 上传文件
	 * @param string $pageItemName
	 * @param string $targetPath
	 * @return string|boolean
	 */
	function uploadFile($pageItemName,$targetPath){
		self::setPath($targetPath);
		if (!empty($_FILES)) {
			$tempFile = $_FILES[$pageItemName]['tmp_name'];
			$fileParts = pathinfo($_FILES[$pageItemName]['name']);
			$fileExtension = strtolower($fileParts['extension']);
			$randNum = rand(0,999);//随机数，保证重复调用的时候文件名不唯一
			$newFileName = date('YmdHis').$randNum.'.'.$fileExtension;
			$targetFile = rtrim($targetPath,' /') . '/' . $newFileName;
			//类型未取到，跳过
			if($_FILES[$pageItemName]['size'] >  self::getLimitSize($fileExtension)){//验证大小
				return false;
			}
			
			if (in_array($fileExtension,$this->fileType)) {//验证文件支持类型
				if($_FILES[$pageItemName]['size'] >  $this->getLimitSize($fileExtension)){//验证大小
					return false;
				}
				move_uploaded_file($tempFile,$targetFile);//保存文件
				$newFileUrl = './' . $targetPath.'/'.$newFileName;
				return array('url'=>$newFileUrl,'expand'=>$fileExtension);
			} else {
				// 不支持的类型
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 上传图片生成缩略图
	 * @param string $picName
	 * @param string $targetPath
	 * @param int $width
	 * @param int $height
	 * @return multitype:string |boolean
	 */
	function uploadImage($picName,$targetPath,$width,$height){
		self::setPath($targetPath);
		if (!empty($_FILES)) {
			foreach($_FILES as $key => $value){
				if($key == $picName){
					$tempFile = $value['tmp_name'];
					$fileParts = pathinfo($value['name']);
				}
			}
			// 验证是否是图片类型
			$fileExtension = strtolower($fileParts['extension']);
			$randNum = rand(0,999);//随机数，保证重复调用的时候文件名不唯一
			$newFileName = date('YmdHis').$randNum.'.'.$fileExtension;
			$targetFile = rtrim($targetPath,' /') . '/' . $newFileName;
			if (in_array($fileExtension,$this->imageTypeView)) {
				if($_FILES['file']['size'] >  $this->getLimitSize($fileExtension)){//验证大小
					return false;
				}
				move_uploaded_file($tempFile,$targetFile);//保存主图
				$newFileUrl = './' . $targetPath.'/'.$newFileName;
				if($width && $height){//生成缩略图
					$newThumbUrl = './' . $targetPath.'/'.date('YmdHis').$randNum. '_thumb.'.$fileExtension;//缩略图路径
					common::img2thumb($newFileUrl, $newThumbUrl,$width,$height,0);//生成缩略图
					$url = array('url' => $newFileUrl,'thumb' => $newThumbUrl,'expand'=>$fileExtension);
				}else{//不生成缩略图
					$url = array('url' => $newFileUrl);
				}
				// 将图片的相对路径以对象的方式返回
				return $url;
			} else {
				// 不支持的图片类型
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 设置文件大小限制
	 * @param int $sizeLimitList
	 */
	function setLimit($sizeLimitList){
		foreach ($sizeLimitList as $key=>$value){
			switch ($key){
				case 'image':
					$this->imageLimit = $value;
					break;
				case 'office':
					$this->officeLimit = $value;
					break;
				case 'package':
					$this->packageLimit = $value;
					break;
				case 'sound':
					$this->soundLimit = $value;
					break;
				case 'video':
					$this->videoLimit = $value;
					break;
				default:
					break;
			}
		}
	}
	
	/**
	 * 获取对应类型的文件大小限制
	 * @param string $fileType
	 * @return int
	 */
	function getLimitSize($fileType){
		if(in_array($fileType,$this->imageType)){
			return $this->imageLimit;
		}
		if(in_array($fileType,$this->officeType)){
			return $this->officeLimit;
		}
		if(in_array($fileType,$this->soundType)){
			return $this->souncLimit;
		}
		if(in_array($fileType,$this->packageType)){
			return $this->packageType;
		}
		if(in_array($fileType,$this->videoType)){
			return $this->videoLimit;
		}
		return 0;
	}
	
	/**
	 * 设置文件路径
	 * @param string $path
	 */
    private function setPath($path){
		if(!file_exists($path)) {
			$dirs = explode('/' , $path);
			$count = count($dirs);
			$path = '.';
			for ($i = 0; $i < $count; ++$i) {
				$path .= '/' . $dirs[$i];
				if(!is_dir($path)){//不是目录
					if(!mkdir($path,0755)){
					}
				}
			}
		}
	}
	
}
/* End of file UtilFile.php */
/* Location: ./include/UtilFile.php */