<?php
	header("Content-Type:text/html;charset=UTF-8");
	require_once 'config.php';
?>
<html>
<head>
<title>得到当前会话用户出售中未被橱窗推荐的商品列表</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src=js/colors.js></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<p>得到当前会话用户出售中的商品列表</p>
<p id="msgs">
<?php include 'header.php';?>
</p>
<?php
	/* Build By fhalipay */
	
/* 得到当前会话用户出售中未被橱窗推荐的商品列表 Start*/

	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

	    	'method' => 'taobao.products.get',  //API名称
		   'session' => $sessions, //session
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */				 

		/* API应用级输入参数 Start*/

			'fields' => 'product_id,outer_id,created,tsc,cid,cat_name,props,props_str,binds_str,sale_props_str,name,binds,sale_props,price,desc,pic_url,modified,product_imgs,product_prop_imgs,status',
				 'nick' => '皮皮小站', //用户名
				
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
	if (empty($result)){
		echo '平台无任何数据返回';
		exit;
	}else{
	//获取错误信息
	$msg = $result['msg'];
	
	//返回结果
	$item = $result['items']['item'];
	$total_results = $result['total_results'];
	}
/* 得到当前会话用户出售中未被橱窗推荐的商品列表 End*/	

	
?>
<form action="" method="get" name="form1" id="form1">
  <table border="0" width="100%" class="table">
    <tr>
      <td>自定义类目</td>
      <td><?php
/* 获取前台展示的店铺内卖家自定义商品类目 Start*/
	
	//参数数组
	$paramArr = array(

		/* API系统级输入参数 Start */

	    	'method' => 'taobao.sellercats.list.get',  //API名称
//		   'session' => $sessions, //session
	     'timestamp' => date('Y-m-d H:i:s'),			
		    'format' => 'xml',  //返回格式,本demo仅支持xml
    	   'app_key' => $appKey,  //Appkey			
	    		 'v' => '2.0',   //API版本号		   
		'sign_method'=> 'md5', //签名方式			

		/* API系统级参数 End */				 

		/* API应用级输入参数 Start*/

	    	'fields' => 'iid,detail_url,num_iid,title,nick,type,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,auto_repost,approve_status,postage_id,product_id,auction_point,property_alias,item_imgs,prop_imgs,skus,outer_id,is_virtual,is_taobao,is_ex,videos,is_3D,score,volume,one_station',  //返回字段
//              'nick' => $userNick,  //卖家昵称
		   'nick' => $userNick,//商品数字id  
			  
		/* API应用级输入参数 End*/
	);


	//生成签名
	$sign = createSign($paramArr,$appSecret);
	
	//组织参数
	$strParam = createStrParam($paramArr);
	$strParam .= 'sign='.$sign;
	
	//构造Url
	$urls = $url.$strParam;

	$shop = vita_get_url_content($urls);

	//解析Xml数据
	$shop = getXmlData($shop);
	$shop = $shop['seller_cats']['seller_cat'];
	
/* 获取前台展示的店铺内卖家自定义商品类目 End*/	
?>
        <select name="seller_cids">
          <option value="">选择</option>
          <?php
		foreach ($shop as $key=>$val){
    ?>
          <option value="<?php echo $val['cid']?>"><?php echo $val['name']?></option>
          <?php }?>
        </select></td>
      <td>是否参与折扣</td>
      <td><select name="has_discount">
          <option value="">选择</option>
          <option value="true">是</option>
          <option value="false">否</option>
        </select></td>
      <td>是否在淘宝显示 </td>
      <td><select name="is_tabao">
          <option value="">选择</option>
          <option value="true">是</option>
          <option value="false">否</option>
        </select></td>
      <td>是否在外部网店显示 </td>
      <td><select name="is_ex">
          <option value="">选择</option>
          <option value="true">是</option>
          <option value="false">否</option>
        </select></td>
    </tr>
    <tr bgcolor="#dddddd">
      <td>所有类目</td>
      <td><?php require_once 'allItemcats.php';?></td>
      <td>是否橱窗展示</td>
      <td><select name="has_showcase">
          <option value="">选择</option>
          <option value="true">是</option>
          <option value="false">否</option>
        </select></td>
      <td>商品标题</td>
      <td><input type="text" name="q" id="q"></td>
      <td>排序</td>
      <td><select name="order_by">
          <option value="">选择</option>
          <option value="list_time:desc">上架时间降序</option>
          <option value="list_time：asc">上架时间升序</option>
          <option value="delist_time:desc">下架时间降序</option>
          <option value="delist_time：asc">下架时间升序</option>
          <option value="num:desc">商品数量降序</option>
          <option value="num：asc">商品数量升序</option>
        </select></td>
    </tr>
    <tr>
      <td colspan="8" bgcolor="#FFFFFF"><input type="submit" value="搜索" ></td>
    <tr>
  </table>
