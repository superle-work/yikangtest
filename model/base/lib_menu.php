<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('m_base_menu')) require 'model/base/table/m_base_menu.php';
if(!class_exists('m_base_menu_top')) require 'model/base/table/m_base_menu_top.php';
/**
 * 提供菜单管理服务
 * @name lib_menu.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-02
 */
class lib_menu extends base_model{
    private $m_left;
    private $m_top;
    
    function __construct(){
        parent::__construct();
        $this->m_left = new m_base_menu();
        $this->m_top = new m_base_menu_top();
    }

    /**
     * 获取菜单列表
     * 参数:$type  账号类型:1->管理员，2:子账户
     * @return array $result
     */
    public function getMenuList($type){
    	//添加条件(按账号类型显示菜单列表)
    	switch ($type) {
    		case 1:
    			$condition='';
    			$conditions=" ";
    			break;
    		case 2:
    			$condition=" and id in (2)";
    			$conditions=" and id in (29)";
    			break;
    		default:
    			$condition='';
    			$conditions="";
    			break;
    	}
        //获取顶级菜单
        $sqlMenuTop = "SELECT * FROM base_menu_top WHERE is_use = 1 {$condition} ORDER BY sort ASC";
        $resultMenuTop = $this->m_left->findSql($sqlMenuTop);
        
        foreach ($resultMenuTop as $menuTop){
            //获取二级菜单
            $sqlMenu= "SELECT * FROM base_menu WHERE is_use = 1 {$conditions} AND menu_top_id = {$menuTop['id']} ORDER BY sort ASC, team ASC";
            $resultMenu = $this->m_left->findSql($sqlMenu);
            //获取二级菜单的所有team
            $sqlTeam = "SELECT DISTINCT team FROM base_menu WHERE menu_top_id = {$menuTop['id']}  ORDER BY  team ASC";
            $resultTeam = $this->m_left->findSql($sqlTeam);
            //按照team分组排序返回subList
            $menuSubList = array();//初始化menuSubList
            for($i = 0; $i < count($resultTeam);$i++){
                foreach ($resultMenu as $menu){
                    if($resultTeam[$i]['team'] == $menu['team']){
                        $menuSubList[$i] [] = $menu;
                    }
                }
            }
            //构造对象化的menuList
            $menuList[] = array(
                'id'=> $menuTop['id'],
                'name' => $menuTop['name'],
                'alias' => $menuTop['alias'],
                'sort' => $menuTop['sort'],
                'subList' => $menuSubList
            );
        }

        return common::errorArray(0, "获取成功", $menuList);
    }
    
    //************************************左侧菜单***********************************
    
	/**
	 * 菜单信息修改
	 * @param array $condition
	 * @param array $menuInfo
	 * @return array $result
	 */
	public function updateMenu($condition,$menuInfo){
		try{
			$result = $this->m_left->update ($condition,$menuInfo );
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
	 * 获取菜单列表
	 * @param array $conditions
	 * @param string $sort
	 * @return array $result
	 */
	public function findAllMenu($conditions,$sort = ''){
		try{
			$result = $this->m_left->findAll($conditions,$sort);
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
	 * 按模块查询所有菜单
	 * @return array
	 */
	public function findAllMenuSql(){
		try{
			$sql = "select  a.*,b.name as module from base_menu as a left join base_menu_top as b on a.menu_top_id = b.id order by b.sort asc,b.id asc,a.team asc,a.sort asc,a.id;";
			$result = $this->m_left->findSql($sql);
			if(true == $result ){
				return common::errorArray(0, "查找成功", $result);
			}else{
				return common::errorArray(1, "查找为空", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 获取某个菜单
	 * @param array $conditions
	 * @return array $result
	 */
	public function findMenu($conditions){
		try{
			$result = $this->m_left->find($conditions);
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
	 * 添加菜单
	 * @param array $row
	 * @return array $result
	 */
	public function addMenu($row){
		try{
			$id = $this->m_left->create($row);
			if(true == $id ){
				return common::errorArray(0, "添加成功", $id);
			}else{
				return common::errorArray(1, "添加失败", $id);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	/**
	 * 删除菜单
	 * @param array $conditions
	 * @return array $result
	 */
	public function deleteMenu($conditions){
		try{
			$result = $this->m_left->delete($conditions);
			if(true == $result ){
				return common::errorArray(0, "删除成功", $result);
			}else{
				return common::errorArray(1, "无删除项目", $result);
			}
		}catch (Exception $ex){
		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
		    return  common::errorArray(1, "数据库操作失败",$ex);
		}
	}
	
	//************************************顶部菜单***********************************
	
	/**
	 * 查询所有模块
	 * @param array $conditions
	 * @param array $sort
	 * @return array $result
	 */
	public function findAllMenuTop($conditions,$sort){
		try{
			$result = $this->m_top->findAll($conditions,$sort);
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
	 * 查询某个模块
	 * @param array $conditions
	 * @return array $result
	 */
	public function findMenuTop($conditions){
		try{
			$result = $this->m_top->find($conditions);
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

}