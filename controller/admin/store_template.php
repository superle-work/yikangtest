<?php
if(!class_exists('admin_controller')) require 'include/base/controller/admin/admin_controller.php';
if(!class_exists('lib_template')) require 'model/store/lib_template.php';
if(!class_exists('lib_template_property')) require 'model/store/lib_template_property.php';
/**
 * 模板管理
 * @name store_template.php
 * @package cws
 * @category controller
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-04
 */
class store_template extends admin_controller{
    private $lib_template;
    private $lib_template_property;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
		$this->rightVerify($_SESSION['admin'], "./admin.php?c=base_main&a=login");
	    $this->lib_template = new lib_template();
	    $this->lib_template_property = new lib_template_property();
	}
	
	/**
	 * 模板列表页面
	 */
	function templateList(){
		$this->getSetMenu($this);
		$this->log(__CLASS__, __FUNCTION__, "模板列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/page/template/templateList.html");
	}

	/**
	 * 查看模板属性
	 */
	function findTemplateProperty(){
		$this->getMenu($this);
		$condition = array('id' => $this->spArgs('id'));//模板id
		$resultTemplate = $this->lib_template->findTemplate($condition);
		$rawString =rtrim( $resultTemplate['data']['ptids'],',');
		$pidArray = explode(',', $rawString);
		foreach ($pidArray as $per){
			$conditionProperty = array('id' => $per);		
			$this->lib_template_property = new lib_template_property();
			$resultProperty = $this->lib_template_property->findProperty($conditionProperty);
			$resultTemplate['subList'][] = $resultProperty['data'];
		}
		
		$this->templateList = $resultTemplate;
		$this->log(__CLASS__, __FUNCTION__, "管理员列表页面", 1, 'view');
		$this->display("../template/admin/{$this->theme}/store/pagetemplateManage/templatePropertyList.html");
	}
	
	/**
     * 模板属性页面
     */
    function templateProperty(){
		$this->getSetMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "管理员列表页面", 1, 'view');
        $this->display("../template/admin/{$this->theme}/store/page/template/templateProperty.html");
    }
    
    /**
     * 添加属性页面
     */
    function addProperty(){
		$this->getMenu($this);
        $this->log(__CLASS__, __FUNCTION__, "管理员列表页面", 1, 'add');
    	$this->display("../template/admin/{$this->theme}/store/page/template/addProperty.html");
    }
    
    /**
     * 添加模板页面
     */
    function addTemplate(){
		$this->getMenu($this);
    	$result = $this->lib_template_property->findNameListByProperty();
    	$result = $result['data'];
    	foreach ($result as &$per){
    		$valueList  = explode(',', $per['valueList'] );
    		$idList = explode(',', $per['idList'] );
            $property['name'] = $per['name'];
    		$list = array();
    		for($i = 0 ; $i < count($valueList);$i++){
    			$list[] = array(
    						"id" => $idList[$i],
    						"value" => $valueList[$i]
    					);
    		}
    		$property['list'] = $list;
    		$resultList[] = $property;
    	}
    	
    	$this->propertyList = $resultList;
    	$this->log(__CLASS__, __FUNCTION__, "添加模板页面", 1, 'add');
    	$this->display("../template/admin/{$this->theme}/store/page/template/addTemplate.html");
    }
    
    /**
     * 编辑属性页面
     */
	function editProperty(){
		$this->getMenu($this);
		$condition = array(
				'name' => $this->spArgs('name')
				);
		$result = $this->lib_template_property->findAllProperty($condition);
		//查询排序
		$sortResult = $this->lib_template_property->findProperty($condition);
		$propertyList['name'] = $this->spArgs('name');
		$propertyList['sort'] = $sortResult['data']['sort'];		
		$propertyList['values'] =  $result['data'];
		
		$this->propertyList  = $propertyList;
		$this->log(__CLASS__, __FUNCTION__, "编辑属性页面", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/store/page/template/editProperty.html");
	}
	
	/**
	 * 编辑模板页面
	 */
	function editTemplate(){
		$this->getMenu($this);
		$condition = array('id' => $this->spArgs('id'));
		$result = $this->lib_template->findTemplate($condition);
		$template['name'] = $result['data']['name'];
		$template['id'] = $result['data']['id'];
		$ptids = $result['data']['ptids'];
        //获取当前模板引用的所有属性id的list记录集合
        $pidList = explode(',', $ptids);
        $template['idList'] = $pidList;//当前模板引用的所有属性
        //获取所有公共属性
        $resultList = $this->lib_template_property->findNameListByProperty();
        $resultList = $resultList['data'];
        
        foreach ($resultList as &$per){
            $valueList  = explode(',', $per['valueList'] );
            $idList = explode(',', $per['idList'] );
            $property['name'] = $per['name'];
            $list = array();
            for($i = 0 ; $i < count($valueList);$i++){
                $list[] = array(
                            "id" => $idList[$i],
                            "value" => $valueList[$i]
                        );
            }
            $property['list'] = $list;
            $allPropertyList[] = $property;
        }
        $template['allPropertyList'] = $allPropertyList;//系统所有的属性
		$this->template  = $template;
		$this->log(__CLASS__, __FUNCTION__, "编辑模板页面", 1, 'edit');
		$this->display("../template/admin/{$this->theme}/store/page/template/editTemplate.html");
	}

	/**
     *根据给予的属性名称，确认其是否存在,存在直接返回false
     */
    function isPropertyExist(){
        $result = $this->lib_template_property->isPropertyExist ( $this->spArgs("name") );
        echo json_encode ( $result['errorCode'] );
    }

    /**
     * 根据传入的模板id 获取其全部属性
     */
    function findPropertyList(){
        $result = $this->lib_template->findTemplate(array('id' => $this->spArgs('tid')));
        $ptids = $result['data']['ptids'];
        $result = $this->lib_template_property->findGroupPropertyList($ptids);
        echo json_encode($result);
    }
    
	/**
	 * 添加模板属性
	 */
	function insertProperty(){
		$valueList =  explode(',', rtrim($this->spArgs('values'),','));
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "添加模板属性", 0, 'add');
		echo $this->_insertProperty($this->spArgs('name'), $valueList,$this->spArgs('sort'));
	}
	
	/**
	 * 添加属性
	 * @param string $name
	 * @param array $valueArray
	 * @return string
	 */
	private function  _insertProperty($name,$valueArray,$sort){
		foreach ($valueArray as $value){
			$propertyInfo = array(
					"name" => $name,
					"value" => $value,
					"sort" =>$sort
			) ;
			$result = $this->lib_template_property->addProperty($propertyInfo);
			if($result['errorCode'] != 0){
				return  json_encode($result);
			}
		}
		
		return  json_encode($result);
	}
	
	/**
	 * 通过模板修改来添加属性
	 * @param array $addValues
	 * @return string
	 */
	private function  _insertPropertyForUpdate($addValues){
		foreach ($addValues as $value){
			$propertyInfo = array(
					"name" => $value->name,
					"value" => $value->value,
					"sort" => $value->sort
			) ;
			$result = $this->lib_template_property->addProperty($propertyInfo);
			if($result['errorCode'] != 0){
				return  json_encode($result);
			}
		}
	
		return  json_encode($result);
	}

	/**
     *根据给予的模板名称，确认其是否存在,存在直接返回false
     */
    function isTemplateExist(){
        $result = $this->lib_template->isTemplateExist ( $this->spArgs("name") );
        echo  $result['errorCode'] ;
    }
    
    /**
     * 类别模板解绑
     */
    function deleteBind(){
    	$tid = $this->spArgs('tid');
    	
    	$cid = $this->spArgs('cid');
    	if($cid == 0 || $cid == null || $cid == ''){
    		echo json_encode(common::errorArray(1, "该模板未绑定分类，不用解绑", false));
    	}else{
    		//将template的cid置为0
    		$this->lib_template = new lib_template();
    		$templateResult = $this->lib_template->updateTemplate (
    				 array('id' => $tid),
    				 array('cid' => 0)
    				);
    		//将category的tid置为0
    		if(!class_exists('lib_category')) include "model/store/lib_category.php";
    		$m_category = new lib_category();
    		$categoryResult = $m_category->updateCategory (
    				array('id' => $cid),
    				array('tid' => 0)
    		);
    		$this->log(__CLASS__, __FUNCTION__, "类别模板解绑", 0, 'del');
    		echo json_encode(common::errorArray(0, "解绑完成", 
    					array('emplateResult' => $templateResult['errorInfo'],'categoryResult' => $categoryResult['errorInfo'],)		
    				));
    	}
    }

	/**
	 * 添加模板
	 */
	function insertTemplate(){
		$templateInfo = array(
				"name" => $this->spArgs('name'),
				"ptids" => rtrim($this->spArgs('ptids'),',')
		);
		$result = $this->lib_template->addTemplate($templateInfo);
		$this->log(__CLASS__, __FUNCTION__, "添加模板", 0, 'add');
		echo json_encode($result);
	}
	
	/**
	 * 更新属性
	 */
	function updateProperty(){
		//更新排序
		$name = $this->spArgs('name');
		$sort = $this->spArgs('sort');
		$result = $this->lib_template_property->updateProperty(array('name'=>$name), array('sort'=>$sort));
		$editValues = json_decode($this->spArgs('editValues'));
		$addValues = json_decode($this->spArgs('addValues'));
		if(count($addValues) > 0){//有的话就添加
			$resultAdd = $this->_insertPropertyForUpdate( $addValues);
		}
		if(count($editValues) > 0){//有的话就编辑
			$resultUpdate = $this->_updateProperty( $editValues);
		}
		$this->log(__CLASS__, __FUNCTION__, "更新属性", 0, 'update');
		echo json_encode(common::errorArray(0, "更新完成", 1));
	}
	
	/**
	 * 更新属性
	 * @param array $editValues
	 * @return array $result
	 */
	private function _updateProperty($editValues){
		foreach ($editValues as $per){
			$condition = array('id' => $per->id);
			$propertyInfo = array(
					'value' => $per->value
					);
			$result = $this->lib_template_property->updateProperty($condition,$propertyInfo);
		}
		return json_encode($result);
	}
	
	/**
	 * 更新模板
	 */
	function updateTemplate(){
		$condition = array('id' => $this->spArgs('id'));
		if($this->spArgs('name') != null && $this->spArgs('name') != ''){
			$templateInfo['name'] = $this->spArgs('name');
		}
		if($this->spArgs('ptids') != null && $this->spArgs('ptids') != ''){
			$templateInfo['ptids'] = rtrim($this->spArgs('ptids'),',');
		}
	
		$result = $this->lib_template->updateTemplate($condition,$templateInfo);
		//日志记录
		$this->log(__CLASS__, __FUNCTION__, "更新模板", 0, 'edit');
		echo json_encode($result);
	}
	
	/**
	 * 判断模板名称是否重复 编辑模板是使用
	 */
	function isTemplateNameExist(){
		$preName = $this->spArgs('preName');
		$curName = $this->spArgs('curName');
		if($preName == $curName){
			echo 1;
		}else{
			$result = $this->lib_template->isTemplateExist($curName);
			echo $result['errorCode'];
		}
	}
	
	/**
	 * 删除模板属性值
	 */
	function deletePropertyValue(){
		$condition = array(
				'id' => $this->spArgs('id')
		);
		$templateList = $this->lib_template->findTemplate("ptids lik '{$condition['id']},%' or ptids like '%,{$condition['id']},%'");
		$templateList = $templateList['data'];
		if(count($templateList) > 0){
			echo json_encode(common::errorArray(2, "已在模板中使用，请在模板中删除后再删除！", $templateList));
		}else{
			$result = $this->lib_template_property->deleteProperty($condition);
			$this->log(__CLASS__, __FUNCTION__, "删除模板属性值", 0, 'del');
			echo json_encode($result);
		}
		
	}
	
	/**
	 * 删除模板属性
	 */
	function deleteProperty(){
		$condition = array('name' => $this->spArgs('name'));
		$this->lib_template_property = new lib_template_property();
		$resultProperty = $this->lib_template_property->findAllProperty($condition);
		foreach ($resultProperty['data'] as $per){
			$templateList = $this->lib_template->findTemplate("ptids lik '{$condition['id']},%' or ptids like '%,{$condition['id']},%'");
			$templateList = $templateList['data'];
			if(count($templateList) > 0){
				echo json_encode(common::errorArray(2, "已在模板中使用，请在模板中删除后再删除！", $templateList));
				return;
			}
		}
		$result = $this->lib_template_property->deleteProperty($condition);
		$this->log(__CLASS__, __FUNCTION__, "删除模板属性", 0, 'del');
		echo json_encode($result);
	}
	
	/**
	 * 删除模板
	 */
	function deleteTemplate(){
		$condition = array('id' => $this->spArgs('id'));
		//在分类中查看该模板有没有使用，使用了就不让删
		if(!class_exists('lib_category')) include 'model/store/lib_category.php';
		$category = new lib_category();
		$resultCate = $category->findCategory(array('tid' => $condition['id']));
		if($resultCate['errorCode'] == 0 && $resultCate['data'] != false){
			echo json_encode(common::errorArray(1, "该模板已被分类【{$resultCate['data']['name']}】使用，无法删除", $resultCate['data']));
		}else{
			$result = $this->lib_template->deleteTemplate($condition);
			$this->log(__CLASS__, __FUNCTION__, "删除模板", 0, 'del');
			echo json_encode($result);
		}
	}
	
	/**
	 * 批量删除模板
	 */
	function batchDeleteTemplate(){
		$tids = $this->spArgs('ids');
		$result = $this->lib_template->batchDeleteTemplate($tids);
		echo json_encode($result);
	}
    
    /**
	 * 分页查询模板公用属性
	 */
	function pagingProperty(){
		$page = $this->getPageInfo($this);
		$conditions = array();
		$sort = "sort asc";
		if($this->spArgs('name') != null  && $this->spArgs('name') != ''){
			$keywords['name'] = $this->spArgs('name');
		}
		$result = $this->lib_template_property->pagingProperty ( $page, $conditions, $sort, $keywords);
		echo json_encode($result);
	}
	
	/**
	 * 分页查询模板公用属性
	 */
	function pagingTemplate(){
		$page = $this->getPageInfo($this);
		$sort ="name desc";
		$conditionList = $this->getPagingList($this, array('name' => 'like'));
		$result = $this->lib_template->pagingTemplate( $page, $conditionList, $sort);
		echo json_encode($result);
	}
}