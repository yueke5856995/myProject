<?php
namespace Api\Controller;

class UserController extends BaseController {
    /**
     * 登录注册
     */
    public function login(){
        $Login = M('Login');
        #获取openID和session_key
        $wxData=$Login->get_openid();
        #注册/登录（若数据库没openid为注册，若有即是登录）
        $data = $Login->register($wxData);

        return_json(1,'成功',$data);
    }

    /**
     * 用户信息修改
     * 修改从微信返回的头像昵称性别城市等
     */
    public function modify_info(){
        $WxUser = M('WxUser');

        $rs = $WxUser->modifyInfo();

        $rs ? return_json(1,'成功') : return_json(0,'失败');
    }




    //绑定手机号
    public function mobile(){
        $WxUser = M('WxUser');

        $rs = $WxUser->mobile();

        $rs ? return_json(1,'成功') : return_json(0,'失败');

    }


    //获取个人信息
    public function user_info(){
        $WxUser = M('WxUser');

        $rs = $WxUser->getInfo();

        $rs ? return_json(1,'成功',$rs) : return_json(0,'失败');
    }


    /**
     * 流水记录
     */
    public function cashList(){
        $userId = I('userId');
        $where['targetId|dataId']=$userId;
        $data = M('log_moneys')->field('money,dataSrc,moneyType,createTime')->where($where)->select();
        foreach ($data as &$v){
            $v['dataSrc']=get_money_type($v['dataSrc']);
        }
        if($data!==false){
            return_json(1,'成功',$data);
        }else{
            return_json(0,'失败');
        }
    }


}