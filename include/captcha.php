<?php
/**
 * @name Capcha
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class Captcha {
    var $captchaWidth = 70; // 文件上传路径 结尾加斜杠
    var $captchaHeight = 25; // 缩略图路径（必须在$images_dir下建立） 结尾加斜杠
    
    /**
     * +----------------------------------------------------------
     * 构造函数
     * +----------------------------------------------------------
     */
    function Captcha($captchaWidth, $captchaHeight) {
        $this->captchaWidth = $captchaWidth;
        $this->captchaHeight = $captchaHeight;
    }
    
    /**
     * +----------------------------------------------------------
     * 图片上传的处理函数
     * +----------------------------------------------------------
     */
    public function createCaptcha($key) {
        $word = $this->create_word();
        // 把验证码字符串写入session
        $_SESSION[$key] = md5($word);
        // 绘制基本框架
        $im = imagecreatetruecolor($this->captchaWidth, $this->captchaHeight);
        $bg_color = imagecolorallocate($im, 235, 236, 237);
        imagefilledrectangle($im, 0, 0, $this->captchaWidth, $this->captchaHeight, $bg_color);
        $border_color = imagecolorallocate($im, 118, 151, 199);
        imagerectangle($im, 0, 0, $this->captchaWidth - 1, $this->captchaHeight - 1, $border_color);
        // 添加干扰
        for($i = 0; $i < 5; $i++) {
            $rand_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagearc($im, mt_rand(-$this->captchaWidth, $this->captchaWidth), mt_rand(-$this->captchaHeight, $this->captchaHeight), mt_rand(30, $this->captchaWidth *
                     2), mt_rand(20, $this->captchaHeight * 2), mt_rand(0, 360), mt_rand(0, 360), $rand_color);
        }
        for($i = 0; $i < 50; $i++) {
            $rand_color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagesetpixel($im, mt_rand(0, $this->captchaWidth), mt_rand(0, $this->captchaHeight), $rand_color);
        }
        // 生成验证码图片
        $text_color = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
        imagestring($im, 6, 18, 5, $word, $text_color);
        // header
        header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
        header("Content-type: image/png;charset=utf-8");
        /* 绘图结束 */
        imagepng($im);
        imagedestroy($im);
        return true;
    }
    
    /**
     * +----------------------------------------------------------
     * 图片上传的处理函数
     * +----------------------------------------------------------
     */
    private function create_word() {
        // 设置随机字符范围
        $chars = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";
        $word = '';
        for($i = 0; $i < 4; $i++) {
            $word .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        
        return $word;
    }
}
?>