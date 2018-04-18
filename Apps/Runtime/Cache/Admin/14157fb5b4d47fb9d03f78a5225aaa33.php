<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo ($CONF['shopTitle']['fieldValue']); ?>后台管理中心</title>
    <link href="/pjhl/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/pjhl/Apps/Admin/View/css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="/pjhl/Public/plugins/webuploader/style.css"/>
    <link rel="stylesheet" type="text/css" href="/pjhl/Public/plugins/webuploader/webuploader.css"/>
    <style>
        .filelist li .setdef {
            background: none repeat scroll 0 0 #F48C3A;
            bottom: 0;
            line-height: 18px;
            height: 18px;
            position: absolute;
            right: 0;
            padding: 0 5px;
            z-index: 9999;
            color: #FFFFFF;
        }
        .filelist li .setdel:hover{
            color: #E4F14A;
        }
        .filelist li .setdel {
            cursor:pointer;
            background: none repeat scroll 0 0 #3b72a5;
            top: 200;
            color: #FFFFFF;
            height: 18px;
            line-height: 18px;
            padding: 0 5px;
            position: absolute;
            right: 0px;
            z-index: 9999;
        }

        .filelist li img {
            cursor: pointer;
        }


        #uploader .filelist li{
            display: inline;
            float: left;
            font-size: 12px;
            height: auto;
            margin: 0 8px 8px 0;
            overflow: hidden;
            position: relative;
            text-align: center;
            width: 152px;
        }
    </style>

    <!--[if lt IE 9]>
    <script src="/pjhl/Public/js/html5shiv.min.js"></script>
    <script src="/pjhl/Public/js/respond.min.js"></script>
    <![endif]-->
    <script src="/pjhl/Public/js/jquery.min.js"></script>
    <script src="/pjhl/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/pjhl/Public/plugins/plugins/plugins.js"></script>
    <script src="/pjhl/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
</head>
<script type="text/javascript">
    var WST = ThinkPHP = window.Think = {
        "ROOT"   : "/pjhl",
        "APP"    : "/pjhl/index.php",
        "PUBLIC" : "/pjhl/Public",
        "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>",
        "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
        "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
        "DOMAIN" : "<?php echo WSTDomain();?>",
        "CITY_ID" : "<?php echo ($currArea['areaId']); ?>",
        "CITY_NAME" : "<?php echo ($currArea['areaName']); ?>",
        "DEFAULT_IMG": "<?php echo WSTDomain();?>/<?php echo ($CONF['goodsImg']); ?>",
        "MALL_NAME" : "<?php echo ($CONF['mallName']); ?>",
        "SMS_VERFY"  : "<?php echo ($CONF['smsVerfy']); ?>",
        "PHONE_VERFY"  : "<?php echo ($CONF['phoneVerfy']); ?>",
        "IS_LOGIN" :"<?php echo ($WST_IS_LOGIN); ?>",
        "WST_STYLE" :"<?php echo ($WST_STYLE); ?>"
    }
</script>
<script src="/wstmall_v1.9.5_170829/Public/js/think.js"></script>
<script>
    var ablumInit = false;
    $(function () {
      uploadAblumInit();
        var uploading = null;
        uploadFile({
            server:Think.U('Admin/shops/uploadPic'),pick:'#goodImgPicker',
            formData: {dir:'orders'},
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,png',
                mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'
            },
            callback:function(f){
                layer.close(uploading);
                var json = WST.toJson(f);
                console.log(json)
                $('#goodsImgPreview').attr('src',WST.DOMAIN+"/"+json.file.savepath+json.file.savethumbname);
                $('#goodsImg').val(json.file.savepath+json.file.savename);
                $('#goodsThums').val(json.file.savepath+json.file.savethumbname);
                $('#goodsImgPreview').show();
            },
            progress:function(rate){
                uploading = WST.msg('正在上传图片，请稍后...');
            }
        });
        $('input[name="isDistribut"]').click(function(){
            if($(this).val()==1){
                $(".commission_tr").show();
            }else{
                $(".commission_tr").hide();
            }
        });
    });

    function imglimouseover(obj){
        if(!$(obj).find('.file-panel').html()){
            $(obj).find('.setdel').addClass('trconb');
            $(obj).find('.setdel').css({"display":""});
        }
    }

    function imglimouseout(obj){

        $(obj).find('.setdel').removeClass('trconb');
        $(obj).find('.setdel').css({"display":"none"});
    }

    function imglidel(obj,id){
        if (confirm('是否删除图片?')) {
            $.ajax({
                //请求方式
                type:'POST',
                //发送请求的地址
                url:"<?php echo U('Admin/orders/delImg');?>",
                //服务器返回的数据类型
                dataType:'json',
                //发送到服务器的数据，对象必须为key/value的格式，jquery会自动转换为字符串格式
                data:{id:id},
                success:function(data){
                    if(data!=false){
                        WST.msg( '删除成功', {icon: 1});
                        $(obj).parent().remove();
                    }

                },
                error:function(jqXHR){
                    //请求失败函数内容
                }
            });
            return;
        }
    }

    function imgmouseover(obj){
        $(obj).find('.wst-gallery-goods-del').show();
    }
    function imgmouseout(obj){
        $(obj).find('.wst-gallery-goods-del').hide();
    }
    function delImg(obj){
        $(obj).parent().remove();
    }