</form>
<table border="0" width="100%" class="table">
  <tr>
    <td>橱窗推荐</td>
    <td>宝贝名称</td>
    <td>数量</td>
    <td>剩余时间</td>
    <td>价格</td>
    <td>修改设置</td>
  </tr>
  <?php 
  if(!empty($msg)){
  //错误信息
  	echo '<tr><td colspan=10>错误信息:'.$msg.'</td></tr>';
  }else{
  //处理正确返回信息
	if ($total_results=='0'){
	echo '<tr><td>没有合乎条件的数据</td></tr>';	
	}else if($total_results=='1'){	
  ?>
  <tr>
    <td><?php echo gethas_showcase($item['has_showcase']);?></td>
    <td><?php echo $item['title'];?></td>
    <td><?php echo $item['num'];?></td>
    <td><?php 
		$curtime=date('Y-m-d H:i:s');	
		$strtime=$item['delist_time'];
		$s=strtotime($curtime)-strtotime($strtime);
		$k=60*60*24;
		$day=$s/$k;//天数
		$day = str_replace('-','',round($day,0)).'天';
		$s=$s%$k;
		$k=$k/24;
		$hour=$s/$k;//小时数
		$hour = str_replace('-','',round($hour,0)).'小时';
		$s=$s%$k;
		$k=$k/60;
		$minute=$s/$k;//分数
		$minute = str_replace('-','',round($minute,0)).'分';
		$second=$s%$k;//秒数
		$second = str_replace('-','',round($second,0)).'秒';
		echo $day.$hour.$minute;
	?></td>
    <td><?php echo $item['price'];?></td>
    <td><?php
		if($item['has_showcase']=='false'){
    		echo '<a href="taobao.item.recommend.add.php?num_iid='.$item['num_iid'].'" target="_blank">橱窗推荐</a>';		
		}else{
    		echo '<a href="taobao.item.recommend.delete.php?num_iid='.$item['num_iid'].'" target="_blank">取消橱窗推荐</a>';		
		}
    ?></td>
  </tr>
  <?php
	}else{
  foreach ($item as $key => $val){
  ?>
  <tr>
    <td><?php echo gethas_showcase($val['has_showcase']);?></td>
    <td><?php echo $val['title'];?></td>
    <td><?php echo $val['num'];?></td>
    <td><?php 
		$curtime=date('Y-m-d H:i:s');	
		$strtime=$val['delist_time'];
		$s=strtotime($curtime)-strtotime($strtime);
		$k=60*60*24;
		$day=$s/$k;//天数
		$day = str_replace('-','',round($day,0)).'天';
		$s=$s%$k;
		$k=$k/24;
		$hour=$s/$k;//小时数
		$hour = str_replace('-','',round($hour,0)).'小时';
		$s=$s%$k;
		$k=$k/60;
		$minute=$s/$k;//分数
		$minute = str_replace('-','',round($minute,0)).'分';
		$second=$s%$k;//秒数
		$second = str_replace('-','',round($second,0)).'秒';
		echo $day.$hour.$minute;
	?></td>
    <td><?php echo $val['price'];?></td>
    <td><?php
		if($val['has_showcase']=='false'){
    		echo '<a href="taobao.item.recommend.add.php?num_iid='.$val['num_iid'].'" target="_blank">橱窗推荐 </a>';		
		}else{
    		echo '<a href="taobao.item.recommend.delete.php?num_iid='.$val['num_iid'].'" target="_blank">取消橱窗推荐</a>';		
		}
    ?></td>
  </tr>
  <?php
  }
  }
  ?>
  <tr>
    <td colspan="15"><?php
	// 分页 new PageClass(数据总数,每页数量,页码,URL组成);
	$pages = new PageClass($total_results,$page_size,$_GET['page'],'?'.$_SERVER['QUERY_STRING'].'&page={page}');
	echo $pages -> myde_write();
	?></td>
  </tr>
</table>
<?php }?>
</body>
</html>
