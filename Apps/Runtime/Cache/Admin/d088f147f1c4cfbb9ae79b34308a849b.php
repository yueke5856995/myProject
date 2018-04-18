<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
	<head>
  		<meta charset="utf-8">
      	<meta http-equiv="X-UA-Compatible" content="IE=edge">
      	<link rel="shortcut icon" href="favicon.ico"/>
      	<meta http-equiv="cache-control" content="no-cache">
      	<title>订单信息 - <?php echo ($CONF['mallTitle']); ?></title>
      	<meta name="keywords" content="<?php echo ($CONF['mallKeywords']); ?>,订单信息" />
      	<meta name="description" content="<?php echo ($CONF['mallDesc']); ?>,订单信息" />
      	<link rel="stylesheet" href="/pjhl/Public/home/css/common.css" />
     	<link rel="stylesheet" href="/pjhl/Public/home/css/checkorderinfo.css" />
     	<link rel="stylesheet" href="/pjhl/Public/home/css/base.css" />
		<link rel="stylesheet" href="/pjhl/Public/home/css/head.css" />
   	</head>
   	<body>
	
	<div style="position: relative;width:960px;margin:0 auto">
		<button class="wst-print-btn" onclick="window.print()">打印</button>
		<?php if(is_array($orders)): $i = 0; $__LIST__ = $orders;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$orderInfo): $mod = ($i % 2 );++$i; if($key>0){ ?>
        <div class="wst-print-page"></div>
        <?php } ?>
        <div><br/>
        	<div class="wst-odetal-box">
        	<table cellspacing="0" cellpadding="0" class="wst-tab" width="960">
        		<caption class="wst-tab-cpt">订单信息</caption>
        		<tbody>
	        		<tr>
	        			<td class="wst-td-title">订单编号：</td>
	        			<td class="wst-td-content"><?php echo ($orderInfo["order"]["orderNo"]); ?></td>
	        		</tr>
	        		<tr>
	        			<td class="wst-td-title">支付方式：</td>
	        			<td class="wst-td-content"><?php echo ($orderInfo["order"]["payType"]==0?"货到付款":"在线支付"); ?></td>
	        		</tr>
	        		<tr>
	        			<td class="wst-td-title">配送方式：</td>
	        			<td class="wst-td-content">
	        			店铺配送
	        			</td>
	        		</tr>
	        		<tr>
	        		    <td class="wst-td-title">买家留言：</td>
	        		    <td class="wst-td-content"><?php echo ($orderInfo["order"]["orderRemarks"]); ?></td>
	        		</tr>
	        		<tr>
	        			<td class="wst-td-title">下单时间：</td>
	        			<td class="wst-td-content"><?php echo ($orderInfo["order"]["createTime"]); ?></td>
	        		</tr>
					<tr>
						<td class="wst-td-title">出货时间：</td>
						<td class="wst-td-content">
						<select name="" id="">
							<option value="">一天</option>
							<option value="">两天</option>
							<option value="">三天</option>
							<option value="">四天</option>
							<option value="">五天</option>
							<option value="">六天</option>
							<option value="">七天</option>
						</select>
						</td>
					</tr>
        		</tbody>
        	</table>
        	</div>
        	<br/><br/>
        	<div class="wst-odetal-box">
        	<table cellspacing="0" cellpadding="0" class="wst-tab" width="960">
        		<caption class="wst-tab-cpt">收货人信息</caption>
        		<tbody>
	        		<tr>
	        			<td class="wst-td-title">收货人姓名：</td>
	        			<td class="wst-td-content"><?php echo ($orderInfo["order"]["userName"]); ?></td>
	        		</tr>
	        		<tr>
	        			<td class="wst-td-title">地址：</td>
	        			<td class="wst-td-content"><?php echo ($orderInfo["order"]["userAddress"]); ?></td>
	        		</tr>
	        		<tr>
	        			<td class="wst-td-title">固定电话：</td>
	        			<td class="wst-td-content"><?php echo ($orderInfo["order"]["userPhone"]); ?> <?php if($orderInfo["order"]["userTel"] != ""): ?>| <?php echo ($orderInfo["order"]["userTel"]); endif; ?></td>
	        		</tr>
	        		<tr>
	        			<td class="wst-td-title">预约取件时间：</td>
	        			<td class="wst-td-content"><?php echo ($orderInfo["order"]["receiveTime"]); ?></td>
	        		</tr>
        		</tbody>
        	</table>
        	</div>
        	<div class="wst-odetal-box" style='padding-bottom:5px;'>
        	<table cellspacing="0" cellpadding="0" class="wst-tab" width="960" style="margin:0 auto;">
        		   <td colspan='4' class='wst-cart-total-money'>
        		  	 商品总金额： ￥<?php echo ($orderInfo["order"]["totalMoney"]); ?><br/>
        		   	+ 运费：￥<?php echo ($orderInfo["order"]["deliverMoney"]); ?><br/>
        		   	<?php if($orderInfo["order"]["couponMoney"] > 0): ?>店铺优惠：- ￥<?php echo ($orderInfo["order"]["couponMoney"]); ?><br/><?php endif; ?>
        		  	  订单总金额：<span class='wst-cart-order-money'>￥<?php echo ($orderInfo["order"]["totalMoney"]+$orderInfo["order"]["deliverMoney"]-$orderInfo["order"]["couponMoney"]); ?></span><br/>
        		 	<?php if($orderInfo["order"]["useScore"] > 0): ?>- 使用积分：<?php echo ($orderInfo["order"]["useScore"]); ?> 点（抵￥<?php echo ($orderInfo["order"]["scoreMoney"]); ?> ）<br/><?php endif; ?>
        		   <span class='wst-cart-order-txt'>实付总金额：</span><span class='wst-cart-order-money'>￥<?php echo ($orderInfo["order"]["realTotalMoney"]); ?></span></td>
        		</tr>
        	</table>
        	</div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <div class="wst-clear"></div>
    <div style="height: 20px;"></div>
</body>

</html>