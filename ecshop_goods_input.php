<?php
require_once 'config.php';	
include_once 'cache/goods_cache.php';
$num_iid_list = array(); 
for($i = ($page_no-1)*20;$i < (($page_no-1)*20+20);$i++){
	$num_iid_list[] = $goods_list[$i];	
}  
$num_iid = implode(",", $num_iid_list);
echo $num_iid.'<br>';
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
$items = $result['items']['item'];
$msg = $result['msg'];
if(empty($msg)){
	include_once "lib/ez_sql/shared/ez_sql_core.php";
	include_once "lib/ez_sql/mysql/ez_sql_mysql.php";
	$db = new ezSQL_mysql($db_user,$db_password,$db_name,$db_host);
	$db->query("set names utf8");
	foreach($items as $val){
		//$val['title'] = iconv('UTF-8','GBK',$val['title']);
		//$val['desc'] = iconv('UTF-8','GBK',$val['desc']);
		echo iconv('UTF-8','GBK',$val['title']).'<br>';
		$db->query("INSERT INTO ecs_goods (goods_id,cat_id,goods_sn,goods_name,goods_name_style,goods_number,market_price,shop_price,warn_number,goods_desc,goods_thumb,goods_img,original_img,is_real,is_on_sale,is_alone_sale,add_time,sort_order,last_update,goods_type,give_integral,rank_integral) VALUES (null,29,'".$val['num_iid']."','".stripslashes($val['title'])."','+','".$val['num']."','".$val['price']."','".$val['price']."',1,'".stripslashes($val['desc'])."','".$val['pic_url']."_100x100.jpg','".$val['pic_url']."','".$val['pic_url']."',1,1,1,'".time()."',100,'".time()."',10,'-1','-1')");
		$goods_id = mysql_insert_id();
		if($goods_id){
			$db->query("INSERT INTO ecs_goods_taobao (goods_id,num_id,cid,etc) VALUES ('".$goods_id."','".$val['num_iid']."','".$val['cid']."','".stripslashes(serialize($val))."');");
		}
	}
	
	if(count($goods_list) > $page_no*20){
		echo '<script>document.location.href = "ecshop_goods_input.php?page='.($page_no+1).'";</script>';
	}else{
		echo '入加完毕';	
	}
}else{
	echo '<br>'.$msg;

}
?>