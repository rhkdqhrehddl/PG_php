<?php
	session_start();
	header('Set-Cookie: PHPSESSID='.session_id().'; SameSite=None; Secure');
	/*
	 * [결제 인증요청 페이지(STEP2-1)]
	 *
	 * 샘플페이지에서는 기본 파라미터만 예시되어 있으며, 별도로 필요하신 파라미터는 연동메뉴얼을 참고하시어 추가 하시기 바랍니다.
	 */

	/*
	 * 1. 기본결제 인증요청 정보 변경
	 *
	 * 기본정보를 변경하여 주시기 바랍니다.(파라미터 전달시 POST를 사용하세요)
	 */

	$server_domain = $_SERVER['HTTP_HOST'];
	$CST_PLATFORM               = $_POST["CST_PLATFORM"];						//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
	$CST_MID                    = $_POST["CST_MID"];							//상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
																																					//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
	$LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)    
												 
	$LGD_BUYERSSN				= $_POST["LGD_BUYERSSN"];						//인증요청자 생년월일 6자리 (YYMMDD) or 사업자번호 10자리
	$LGD_CHECKSSNYN             = $_POST["LGD_CHECKSSNYN"];						//인증요청자 생년월일,사업자번호 일치 여부 확인 플래그 ( 'Y'이면 인증창에서 고객이 입력한 생년월일,사업자번호 일치여부를 확인합니다.)
	$LGD_PRODUCTINFO            = $_POST["LGD_PRODUCTINFO"];                     //상품명(페이나우 빌링 사용시 페이나우 뷰를 호출하기 위한 필수 파라미터)
    $LGD_AMOUNT                 = $_POST["LGD_AMOUNT"];                          //금액(페이나우 빌링 사용시 페이나우 뷰를 호출하기 위한 필수 파라미터)
    $LGD_EASYPAY_ONLY           = $_POST["LGD_EASYPAY_ONLY"];                    //페이나우사용여부(페이나우 빌링 사용시 필수)
	/*
	 * LGD_RETURNURL 을 설정하여 주시기 바랍니다. 반드시 현재 페이지와 동일한 프로트콜 및  호스트이어야 합니다. 아래 부분을 반드시 수정하십시요.
	 */    
	$LGD_RETURNURL				= "https://" . $server_domain . "/SmartXPayBilling/returnurl.php";  
	$LGD_PAYWINDOWTYPE    		= "CardBillingAuth_smartphone";
	$LGD_VERSION				= "PHP_SmartXPay_CardBilling";

	$CST_WINDOW_TYPE = "submit";										// 수정불가
	$payReqMap['CST_PLATFORM']           = $CST_PLATFORM;				// 테스트, 서비스 구분
	$payReqMap['CST_WINDOW_TYPE']        = $CST_WINDOW_TYPE;			// 수정불가
	$payReqMap['CST_MID']                = $CST_MID;					// 상점아이디
	$payReqMap['LGD_MID']                = $LGD_MID;					// 상점아이디
	$payReqMap['LGD_BUYERSSN']           = $LGD_BUYERSSN;				// 요청자 생년월일 / 사업자번호              
	$payReqMap['LGD_CHECKSSNYN']         = $LGD_CHECKSSNYN;				// 요청자 정보 일치여부 확인값
	$payReqMap['LGD_PRODUCTINFO']        = $LGD_PRODUCTINFO;			// 상품명(페이나우 빌링 사용시 필수)
    $payReqMap['LGD_AMOUNT']             = $LGD_AMOUNT;					// 금액(페이나우 빌링 사용시 필수)
    $payReqMap['LGD_EASYPAY_ONLY']       = $LGD_EASYPAY_ONLY;			// 페이나우사용여부(값:PAYNOW, 페이나우 빌링 사용시 필수)               	
	$payReqMap['LGD_RETURNURL']        	 = $LGD_RETURNURL;     	   
	$payReqMap['LGD_PAYWINDOWTYPE']      = $LGD_PAYWINDOWTYPE;          
	$payReqMap['LGD_VERSION']            = $LGD_VERSION;               
	$payReqMap['LGD_DOMAIN_URL'] 		 = "xpayvvip";	
    $payReqMap['LGD_ENCODING'] 			        = "UTF-8";
	$payReqMap['LGD_ENCODING_RETURNURL'] 		= "UTF-8";
	
	$_SESSION['PAYREQ_MAP'] = $payReqMap;     
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>토스페이먼츠 전자결서비스 결제테스트</title>
<!-- test -->
<script language="javascript" src="https://pretest.tosspayments.com:9443/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
<!-- 
  service  
<script language="javascript" src="https://xpayvvip.tosspayments.com/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
 -->
<script type="text/javascript">

/*
* iframe으로 결제창을 호출하시기를 원하시면 iframe으로 설정 (변수명 수정 불가)
*/
	var LGD_window_type = '<?= $CST_WINDOW_TYPE ?>'; 
/*
* 수정불가
*/
function launchCrossPlatform(){
		lgdwin = open_paymentwindow(document.getElementById('LGD_PAYINFO'), '<?= $CST_PLATFORM ?>', LGD_window_type);
}
/*
* FORM 명만  수정 가능
*/
function getFormObject() {
				return document.getElementById("LGD_PAYINFO");
}
</script>
</head>
<body>
<form method="post" name="LGD_PAYINFO" id="LGD_PAYINFO" action="">


<table>
		<tr>
				<td>생년월일 6자리 (YYMMDD) or 사업자번호</td>
				<td><?= $LGD_BUYERSSN ?></td>
		</tr>
		<tr>
				<td>생년월일,사업자번호 일치 여부 확인 플래그 </td>
				<td><?= $LGD_CHECKSSNYN ?></td>
		</tr>
		<tr>
				<td>cst_mid </td>
				<td><?= $CST_MID ?></td>
		</tr>
		<tr>
				<td>lgd_mid </td>
				<td><?= $LGD_MID ?></td>
		</tr>
		<tr>
				<td>return_url </td>
				<td><?= $LGD_RETURNURL ?></td>
		</tr>

	<tr>
				<td colspan="2">* 추가 상세 인증요청 파라미터는 메뉴얼을 참조하세요.</td>
		</tr>
		<tr>
				<td>
		<input type="button" value="인증요청" onclick="launchCrossPlatform();"/>        
				</td>
		</tr>
</table>
<?php
	foreach ($payReqMap as $key => $value) {
		echo "<input type='hidden' name='$key' id='$key' value='$value'>";
	}
	var_dump($_SESSION);
?>
</form>
</body>

</html>
