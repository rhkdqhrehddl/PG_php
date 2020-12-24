<!-- <SCRIPT language=JavaScript src="https://pgweb.tosspayments.com/js/DACOMEscrow_UTF8.js"></SCRIPT> -->
<!-- TEST  -->
<SCRIPT language=JavaScript src="http://pgweb.tosspayments.com:7085/js/DACOMEscrow_UTF8.js"></SCRIPT>

<SCRIPT language=javascript>
function linkESCROW(mid,oid)
{
	checkDacomESC (mid, oid, '');
	location.reload();
}
</SCRIPT>

<?php

//**************************//
//
// 배송결과 송신 PHP 예제
//
//**************************//

// 테스트용
$service_url = "http://pgweb.tosspayments.com:7085/pg/wmp/mertadmin/jsp/escrow/rcvdlvinfo.jsp"; 

// 서비스용
//$service_url = "https://pgweb.tosspayments.com/pg/wmp/mertadmin/jsp/escrow/rcvdlvinfo.jsp"; 

$mid = get_param("mid");						// 상점ID
$oid = get_param("oid");						// 주문번호
$productid = get_param("productid");			// 상품ID
$orderdate = get_param("orderdate");			// 주문일자
$dlvtype = get_param("dlvtype");				// 등록내용구분
$rcvdate = get_param("rcvdate");				// 실수령일자
$rcvname = get_param("rcvname");				// 실수령인명
$rcvrelation = get_param("rcvrelation");		// 관계
$dlvdate = get_param("dlvdate");				// 발송일자
$dlvcompcode = get_param("dlvcompcode");		// 배송회사코드
$dlvcomp = get_param("dlvcomp");				// 배송회사명
$dlvno = get_param("dlvno");					// 운송장번호
$dlvworker = get_param("dlvworker");			// 배송자명
$dlvworkertel = get_param("dlvworkertel");		// 배송자전화번호

$mertkey = "116063976f7a62cd9770548377f40901";	// 각 상점의 테스트용 상점키와 서비스용 상점키(반드시 설정해 주세요)
												// 테스트상점관리자와 서비스상점관리자의 계약정보관리에서 조회 가능

$hashdata = "03"==$dlvtype ? md5($mid.$oid.$dlvdate.$dlvcompcode.$dlvno.$mertkey) : md5($mid.$oid.$dlvtype.$rcvdate.$mertkey);

$str_url = $service_url."?mid=$mid&oid=$oid&productid=$productid&orderdate=$orderdate&dlvtype=$dlvtype&rcvdate=$rcvdate&rcvname=$rcvname&rcvrelation=$rcvrelation&dlvdate=$dlvdate&dlvcompcode=$dlvcompcode&dlvno=$dlvno&dlvworker=$dlvworker&dlvworkertel=$dlvworkertel&hashdata=$hashdata"; 

$ch = curl_init(); 

curl_setopt ($ch, CURLOPT_URL, $str_url); 
//curl_setopt ($ch, CURLOPT_COOKIEJAR, COOKIE_FILE_PATH);
//curl_setopt ($ch, CURLOPT_COOKIEFILE, COOKIE_FILE_PATH);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$fp = curl_exec ($ch);

if(curl_errno($ch)){
	// 연결실패시 DB 처리 로직 추가
	echo "FAIL";
}else{
	if(trim($fp)=="OK"){
		// 정상처리되었을때 DB 처리
		echo "OK";
	}else{
		// 비정상처리 되었을때 DB 처리
		echo  explode(".", iconv("EUC-KR", "UTF-8", $fp))[0];
	}
}
curl_close($ch);
												
