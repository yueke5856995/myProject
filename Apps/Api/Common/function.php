<?php


//二维数组排序
//$array 要排序的数组
//$row  排序依据列
//$type 排序类型[asc or desc]
//return 排好序的数组
function array_sort($array,$row,$type){

    if($type == 'asc'){
    usort($array, function($a, $b) use($row,$type) {
        if ($a[$row]  == $b[$row]) return 0;
        return ($a[$row] > $b[$row]) ? 1 : -1;
    });
    }elseif($type== 'desc'){
        usort($array, function($a, $b) use($row,$type) {
            if ($a[$row]  == $b[$row]) return 0;
            return ($a[$row] > $b[$row]) ? -1 : 1;
        });
    };
    return $array;
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
 *    作用：产生随机字符串，不长于32位
 */
function randomkeys($length){
    $pattern = '1234567890123456789012345678905678901234';
    $key = null;
    for ($i = 0; $i < $length; $i++) {
        $key .= $pattern{mt_rand(0, 30)};    //生成php随机数
    }
    return $key;
}

function send_sms($mobile,$content){
    $now = gmdate("D, d M Y H:i:s") . " GMT";
    header("Date: $now");
    header("Expires: $now");
    header("Last-Modified: $now");
    header("Pragma: no-cache");
    header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
    //接口参数
    $svr_url = 'https://dx.ipyy.net/smsJson.aspx';   // 服务器接口路径
    $svr_param = array();
    $svr_param['userid'] = 'ZZ00252';
    $svr_param['account'] = 'ZZ00252';    // 账号
    $svr_param['password'] = md5('ZZ0025245');    // 密码
    $svr_param['action']='send';
    $svr_param['mobile']=$mobile;
    $svr_param['content']=$content;
    $res =request_post($svr_url,$svr_param);
    return $res;
}


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

    /** 返回数据
     * @param $status
     * @param $msg
     * @param $data
     */
    function return_json($status,$msg,$data=null){
        $return['status']=$status;
        $return['msg']=$msg;
        $return['data']=$data;
        $return=json_encode($return,JSON_UNESCAPED_UNICODE);
        echo $return;
    }

/**
 * 单个上传图片
 */
    function uploadPic(){
        $config = array(
            'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
            'exts'          =>  array('jpg','png','gif','jpeg'), //允许上传的文件后缀
            'rootPath'      =>  './Upload/', //保存根路径
            'driver'        =>  'LOCAL', // 文件上传驱动
            'subName'       =>  array('date', 'Y-m'),
            'savePath'      =>  'image'."/",
            'saveName'      => time().mt_rand().mt_rand(),
        );


        $upload = new \Think\Upload($config);
        $rs = $upload->upload($_FILES);
        $Filedata = key($_FILES);
        if(!$rs){
            $this->error($upload->getError());
        }else{
            $images = new \Think\Image();
            $images->open('./Upload/'.$rs[$Filedata]['savepath'].$rs[$Filedata]['savename']);
            $newsavename = str_replace('.','_thumb.',$rs[$Filedata]['savename']);
            $vv = $images->thumb(I('width',750), I('height',750))->save('./Upload/'.$rs[$Filedata]['savepath'].$rs[$Filedata]['savename']);
            $vv = $images->thumb(I('width',300), I('height',300))->save('./Upload/'.$rs[$Filedata]['savepath'].$newsavename);

            if(C('WST_M_IMG_SUFFIX')!=''){
                $msuffix = C('WST_M_IMG_SUFFIX');
                $mnewsavename = str_replace('.',$msuffix.'.',$rs[$Filedata]['savename']);
                $mnewsavename_thmb = str_replace('.',"_thumb".$msuffix.'.',$rs[$Filedata]['savename']);
                $images->open('./Upload/'.$rs[$Filedata]['savepath'].$rs[$Filedata]['savename']);
                $images->thumb(I('width',700), I('height',700))->save('./Upload/'.$rs[$Filedata]['savepath'].$mnewsavename);
                $images->thumb(I('width',250), I('height',250))->save('./Upload/'.$rs[$Filedata]['savepath'].$mnewsavename_thmb);
            }
            $rs[$Filedata]['savepath'] = "Upload/".$rs[$Filedata]['savepath'];
            $rs[$Filedata]['savethumbname'] = $newsavename;
            $rs['status'] = 1;
            return $rs;
        }
    }











