<?php
namespace Api\Model;
/**
 * Class PayModel
 * @package Api\Model
 * 支付方法
 */
class PayModel extends BaseModel{

    /**
     * 微信小程序支付
     */
    public function WxMiniAPP($notifyUrl,$body,$orderId,$money,$openId){
        //调起统一支付api
        /**
         * 相关参数（公共参数无需修改，业务参数视项目修改）
         * 首先设置config的参数，
         * 如有退款，下载证书，并在function的curl_post_ssl设置正确证书路径（绝对路径）
         */
        //公共参数
        $api_data['appid']=C('WX_APPID'); //appid config中配置
        $api_data['mch_id']=C('WX_MCH_ID'); //mch_id config中配置
        $api_data['nonce_str']=$this->createNoncestr(32); //创建32位随机码，用于验签
        $api_data['spbill_create_ip']=get_client_ip(); //获取真实ip
        $api_data['trade_type']='JSAPI'; //下单类型，小程序为jsapi
        $api_data['notify_url']= $notifyUrl;//异步通知回调地址
        //业务参数
        $api_data['body']=$body; // 给用户的下单提示
        $api_data['out_trade_no']=$orderId;//订单号，唯一
        $api_data['total_fee']=$money*100;//订单金额，微信单位为分
        $api_data['openid']=$openId; //用户openid

        #一次签名
        $api_data['sign'] = $this->MakeSign($api_data);//签名
        $xml = $this->ToXml($api_data);
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
        //相关参数结束
        $tmpInfo=$this->request_post($url,$xml);
        $arr = $this->FromXml($tmpInfo);
        if($arr['return_code']=="SUCCESS"){
            #二次签名
            $resign_data=$this->reSign($arr['prepay_id']);
            $info['paySign']=$resign_data['paySign'];
            $info['timeStamp']=$resign_data['timeStamp'];
            $info['nonceStr']=$resign_data['nonceStr'];
            $info['package']=$resign_data['package'];
            $info['signType']="MD5";
            //发给前端小程序
            echo json_encode($info,JSON_UNESCAPED_UNICODE);
            //支付结束
        }else{
            echo $arr['err_code'].$arr['err_code_des'];
            exit();
        }
    }



///////////////////////////////////以下为支付基础方法//////////////////////////////////////////////////////////////

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    protected function MakeSign($arr){
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
     *    作用：产生随机字符串，不长于32位
     */
    public function createNoncestr($length = 32){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     *    作用：产生随机字符串，不长于32位
     */
    public function randomkeys($length)
    {
        $pattern = '1234567890123456789012345678905678901234';
        $key = null;
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 30)};    //生成php随机数
        }
        return $key;
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
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ToXml($arr){
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
     * 模拟get进行url请求
     * @param string $url
     * @param array $post_data
     */
    function request_get($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    /**
     * 模拟post进行url请求
     * @param string $url
     * @param array $post_data
     */
    function request_post($url = '', $post_data = array()) {
        if (empty($url) || empty($post_data)) {
            return false;
        }

        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        $data = curl_exec($ch);//运行curl
        curl_close($ch);

        return $data;
    }

    /**
     * @param $url
     * @param $vars
     * @param int $second
     * @param array $aHeader
     * @return bool|mixed
     * 带证书的post访问
     */
    function curl_post_ssl($url, $vars, $second=30,$aHeader=array()){
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

        //以下两种方式需选择一种

        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,'E:/wxxcx/xixiapai/Apps/Api/Common/apiclient_cert.pem');
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,'E:/wxxcx/xixiapai/Apps/Api/Common/apiclient_key.pem');

        //第二种方式，两个文件合成一个.pem文件
        //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }
}