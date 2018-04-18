<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['mallTitle']); ?>后台管理中心</title>
      <link href="/pjhl/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="/pjhl/Apps/Admin/View/css/AdminLTE.css">
      <!--[if lt IE 9]>
      <script src="/pjhl/Public/js/html5shiv.min.js"></script>
      <script src="/pjhl/Public/js/respond.min.js"></script>
      <![endif]-->
      <script src="/pjhl/Public/js/jquery.min.js"></script>
      <script src="/pjhl/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
      <script src="/pjhl/Public/js/common.js"></script>
      <script>
      function enterLicense(){
    	  location.href="<?php echo U('Admin/Index/enterLicense');?>";
      }
      $(function () {
	      WST.getWSTMAllVersion("<?php echo U('Admin/Index/getWSTMallVersion');?>");
      });
      </script>
   </head>
   <body>
      <div class='panel wst-panel-full'>
         <div class="col-xs-12 wstmall-login-tips">
             <p style='font-size:16px;'>您好，<?php echo session('WST_STAFF.staffName');?>，欢迎使用红狐企业网管理后台。 您上次登录的时间是 <?php echo session('WST_STAFF.lastTime');?> ，IP 是 <?php echo session('WST_STAFF.lastIP');?></p>
         </div>
         <div class='row' style='padding-left:10px;margin-right:10px;'>
	         <div class="col-md-9">
	           <div class="box-header">
	             <h4 class="text-blue">本日动态</h4>
	             <table class="table table-hover table-striped table-bordered wst-form">
					 <tr>
						 <td width="20%" align='right'>本日洗衣下单量及金额：</td>
						 <td width="30%"><?php echo ($todayInfo["ordersNew"]); ?>/<?php echo ($todayInfo["sumOrdersNew"]); ?></td>
						 <td width="20%" align='right'>本日购衣下单量及金额：</td>
						 <td width="30%"><?php echo ($todayInfo["clothesOrdersNew"]); ?>/<?php echo ($todayInfo["sumClothesOrders"]); ?></td>
					 </tr>
	                <tr>
	                   <td width="20%" align='right'>本日新增会员数：</td>
	                   <td width="30%"><?php echo ($todayInfo["newVipUser"]); ?></td>
	                   <td width="20%" align='right'>本日升级会员金额：</td>
	                   <td width="30%"><?php echo ($todayInfo["sumVipUser"]); ?></td>
	                </tr>
					 <tr>
						 <td width="20%" align='right'>本日新增用户数：</td>
						 <td width="30%"><?php echo ($todayInfo["newUser"]); ?></td>
						 <td width="20%" align='right'>本日总收入：</td>
						 <td width="30%"><?php echo ($todayInfo["totalMoney"]); ?></td>
					 </tr>
	             </table>
	           </div>
	           <div class="box-header">
	             <h4 class="text-blue">本月动态</h4>
	             <table class="table table-hover table-striped table-bordered wst-form">
	                <tr>
	                   <td width="20%" align='right'>本月洗衣下单量及金额：</td>
	                   <td width="30%"><?php echo ($monthInfo["ordersNew"]); ?>/<?php echo ($monthInfo["sumOrdersNew"]); ?></td>
	                   <td width="20%" align='right'>本月购衣下单量及金额：</td>
	                   <td width="30%"><?php echo ($monthInfo["clothesOrdersNew"]); ?>/<?php echo ($monthInfo["sumClothesOrders"]); ?></td>
	                </tr>
					 <tr>
						 <td width="20%" align='right'>本月新增会员数：</td>
						 <td width="30%"><?php echo ($monthInfo["newVipUser"]); ?></td>
						 <td width="20%" align='right'>本月升级会员金额：</td>
						 <td width="30%"><?php echo ($monthInfo["sumVipUser"]); ?></td>
					 </tr>
					 <tr>
						 <td width="20%" align='right'>本月新增用户数：</td>
						 <td width="30%"><?php echo ($monthInfo["newUser"]); ?></td>
						 <td width="20%" align='right'>本月总收入：</td>
						 <td width="30%"><?php echo ($monthInfo["totalMoney"]); ?></td>
					 </tr>
	             </table>
	           </div>
				 <div class="box-header">
					 <h4 class="text-blue">本年动态</h4>
					 <table class="table table-hover table-striped table-bordered wst-form">
						 <tr>
							 <td width="20%" align='right'>本年洗衣下单量及金额：</td>
							 <td width="30%"><?php echo ($yearInfo["ordersNew"]); ?>/<?php echo ($yearInfo["sumOrdersNew"]); ?></td>
							 <td width="20%" align='right'>本年购衣下单量及金额：</td>
							 <td width="30%"><?php echo ($yearInfo["clothesOrdersNew"]); ?>/<?php echo ($yearInfo["sumClothesOrders"]); ?></td>
						 </tr>
						 <tr>
							 <td width="20%" align='right'>本年新增会员数：</td>
							 <td width="30%"><?php echo ($yearInfo["newVipUser"]); ?></td>
							 <td width="20%" align='right'>本年升级会员金额：</td>
							 <td width="30%"><?php echo ($yearInfo["sumVipUser"]); ?></td>
						 </tr>
						 <tr>
							 <td width="20%" align='right'>本年新增用户数：</td>
							 <td width="30%"><?php echo ($yearInfo["newUser"]); ?></td>
							 <td width="20%" align='right'>本年总收入：</td>
							 <td width="30%"><?php echo ($yearInfo["totalMoney"]); ?></td>
						 </tr>
					 </table>
				 </div>
				 <div class="box-header">
					 <h4 class="text-blue">动态信息</h4>
					 <table class="table table-hover table-striped table-bordered wst-form">
						 <tr>
							 <td width="20%" align='right'>用户反馈</td>
							 <td width="30%"><?php echo ($sumInfo["newNews"]); ?></td>
							 <td width="20%" align='right'>总vip会员数/总会员数：</td>
							 <td width="30%"><?php echo ($sumInfo["userVipSum"]); ?>/<?php echo ($sumInfo["userSum"]); ?></td>
						 </tr>
						 <tr>
							 <td width="20%" align='right'>新增洗衣商品数：</td>
							 <td width="30%"><?php echo ($sumInfo["goodsNew"]); ?></td>
							 <td width="20%" align='right'>总洗衣商品数：</td>
							 <td width="30%"><?php echo ($sumInfo["goodsSum"]); ?></td>
						 </tr>
						 <tr>
							 <td width="20%" align='right'>新增出售商品数：</td>
							 <td width="30%"><?php echo ($sumInfo["clothesGoodsNew"]); ?></td>
							 <td width="20%" align='right'>总出售商品数：</td>
							 <td width="30%"><?php echo ($sumInfo["clothesGoodsSum"]); ?></td>
						 </tr>
					 </table>
				 </div>
	           <div class="box-header">
	             <h4 class="text-blue">系统信息</h4>
	             <table class="table table-hover table-striped table-bordered wst-form">
                    <tr>
                    	<td width="20%" align="right">操作系统：</td>
                    	<td width="30%">Windows 2008</td>
                    	<td width="20%" align="right">系统开发：</td>
                    	<td width="30%">Thinkphp3.2.2</td>
                    </tr>
                    <tr>
                    	<td align="right">Web服务器：</td>
                    	<td>IIS</td>
                    	<td align="right">技术支持：</td>
                    	<td><a href="http://www.honghukeji.cn/" target="_blank">红狐企业网</a>
                    	</td>
                    </tr>
                    <tr>
                    	<td align="right">程序语言：</td>
                    	<td>PHP</td>
                    	<td align="right">联系电话：</td>
                    	<td>0371-66110309</td>
                    </tr>
                    <tr>
                    	<td align="right">数据库：</td>
                    	<td>MySQL</td>
                    	<td align="right">联系QQ：</td>
                    	<td>123434446</td>
                    </tr>
	             </table>
	           </div>
	        </div>
	        <div class="col-md-3">

	        </div>
	      </div>  
      </div>
   </body>
</html>