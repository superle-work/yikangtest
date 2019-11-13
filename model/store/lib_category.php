<?php

if(!class_exists('base_model')) require 'include/base/model/base_model.php';

if(!class_exists('m_store_category')) require "model/store/table/m_store_category.php";



/**

 * 提供商城类别管理服务

 * @name lib_category.php

 * @package cws

 * @category model

 * @link http://www.chanekeji.com

 * @author linli

 * @version 2.0

 * @copyright CHANGE INC

 * @since 2016-08-04

 */

class lib_category extends base_model{

    private $m_category;

    

    function __construct(){

        parent::__construct();

        $this->m_category = new m_store_category();

    }

    

	/**

	 * 获取所有一级分类及其对应的子分类

	 * @return array $result

	 */

	function getCategoryFirSec(){
		try {

			$sqlFir = "select * from store_category where rank = 1 order by order_index";
			$firList = $this->m_category -> findSql($sqlFir);

			foreach ($firList as &$fir){

				$sqlSec = "select * from store_category where rank = 2 and fir = '{$fir['fir']}' order by order_index";

				$secList = $this->m_category -> findSql($sqlSec);

				$fir['subList'] = $secList;

			}
			return common::errorArray(0, "查找成功", $firList);
		}catch (Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);
			return common::errorArray(1, "数据库操作失败", $ex);
		}

	}

	

	/**

	 * 查找类别

	 * @param array $condition

	 * @return array $result

	 */

	public function findCategory($condition){

		try {

			$reslut = $this->m_category -> find($condition);

			return common::errorArray(0, "查找成功", $reslut);

		}catch (Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

			return common::errorArray(1, "查找失败", $reslut);

		}

	}

	

	/**

	 * 获取标签及其子标签下所有商品

	 * @param int $cid

	 * @return array $result  ['data']=gids

	 */

	public function getCateGids($cid){

	    try{

	        $cateInfo = $this->m_category->find( array('id'=>$cid));

	        if(true != $cateInfo){

	            return common::errorArray(1, "查找父标签失败", $cateInfo);

	        }

	        $condition = array();

	        if($cateInfo['rank'] >= '4'){

	            $condition['fou'] =  $cateInfo['fou'];

	        }

	        if($cateInfo['rank'] >= '3'){

	            $condition['thr'] =  $cateInfo['thr'];

	        }

	        if($cateInfo['rank'] >= '2'){

	            $condition['sec'] =  $cateInfo['sec'];

	        }

	        if($cateInfo['rank'] >= '1'){

	            $condition['fir'] =  $cateInfo['fir'];

	        }

	        $result = $this->m_category->findAll($condition);

	        if(true == $result){

	            $aGids = array();

	            foreach($result as $cate){

	                if($cate['gids'] != ''){

	                    $aGids = array_merge($aGids, explode(',', $cate['gids']));

	                }

	            }

	            $gids = implode(',' , array_unique($aGids));

	            if($gids == ''){

	                $gids = 0;

	            }

	            return common::errorArray(0, "查找成功", $gids);

	        }else{

	            return common::errorArray(1, "查找失败", $result);

	        }

	    }catch (Exception $ex){

	        $this->errorLog(__CLASS__, __FUNCTION__, $ex);

	        return  common::errorArray(1, "数据库操作失败",$ex);

	    }

	}

	

	/**

	 * 查找对应条件的所有类别

	 * @param array $condition

	 * @return array $result

	 */

	public function findAllCategory($condition,$sort = null){

		try {

			$reslut = $this->m_category -> findAll($condition,$sort);

			return common::errorArray(0, "查找成功", $reslut);

		}catch (Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

			return common::errorArray(1, "查找失败", $reslut);

		}

	}



	/**

	 * 添加类别

	 * @param array $conditions

	 * @return array $result

	 */

	public function addCategory($conditions){

		try {

// 			$categoryTemp = $this->isCategoryExisit($conditions['name']);

// 			if($categoryTemp['errorCode']==1){

				$condition = $conditions;

				$condition['add_time'] = date('Y-m-d',time());

				$reslut = $this->m_category -> create($condition);

				if(true ==$reslut){

					return common::errorArray(0, "添加成功", array(

							"addCatageryId" =>$reslut

					));

				}else{

					return common::errorArray(1, "添加失败", array(

							"addCatageryId" =>$reslut

					));

				}

// 			}else{

// 				return common::errorArray(1, "添加失败", array(

// 						"addCatageryId" =>$reslut

// 				));

// 			}

		}catch(Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

			return common::errorArray(1, "数据库操作失败", $ex);

		}

	}



	/**

    	 * 获取指定分类子分类最大编号

    	 * @param array $conditions

    	 * @return array $result

    	 */

    	function getCategoryNum($conditions){

    		try {

    			$rank = $conditions['rank'];

    			if ($rank == 1){

    				$sql = 'SELECT  max(fir) FROM store_category WHERE sec is null';

    				$fir = $this->findSql($sql);

    				$fir = $fir['0']['max(fir)'];

    				$max = $fir;

    			}else{

    				$fir = $conditions['fir'];

    				if ($rank == 2){

    					$sql = 'SELECT  max(sec) FROM store_category WHERE fir='.$fir;

    					$sec = $this->findSql($sql);

    					$sec = $sec['0']['max(sec)'];

    					$max = $sec;

    				}else{

    					$sec = $conditions['sec'];

    					if ($rank == 3){

    						$sql = 'SELECT  max(thr) FROM store_category WHERE fir='.$fir.' and sec='.$sec;

    						$thr = $this->findSql($sql);

    						$thr = $thr['0']['max(thr)'];

    						$max = $thr;

    					}else{

    						$thr = $conditions['thr'];

    						$sql = 'SELECT  max(fou) FROM store_category WHERE fir='.$fir.' and sec='.$sec.' and thr='.$thr;

    						$fou = $this->findSql($sql);

    						$fou = $fou['0']['max(fou)'];

    						$max = $fou;

    					}

    				}

    		}

    		if($max !=null && $max !=""){

    		    //有数据时，即有子分类时

    		    $max = (int)$max;

    		    if(($max+1) < 10){

    		        $subCateNo = "00".($max+1);

    		    }else if(($max+1) < 100){

    		        $subCateNo = "0".($max+1);

    		    }else{

    		        $subCateNo = ($max + 1);

    		    }

    		}else{

    		    //查询结果为空，无子分类时,当前子分类编号置为“001”

    		    $subCateNo = "001";

    		}

    		return common::errorArray(0,"查询成功",$subCateNo);

		}catch(Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

			return common::errorArray(2, "数据库操作失败", $ex);

		}

    }

    

	/**

	 * 查找对应类别

	 * @param array $conditions

	 * @param string $sort

	 * @param int $is_use

	 * @return array $result

	 */

	public function getCategorys($conditions,$sort,$is_use = 1){

			if(!empty($is_use)){

				$conditions['is_use'] = $is_use;//默认显示isuse为1的分类，即过滤停用的分类

			}

    		try {

    			$reslut = $this->m_category -> findAll($conditions,$sort);

    			if(true == $reslut){

    				return common::errorArray(0, "查询成功",$reslut);

    			}else{

    				return common::errorArray(1, "查询为空", $reslut);

    			}

    		}catch(Exception $ex){

    		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

    			return common::errorArray(2, "数据库操作失败", $ex);

    		}

    }

    

    /**

     ** 通过一级分类获取二级和对应三级子分类属性,都是同层次展示

     * @param string $fir

	 * @return array $result

     **/

    public function getCategorysByFir($fir) {

        try {

            $sql = "select * from store_category where fir ={$fir} and rank !=1  order by sec,thr";

            $reslut = $this->m_category->findSql ( $sql );

            if (true == $reslut) {

                return common::errorArray ( 0, "查询成功", $reslut );

            } else {

                return common::errorArray ( 1, "查询为空", $reslut );

            }

        } catch ( Exception $ex ) {

            $this->errorLog(__CLASS__, __FUNCTION__, $ex);

            return common::errorArray ( 1, "数据库操作失败", $ex );

        }

    }

    

    /**

     * 通过一级分类获取二级和对应三级子分类属性（数据格式为对象）

     * @param string $fir

     * @return array $result

     */

    public function getCategorysByFirObj($fir){

    	try {

    		$sqlSec = "select * from store_category where fir ={$fir} and rank =2  order by sec";

    		$reslutSec = $this->m_category->findSql ( $sqlSec );

    		foreach($reslutSec as &$perSec){

    			$sqlThr = "select * from store_category where fir ={$fir}  and sec = {$perSec['sec']} and rank =3  order by thr";

    			$reslutThr = $this->m_category->findSql ( $sqlThr );

    			$perSec['subList'] = $reslutThr;

    		}

    		if (true == $reslutSec) {

    			return common::errorArray ( 0, "查询成功", $reslutSec );

    		} else {

    			return common::errorArray ( 1, "查询为空", $reslutSec );

    		}

    	} catch ( Exception $ex ) {

    	    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

    		return common::errorArray ( 1, "数据库操作失败", $ex );

    	}

    }

	

	/**

	 * 修改分类

	 * @param array $conditions

	 * @param array $categoryInfo

	 * @return array $result

	 */

	public function updateCategory($conditions,$categoryInfo){

		try {

// 				if($categoryInfo['name'] != null && $categoryInfo['name'] != false){//判断是否修改类别名

// 					$categoryTemp = $this->isCategoryExisit($categoryInfo['name'],$categoryInfo['id']);//修改类别名判断是否已存在

// 					if($categoryTemp['errorCode']== 0){//存在则返回错误

// 						return common::errorArray(1, "修改失败", $categoryTemp);

// 					}

// 				}

			

				$reslut = $this->m_category -> update($conditions,$categoryInfo);

				if(true ==$reslut){

					return common::errorArray(0, "修改成功",$reslut);

				}

		}catch(Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

			return common::errorArray(1, "数据库操作失败", $ex);

		}

	}

	

	/**

	 * 查看分类名存在

	 * @param string $name

	 * @param string $id

	 * @return array $result

	 */

	public  function isCategoryExisit($name,$id){

		$conditions = array(

				'name' => $name

		);

		try {

			$result = $this->m_category -> find($conditions);

			if($result == true && $result['id'] != $id){

				return common::errorArray(0, "类名存在",$result);

			}else{

				return common::errorArray(1, "类名不存在", $result);

			}

		}catch(Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

			return common::errorArray(2, "数据库操作失败", $ex);

		}

	}

	

	/**

	 * 删除分类

	 * @param int $gid

	 * @return array $result

	 */

	public function deleteCategory($id){

		try {

			$conditionId = array(

				'id'=> $id

			);

			$category_array = $this->getCategorys($conditionId);

			$category = $category_array['data'][0];

			$conditions =array(

				'fir'=>$category['fir']

			);

			if($category['sec']){

				$condition =array(

						'sec'=>$category['sec']

				);

				$conditions = array_merge($conditions,$condition);

				if($category['thr']){

					$condition =array(

							'thr'=>$category['thr']

					);

					$conditions = array_merge($conditions,$condition);

					if($category['fou']){

						$condition =array(

								'fou'=>$category['fou']

						);

						$conditions = array_merge($conditions,$condition);

					}

				}

			}

			

			$reslut = $this->m_category -> delete($conditions);

			if(true ==$reslut){

				//删除分类后，删除该分类下的所有商品

				if(!class_exists("lib_goods"))include "model/store/lib_goods.php";

				$lib_goods=new lib_goods();

				$res=$lib_goods->batchDelete($category['gids']);

				return common::errorArray(0, "删除成功",$reslut);

			}else{

				return common::errorArray(1, "删除失败", $reslut);

			}

		}catch(Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

			return common::errorArray(2, "数据库操作失败", $ex);

		}

	}

    	

	/**

	 * 删除分类

	 * @param array $conditions

	 * @return array $result

	 */

	public function deleteCategoryByNum($conditions){

		try {

			$reslut = $this->m_category -> delete($conditions);

			if(true ==$reslut){

				return common::errorArray(0, "删除成功",$reslut);

			}else{

				return common::errorArray(1, "删除失败", $reslut);

			}

		}catch(Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

			return common::errorArray(2, "数据库操作失败", $ex);

		}

	}

	

	/**

	 * 分页查询类别

	 * @param array$page 分页信息

	 * @param array $conditions 条件查询条件

	 * @param string $sort 排序字段及方式

	 * @param string $keywords 模糊查询

	 * @return array $result

	 */

	public function pagingCategorys($page, $conditions, $sort = null, $keywords = null){

	    $result = $this->m_category->paging($page, $conditions, $sort, $keywords);

		$this->errorLog(__CLASS__, __FUNCTION__, $result);

		return common::errorArray($result['errorCode'], $result['errorInfo'], $result);

	}

	

    /**

     *  保存或更新单个类别信息

     *  @param $flag 当前操作标识：0表示添加，1表示更新

     *  @param $category 当前待保存的分类信息

     *  @return array $result

     */

     function singleSaveCategory($flag,$category){

        if($flag == 0){//添加

          try{

          	$category['add_time'] = date("Y-m-d",time());

          	$result = $this->m_category->create($category);

          

          	if($result){

          		return common::errorArray(0, "添加类别成功", $result);

          	}else{

          		return common::errorArray(1, "添加类别失败", $result);

          	}

          }catch (Exception $ex){

            $this->errorLog(__CLASS__, __FUNCTION__, $ex);

          	return common::errorArray(1, "数据库操作失败", $ex);

          }

        }else{//更新

	        try{

				$id = $category['cid'];

				unset($category['cid']);//删除该条件

				$result = $this->m_category->update(array('id' => $id),$category);

					

				if($result){

					return common::errorArray(0, "更新类别成功", $result);

				}else{

					return common::errorArray(1, "更新类别失败", $result);

				}

			}catch (Exception $ex){

			    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

				return common::errorArray(1, "数据库操作失败", $ex);

			}

        }

     }

     

    /**

     * 批量插入和更新类别

     * @param array $records

     * @param array $updateData

     * @return array $result

     */

    function batchCategory($addData,$updateData){

        $addInfo = $addData;

        $updateInfo = $updateData;

    	 if(count($addInfo) == 0 && count($updateInfo) == 0){

            return common::errorArray(1, "数据全为空", 0);

        }



        $addSql = "INSERT INTO store_category ";

        $addFields = "(";

        $addValues = "";



        $addResult = true;



        if(count($addInfo) > 0){

            $i = 0;

            $fieldCount = count($addInfo['0']);

            foreach ($addInfo['0'] as $keyAdd => $addPer){

                $i++;

                if($i == $fieldCount){

                    $addFields .= $keyAdd .")";

                }else{

                    $addFields .= $keyAdd . ",";

                }

            }



            $j = 0;

            $fieldCountVaule = count($addInfo);

            foreach ($addInfo as $addPerValue){

                $j++;

                $addValues .= "(";

                if($j == $fieldCountVaule){

                    $addValues .= $this->getPerValue($addPerValue);

                }else{

                    $addValues .= $this->getPerValue($addPerValue) . ",";

                }

            }



            $addSql .= $addFields . " VALUES " . $addValues;



            $addResult = $this->m_category->runSql($addSql);



        }



        if($addResult == false){

            return common::errorArray(1, '插入失败', 0);

        }

        if(count($updateInfo) > 0){

            foreach ($updateInfo as $perUpdate){

                $conditions  = array();

                if($perUpdate['rank'] == 1){

                    $conditions = array(

                            'rank' => $perUpdate['rank'],

                            'fir' => $perUpdate['fir'],

                    );

                }else if($perUpdate['rank'] == 2){

                    $conditions = array(

                            'rank' => $perUpdate['rank'],

                            'fir' => $perUpdate['fir'],

                            'sec' => $perUpdate['sec'],

                    );

                }else if($perUpdate['rank'] == 3){

                    $conditions = array(

                            'rank' => $perUpdate['rank'],

                            'fir' => $perUpdate['fir'],

                            'sec' => $perUpdate['sec'],

                            'thr' => $perUpdate['thr'],

                    );

                }else if($perUpdate['rank'] == 4){

                    $conditions = array(

                            'rank' => $perUpdate['rank'],

                            'fir' => $perUpdate['fir'],

                            'sec' => $perUpdate['sec'],

                            'thr' => $perUpdate['thr'],

                            'fou' => $perUpdate['fou']

                    );

                }

                $updateResult = $this->updateCategoryNoSame(

                        $conditions,

                        array(

                        'name' => $perUpdate['name'],

//                      'icon' => $perUpdate['icon'],

                        'order_index' =>$perUpdate['order_index'],

                        //'tid' =>$perUpdate['tid'],

                        //'is_use' =>$perUpdate['is_use'],

//                      'img_url'=> $perUpdate['img_url']

                    )

                );



                if($updateResult['errorCode'] != 0){

                    return common::errorArray(1, '更新失败', 0);

                }

            }

        }

        

        return common::errorArray(0, '更新成功', 1);

    }

    

    /**

     * 获取每一个类别的值

     * @param array $arrayValue

     * @return string 

     */

    private function getPerValue($arrayValue){

        $strValue = "";

        $i = 0;

        $fieldCount = count($arrayValue);

        foreach ($arrayValue as $perValue){

            //$strValue .= "'"+common::guid() + "',";

            $i++;

            if($i == $fieldCount){

                $strValue .= "'" .  $perValue ."')";

            }else{

                $strValue .= "'" . $perValue . "',";

            }

        }



        return $strValue;

    }

    

    /**

     * 修改分类不判断名字重复

     * @param array $conditions

     * @param array $categoryInfo

     * @return array $result

     */

    public function updateCategoryNoSame($conditions,$categoryInfo){

        try {

            $reslut = $this->m_category -> update($conditions,$categoryInfo);

            if(true ==$reslut){

                return common::errorArray(0, "修改成功",$reslut);

            }else{

                return common::errorArray(1, "修改失败", $reslut);

            }

        }catch(Exception $ex){

            $this->errorLog(__CLASS__, __FUNCTION__, $ex);

            return common::errorArray(1, "数据库操作失败", $ex);

        }

     }

     



     /**

      * 添加商品时批量修改gids

      */

     public function updateGids($gid, $cids){

         try {

             $sql = "UPDATE store_category

             SET gids = IF(gids,CONCAT(gids,',','{$gid}'),'{$gid}')

             WHERE id IN ({$cids})";

             $result = $this->m_category->runSql($sql);

             if($result == true){

                 return common::errorArray(0, "修改成功",$result);

             }else{

                 return common::errorArray(1, "修改失败", $result);

             }

         }catch(Exception $ex){

             $this->errorLog(__CLASS__, __FUNCTION__, $ex);

             return common::errorArray(1, "数据库操作失败", $ex);

         }

     }

     

     

     /**

      *分类-上移

      **/

     public function moveUp($cate,$preCate){

		return	$this->movePri($cate,$preCate);

     }

     

     /**

      * 启用、停用分类

      * @param int $id

      * @param int $isUse

      * @return array $result

      */

     public function changeStatus($id,$isUse){

     	try {

     		$reslut = $this->m_category -> update(array('id' => $id),array('is_use' => $isUse));

     		if(true ==$reslut){

     			return common::errorArray(0, "修改分类显示成功",$reslut);

     		}else{

     			return common::errorArray(1, "修改分类显示失败", $reslut);

     		}

     	}catch(Exception $ex){

     	    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

     		return common::errorArray(2, "数据库操作失败", $ex);

     	}

     }



	/**

	 * 通过cid获取一级分类及其对应的子分类或者商品

	 * @return array $result

	 */

	function getCateFirSecThrGoods($cid){

		if(!class_exists('lib_goods')) include 'model/store/lib_goods.php';

		$m_goods = new lib_goods();

		//分类等级		

		$store_config = new UtilConfig('store_config');

		$cateConfig = $store_config->getConfigValue('cate_level');

		$cateLevel = $cateConfig['data'];

		try {

			//查找一级信息

			$sqlFir = "select * from store_category where id = {$cid} order by order_index";

			$firList = $this->m_category -> findSql($sqlFir);

			if($cateLevel != 1){

				//查找二级信息

				$sqlSec = "select * from store_category where rank = 2 and fir = '{$firList[0]['fir']}' order by order_index";

				$secList = $this->m_category -> findSql($sqlSec);

				if(count($secList)>0){ //有二级分类

					if($cateLevel == 2){

						$result['level'] = 2;

						$result['data'] = $secList;

						return common::errorArray(0, "查询成功", $result);	

					}else{

						$thrCate = array();

						foreach ($secList as $sec){//查找3级分类

							$sqlSec = "select * from store_category where rank = 3 and fir = '{$firList[0]['fir']}'and sec = {$sec['sec']} order by order_index";

							$thrList = $this->m_category -> findSql($sqlSec);

							if(!empty($thrList)){//过滤不存在三级分类的二级分类   如果不存在三级但是绑了商品 BUG

								$thrCate[$sec['name']] = $thrList;

							}				

						}

						if(count($thrCate)>0){ //存在3级分类

							$result['level'] = 3;

							$result['data'] = $thrCate;

							return common::errorArray(0, "查询成功", $result);	

						}else{//不存在 返回二级分类信息

							$result['level'] = 2;

							$result['data'] = $secList;

							return common::errorArray(0, "查询成功", $result);	

						}

					}

				}else{//查询一级分类的商品

					if(!empty($firList[0]['gids'])){

						$sqlGoods = "select * from store_goods where updown = 1 and id in ({$firList[0]['gids']}) limit 24 order by sort_num ";

						$goodsResult = $m_goods->findSql($sqlGoods);

						$result['level'] = 1;

						$result['data'] = $goodsResult;

						return common::errorArray(0, "查询成功", $result);	

					}else{

						return common::errorArray(1, "查询失败", $firList);

					}				

				}				

			}else{//只有一级分类

				if(!empty($firList[0]['gids'])){

					$sqlGoods = "select * from store_goods where updown = 1 and id in ({$firList[0]['gids']}) order by sort_num";

					$goodsResult = $m_goods->findSql($sqlGoods);

					$result['level'] = 1;

					$result['data'] = $goodsResult;

					return common::errorArray(0, "查询成功", $result);	

				}else{

					return common::errorArray(1, "查询失败", $firList);

				}	

			}			

		}catch (Exception $ex){

		    $this->errorLog(__CLASS__, __FUNCTION__, $ex);

			return common::errorArray(1, "数据库操作失败", $ex);

		}

	}



	/**

	 * 分类为3级的时候分页查询数据

	 * @param array $page

	 * @param array  $conditionList

	 *  array(

	 *  	array("field" => "name","operator" => "=","value" => $this->spArgs('phone'),

	 *  	array("field" => "name","operator" => "like","value" => $this->spArgs('name'),

	 *  	array("field" => "id","operator" => "in","value" => $this->spArgs('ids'),

	 *  	array("field" => "name","operator" => ">=","value" => $this->spArgs('from'),

	 *  	array("field" => "name","operator" => "<=","value" => $this->spArgs('to')

	 *  )

	 * @param string $sort

	 * @param array $orList

	        $orList = array();

			$orConditionList = array();

			array_push($orConditionList, array('field'=>"option_name","operator"=>"like","value"=>"{$this->spArgs('searchText')}"));

			array_push($orConditionList, array('field'=>"num","operator"=>"=","value"=>"{$this->spArgs('searchText')}"));

			array_push($orList, $orConditionList);

	 * @return array

	 */

	public function pagingThrCate($page,$fir)

	{

		$page['pageIndex'] ? $pageIndex = $page['pageIndex'] : $pageIndex = 1;

		$page['pageSize'] ? $pageSize = $page['pageSize'] : $pageSize = 10;

		$m = ($pageIndex -1) * $pageSize;

		$n =  $pageSize;		

		//查找二级信息

		$sqlSec = "select * from store_category where rank = 2 and fir = {$fir} order by order_index LIMIT {$m}, {$n}";

		$secList = $this->m_category -> findSql($sqlSec);

		$thrCate = array();

		foreach ($secList as $sec){//查找3级分类

			$sqlSec = "select * from store_category where rank = 3 and fir = '{$fir}'and sec = {$sec['sec']} order by order_index";

			$thrList = $this->m_category -> findSql($sqlSec);

			if(!empty($thrList)){//过滤不存在三级分类的二级分类   如果不存在三级但是绑了商品 BUG

				$thrCate[$sec['name']] = $thrList;

			}				

		}

		try {

			$result['dataList'] = $thrCate; //$this->findSql($sqlLimit);

			$sql = "select * from store_category where rank = 2 and fir = {$fir} ";

			$sql = "SELECT count(*) as total_record_num  from ( {$sql} ) as count_table";

			$count = $this->findSql($sql);

			//如果之后1页，手动添加分页信息

			if($result['pageInfo']==NULL){

				$result['pageInfo']['current_page'] = $pageIndex;

				$result['pageInfo']['first_page'] = 1;

				$result['pageInfo']['prev_page']=$pageIndex - 1;

				$result['pageInfo']['next_page']=$pageIndex + 1;

				$result['pageInfo']['last_page']=ceil ($count[0]['total_record_num'] / $pageSize);

				$result['pageInfo']['total_count']= $count[0]['total_record_num'];

				$result['pageInfo']['total_page'] = ceil ($count[0]['total_record_num'] / $pageSize);

				$result['pageInfo']['page_size'] = $pageSize;

				$result['pageInfo']['all_pages'] = ceil ($count[0]['total_record_num'] / $pageSize);

			}

			return common::errorArray(0, "查询成功", $result);

		} catch (Exception $ex) {return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

}