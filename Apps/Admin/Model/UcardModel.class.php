<?php
 namespace Admin\Model;

 /**
 * 微信小程序会员列表服务类
 */
class UcardModel extends BaseModel {
	 /**
      * 获取卡片流水
      */
	 protected $tableName = 'user_card';
	 public function UcardWash(){
        $ucardId = I('id',0);
        $sql = "select * from __PREFIX__log_card where dataFlag = 1 ";
        if($ucardId > 0){$sql .=" and ucardId = $ucardId";}
        $sql .= " order by createTime desc";
        $data = $this->pageQuery($sql);
        return $data;
     }

};
?>