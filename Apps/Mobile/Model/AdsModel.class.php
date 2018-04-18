<?php
namespace Mobile\Model;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 广告服务类
 */
class AdsModel extends BaseModel {
	/**
	 * 获取广告
	 */
	public function getAds($areaId2,$adType){
		$today = date("Y-m-d");
		$data = S('WST_CACHE_ADS_'.$areaId2."_".$adType);
		if(!$data){
			//获取所在省份
			$sql = "select parentId from __PREFIX__areas where areaId=".$areaId2;
			$areaId1 = $this->queryRow($sql);
			$areaId1 = (int)$areaId1['parentId'];
			$sql = "select adId,adName,adURL,adFile from __PREFIX__ads WHERE (areaId2 = $areaId2 or areaId1 = 0 or (areaId1=".$areaId1." and areaId2=0))
						AND adStartDate<='$today' AND adEndDate >='$today' and adPositionId=".$adType." order by adSort asc";
			$data = $this->query($sql);
			S('WST_CACHE_ADS_'.$areaId2."_".$adType,$data,86400);
		}
		return $data;
	}

	/**
	 * 获取主页分类广告以及下面的商品
	 */
	public function getAdsByCat($areaId2,$rows){
		$today = date("Y-m-d");
		$data = S('WST_CACHE_ADS_CAT_'.$areaId2);
		if(!$data){
			//获取所在省份
			$sql = "select parentId from __PREFIX__areas where areaId=".$areaId2;
			$rs = $this->queryRow($sql);
			$areaId1 = $rs['parentId'];
			$sql = "select adId,adURL,adFile,adPositionId as goodsChilds from __PREFIX__ads WHERE (areaId2 = $areaId2 or areaId1 = 0 or areaId1=".$areaId1.")
					AND adStartDate<='$today' AND adEndDate >='$today' and adPositionId>0 order by adSort asc";
			$rs = $this->query($sql);
			$data = array();
			foreach ($rs as $v){
				$data[] = $v;
			}
			 
		}

		$m = M('goods');
		
		foreach($data as &$v){
	    	
	    	$childs = $m->field("goodsId,goodsName,goodsImg")->where(array('goodsCatId1'=>$v['goodsChilds'],'isSale'=>1))->limit($rows)->order("createTime DESC")->select();
	    	// 判断是否有二级分类
	    	if(!empty($childs)){
	    		$v['goodsChilds']=$childs;
	    	}else{
	    		array_pop($v);
	    	}

	    }
	   // S('WST_CACHE_ADS_CAT_'.$areaId2,$data,86400);
	    	
	    return $data;
		
	}
	/**
	 * 广告统计
	 */
	public function statistics($adId){
		$sql = "UPDATE __PREFIX__ads set adClickNum = adClickNum+1 WHERE adId = $adId";		
		$rs = $this->execute($sql);
	}
}