</script>
<body class="wst-page">
<form name="myform" method="post" id="myform">
    <input type='hidden' id='id' value='<?php echo ($object["orderId"]); ?>'/>
    <table class="table table-hover table-striped table-bordered wst-form">
        <tr>
            <td>
                <span style='font-weight:bold;'>订单号：<?php echo ($object['orderNo']); ?></span>
                <span style='margin-left:100px;'>
              状态： <?php if($vo["orderStatus"] == 0): ?>下单成功
               <?php elseif($vo["orderStatus"] == 1): ?>上门取件
               <?php elseif($vo["orderStatus"] == 2): ?>护理中
			   <?php elseif($vo["orderStatus"] == 3): ?>护理完成
			   <?php elseif($vo["orderStatus"] == 4): ?>订单完成
               <?php else: ?>
                   买家已删除<?php endif; ?>
             </span></td>
        </tr>
        <tr>
            <td style='font-weight:bold;'>订单日志</td>
        </tr>
        <tr>
            <td>
                <table width='700'>
                    <tr>
                        <td width='220'>操作时间</td>
                        <td width='350'>操作信息</td>
                        <td width='230'>操作人</td>
                    </tr>
                    <?php if(is_array($object['log'])): $i = 0; $__LIST__ = $object['log'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($log['logTime']); ?></td>
                            <td><?php echo ($log['logContent']); ?></td>
                            <td><?php echo ($log['loginName']); ?>
                                <?php if(!empty($log['shopName'])): ?>(<?php echo ($log['shopName']); ?>)<?php endif; ?>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
            </td>
        </tr>
        <tr>
            <td style='font-weight:bold;'>订单信息</td>
        </tr>
        <tr>
            <td>
                <table width='700'>
                    <tr>
                        <td style='text-align:right'>支付方式：</td>
                        <td>
                            <?php if($object["payType"] == 1): ?>现金付款
                                <?php else: ?>
                                积分付款<?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align:right'>配送方式：</td>
                        <td>
                            <?php if($object["deliverType"] == 1): ?>快递送件
                                <?php else: ?>
                                预约取送<?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align:right'>用户留言：</td>
                        <td><?php echo ($object['orderRemarks']); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style='font-weight:bold;'>取件信息</td>
        </tr>
        <tr>
            <td>
                <table width='700'>
                    <tr>
                        <td width='120' style='text-align:right'>姓名：</td>
                        <td><?php echo ($object['userName']); ?></td>
                    </tr>
                    <tr>
                        <td style='text-align:right'>地址：</td>
                        <td><?php echo ($object['userAddress']); ?></td>
                    </tr>
                    <tr>
                        <td style='text-align:right'>联系方式：</td>
                        <td>
                            <?php echo ($object['userPhone']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align:right'>取件时间：</td>
                        <td>
                            <?php echo ($object['receiveTime']); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style='font-weight:bold;'>照片</td>
        </tr>
        <tr style="min-height: 150px">
            <td>
                <div id='galleryImgs' class='wst-gallery-imgs'>
                        <div id="tt"></div>
                        <?php if(count($object['gallery']) == 0): ?><div id="wrapper">
                                <div id="container">
                                    <!--头部，相册选择和格式选择-->
                                    <div style="border-bottom:1px solid #dadada;padding:10px; ">
                                        图片大小:800 x 800 (px)(格式为 gif, jpg, jpeg, png)
                                    </div>
                                    <div id="uploader">
                                        <div class="queueList">
                                            <div id="dndArea" class="placeholder">
                                                <div id="filePicker"></div>
                                            </div>
                                            <ul class="filelist"></ul>
                                        </div>
                                        <div class="statusBar" style="display:none">
                                            <div class="progress">
                                                <span class="text">0%</span>
                                                <span class="percentage"></span>
                                            </div>
                                            <div class="info"></div>
                                            <div class="btns">
                                                <div id="filePicker2" class="webuploader-containe webuploader-container"></div><div class="uploadBtn state-finish">开始上传</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div id="wrapper">
                                <div id="container">
                                    <div id="uploader">
                                        <div class="queueList">
                                            <div id="dndArea" class="placeholder element-invisible">
                                                <div id="filePicker" class="webuploader-container"></div>
                                            </div>
                                            <ul class="filelist">
                                                <?php if(is_array($object['gallery'])): $i = 0; $__LIST__ = $object['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="border: 1px solid rgb(59, 114, 165)" order="100" onmouseover="imglimouseover(this)" onmouseout="imglimouseout(this)">
                                                        <input type="hidden" class="gallery-img" iv="<?php echo ($vo["goodsThumbs"]); ?>" v="<?php echo ($vo["goodsImg"]); ?>" />
                                                        <img width="152" height="152" class='lazyImg' src="/pjhl/<?php echo ($vo["goodsThumbs"]); ?>"><span class="setdef" style="display:none">默认</span><span class="setdel" onclick="imglidel(this,<?php echo ($vo["id"]); ?>)" style="display:none">删除</span>
                                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </ul>
                                        </div>
                                        <div class="statusBar" style="">
                                            <div class="progress">
                                                <span class="text"></span>
                                                <span class="percentage"></span>
                                            </div>
                                            <div class="info"></div>
                                            <div class="btns">
                                                <div id="filePicker2" class="webuploader-containe webuploader-container"></div>
                                                <div class="uploadBtn state-finish">开始上传</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><?php endif; ?>
                    </div>
            </td>

        </tr>
        <tr>
            <td style='text-align:right;padding-right:10px;'>总金额：￥<?php echo ($object['totalMoney']); ?><br/>
                <span>订单金额：</span><span>￥<?php echo ($object['totalMoney']); ?></span><br/>
                <span>快递金额：</span><span>￥<?php echo ($object['deliverMoney']); ?></span><br/>
                <span style='font-weight:bold;font-size:20px'>实付金额：</span><span
                        style='font-weight:bold;font-size:20px;color:red;'>￥<?php echo ($object['realTotalMoney']); ?></span></td>
        </tr>
        <tr>
            <td colspan='2' align='center'>
                <button type="button" class="btn btn-primary" onclick='javascript:location.href="<?php echo ($referer); ?>"'>返&nbsp;回
                </button>
            </td>
        </tr>
    </table>
</form>
</body>
<script src="/wstmall_v1.9.5_170829/Public/js/common.js"></script>
<script src="/wstmall_v1.9.5_170829/Apps/Home/View/default/js/shopcom.js"></script>
<script src="/wstmall_v1.9.5_170829/Apps/Home/View/default/js/head.js"></script>
<script src="/wstmall_v1.9.5_170829/Apps/Home/View/default/js/common.js"></script>
<script src="/wstmall_v1.9.5_170829/Public/plugins/layer/layer.min.js"></script>
<script src="/wstmall_v1.9.5_170829/Apps/Home/View/default/js/audio.js"></script>
<script type="text/javascript" src="/wstmall_v1.9.5_170829/Public/plugins/webuploader/webuploader.js"></script>
<script type="text/javascript" src="/pjhl/Apps/Admin/View/js/orderbatchupload.js"></script>
</html>