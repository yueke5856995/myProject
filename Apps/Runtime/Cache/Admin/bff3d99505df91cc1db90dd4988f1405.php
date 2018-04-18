<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['shopTitle']['fieldValue']); ?>后台管理中心</title>
      <link href="/pjhl/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="/pjhl/Apps/Admin/View/css/AdminLTE.css" rel="stylesheet" type="text/css" />
      <!--[if lt IE 9]>
      <script src="/pjhl/Public/js/html5shiv.min.js"></script>
      <script src="/pjhl/Public/js/respond.min.js"></script>
      <![endif]-->
       <script src="/pjhl/Public/js/jquery.min.js"></script>
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
   <script src="/pjhl/Public/js/think.js"></script>
   <script>
       function editName(obj){
           Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
           $.post("<?php echo U('Admin/Orders/editName');?>",{id:$(obj).attr('dataId'),adminRemarks:obj.value},function(data,textStatus){
               var json = WST.toJson(data);
               if(json.status=='1'){
                   Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
                   }});
               }else{
                   Plugins.closeWindow();
                   Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
               }
           });
       }

       function batchPrint(status){
           var ids = WST.getChks('.chk_'+status);
           ids = ids.join(',');
           if(ids==''){
               WST.msg('请选择要打印的订单 !', {icon: 5});
               return;
           }
           console.log(Think);
           location.href = Think.U('Admin/Orders/printOrders',{"orderIds":ids});
       }

       function batchChangeStatus(v)
       {
           var ids = [];
           $('.chk_1').each(function(){
               if($(this).prop('checked'))ids.push($(this).val());
           })
           if(ids==''){
               WST.msg('请选择要操作的订单 !', {icon: 5});
               return;
           }
           Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
           $.post("<?php echo U('Admin/orders/changeOrdersStatus');?>",{id:ids.join(','),status:v},function(data,textStatus){
               var json = WST.toJson(data);
               if(json.status=='1'){
                   Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
                       location.reload();
                   }});
               }else{
                   Plugins.closeWindow();
                   Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});

               }
           });
       }

       function batchReturnFactory(v)
       {
           var ids = [];
           $('.chk_1').each(function(){
               if($(this).prop('checked'))ids.push($(this).val());
           })
           if(ids==''){
               WST.msg('请选择要操作的订单 !', {icon: 5});
               return;
           }
           Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
           $.post("<?php echo U('Admin/orders/returnFactory');?>",{id:ids.join(','),status:v},function(data,textStatus){
               var json = WST.toJson(data);
               if(json.status=='1'){
                   Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
                       location.reload();
                   }});
               }else{
                   Plugins.closeWindow();
                   Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});

               }
           });
       }

       function handle(url){
           Plugins.Modal({url:url,title:'发送快递',width:600});
       }
   </script>
   <body class='wst-page'>
      <form method="post" action='<?php echo U("Admin/Orders/index");?>'>
       <div class='wst-tbar'>
       店铺：<input type='text' name='shopName' id='shopName' value='<?php echo ($shopName); ?>'/>
       订单：<input type='text' name='orderNo' id='orderNo' value='<?php echo ($orderNo); ?>'/>
       订单状态：<select name='orderStatus' id='orderStatus'>
             <option value='-9999'>请选择</option>
             <option value='0'>下单成功</option>
             <option value='1'>上门取件</option>
             <option value='2'>维护中</option>
             <option value='3'>正在送件</option>
             <option value='4'>订单完成</option>
          </select>
       是否支付：<select name='isPay' id='isPay'>
           <option value=''>请选择</option>
           <option value='1'>已支付</option>
           <option value=''>未支付</option>
       </select>
       <button type="submit" class="btn btn-primary glyphicon glyphicon-search">查询</button> 
       </div>
       </form>
       <div class="wst-body">

           <div class='wst-tbar'>
               <?php if(in_array('splb_04',$WST_STAFF['grant']) and $goodsStatus == 0){ ?>
               <?php switch($orderStatus): case "0": ?><button type="button" class="btn btn-primary glyphicon"
                               onclick='javascript:batchChangeStatus(1)'>批量取件</button><?php break;?>
                   <?php case "1": ?><button type="button" class="btn btn-primary glyphicon"
                               onclick='javascript:batchChangeStatus(2)'>开始维护</button><?php break;?>
                   <?php case "2": ?><button type="button" class="btn btn-primary glyphicon" onclick='javascript:batchReturnFactory(1)'>批量返厂</button>
                       <button type="button" class="btn btn-primary glyphicon"
                               onclick='javascript:batchChangeStatus(3)'>批量送件</button><?php break;?>
                   <?php case "3": ?><button type="button" class="btn btn-primary glyphicon"
                               onclick='javascript:batchChangeStatus(4)'>批量完成</button><?php break; endswitch;?>
               <?php } ?>
               <button type="button" class="btn btn-primary glyphicon" onclick='javascript:batchPrint(1)'>批量打印</button>

           </div>
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
                 <th>
                 <input type='checkbox' onclick='WST.checkChks(this,".chk_1")'/>
                 </th>
                 <th>
                     序号
                 </th>
                 <th>
                     订单编号
                 </th>
                 <th>
                     客户名称
                 </th>
                 <th>
                     客户手机
                 </th>
                 <th>
                     客户地址
                 </th>
                 <th>
                     金额
                 </th>
                 <th>
                    是否支付
                 </th>
                 <th>
                     支付类型
                 </th>
                 <th>
                     下单时间
                 </th>
                 <th>
                     订单状态
                 </th>
                 <th>
                     返厂维护
                 </th>
                 <th>
                     备注
                 </th>
                 <th>
                     负责店铺
                 </th>
                 <th>
                     操作
                 </th>
             </tr>
           </thead>
           <tbody>
           <?php if(empty($Page['root'])): ?><tr>
                   <td colspan='15' align='center'>暂无数据</td>
               </tr><?php endif; ?>
           <?php if(is_array($Page['root'])): $key = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><tr>
               <td>
                   <input type='checkbox' class='chk_1' value='<?php echo ($vo["orderId"]); ?>'/>
               </td>
                 <td>
                     <?php echo ($key); ?>
                 </td>
                 <td>
                     <?php echo ($vo['orderNo']); ?>
                 </td>
                 <td><a href="<?php echo U('Admin/WxUsers/toEdit',array('id'=>$vo['userId']));?>"><?php echo ($vo['userName']); ?></a></td>
                 <td><?php echo ($vo['userPhone']); ?></td>
                 <td><?php echo ($vo['userAddress']); ?></td>
                 <td>
                     ￥<?php echo ($vo['totalMoney']); ?>
                 </td>
               <?php switch($vo['isPay']): case "0": ?><td width='120' style="background-color: red">
                       未支付
                       </td><?php break;?>

                   <?php case "1": ?><td width='120'>
                             已支付
                         </td><?php break; endswitch;?>

             <td width='120'>
                 <?php switch($vo['payType']): case "0": ?>积分支付<?php break;?>
                     <?php case "1": ?>现价支付<?php break; endswitch;?>
             </td>
               <td width='150'><?php echo ($vo['createTime']); ?></td>
               <td width='120'>
               <?php if($vo["orderStatus"] == 0): ?>下单成功
               <?php elseif($vo["orderStatus"] == 1): ?>上门取件
               <?php elseif($vo["orderStatus"] == 2): ?>护理中
			   <?php elseif($vo["orderStatus"] == 3): ?>护理完成
			   <?php elseif($vo["orderStatus"] == 4): ?>订单完成
               <?php else: ?>
                   买家已删除<?php endif; ?>
               </td>
               <td width='120'>
                   <?php if($vo["isFactory"] == 0): ?>无需返厂
                       <?php elseif($vo["isFactory"] == 1): ?>发往工厂
                       <?php elseif($vo["isFactory"] == 2): ?>接收维护
                       <?php elseif($vo["isFactory"] == 3): ?>发回商铺
                       <?php elseif($vo["isFactory"] == 4): ?>接受完毕<?php endif; ?>
               </td>
                 <td><input type='text' value='<?php echo ($vo['adminRemarks']); ?>' onchange='javascript:editName(this)' dataId="<?php echo ($vo["orderId"]); ?>" class='form-control'/></td>
                 <td><a href="<?php echo U('shops/toEdit',array('id'=>$vo['shopId']));?>"><?php echo ($vo["shopName"]); ?></td>
               <td width='160'>
               <a class="btn btn-primary glyphicon" href="<?php echo U('Admin/Orders/toView',array('id'=>$vo['orderId']));?>">查看</a>&nbsp;
               <a class="btn btn-success glyphicon" href="<?php echo U('Admin/orders/printOrders',array('orderIds'=>$vo['orderId']));?>">打印</a>&nbsp;
               <a class="btn btn-success glyphicon" onclick="handle('<?php echo U('Admin/orders/tohandle',array('orderId'=>$vo['orderId']));?>')">信息录入</a>&nbsp;
               </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='15' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
       </div>
   </body>

   <script src="/pjhl/Public/js/jquery.min.js"></script>
   <script src="/pjhl/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
   <script src="/pjhl/Public/js/common.js"></script>
   <script src="/pjhl/Public/plugins/plugins/plugins.js"></script>
   <script src="/pjhl/Public/plugins/layer/layer.min.js"></script>

</html>