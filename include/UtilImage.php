<?php
/**
 * 提供图片处理工具服务
 * @name UtilImage
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class UtilImage{
    
    /**
     * 生成缩略图
     * @author jianfang
     * @param string     源图绝对完整地址{带文件名及后缀名}
     * @param string     目标图绝对完整地址{带文件名及后缀名}
     * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
     * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
     * @param int        是否裁切{宽,高必须非0}
     * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
     * @return boolean
     */
    public static function img2thumb($src_img, $dst_img, $width = 200, $height = 260, $cut = 0, $proportion = 0){
        if(!is_file($src_img)){
            return false;
        }
        $ot = pathinfo($dst_img, PATHINFO_EXTENSION);
        $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
        $srcinfo = getimagesize($src_img);
        $src_w = $srcinfo[0];
        $src_h = $srcinfo[1];
        $type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
        $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
        $dst_h = $height;
        $dst_w = $width;
        $x = $y = 0;
        /**
         * 缩略图不超过源图尺寸（前提是宽或高只有一个）
         */
        if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0)){
            $proportion = 1;
        }
        if($width> $src_w){
            $dst_w = $width = $src_w;
        }
        if($height> $src_h){
            $dst_h = $height = $src_h;
        }
        if(!$width && !$height && !$proportion){
            return false;
        }
        if(!$proportion){
            if($cut == 0){
                if($dst_w && $dst_h){
                    if($dst_w/$src_w> $dst_h/$src_h){
                        $dst_w = $src_w * ($dst_h / $src_h);
                        $x = 0 - ($dst_w - $width) / 2;
                    }
                    else{
                        $dst_h = $src_h * ($dst_w / $src_w);
                        $y = 0 - ($dst_h - $height) / 2;
                    }
                }
                else if($dst_w xor $dst_h){
                    if($dst_w && !$dst_h)  //有宽无高
                    {
                        $propor = $dst_w / $src_w;
                        $height = $dst_h  = $src_h * $propor;
                    }
                    else if(!$dst_w && $dst_h)  //有高无宽
                    {
                        $propor = $dst_h / $src_h;
                        $width  = $dst_w = $src_w * $propor;
                    }
                }
            }
            else{
                if(!$dst_h){//裁剪时无高
                    $height = $dst_h = $dst_w;
                }
                if(!$dst_w){//裁剪时无宽
                    $width = $dst_w = $dst_h;
                }
                $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
                $dst_w = (int)round($src_w * $propor);
                $dst_h = (int)round($src_h * $propor);
                $x = ($width - $dst_w) / 2;
                $y = ($height - $dst_h) / 2;
            }
        }
        else{
            $proportion = min($proportion, 1);
            $height = $dst_h = $src_h * $proportion;
            $width  = $dst_w = $src_w * $proportion;
        }
    
        $src = $createfun($src_img);
        $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
    
        if(function_exists('imagecopyresampled')){
            imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        else{
            imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        $otfunc($dst, $dst_img);
        imagedestroy($dst);
        imagedestroy($src);
        return true;
    }
	
    /**
     * 上传图片同时为其生成缩略图
     * @param string $picName 页面文件流name
     * @param string $targetPath 保存在服务器的路径
     * @param int $width 缩率图宽度
     * @param int $height 缩率图高度
     * @return multitype:array|boolean
     */
    public static  function uploadPhoto($picName,$targetPath,$width = 100,$height = 100) {
        if(!file_exists($targetPath)) {
            $dirs = explode('/' , $targetPath);
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
        if (!empty($_FILES[$picName])) {
            $tempFile = $_FILES[$picName]['tmp_name'];
            // 验证是否是图片类型
            $fileTypes = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG');
            $fileParts = pathinfo($_FILES[$picName]['name']);
            $fileExtension = strtolower($fileParts['extension']);
            $randNum = rand(0,999);//随机数，保证重复调用的时候文件名不唯一
            $newFileName = date('YmdHis').$randNum.'.'.$fileExtension;
            $targetPath = rtrim($targetPath,'/');
            $targetFile = $targetPath . '/' . $newFileName;
            if (in_array($fileExtension,$fileTypes)) {
                move_uploaded_file($tempFile,$targetFile);//保存主图
                $newFileUrl = './' . $targetPath.'/'.$newFileName;
                $newThumbUrl = './' . $targetPath.'/'.date('YmdHis').$randNum. '_thumb.'.$fileExtension;//缩略图路径
                UtilImage::img2thumb($newFileUrl, $newThumbUrl,$width,$height,0);//生成缩略图
                $url = array('url' => $newFileUrl,'thumb' => $newThumbUrl);
                // 将图片的相对路径以对象的方式返回
                return $url;
            } else {
                // 不支持的图片类型
                return common::errorArray(1,'上传图片格式错误',$fileExtension);
            }
        }else{
            return false;
        }
    }
    
    /**
     * 仅仅上传图片
     * @param string $picName 页面文件流name
     * @param string $targetPath 保存在服务器的路径
     * @param int $width 缩率图宽度
     * @param int $height 缩率图高度
     * @param int $proportion 缩放比率 设置时width和height均失效 (0-1之间的数值)
     * @return string url
     */
    public static  function uploadPhotoJust($picName,$targetPath,$width = 0,$height = 0,$proportion = 0) {
        if(!file_exists($targetPath)) {
            $dirs = explode('/' , $targetPath);
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
        if (!empty($_FILES)) {
            $tempFile = $_FILES[$picName]['tmp_name'];
            // 验证是否是图片类型
            $fileTypes = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG');
            $fileParts = pathinfo($_FILES[$picName]['name']);
            $fileExtension = strtolower($fileParts['extension']);
            $randNum = rand(0,999);//随机数，保证重复调用的时候文件名不唯一
            $newFileName = date('YmdHis').$randNum.'.'.$fileExtension;
            $targetPath = rtrim($targetPath,'/');
            $targetFile = $targetPath . '/' . $newFileName;
            if (in_array($fileExtension,$fileTypes)) {
                move_uploaded_file($tempFile,$targetFile);//保存主图
                $newFileUrl = './' . ltrim($targetPath,'/').'/'.$newFileName;
                if($width != 0 && $height != 0 || $proportion != 0){
                    UtilImage::img2thumb($newFileUrl, $newFileUrl,$width,$height,$proportion);//生成压缩图
                }
                $url =  $newFileUrl;
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
     * 获取上传的文件信息并验证
     */
    public static function verifyImage(){
        foreach($_FILES as $key=>$value){
            $result = self::verifyImageError($key);
            if($result['errorCode'] == 1) return $result;
        }
        return common::errorArray(0, '文件能上传', true);
    }
    
    /**
     * 验证上传图片是否正确
     * @param string $picName 文件名
     * @return array $result
     */
    private function verifyImageError($picName){
        switch($_FILES[$picName]['error']){
            case 0:
                $result = common::errorArray(0, '图片正常', true);
                break;
            case 1:
                $result = self::error('图片大小超出服务器允许大小，上传失败');
                break;
            case 2:
                $result = self::error('图片大小超出浏览器限制 ，上传失败');
                break;
            case 3:
                $result = self::error('图片仅部分被上传，上传失败');
                break;
            case 4:
                $result = self::error('没有找到要上传的图片，上传失败');
                break;
            case 5:
                $result = self::error('服务器临时文件夹丢失 ，上传失败');
                break;
            case 6:
                $result = self::error('图片写入到临时文件夹出错，上传失败');
                break;
        }
        return $result;
    }
    
    /**
     * 错误信息格式
     * @param string $info
     * @return array $result
     */
    private function error($info){
        return common::errorArray(1, $info, false);
    }
}
/* End of file UtilImage.php */
/* Location: ./include/UtilImage.php */