<?php
	header("Content-Type:text/html;charset=UTF-8");
	require_once 'config.php';
?>	
<html>
<head>
<title>批量获取商品信息</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src=js/colors.js></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<p>批量获取商品信息</p>

<?php
/* 橱窗推荐一个商品 Start*/

	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

	    	'method' => 'taobao.items.list.get',  //API名称
		   'session' => $sessions, //session
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */				 

		/* API应用级输入参数 Start*/
			'fields' => 'detail_url,num_iid,title,nick,type,desc,skus,props_name,created,is_lightning_consignment,is_fenxiao,auction_point,property_alias,template_id,cid,seller_cids,props,input_pids,input_str,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,item_imgs,prop_imgs,outer_id,is_virtual,is_taobao,is_ex,is_timing,videos,is_3D,one_station,second_kill,auto_fill',
		   'num_iids' => $num_iid, //商品数字ID			   

				
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
	print_r($result);
	//获取错误信息
	$msg = $result['msg'];
	

	
/* 橱窗推荐一个商品 End*/	


	if(empty($msg)){
		echo '<table><tr><th>成功橱窗推荐</th></tr>';

		echo '<tr><td>商品修改时间：'.$result['item']['modified'].'</td></tr>';
		echo '<tr><td>商品数字ＩＤ：'.$result['item']['num_iid'].'</td>';
		echo '</tr></table>';
	}else{
		echo '<br>'.$msg;

	}

?>
</body>
</html>
