<?php
namespace Api\Model;
use Think\Model;
class BaseModel extends Model{

    //检查access_token是否过期
    public function ext_access_token(){
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

    //获取二维码
    public function create_rqcode($userid){
        $this->ext_access_token();
        $url_file='./access_token';
        $access_token=file_get_contents($url_file);
        $access_token=json_decode($access_token);
        $data['scene']=$userid;
        $data['width']='400';
        $data['page'] = 'pages/grab/grab';
        $post_data=json_encode($data);
        $url='https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token->access_token;
        $result=api_notice_increment($url,$post_data);
        file_put_contents('./Upload/rqcode/'.$userid.'.jpg',$result);
        M('active')->where(array('activeId'=>$userid))->setField('rqcode','Upload/rqcode/'.$userid.'.jpg');
    }

    /**
     * 获取配置
     */
    public function loadConfigs(){
        $type = I('type',0);
            $sql = "select fieldCode,fieldValue from __PREFIX__sys_configs ";
            if($type){$sql .= "where parentId = $type";}
            $sql = "order by parentId asc,fieldSort asc";
            $rs = $this->query($sql);
            $configs = array();
            if(count($rs)>0){
                foreach ($rs as $key=>$v){
                    $configs[$v['fieldCode']] = $v['fieldValue'];
                }
            }
            unset($rs);

        return $configs;
    }

    //获取商铺经纬度列表
    public function get_shopsite(){
        $shop=S('shops');
        foreach ($shop as $v){
            $site_str[]=array('shopId'=>$v['shopId'],'latitude'=>$v['latitude'],'longitude'=>$v['longitude']);
        }
        return $site_str;
    }

    public function get_near_shopid($latitude=100,$longitude=100){
        $shop_site=$this->get_shopsite();
        $distance=pow($latitude-$shop_site[0]['latitude'],2)+pow($longitude-$shop_site[0]['longitude'],2);
        $shopId=$shop_site[0]['shopId'];
        foreach ($shop_site as $v){
            $distance1=pow($latitude-$v['latitude'],2)+pow($longitude-$v['longitude'],2);
            if($distance>$distance1){
                $distance=$distance1;
                $shopId=$v['shopId'];
            }
        }
        return $shopId;

    }



}