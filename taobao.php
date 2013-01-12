<?php
	header("Content-Type:text/html;charset=UTF-8");
	require_once 'config.php';
?>	
<html>
<head>
<title>获取卖家最近订单</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src=js/colors.js></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>

<?php

/* 获取卖家店铺剩余橱窗数量 Start*/

	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

	    	'method' => 'taobao.trades.sold.get',  //API名称
		   'session' => $sessions, //session
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */				 

		/* API应用级输入参数 Start*/
			   'fields' => 'buyer_nick,tid,orders',
		'start_created' => date('Y-m-d H:i:s', strtotime('-10 day')),
		/* API应用级输入参数 End*/
	);

	//生成签名
	$sign = createSign($paramArr,$appSecret);
	
	//组织参数
	$strParam = createStrParam($paramArr);
	$strParam .= 'sign='.$sign;
	
	//构造Url
	$urls = $url.$strParam;
	
	//连接超时自动重试
	$cnt=0;	
	while($cnt < 3 && ($result=@vita_get_url_content($urls))===FALSE) $cnt++;
	
	//解析Xml数据
	$result = getXmlData($result);

	//获取错误信息
	$msg = $result['msg'];
	

	
/* 获取卖家店铺剩余橱窗数量 End*/	


?>
<p>info</p>
<p id="msgs">
<?php include 'header.php';?>
</p>
<form action="" method="get" name="form1" id="form1">
<table border="0" width="100%" class="table">

  <tr><td colspan="2"></td></tr>      
	  
  <tr>
    <td colspan="2">
<?php


	if(empty($msg)){
		print_r($result);
	}else{
		echo '<span><font color=red>'.$msg.'</font></span>';
	}
?>    
    </td>
  </tr> 
</table>
</form>

</body>
</html>
