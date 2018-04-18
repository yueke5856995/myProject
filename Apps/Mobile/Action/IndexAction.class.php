<?php
namespace Mobile\Action;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 首页控制器
 */
class IndexAction extends BaseAction {
    /**
    * 获取所有商品分类列表
    * http://00.37518.com/index.php?m=Mobile&c=Index&a=GoodsCats;
    */
    public function GoodsCats(){
   		//获取分类
		$gcm = D('Mobile/GoodsCats');
		$rs = $gcm->queryCats();
       // var_dump($rs);
		$this->ajaxReturn($rs);
   		
    }

    /**
     * 首页轮播图
     * http://00.37518.com/index.php?m=Mobile&c=Index&a=BannerPic;
     */
    public function BannerPic(){
        $rs=WSTAds(-10);
        $this->ajaxReturn($rs);
    }


    /**
     * 首页模块图以及商品
     * http://00.37518.com/index.php?m=Mobile&c=Index&a=Ads&rows=@value;
     * @param rows 模块下的商品显示数量 (不填默认全部)
     * @return array 
     */
    public function Ads(){
        $rows=I("rows");
        $ads = D('Mobile/Ads');
        $areaId2 = $this->getDefaultCity();
        //分类广告
        $rs = $ads->getAdsByCat($areaId2,$rows);
        $this->ajaxReturn($rs);
    }


    



}