<?php
 namespace Admin\Action;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.com 
 * 联系QQ:707563272
 * ============================================================================
 * 申请提现控制器
 */
class CashDrawsAction extends BaseAction{
	/**
	 * 跳去提现页面
	 */
	public function toHandle(){
		$this->isLogin();
		$this->checkPrivelege('txgl_04');
		$rs = D('Admin/CashDraws')->get();
		$this->assign('object',$rs);
		$this->view->display('/moneys/handle');
	}
	
	/**
	 * 申请提现
	 */
	public function handle(){
		$this->isLogin();
		$this->checkPrivelege('txgl_04');
        $rs = $this->payment();
		$this->ajaxReturn($rs);
	}
	
	/**
	 * 获取提现记录列表
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('txgl_00');
		$page = D('Admin/CashDraws')->queryByPage();
		$pager = new \Think\Page($page['total'],$page['pageSize'],I());
		$page['pager'] = $pager->show();
		$this->assign('Page',$page);
		$this->assign('targetType',(int)I('targetType',-1));
		$this->assign('startDate',I('startDate'));
		$this->assign('endDate',I('endDate'));
		$this->assign('cashSatus',(int)I('cashSatus'));
		$this->view->display('/moneys/list_draws');
	}

    //企业付款
    public function payment(){
	    $cashId=I('id');
	    $cashInfo = M('Cash_draws')->find($cashId);
        $userid = $cashInfo['targetId'];
        $tx_orderid=$cashInfo['cashId'];
        $money =$cashInfo['money'];
        $userinfo=M('wx_users')->find($userid);
        //企业付款参数
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $api_data['mch_appid']=C('APPID'); //appid config中配置
        $api_data['mchid']=C('MCH_ID'); //mch_id config中配置
        $api_data['nonce_str']=$this->createNoncestr(32); //创建32位随机码，用于验签
        $api_data['spbill_create_ip']=get_client_ip(); //获取真实ip

        $api_data['partner_trade_no']=$tx_orderid;
        $api_data['openid']=$userinfo['openId'];
        $api_data['check_name']='NO_CHECK';
        $api_data['amount']=$money*100;
        $api_data['desc'] = '提现';
        //一次签名
        $api_data['sign'] = $this->MakeSign($api_data);
        $xml = $this->ToXml($api_data);
        $tmpInfo=curl_post_ssl($url,$xml);
        $arr = $this->FromXml($tmpInfo);

        //相关参数结束

        if($arr['result_code']=="SUCCESS"){
            //业务逻辑
            $cash_draws=M('cash_draws');
            $rs=$cash_draws->where(array('cashId'=>$tx_orderid))->setField('cashSatus',1);
            if($rs){
                //资金流水_提现 [status=4]
                $log_moneys=M('log_moneys');
                $data['targetId']=0;
                $data['dataId']=$userid;
                $data['dataSrc']=4;
                $data['moneyType']=2;
                $data['money']=$money;
                $data['createTime']=date('Y-m-d H:i:s');
                $log_moneys->add($data);
                //echo json_encode($arr['refund_id'],JSON_UNESCAPED_UNICODE);
                return 1;
            }else{
                // echo json_encode($cash_draws->getDbError(),JSON_UNESCAPED_UNICODE);
                return 0;
            }
            //退款结束
        }else {
            $cash_draws=M('cash_draws');
            $rs=$cash_draws->where(array('cashId'=>$tx_orderid))->setField('cashRemarks',$arr['err_code'] . $arr['err_code_des']);
            return 0;
            exit();
        }
    }

    public function createNoncestr($length = 32){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }


    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    protected function MakeSign($arr)
    {
        //签名步骤一：按字典序排序参数
        ksort($arr);
        $string = $this->ToUrlParams($arr);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . C('SIGNKEY');
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        //将XML转为array
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 格式化参数格式化成url参数
     */
    protected function ToUrlParams($arr)
    {
        $buff = "";
        foreach ($arr as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

};
?>