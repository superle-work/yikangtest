<?php
if(!class_exists('base_controller')) require 'include/base/controller/base_controller.php';
/**
 *千界端控制器基类
 * @name change_controller.php
 * @package cwms
 * @category base
 * @link http://www.changekeji.com
 * @author jianfang
 * @version 2.0
 * @since 2016-05-26
 */
class change_controller extends base_controller{
    /**
     * 获取主题设置菜单
     * @param object $controller
     * @param string $menu
     */
    protected function getMenuTheme($controller,$menu){
        $controller->menu = $menu;
        $controller->theme = $this->getConfigValue('admin_theme');//后台主题
        $controller->skin = $this->getConfigValue('admin_skin');//后台主题
    }
	
}