/*
$hashdate;										// 인증키
$datasize = 1;									// 여러건 전송일대 상점셋팅

// DB에서 배송정보 쿼리
$query = "select * from temp";
$result = mysql_query($query);
while($row=FetchNext($result))
{

	$mid =  $row["mid"];
	$oid =  $row["oid"];
	$dlvtype =  $row["dlvtype"];

	if("03"==$dlvtype)
	{
		// 발송정보
		$dlvdate =  $row["dlvdate"];
		$dlvcomp =  $row["dlvcomp"];
		$dlvno =  $row["dlvno"];
		$dlvworker =  $row["dlvworker"];
		$dlvworkertel =  $row["dlvworkertel"];

		$hashdata = md5($mid.$oid.$dlvdate.$dlvcompcode.$dlvno.$mertkey);
	}
	else if("01"==$dlvtype)
	{
		// 수령정보 
		$rcvdate =  $row["rcvdate"];
		$rcvname =  $row["rcvname"];
		$rcvrelation =  $row["rcvrelation"];

		$hashdata = md5($mid.$oid.$dlvtype.$rcvdate.$mertkey);
	}

	// 토스페이먼츠의 배송결과등록페이지를 호출하여 배송정보등록함
	
	*	아래 URL 을 호출시 파라메터의 값에 공백이 발생하면 해당 URL이 비정상적으로 호출됩니다.
	*	배송사명등을 파라메터로 등록시 공백을 "||" 으로 변경하여 주시기 바랍니다.
	
	$str_url = $service_url."?mid=$mid&oid=$oid&productid=$productid&orderdate=$orderdate&dlvtype=$dlvtype&rcvdate=$rcvdate&rcvname=$rcvname&rcvrelation=$rcvrelation&dlvdate=$dlvdate&dlvcompcode=$dlvcompcode&dlvno=$dlvno&dlvworker=$dlvworker&dlvworkertel=$dlvworkertel&hashdata=$hashdata"; 

	

		* windows
		curl 방식
		php 4.3 버전 이상에서 지원
		php.ini 파일 안에 extension=php_curl.dll 를 사용할수 있도록 풀어주어야 한다.

		* LINUX
		1. http://curl.haxx.se/download.html 에서 curl 을 다운 받는다.
		2. curl 설치
		shell>tar -xvzf curl-7.10.3.tar.gz 
		shell>cd curl-7.10.3
		shell>./configure 
		shell>make 
		shell>make instal
		※curl 라이브러리는 /usr/local/lib 나머지 헤더는/usr/local/include/curl 로 들어간다 
		3. PHP설치
		shell>./configure \
		아래 항목 추가
		--with-curl \
		shell>make
		shell>make install

	




	$ch = curl_init(); 

	curl_setopt ($ch, CURLOPT_URL, $str_url); 
	curl_setopt ($ch, CURLOPT_COOKIEJAR, COOKIE_FILE_PATH);
	curl_setopt ($ch, CURLOPT_COOKIEFILE, COOKIE_FILE_PATH);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 

	$fp = curl_exec ($ch);

	if(curl_errno($ch)){
		// 연결실패시 DB 처리 로직 추가
	}else{
		if(trim($fp)=="OK"){
			// 정상처리되었을때 DB 처리
		}else{
			// 비정상처리 되었을때 DB 처리
		}
	}
	curl_close($ch);

	
	*	fopen 방식
	*	php 4.3 버전 이전에서 사용가능
	

	$fp = @fopen($str_url,"r");

	if(!$fp)
	{
		// 연결실패시 DB 처리 로직 추가
	}
	else
	{
		// 해당 페이지 return값 읽기
		while(!feof($fp))
		{
				$res .= fgets($fp,3000);
		}

		if(trim($res) == "OK")
		{
				// 정상처리되었을때 DB 처리
		}
		else
		{
				// 비정상처리 되었을때 DB 처리
		}
	}
}
*/

//**********************************
// 아래 있는 그대로 사용하십시요.
//**********************************
function get_param($name)
{
	global $_POST, $_GET;
	if (!isset($_POST[$name]) || $_POST[$name] == "") {
		if (!isset($_GET[$name]) || $_GET[$name] == "") {
			return false;
		} else {
			 return $_GET[$name];
		}
	}
	return $_POST[$name];
}

?>

<td><a href="javascript: linkESCROW('<?= $mid ?>', '<?= $oid ?>');">구매확인</a></td>