<?php
require_once 'config.php';	
include_once 'cache/goods_cache.php';	   
$paramArr = array(

	/* API系统级输入参数 Start */

		'method' => 'taobao.items.search',  //API名称
	   'session' => $sessions, //session
	 'timestamp' => date('Y-m-d H:i:s'),			
		'format' => 'xml',  //返回格式,本demo仅支持xml
	   'app_key' => $appKey,  //Appkey			
			 'v' => '2.0',   //API版本号		   
	'sign_method'=> 'md5', //签名方式			

	/* API系统级参数 End */				 

	/* API应用级输入参数 Start*/

		'fields' => 'detail_url,num_iid,title,nick,type,volume,cid,pic_url,delist_time,location,price,post_fee,score',
		 'nicks' => $nick, //用户名
			 'q' => $q, //搜索字段。搜索商品的title。 
		   'cid' => $cid?$cid:'', //商品类目ID。ItemCat中的cid字段。可以通过taobao.itemcats.get取到 
   'seller_cids' => $seller_cids, //卖家店铺内自定义类目ID。多个之间用","分隔。可以根据taobao.sellercats.list.get获得 
	   'page_no' => $page_no, //页码。取值范围:大于零的整数;默认值为1，即返回第一页数据。 
	 'page_size' => 200, //每页条数。取值范围:大于零的整数;最大值：200；默认值：40。 
  'has_discount' => $has_discount, //是否参与会员折扣。可选值：true，false。默认不过滤该条件
  'has_showcase' => $has_showcase, //是否橱窗推荐。 可选值：true，false。默认不过滤该条件 
	  'order_by' => $order_by, //排序方式。格式为column:asc/desc ，column可选值:list_time(上架时间),delist_time(下架时间),num(商品数量);默认上架时间降序(即最新上架排在前面)。如按照上架时间降序排序方式为list_time:desc 
	 'is_taobao' => $is_taobao, //商品是否在淘宝显示 
		 'is_ex' => $is_ex, //商品是否在外部网店显
			
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
$item = $result['item_search']['items']['item'];
$total_results = $result['total_results'];

if($total_results=='1'){
	$item[] = $item;	
}

foreach ($item as $key => $val){
	$goods_list[] = $val['num_iid'];
}
$goods_list = array_unique($goods_list);
$cachetext = "<?php\r\n".
		'$goods_list='.arrayeval($goods_list).
		"\r\n?>";
if(!swritefile('cache/goods_cache.php', $cachetext)) {
		exit("File: $cachefile write error.");
	}

if($total_results > count($goods_list)){
	echo '已采集'.count($goods_list).'条商品,总数为'.$total_results.'<a href="http://www.aitiao.com/creat_goods_cache.php?page='.($page_no+1).'&page_size=200">下一页</a>';	
}else{
	echo '全部完成'.count($goods_list).'条商品';	
}

?>