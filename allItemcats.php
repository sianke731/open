<?php
	require_once 'config.php';	   
/* 获取后台供卖家发布商品的标准商品类目 Start*/

	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

	    	'method' => 'taobao.itemcats.get',  //API名称
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */				 

		/* API应用级输入参数 Start*/

	    	'fields' => 'cid,parent_cid,name,is_parent,status,sort_order,last_modified',  //返回字段
   	    'parent_cid' => '0',  //父商品类目id，此DEMO为获取所有类目
				
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

	//返回结果
	$ItemCat = $result['item_cats']['item_cat'];
	
/* 获取后台供卖家发布商品的标准商品类目 End*/	
?>

	<select name="cid" style="width:200px; ">
	<option value="0" selected="selected">所有分类</option>

	<?
		foreach ($ItemCat as $ItemCats => $val) { 
	?>

	<option value="<?php echo $val['cid'];?>"><?php echo $val['name'];?></option>>
    <? }?>
	</select> 
 