<?php
require_once 'config.php';	
include_once 'cache/goods_cache.php';	   
$paramArr = array(

	/* APIϵͳ��������� Start */

		'method' => 'taobao.items.search',  //API����
	   'session' => $sessions, //session
	 'timestamp' => date('Y-m-d H:i:s'),			
		'format' => 'xml',  //���ظ�ʽ,��demo��֧��xml
	   'app_key' => $appKey,  //Appkey			
			 'v' => '2.0',   //API�汾��		   
	'sign_method'=> 'md5', //ǩ����ʽ			

	/* APIϵͳ������ End */				 

	/* APIӦ�ü�������� Start*/

		'fields' => 'detail_url,num_iid,title,nick,type,volume,cid,pic_url,delist_time,location,price,post_fee,score',
		 'nicks' => $nick, //�û���
			 'q' => $q, //�����ֶΡ�������Ʒ��title�� 
		   'cid' => $cid?$cid:'', //��Ʒ��ĿID��ItemCat�е�cid�ֶΡ�����ͨ��taobao.itemcats.getȡ�� 
   'seller_cids' => $seller_cids, //���ҵ������Զ�����ĿID�����֮����","�ָ������Ը���taobao.sellercats.list.get��� 
	   'page_no' => $page_no, //ҳ�롣ȡֵ��Χ:�����������;Ĭ��ֵΪ1�������ص�һҳ���ݡ� 
	 'page_size' => 200, //ÿҳ������ȡֵ��Χ:�����������;���ֵ��200��Ĭ��ֵ��40�� 
  'has_discount' => $has_discount, //�Ƿ�����Ա�ۿۡ���ѡֵ��true��false��Ĭ�ϲ����˸�����
  'has_showcase' => $has_showcase, //�Ƿ�����Ƽ��� ��ѡֵ��true��false��Ĭ�ϲ����˸����� 
	  'order_by' => $order_by, //����ʽ����ʽΪcolumn:asc/desc ��column��ѡֵ:list_time(�ϼ�ʱ��),delist_time(�¼�ʱ��),num(��Ʒ����);Ĭ���ϼ�ʱ�併��(�������ϼ�����ǰ��)���簴���ϼ�ʱ�併������ʽΪlist_time:desc 
	 'is_taobao' => $is_taobao, //��Ʒ�Ƿ����Ա���ʾ 
		 'is_ex' => $is_ex, //��Ʒ�Ƿ����ⲿ������
			
	/* APIӦ�ü�������� End*/
);

//����ǩ��
$sign = createSign($paramArr,$appSecret);

//��֯����
$strParam = createStrParam($paramArr);
$strParam .= 'sign='.$sign;

//����Url
$urls = $url.$strParam;

//���ӳ�ʱ�Զ�����
$cnt=0;	
while($cnt < 3 && ($result=@vita_get_url_content($urls))===FALSE) $cnt++;

//����Xml����
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
	echo '�Ѳɼ�'.count($goods_list).'����Ʒ,����Ϊ'.$total_results.'<a href="http://www.aitiao.com/creat_goods_cache.php?page='.($page_no+1).'&page_size=200">��һҳ</a>';	
}else{
	echo 'ȫ�����'.count($goods_list).'����Ʒ';	
}

?>