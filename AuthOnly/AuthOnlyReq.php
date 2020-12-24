<?php
	session_start();
	header('Set-Cookie: PHPSESSID='.session_id().'; SameSite=None; Secure');
    /*
     * 
     *     
     * 기본 파라미터만 예시되어 있으며, 별도로 필요하신 파라미터는 연동메뉴얼을 참고하시어 추가하시기 바랍니다. 
     *
     */

     
    /*
	 * [상점인증요청 페이지]
     * 1. 기본인증정보 변경
     *
     * 인증기본정보를 변경하여 주시기 바랍니다. 
     */
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];                       //토스페이먼츠 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];                            //상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                                //테스트 아이디는 't'를 반드시 제외하고 입력하세요.(CST_MID: LGD_MID를 설정하기 위한 상점아이디)
	$LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점 아이디 LGD_MID는 CST_PLATFORM의 값에 따라 테스트용 또는 서비스용 아이디로 설정됨
    

    $LGD_BUYER		            = $_POST["LGD_BUYER"];                        	//성명
    $LGD_BUYERSSN               = $_POST["LGD_BUYERSSN"];                       //인증요청자 생년월일 6자리 (YYMMDD) or 사업자번호 10자리
																				//휴대폰 본인인증을 사용할 경우 생년월일은 '0' 6자리를 넘기세요. 예)000000
																				//기타 인증도 사용할 경우 생년월일 (보안을 위해 DB나 세션에 저장처리 권장)\

	$LGD_NAMECHECKYN		 	= $_POST["LGD_NAMECHECKYN"];					//계좌실명확인여부
	$LGD_HOLDCHECKYN 			= $_POST["LGD_HOLDCHECKYN"];					//휴대폰본인확인 SMS발송 여부
	$LGD_MOBILE_SUBAUTH_SITECD 	= $_POST["LGD_MOBILE_SUBAUTH_SITECD"];			//신용평가사에서 부여받은 회원사 고유 코드
																				//(CI값만 필요한 경우 옵션, DI값도 필요한 경우 필수)
    $LGD_CUSTOM_USABLEPAY  		= $_POST["LGD_CUSTOM_USABLEPAY"];               //[반드시 설정]상점정의 이용가능 인증수단으로 한 개의 값만 설정 (예:"ASC007")
    $LGD_TIMESTAMP        		= $_POST["LGD_TIMESTAMP"];                		//타임스탬프(YYYYMMDDhhmmss)
    $LGD_CUSTOM_SKIN      		= "red";										//본인확인창 스킨
    $LGD_WINDOW_TYPE            = $_POST["LGD_WINDOW_TYPE"];					//본인확인창 호출 방식 (수정불가)  

    // LGD_RETURNURL 을 설정하여 주시기 바랍니다. 반드시 현재 페이지와 동일한 프로트콜 및  호스트이어야 합니다. 아래 부분을 반드시 수정하십시요.
    $LGD_RETURNURL              = "https://localhost:9443/AuthOnly/returnurl.php";         
	
	$configPath					= "C:/lgdacom"; //토스페이먼츠에서 제공한 환경파일("/conf/lgdacom.conf,/conf/mall.conf") 위치 지정.
	
	/*
	*************************************************
	* 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
	* 
	* MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
	*************************************************
	*
	* 해쉬 암호화 적용( LGD_MID + LGD_BUYERSSN + LGD_TIMESTAMP + LGD_MERTKEY )
	* LGD_MID          : 상점아이디
	* LGD_BUYERSSN     : 생년월일 / 사업자번호
	* LGD_TIMESTAMP    : 타임스탬프
	* LGD_MERTKEY      : 상점MertKey (mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
	*
	* MD5 해쉬데이터 암호화 검증을 위해
	* 토스페이먼츠에서 발급한 상점키(MertKey)를 환경설정 파일(lgdacom/conf/mall.conf)에 반드시 입력하여 주시기 바랍니다.
	*/
	
	require_once("C:/lgdacom/XPayClient.php");
	$xpay = new XPayClient($configPath, $CST_PLATFORM);

	if (!$xpay->Init_TX($LGD_MID)) {
    	echo "토스페이먼츠에서 제공한 환경파일이 정상적으로 설치 되었는지 확인하시기 바랍니다.<br/>";
    	echo "mall.conf에는 Mert Id = Mert Key 가 반드시 등록되어 있어야 합니다.<br/><br/>";
    	echo "문의전화 토스페이먼츠 1544-7772<br/>";
    	exit;
    }
    
	$LGD_HASHDATA = md5($LGD_MID.$LGD_BUYERSSN.$LGD_TIMESTAMP.$xpay->config[$LGD_MID]);    
	/*
	*************************************************
	* 2. MD5 해쉬암호화 (수정하지 마세요) - END
	*************************************************
	*/
     
	
	$payReqMap['CST_PLATFORM']              = $CST_PLATFORM;           				// 테스트, 서비스 구분
	$payReqMap['CST_MID']                   = $CST_MID;                				// 상점아이디
	$payReqMap['LGD_MID']                   = $LGD_MID;                				// 상점아이디
	$payReqMap['LGD_HASHDATA'] 				= $LGD_HASHDATA;      	           		// MD5 해쉬암호값
	$payReqMap['LGD_BUYER']              	= $LGD_BUYER;							// 요청자 성명
	$payReqMap['LGD_BUYERSSN']              = $LGD_BUYERSSN;           				// 요청자 생년월일 / 사업자번호
	
	$payReqMap['LGD_NAMECHECKYN']           = $LGD_NAMECHECKYN;           			// 계좌실명확인여부
	$payReqMap['LGD_HOLDCHECKYN']           = $LGD_HOLDCHECKYN;           			// 휴대폰본인확인 SMS발송 여부
	$payReqMap['LGD_MOBILE_SUBAUTH_SITECD'] = $LGD_MOBILE_SUBAUTH_SITECD;           // 신용평가사에서 부여받은 회원사 고유 코드
	
	$payReqMap['LGD_CUSTOM_SKIN'] 			= $LGD_CUSTOM_SKIN;                		// 본인확인창 SKIN
	$payReqMap['LGD_TIMESTAMP'] 			= $LGD_TIMESTAMP;                  		// 타임스탬프
	$payReqMap['LGD_CUSTOM_USABLEPAY']      = $LGD_CUSTOM_USABLEPAY;        		// [반드시 설정]상점정의 이용가능 인증수단으로 한 개의 값만 설정 (예:"ASC007")
	$payReqMap['LGD_WINDOW_TYPE']           = $LGD_WINDOW_TYPE;        				// 호출방식 (수정불가)
	$payReqMap['LGD_RETURNURL'] 			= $LGD_RETURNURL;      			   		// 응답수신페이지
	$payReqMap['LGD_VERSION'] 				= "PHP_Non-ActiveX_AuthOnly";			// 사용타입 정보(수정 및 삭제 금지): 이 정보를 근거로 어떤 서비스를 사용하는지 판단할 수 있습니다.
	$payReqMap['LGD_DOMAIN_URL'] 			= "xpayvvip";  
	
	/*Return URL에서 인증 결과 수신 시 셋팅될 파라미터 입니다.*/
	$payReqMap['LGD_RESPCODE'] 				= "";
	$payReqMap['LGD_RESPMSG'] 				= "";
	$payReqMap['LGD_AUTHONLYKEY'] 			= "";
	$payReqMap['LGD_PAYTYPE'] 				= "";
	
	$payReqMap['LGD_ENCODING'] 			        = "UTF-8";
	$payReqMap['LGD_ENCODING_RETURNURL'] 		= "UTF-8";
	
	$_SESSION['PAYREQ_MAP'] = $payReqMap;

