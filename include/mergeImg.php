<?php
/**
 * 提供图片合成
 * @name mergeImg
 * @package tccm
 * @category include
 * @link http://www.changekeji.com
 * @author leon
 * @version 1.0
 * @since 2016-09-13
 */
class mergeImg{
	
    /**
     * 合成一张包含另一张图片的2张图   保存jpg格式
     * @param string $imgUrl 被包含的图片地址 jpg jpeg格式
     * @param string $backgroundUrl 背景图地址 jpg jpeg格式
     * @param array $imgSite 里层图片位置 array(x=>, y=>)
     * @param array $backSize 背景图片大小 array(x=>, y=>) 如使用嵌套二维码 微信返回尺寸为430*430 调整合适背景大小保证二维码不失真
     * @param array $innerSize 内嵌图片大小 array(x=>, y=>)
     * @param string $targetPath 生成图片的存放的路径   格式 ： upload/image/store/headImage/
     * @param string $fileExtension 生成图片的扩展名  
     */
    public static function mergeContainJpgImg($imgUrl, $backgroundUrl, $imgSite, $backSize = null, $innerSize = null, $targetPathName ){
        header('Content-Type: image/png');  // 设置内容类型标头 —— 这个例子里是 image/png
        //里层图片
        $path_2 = $imgUrl;
        //背景图片
        $path_1 = $backgroundUrl;
        //将图片分别取到两个画布中
        $image_1 = imagecreatefromjpeg($path_1);
        $image_2 = imagecreatefromjpeg($path_2);
        //创建一个和人物图片一样大小的真彩色画布（ps：只有这样才能保证后面copy装备图片的时候不会失真）
        if($backSize == null){
            $backSize['x'] = imagesx($image_1);
            $backSize['y'] = imagesy($image_1);
        }
        if($innerSize == null){
            $innerSize['x'] = imagesx($image_2);
            $innerSize['y'] = imagesy($image_2);
        }else{
            $image = imagecreatetruecolor($innerSize['x'], $innerSize['y']);  //创建一个彩色的底图
            imagecopyresampled($image, $image_2, 0, 0, 0, 0,$innerSize['x'],$innerSize['y'],imagesx($image_2), imagesy($image_2));
            $image_2 = $image;
        } 
        $image_3 = imageCreatetruecolor($backSize['x'],$backSize['y']);
        //为真彩色画布创建白色背景，再设置为透明
        $color = imagecolorallocate($image_3, 255, 255, 255);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);
        //首先将背景图片采样copy到真彩色画布中，不会失真
        imagecopyresampled($image_3, $image_1, 0, 0, 0, 0, $backSize['x'], $backSize['y'], imagesx($image_1), imagesy($image_1));
        //再将里层画布copy到已经具有背景图像的真彩色画布中，同样也不会失真
        imagecopymerge($image_3,$image_2, $imgSite['x'], $imgSite['y'], 0, 0,$innerSize['x'],$innerSize['y'], 100);
        $result = imagejpeg($image_3,$targetPathName);
        // 释放内存
        imagedestroy($image_3);
        return $result;
    } 
    
    /**
     * 配合组合图方法，将生成的图片保存到指定的文件夹中
     * @param string $targetPath
     * @param string 返回文件保存的完整路径 格式如 ：'uploade/image/2017033123432.png'
     */
    public static function getTargetPath($targetPath){
        $fileExtension='png';
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
        //生成唯一的合成二维码名称
        $randNum = rand(0,999);//随机数，保证重复调用的时候文件名不唯一
        $newFileName = date('YmdHis').$randNum.'.'.$fileExtension;
        $newFileUrl = './' . $targetPath.'/'.$newFileName;
        return $newFileUrl; 
    }
    
    /**
     * 合成一张包含另一张图片的2张图   保存jpg格式
     * @param string $imgUrl 被包含的图片地址 jpg jpeg格式
     * @param string $backgroundUrl 背景图地址 jpg jpeg格式
     * @param array $imgSite 里层图片位置 array(x=>, y=>)
     * @param array $backSize 背景图片大小 array(x=>, y=>) 如使用嵌套二维码 微信返回尺寸为430*430 调整合适背景大小保证二维码不失真
     * @param array $innerSize 内嵌图片大小 array(x=>, y=>) 
     */
    public static function mergeContainJpgImgInFile($imgUrl, $backgroundUrl, $url, $imgSite, $backSize = null, $innerSize = null){
        //里层图片
        $path_2 = $imgUrl;
        //背景图片
        $path_1 = $backgroundUrl;
        //将图片分别取到两个画布中
        $image_1 = imagecreatefromjpeg($path_1);
        $image_2 = imagecreatefromjpeg($path_2);
        if($backSize == null){
            $backSize['x'] = imagesx($image_1);
            $backSize['y'] = imagesy($image_1);
        }
        if($innerSize == null){
            $innerSize['x'] = imagesx($image_2);
            $innerSize['y'] = imagesy($image_2);
        }else{
            $image = imagecreatetruecolor($innerSize['x'], $innerSize['y']);  //创建一个彩色的底图
            imagecopyresampled($image, $image_2, 0, 0, 0, 0,$innerSize['x'],$innerSize['y'],imagesx($image_2), imagesy($image_2));
            $image_2 = $image;
        }
        //创建一个和背景图片一样大小的真彩色画布（ps：只有这样才能保证后面copy装备图片的时候不会失真）
        $image_3 = imageCreatetruecolor($backSize['x'],$backSize['y']);
        //为真彩色画布创建白色背景，再设置为透明
        $color = imagecolorallocate($image_3, 255, 255, 255);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);
        //首先将背景图谱按采样copy到真彩色画布中，不会失真
        imagecopyresampled($image_3, $image_1, 0, 0, 0, 0, $backSize['x'], $backSize['y'], imagesx($image_1), imagesy($image_1));
        //再将里层画布copy到已经具有背景图像的真彩色画布中，同样也不会失真
        imagecopymerge($image_3,$image_2, $imgSite['x'], $imgSite['y'], 0, 0,$innerSize['x'], $innerSize['y'], 100);
        imagejpeg($image_3, $url, 90);
    }
    
    
	
}