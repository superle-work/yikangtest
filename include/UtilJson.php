<?php
/**
 * 提供Json配置文件管理服务
 * @name UtilDate
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class UtilJson{
    
    private $path;
    
    function UtilJson($path){
        $this->path = $path;
    }
	
    /**
     * 获取文件配置列表
     * @return array $result
     */
    public function getFileConfigList() {
        $data = file_get_contents($this->path);
        $configList = json_decode($data,true);
        if(count($configList)){ 
            return common::errorArray(0, "获取成功", $configList);
        }else{
            return common::errorArray(1, "获取失败", false);
        }
    }
    
    /**
     * 获取文件配置项
     * @param string $key
     * @return array $result
     */
    public function getFileConfigValue($key) {
        $data = file_get_contents($this->path);
        $configList = json_decode($data,true);
        foreach ($configList as $configKey=>$value){
            if($configKey == $key){
                return common::errorArray(0, "获取成功", $value);
            }
        }
        return common::errorArray(1, "获取失败", false);
    }
    
    /**
     * 修改单个配置
     * @param string $key
     * @param string $value
     * @return array $result
     */
    public function setFileKeyValue($key,$value){
        $data = file_get_contents($this->path);
        $configList = json_decode($data,true);
        $configList[$key] = $value;
        $f = fopen($this->path, 'w+');
        $result = fwrite($f, json_encode($configList));
        fclose($f);
        if($result){
            return common::errorArray(0, "修改成功", true);
        }else{
            return common::errorArray(1, "修改失败", false);
        }
    }
    
    /**
     * 修改所有文件配置
     * @param array $configList
     * @return array $result
     */
    public function setFileAll($configList){
        $f = fopen($this->path, 'w+');
        $result = fwrite($f, json_encode($configList));
        fclose($f);
        if($result){ 
            return common::errorArray(0, "修改成功", true);
        }else{
            return common::errorArray(1, "修改失败", false);
        }
    }
    
    /**
     * 修改部分文件配置
     * @param array $configList
     * @return array $result
     */
    public function setFileConfigSection($configList){
        $data = file_get_contents($this->path);
        $rawConfigList = json_decode($data,true);
        foreach ($configList as $key=>$value){
            $rawConfigList[$key] = $value;
        }
        $f = fopen($this->path, 'w+');
        $result = fwrite($f, json_encode($rawConfigList));
        fclose($f);
        if($result){
            return common::errorArray(0, "修改成功", true);
        }else{
            return common::errorArray(1, "修改失败", false);
        }
    }
	
}
/* End of file DateUtil.php */
/* Location: ./include/UtilJson.php */