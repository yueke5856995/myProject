<?php
namespace Api\Model;
use Think\Model;
class LoginModel extends Model{
    protected $tableName = 'wx_users';
    //获取openid
    public function get_openid(){
        $code=I('code');
        $appid=C('APPID');
        $secret=C('SECRET');
        //初始化
        $str='https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        //打印获得的数据
        $wx_data=json_decode($output,true);
        return $wx_data;
    }


    //注册/登录
    public function register($wxData){
        $sql="openid = $wxData[openid]";
        $user_id=$this->where($sql)->getField('userId');

        //注册并写入数据库
        if(empty($user_id)){
            $data['openId']=$wxData['openid'];
            $data['subscribeTime']=date('Y-m-d H:i:s');
            $user_id=$this->add($data);
            //获取推荐二维码
            $this->create_rqcode($user_id);
        }

        //刷新session并记录返回
        ////////////////////////////////////////

        ///////////////////////////////////////
        return $user_id;

    }

    //获取二维码
    public function create_rqcode($userid){
        $url_file='./access_token';
        $access_token=file_get_contents($url_file);
        $access_token=json_decode($access_token);
        $data['scene']=$userid;
        $data['width']='400';
        $post_data=json_encode($data);
        $url='https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token->access_token;
        $result=api_notice_increment($url,$post_data);
        file_put_contents('./Upload/rqcode/'.$userid.'.jpg',$result);
        $this->where(array('userId'=>$userid))->setField('rqcode','Upload/rqcode/'.$userid.'.jpg');
    }

}