<?php
namespace Api\Action;
class CommonAction extends BaseAction {

    //检查距离变化
    public function ext_distance(){
        $start=I('start');
        //$start='34.7466,113.625368';
        $stop=$this->get_shopsite();
        //计算商铺距离
        $data=S('shops');
        $distance=get_distance($start,$stop);
        $distance=json_decode($distance,true);
        foreach ($distance['result']['elements'] as $key=>$v){
            $shop_site[$key]['shopid']=$data[$key]['shopid'];
            $shop_site[$key]['distance']=$v['distance'];
        }
        //保存距离到本地缓存
        echo json_encode($shop_site,JSON_UNESCAPED_UNICODE);
    }


    //关于我们/帮助中心/客服中心;
    public function article(){
        $catId=I('type');
        $articleId = I('articleId',0);
        if($catId)$where['catId'] = $catId;
        if($articleId)$where['articleId'] = $articleId;
        $article=M('articles');
        $data=$article->where($where)->find();
        if($data!==false){
            return_json(1,'成功',$data);
        }else{
            return_json(-1,'失败',$data);
        }
    }


    //获取商品分类
    public function get_goods_cate(){
        $goodscCate=M('goods_cats');
        $list=$goodscCate->field('catId,catName,catThumbs,parentId,monthNum,FORMAT(priceSection,2) as price')->where(array('isShow'=>1))->select();
        $data=array();
        foreach ($list as $v){
            if($v['parentId']==0){
                foreach ($list as $b){
                    if($b['parentId']==$v['catId']){
                        $v['children'][]=$b;
                    }
                }
                $data[]=$v;
            }
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }



    //短信验证
    public function get_yzm(){
        //业务参数
        $mobile=I('mobile');    //手机号
        $text='1234567890';
        $passcode='';
        for($i=0;$i<4;$i++){
            $j=mt_rand(0,9);
            $passcode.=$text[$j];
        }
        $data['smsPhoneNumber']=$mobile;
        $content = "正在进行绑定手机操作，您的验证码为".$passcode."【抢单联盟】";
        $data['smsContent']=$content;
        $data['smsCode']=$passcode;
        $data['createTime']=date('Y-m-d H:i:s');
        $sms_log=M('log_sms');
        $rs=$sms_log->where(array('smsPhoneNumber'=>$mobile))->find();
        if($rs){
            $rs1=$sms_log->where(array('smsPhoneNumber'=>$mobile))->save($data);
        }else{
            $rs1=$sms_log->add($data);
        }
        if($rs1==false){
            echo 0;exit();
        }
        //发起请求
        $res=send_sms($mobile,$content);
        $res = json_decode($res,true);
        $this->ajaxReturn($res['returnstatus']);
    }

    /**
     * 广告
     */
    public function ads(){
        $adType=I('adType',1);
        $data=M('ads')->field('adFile')->where(array('adType'=>$adType))->order('adsort')->select();
        $data?return_json(1,'成功',$data):return_json(-1,'失败');
    }

}