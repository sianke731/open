<?php
	header("Content-Type:text/html;charset=UTF-8");
	require_once 'config.php';
	/* Build By fhalipay */
/* 得到当前会话用户出售中未被橱窗推荐的商品列表 Start*/

	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

	    	'method' => 'taobao.product.get',  //API名称
		   'session' => $sessions, //session
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */				 

		/* API应用级输入参数 Start*/

			'fields' => 'product_id,outer_id,created,tsc,cid,cat_name,props,props_str,binds_str,sale_props_str,name,binds,sale_props,price,desc,pic_url,modified,product_imgs,product_prop_imgs,status',
			   'product_id' =>$_GET['product_id'],
				
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
	if (empty($result)){
		echo '平台无任何数据返回';
		exit;
	}else{
	//获取错误信息
	$msg = $result['msg'];
	
	//返回结果
	$item = $result['items']['item'];
	$total_results = $result['total_results'];
	
	if($total_results=='1'){
		$item[] = $item;	
	}
	}
/* 得到当前会话用户出售中未被橱窗推荐的商品列表 End*/	

	
?>