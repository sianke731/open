<?php
	$ref =$_SERVER['SCRIPT_NAME'];
	if(empty($sessions)){
		echo 'SESSION状态：异常<a href="'.$sessionurl.$appKey.'&ref='.$ref.'">获取Session</a>';exit;
	}else{
		echo '当前登陆用户:'.$userNick.'<a href="login.php?ac=logout&ref='.$ref.'">退出登陆</a>';
	}
?>
<br />
<a href="taobao.shop.remainshowcase.get.php">获取卖家店铺剩余橱窗数量</a><br />

<a href="taobao.items.onsale.get.php"﻿>获取未被橱窗推荐的商品</a>