?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>토스페이먼츠 전자결제 본인확인서비스  샘플 페이지</title>
<!-- test일 경우 -->
<script language="javascript" src="https://pretest.tosspayments.com:9443/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
<!-- 
  service일 경우 아래 URL을 사용 
<script language="javascript" src="https://xpay.tosspayments.com/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
 -->
<script type="text/javascript">

	/*
	* 수정불가.
	*/
	var LGD_window_type = '<?=$LGD_WINDOW_TYPE?>';

	
	/*
	* 수정불가.
	*/
	function launchCrossPlatform(){
		
		lgdwin = openAuthonly( document.getElementById('LGD_PAYINFO'), '<?= $CST_PLATFORM ?>', LGD_window_type, null );
	}
	
	/*
	* FORM 명만  수정 가능
	*/
	function getFormObject() {
	        return document.getElementById("LGD_PAYINFO");
	}
	
	function  payment_return() {
		
		var fDoc;

		fDoc = lgdwin.contentWindow || lgdwin.contentDocument;
	
		if (fDoc.document.getElementById('LGD_RESPCODE').value == "0000") {
			document.getElementById("LGD_AUTHONLYKEY").value = fDoc.document.getElementById('LGD_AUTHONLYKEY').value;
			document.getElementById("LGD_PAYTYPE").value = fDoc.document.getElementById('LGD_PAYTYPE').value;
			
			document.getElementById("LGD_PAYINFO").target = "_self";
			document.getElementById("LGD_PAYINFO").action = "AuthOnlyRes.php";
			document.getElementById("LGD_PAYINFO").submit();
		} else {
			alert("LGD_RESPCODE (결과코드) : " + fDoc.document.getElementById('LGD_RESPCODE').value + "\n" + "LGD_RESPMSG (결과메시지): " + fDoc.document.getElementById('LGD_RESPMSG').value);
			closeIframe();
		}//end if
	}//end payment_return
