<?php
namespace Api\Model;
class WxUserModel extends BaseModel{

    //修改用户信息
    public function modifyInfo(){
        $info=I('post.');
        $data['userName']=$info['nickName'];
        $data['userSex']=$info['gender'];
        $data['userPhoto']=$info['avatarUrl'];
        $data['userAreas']=$info['city'];
        $rs=$this->where(array('userId'=>$info['userId']))->save($data);
        return $rs;
    }

    //绑定手机号
    public function mobile(){
        $userid=I('userId');
        $mobile=I('mobile');
        $passcode=I('passcode');
        $passcode_old=M('log_sms')->where(array('smsPhoneNumber'=>$mobile))->find();
        if($aa=time()-strtotime($passcode_old['createTime'])>300){
            return_json(0,'验证码已失效，请重新获取');
        }elseif($passcode!=$passcode_old['smsCode']){
            return_json(0,'验证码输入错误');
        }else{
            $rs = M('users')->where(array('userId' => $userid))->setField('phone', $mobile);
        }
        return $rs ;
    }

    //获取个人信息
    public function getUserInfo(){
        $userId=I('userId');
        #用户基本信息
        $data=M('Users')->find($userId);

        return $data;
    }


}