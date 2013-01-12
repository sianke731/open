<?php
	error_reporting(0);
	session_start();
	
	require_once 'lib/function.php';
	require_once 'page.Class.php';
	
	
	$db_host = 'localhost';
	$db_user = 'root';
	$db_password = 'root';
	$db_name = 'ecshop';
	
/*  接收参数,此部分不需修改  */
	$userNick=$_SESSION['nick'];
	$sessions=$_SESSION['topsession'];
	
	$num_iid = $_GET['num_iid'];
	$iid = $_GET['iid'];
	$num = $_POST['num'];
	$nick = $_GET['nick']?$_GET['nick']:'皮皮小站'; //卖家名 
	$q = $_GET['q']; //搜索字段。搜索商品的title。 
	$banner = $_GET['banner']; //分类字段
	$cid = $_GET['cid']; //商品类目ID。ItemCat中的cid字段。可以通过taobao.itemcats.get取到 
	$seller_cids = $_GET['seller_cids']; //卖家店铺内自定义类目ID。多个之间用“,”分隔。可以根据taobao.sellercats.list.get获得 
  	$page_no = !($_GET['page'])?'1':intval($_GET['page']); //页码
  	$page_size = !($_GET['page_size'])?'20':intval($_GET['page_size']);//每页条数。取值范围:大于零的整数; 默认值:40;最大200。 
	$has_discount = $_GET['has_discount']; //是否参与会员折扣。可选值：true，false。默认不过滤该条件
	$has_showcase = $_GET['has_showcase']; //是否橱窗推荐。可选值：true，false。默认不过滤该条件	
	$order_by = $_GET['order_by']; //排序方式。格式为column:asc/desc ，column可选值:list_time(上架时间),delist_time(下架时间),num(商品数量);默认上架时间降序(即最新上架排在前面)。如按照上架时间降序排序方式为list_time:desc 
	$is_taobao = $_GET['is_taobao']; //商品是否在淘宝显示 
	$is_ex = $_GET['is_ex']; //商品是否在外部网店显示	

	
/*  以下配置参数根据情况进行修改  */	

	//false为正式测试环境,true为测试环境
	//配置沙箱环境说明：http://open.taobao.com/bbs/read.php?tid=19022	
	$testMode = 'false';
	if ($testMode=='true'){
	   $url = 'http://gw.api.tbsandbox.com/router/rest?';  //沙箱环境提交URL
$sessionurl = 'http://container.api.tbsandbox.com/container?appkey='; //沙箱环境获取SessionKey
	$appKey = '12426462';  
 $appSecret = 'sandbox14256c0f1329e7f81258a4345'; 	

	}else if($testMode=='false'){
	   $url = 'http://gw.api.taobao.com/router/rest?';  //正式环境提交URL
$sessionurl = 'http://container.api.taobao.com/container?appkey='; //正式环境获取SessionKey	   
	$appKey = '12426462'; //填写自己申请的AppKey
 $appSecret = 'a76025114256c0f1329e7f81258a4345'; //填写自己申请的$appSecret
	}		
?>