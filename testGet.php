<?php
	/*
	// 团队标识
	$TG = 27;
	//初始化 
    $curl = curl_init(); 
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, 'http://opapi.hmmcd.com/apple_api/alipay/getLianPayCenterType?type=' . $TG); 
    //设置头文件的信息作为数据流输出 
    curl_setopt($curl, CURLOPT_HEADER, 1); 
    //设置获取的信息以文件流的形式返回，而不是直接输出。 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    print_r('response:' . $data);
	
	echo '<br/>';
	*/
	$accountId = "10083";// 商户ID
	$key = "146341b07e52439f607e7a67fbae2ba3";// 商户KEY
	//初始化 
    $curl = curl_init(); 
	$urlParam = "http://47.97.25.31/api/getamountlist";
	$str = "accountId=" . $accountId . "&alipayAccount=&appid=1880000000000000&bankId=&phone=&seqId=1559207814381&key=" . $key;
	$sign = strtoupper(md5($str));
	
	$postData = array(
		'accountId' => $accountId,
		'alipayAccount' => '',
		'appid' => '1880000000000000',
		'bankId' => '',
		'phone' => '',
		'seqId' => '1559207814381'
	);
	$postData['sign'] = $sign;

	$datas = '';
	foreach ($postData as $key => $val) {
		$datas .=   $key . '=' . $val.'&';
	}
	$datas  = rtrim($datas, '&');

    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $urlParam . '?' . $datas); 
    //设置头文件的信息作为数据流输出; 0 设置为0表示不返回HTTP头部信息 1 设置为1表示返回HTTP头部信息
    curl_setopt($curl, CURLOPT_HEADER, 0); 
    //设置获取的信息以文件流的形式返回，而不是直接输出。 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    print_r($data);

?>
