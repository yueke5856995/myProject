<?php
namespace Api\Action;
class MessageAction extends BaseAction{

   public function send_message(){
       $data['msgType']=I('msgType');
       $data['sendUserId']=I('sendUserId');
       $data['receiveUserId']=I('receiveUserId');
       $data['msgContent']=I('msgContent');
       $data['createTime']=date('Y-m-d H:i:s');
       $messages=M('Messages');
       $rs=$messages->add($data);
       if($rs!==false){
           return_json(1,'成功');
       }else{
           return_json(-1,'失败');
       }
   }

   public function get_message(){
       $userId=I('userId');
       $messages=M('Messages');
       $data=$messages->where(array('receiveUserId'=>$userId,'msgFlag'=>1))->order('msgStatus desc')->select();
       if($data!==false){
           return_json(1,'成功',$data);
       }else{
           return_json(-1,'失败');
       }
   }

   public function del_message(){
        $id=I('id');
        $rs=M('Messages')->where(array('id'=>$id))->setField('msgFlag',0);
       if($rs!==false){
           return_json(1,'成功');
       }else{
           return_json(-1,'失败');
       }
   }
}