</script>
</head>
<body>
<form method="post" id="LGD_PAYINFO" name="LGD_PAYINFO">
<table>	
    <tr>
    	<td>상점아이디(t를 제외한 아이디) </td>
    	<td><?= $CST_MID ?></td>
	</tr>
	<tr>
	    <td>상점아이디</td>
	    <td><?= $LGD_MID ?></td>
	</tr>			
	<tr>
	    <td>서비스,테스트 </td>
	    <td><?= $CST_PLATFORM ?></td>
	</tr>
	<tr>
	    <td>
			생년월일 <br/>
			또는 사업자번호
		</td>
	    <td><?= $LGD_BUYERSSN ?></td>
	</tr>
	<tr>
	    <td>성명</td>
	    <td><?= $LGD_BUYER ?></td>
	</tr>
	<tr>
	    <td>이용가능 인증수단</td>
	    <td><?= $LGD_CUSTOM_USABLEPAY ?></td>
	</tr>
	<tr>
	    <td>웹사이트코드(옵션)</td>
	    <td><?= $LGD_MOBILE_SUBAUTH_SITECD ?></td>
	</tr>
	<tr>
	    <td>타임스탬프</td>
	    <td><?= $LGD_TIMESTAMP ?></td>
	</tr>
	<tr>
	    <td>검증데이터</td>
	    <td><?= $LGD_HASHDATA ?></td>
	</tr>
	
	<tr>
	    <td>실명확인여부</td>
	    <td>
			<?=$LGD_NAMECHECKYN ?>
		</td>
	</tr>
	<tr>
	    <td>휴대폰본인확인SMS발송여부</td>
	    <td>
			<?=$LGD_HOLDCHECKYN ?>
		</td>
	</tr>
	<tr>
	    <td>인증창 스킨 color</td>
	    <td>
			<?=$LGD_CUSTOM_SKIN ?>
		</td>
	</tr>
	<tr>
	    <td>인증창 호출 방식 </td>
	    <td>
			<?=$LGD_WINDOW_TYPE ?>
		</td>
	</tr>													
	<tr>
		<td>
			<input type="button" value="인증요청" onclick="launchCrossPlatform();"/>
   		</td>
	</tr>
</table>
<?php
  foreach ($payReqMap as $key => $value) {
    echo "<input type='hidden' name='$key' id='$key' value='$value'/>";
  }
  var_dump($_SESSION);
?>

</form>
</body>
</html>

