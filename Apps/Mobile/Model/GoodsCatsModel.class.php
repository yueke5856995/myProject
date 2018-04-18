<?php
namespace Mobile\Model;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 商品分类服务类
 */
class GoodsCatsModel extends BaseModel {
	
 	/**
     * 获取商品分类列表
     * catId 分类ID
     * catName 商品名称
     * catFlag 删除属性 isShow 隐藏属性 catSort 排序
     */
	public function queryCats($pid = 0){
	    $m = M('goods_cats');
	    $rs = $m->field("catId,catName")->where('catFlag=1 and isShow=1 and parentId='.$pid)->order("catSort ASC")->select(); 
	    for($i=0,$k=count($rs);$i<$k;$i++){
	    	$catId = $rs[$i]["catId"];
	    	$childs = $m->field("catId,catName")->where('catFlag=1 and isShow=1 and parentId='.$catId)->order("catSort ASC")->select();
	    	// 判断是否有二级分类
	    	if(!empty($childs)){
	    		$rs[$i]["childs"] = $childs;
	    	}
	    }
		return $rs;
	}

	
};
?>