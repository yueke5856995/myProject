<?php
namespace Api\Controller;
/**
 * 基础控制器
 */
use Think\Controller;
class BaseController extends Controller {


    //检查access_token是否过期
    protected function ext_access_token(){
        $url_file='./access_token';
        if(!file_exists($url_file)||(time()-filemtime($url_file))>7100){
            $appid=C('APPID');
            $secret=C('SECRET');
            $str='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
            $ch = curl_init();
            //设置选项，包括URL
            curl_setopt($ch, CURLOPT_URL, $str);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HEADER,0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);//这个是重点。
            //执行并获取HTML文档内容
            $output = curl_exec($ch);
            //释放curl句柄
            curl_close($ch);
            //打印获得的数据
            file_put_contents($url_file,$output);
        }
    }


    //上传/修改照片
    protected function uploadGoodsPic(){
        $picUrl=uploadPic();
        $Filedata=key($picUrl);
        if($picUrl['status']==1){

            $data['Image']=$picUrl[$Filedata]['savepath'].$picUrl[$Filedata]['savename'];
            $data['Thumbs']=$picUrl[$Filedata]['savepath'].$picUrl[$Filedata]['savethumbname'];

            return_json(1,'成功',$data);
        }else{
            return_json(-1,'失败',$picUrl['status']);
        }
    }

    /**
     * 腾讯地图API
     * 获取点之间的距离
     * @param $start
     * @param $stop
     * @return mixed
     */
    protected function get_distance($start,$stop){
        $ch=curl_init();
        //参数
        $model='walking';
        $key='NFKBZ-OUDLR-MLSW5-WDFVB-CR2EE-GWB4M';
        $url='http://apis.map.qq.com/ws/distance/v1/?from='.$start.'&to='.$stop.'&key='.$key;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $output=curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 流水类型
     */
    function get_money_type($type){
        switch ($type){
            case 2:
                return '任务结算';
                break;
            case 3:
                return '充值';
                break;
            case 4:
                return '提现';
                break;
            case 5:
                return '缴费';
                break;
        }
    }


}