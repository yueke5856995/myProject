<?php
namespace Mobile\Action;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 商品控制器
 */
class GoodsAction extends BaseAction {
	
  
	/**
	 * 查询商品详情
	 * 
	 */
	public function getGoodsDetails(){

		$shareUserId = (int)base64_decode(I("shareUserId"));
		if($shareUserId>0){
			session("WST_SHAREUSERID",$shareUserId);
			if(session("WST_USER.userId")>0){
				$dm = D('Home/Distributs');
				$dm->checkShare();
			}
		}
		
		$goods = D('Home/Goods');
		$kcode = I("kcode");
		$scrictCode = md5(base64_encode("wstmall".date("Y-m-d")));
		
		//查询商品详情		
		$goodsId = (int)I("goodsId");
		$this->assign('goodsId',$goodsId);
		$obj["goodsId"] = $goodsId;	
		
		$packages = $goods->getGoodsPackages($goodsId,1);
		$this->assign('packages',$packages);
		
		$goodsDetails = $goods->getGoodsDetails($obj);
		if($kcode==$scrictCode || ($goodsDetails["isSale"]==1 && $goodsDetails["goodsStatus"]==1)){
			if($kcode==$scrictCode){//来自后台管理员
				$this->assign('comefrom',1);
			}
			$shopServiceStatus = 1;
			if($goodsDetails["shopAtive"]==0){
				$shopServiceStatus = 0;
			}
			$goodsDetails["serviceEndTime"] = str_replace('.5',':30',$goodsDetails["serviceEndTime"]);
			$goodsDetails["serviceEndTime"] = str_replace('.0',':00',$goodsDetails["serviceEndTime"]);
			$goodsDetails["serviceStartTime"] = str_replace('.5',':30',$goodsDetails["serviceStartTime"]);
			$goodsDetails["serviceStartTime"] = str_replace('.0',':00',$goodsDetails["serviceStartTime"]);
			$goodsDetails["shopServiceStatus"] = $shopServiceStatus;
			$goodsDetails['goodsDesc'] = htmlspecialchars_decode($goodsDetails['goodsDesc']);
			$goodsDetails['isDistributGoods'] = 0;
			$shopConf = D('Home/Shops')->getShopConf($goodsDetails["shopId"]);
			if($GLOBALS['CONFIG']['isDistribut']==1 && $shopConf['isDistribut']==1 && $goodsDetails['isDistribut']==1){
				if($shopConf['distributType']==1 && $goodsDetails['commission']>0){
					$goodsDetails['isDistributGoods'] = 1;
				}else if($shopConf['distributType']==2){
					$goodsDetails['isDistributGoods'] = 1;
				}
			}
			
			
			
			$areas = D('Home/Areas');
			$shopId = intval($goodsDetails["shopId"]);
			$obj["shopId"] = $shopId;
			$obj["areaId2"] = $this->getDefaultCity();
			$obj["attrCatId"] = $goodsDetails['attrCatId'];
			$shops = D('Home/Shops');
			$shopScores = $shops->getShopScores($obj);
			$this->assign("shopScores",$shopScores);
			
			$mc = D('Home/Coupons');
			$coupons = $mc->getCouponsByShopId($shopId);
			$this->assign('coupons',$coupons);
			
			$shopCity = $areas->getDistrictsByShop($obj);
			$this->assign("shopCity",$shopCity[0]);
			
			$shopCommunitys = $areas->getShopCommunitys($obj);
			$this->assign("shopCommunitys",json_encode($shopCommunitys));
			
			$this->assign("goodsImgs",$goods->getGoodsImgs());
			$this->assign("relatedGoods",$goods->getRelatedGoods($goodsId));
			$this->assign("goodsNav",$goods->getGoodsNav($goodsId));
			$this->assign("goodsAttrs",$goods->getAttrs($obj));
			$this->assign("goodsDetails",$goodsDetails);
			
			$viewGoods = cookie("viewGoods");
			if(!in_array($goodsId,$viewGoods)){
				$viewGoods[] = $goodsId;
			}
			if(!empty($viewGoods)){
				cookie("viewGoods",$viewGoods,25920000);
			}
			//获取关注信息
			$m = D('Home/Favorites');
			$this->assign("favoriteGoodsId",$m->checkFavorite($goodsId,0));
			$m = D('Home/Favorites');
			$this->assign("favoriteShopId",$m->checkFavorite($shopId,1));
			//客户端二维码
			$this->assign("qrcode",base64_encode("{type:'goods',content:'".$goodsId."',key:'wstmall'}"));			
			$this->display('goods_details');
		}else{
			$this->display('goods_notexist');
		}

	}


  
    
    
    
}