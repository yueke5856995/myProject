<?php
namespace Api\Action;
class ShopAction extends BaseAction{


    //商铺详情api
    public function shop_info(){
        $shopid=I('shopId');
        $shops=S('shops');
        $data=array_filter($shops,function($t) use ($shopid) {return $t['shopid'] == $shopid; });
        $data=array_pop($data);
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }



}