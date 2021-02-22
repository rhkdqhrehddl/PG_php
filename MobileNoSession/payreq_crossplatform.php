<?php

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
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];				//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];					//상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                        //테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)
    $LGD_OID                    = $_POST["LGD_OID"];					//주문번호(상점정의 유니크한 주문번호를 입력하세요)
    $LGD_AMOUNT                 = $_POST["LGD_AMOUNT"];					//결제금액("," 를 제외한 결제금액을 입력하세요)
    $LGD_BUYER                  = $_POST["LGD_BUYER"];					//구매자명
    $LGD_PRODUCTINFO            = $_POST["LGD_PRODUCTINFO"];			//상품명
    $LGD_BUYEREMAIL             = $_POST["LGD_BUYEREMAIL"];				//구매자 이메일
    $LGD_CUSTOM_FIRSTPAY        = $_POST["LGD_CUSTOM_FIRSTPAY"];		//상점정의 초기결제수단
    $LGD_PCVIEWYN				= $_POST["LGD_PCVIEWYN"];				//휴대폰번호 입력 화면 사용 여부(유심칩이 없는 단말기에서 입력-->유심칩이 있는 휴대폰에서 실제 결제)
	$LGD_CUSTOM_SKIN            = "SMART_XPAY2";                        //상점정의 결제창 스킨
    $LGD_CUSTOM_PROCESSTYPE     = "TWOTR";                                       //수정불가
	
	$configPath 				= "C:/lgdacom"; 						//토스페이먼츠에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정. 	 
    
	if(PHP_OS === "Linux"){
		$configPath             = "/lgdacom";
	}
	
	$LGD_OSTYPE_CHECK           = "M";
	
    /*
     * 가상계좌(무통장) 결제 연동을 하시는 경우 아래 LGD_CASNOTEURL 을 설정하여 주시기 바랍니다. 
     */    
    $LGD_CASNOTEURL				= "https://" . $server_domain . "/MobileNoSession/cas_noteurl.php";    

    /*
     * LGD_RETURNURL 을 설정하여 주시기 바랍니다. 반드시 현재 페이지와 동일한 프로트콜 및  호스트이어야 합니다. 아래 부분을 반드시 수정하십시요.
     */    
    $LGD_RETURNURL				= "https://" . $server_domain . "/MobileNoSession/returnurl.php";  
	
	/*
	* ISP 카드결제 연동을 위한 파라미터(필수)
	*/
	$LGD_KVPMISPWAPURL		= "";
	$LGD_KVPMISPCANCELURL   = "";
	
	$LGD_MPILOTTEAPPCARDWAPURL = ""; //iOS 연동시 필수
	   
	/*
	* 계좌이체 연동을 위한 파라미터(필수)
	*/
	$LGD_MTRANSFERWAPURL 		= "";
	$LGD_MTRANSFERCANCELURL 	= "";   
	   
    
    /*
     *************************************************
     * 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
     * 
     * MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
     *************************************************
    */
    require_once($configPath . "/XPayClient.php");
    $xpay = new XPayClient($configPath, $LGD_PLATFORM);
   	if (!$xpay->Init_TX($LGD_MID)) {
        echo "토스페이먼츠에서 제공한 환경파일이 정상적으로 설치 되었는지 확인하시기 바랍니다.<br/>";
        echo "mall.conf에는 Mert Id = Mert Key 가 반드시 등록되어 있어야 합니다.<br/><br/>";
        echo "문의전화 토스페이먼츠 1544-7772<br/>";
        exit;
    }
	
	$LGD_TIMESTAMP = $xpay->GetTimeStamp(); 
    $LGD_HASHDATA = $xpay->GetHashData($LGD_MID,$LGD_OID,$LGD_AMOUNT,$LGD_TIMESTAMP);
    /*
     *************************************************
     * 2. MD5 해쉬암호화 (수정하지 마세요) - END
     *************************************************
     */
    $CST_WINDOW_TYPE = "submit";							// 수정불가
    $LGD_CUSTOM_SWITCHINGTYPE = "SUBMIT";					// 신용카드 카드사 인증 페이지 연동 방식										// 수정불가
   
	
	/*
	****************************************************
	* 모바일 OS별 ISP(국민/비씨), 계좌이체 결제 구분 값
	****************************************************
	- 안드로이드: A (디폴트)
	- iOS: N
	- iOS일 경우, 반드시 N으로 값을 수정
	*/
	$LGD_KVPMISPAUTOAPPYN	= "A";		// 신용카드 결제 
	$LGD_MTRANSFERAUTOAPPYN = "A";		// 계좌이체 결제


	
	/*
	****************************************************
	* 모바일 OS별 ISP(국민/비씨), 계좌이체 결제 구분 값
	****************************************************
	- 안드로이드: A (디폴트)
	- iOS: N
	- iOS일 경우, 반드시 N으로 값을 수정
	*/
	$payReqMap['LGD_KVPMISPAUTOAPPYN']	= "A";		// 신용카드 결제 
	$payReqMap['LGD_MTRANSFERAUTOAPPYN']= "A";		// 계좌이체 결제

    // 가상계좌(무통장) 결제연동을 하시는 경우  할당/입금 결과를 통보받기 위해 반드시 LGD_CASNOTEURL 정보를 토스페이먼츠에 전송해야 합니다 .
    $payReqMap['LGD_CASNOTEURL'] = $LGD_CASNOTEURL;               // 가상계좌 NOTEURL

    //Return URL에서 인증 결과 수신 시 셋팅될 파라미터 입니다.*/
    $payReqMap['LGD_RESPCODE']           = "";
    $payReqMap['LGD_RESPMSG']            = "";
    $payReqMap['LGD_PAYKEY']             = "";

    $_SESSION['PAYREQ_MAP'] = $payReqMap;
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>토스페이먼츠 eCredit서비스 결제테스트</title>
<!-- test -->
<script language="javascript" src="https://pretest.tosspayments.com:9443/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
<!-- 
  service  
<script language="javascript" src="https://xpayvvip.tosspayments.com/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
 -->

<script type="text/javascript">


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
        <td>구매자 이름 </td>
        <td><?= $LGD_BUYER ?></td>
    </tr>
    <tr>
        <td>상품정보 </td>
        <td><?= $LGD_PRODUCTINFO ?></td>
    </tr>
    <tr>
        <td>결제금액 </td>
        <td><?= $LGD_AMOUNT ?></td>
    </tr>
    <tr>
        <td>구매자 이메일 </td>
        <td><?= $LGD_BUYEREMAIL ?></td>
    </tr>
    <tr>
        <td>주문번호 </td>
        <td><?= $LGD_OID ?></td>
    </tr>
    <tr>
        <td colspan="2">* 추가 상세 결제요청 파라미터는 메뉴얼을 참조하시기 바랍니다.</td>
    </tr>
    <tr>
        <td colspan="2"></td>
    </tr>    
    <tr>
        <td colspan="2">
		<input type="button" value="인증요청" onclick="launchCrossPlatform();"/>         
        </td>
    </tr>    
</table>

<input type="hidden" id="CST_PLATFORM"				name="CST_PLATFORM"					value="<?=$CST_PLATFORM ?>"/>
<input type="hidden" id="CST_MID"					name="CST_MID"						value="<?=$CST_MID ?>"/>
<input type="hidden" id="LGD_MID"                   name="LGD_MID"                      value="<?=$LGD_MID ?>"/>
<input type="hidden" id="LGD_WINDOW_TYPE"			name="LGD_WINDOW_TYPE"				value="<?=$CST_WINDOW_TYPE ?>"/>
<input type="hidden" id="CST_WINDOW_TYPE"           name="CST_WINDOW_TYPE"              value="<?=$CST_WINDOW_TYPE ?>"/>
<input type="hidden" id="LGD_OID"					name="LGD_OID"						value="<?=$LGD_OID ?>"/>
<input type="hidden" id="LGD_BUYER"					name="LGD_BUYER"					value="<?=$LGD_BUYER ?>"/>
<input type="hidden" id="LGD_PRODUCTINFO"			name="LGD_PRODUCTINFO"				value="<?=$LGD_PRODUCTINFO ?>"/>
<input type="hidden" id="LGD_AMOUNT"				name="LGD_AMOUNT"					value="<?=$LGD_AMOUNT ?>"/>
<input type="hidden" id="LGD_BUYEREMAIL"			name="LGD_BUYEREMAIL"				value="<?=$LGD_BUYEREMAIL ?>"/>
<input type="hidden" id="LGD_CUSTOM_SKIN"			name="LGD_CUSTOM_SKIN"				value="<?=$LGD_CUSTOM_SKIN ?>"/>
<input type="hidden" id="LGD_CUSTOM_PROCESSTYPE"	name="LGD_CUSTOM_PROCESSTYPE"		value="<?=$LGD_CUSTOM_PROCESSTYPE ?>"/>
<input type="hidden" id="LGD_TIMESTAMP"				name="LGD_TIMESTAMP"				value="<?=$LGD_TIMESTAMP ?>"/>
<input type="hidden" id="LGD_HASHDATA"				name="LGD_HASHDATA"					value="<?=$LGD_HASHDATA ?>"/>
<input type="hidden" id="LGD_RETURNURL"				name="LGD_RETURNURL"				value="<?=$LGD_RETURNURL ?>"/>
<input type="hidden" id="LGD_CUSTOM_FIRSTPAY"		name="LGD_CUSTOM_FIRSTPAY"			value="<?=$LGD_CUSTOM_FIRSTPAY ?>"/>
<input type="hidden" id="LGD_CUSTOM_SWITCHINGTYPE"	name="LGD_CUSTOM_SWITCHINGTYPE"		value="<?=$LGD_CUSTOM_SWITCHINGTYPE ?>"/>
<input type="hidden" id="LGD_WINDOW_VER"			name="LGD_WINDOW_VER"				value="<?=$LGD_WINDOW_VER ?>"/>
<input type="hidden" id="LGD_OSTYPE_CHECK"			name="LGD_OSTYPE_CHECK"				value="<?=$LGD_OSTYPE_CHECK ?>"/>
<input type="hidden" id="LGD_VERSION"				name="LGD_VERSION"					value="PHP_Non-ActiveX_Standard"/>
<input type="hidden" id="LGD_DOMAIN_URL"			name="LGD_DOMAIN_URL"				value="xpayvvip"/>
<input type="hidden" id="LGD_CASNOTEURL"			name="LGD_CASNOTEURL"				value="<?=$LGD_CASNOTEURL ?>"/>
<input type="hidden" id="LGD_PCVIEWYN"				name="LGD_PCVIEWYN"					value="<?=$LGD_PCVIEWYN ?>"/>
<input type="hidden" id="LGD_MPILOTTEAPPCARDWAPURL"	name="LGD_MPILOTTEAPPCARDWAPURL"	value="<?=$LGD_MPILOTTEAPPCARDWAPURL ?>"/>
<input type="hidden" id="LGD_RETURN_MERT_CUSTOM_PARAM"	name="LGD_RETURN_MERT_CUSTOM_PARAM"	value="Y" />

<!--
결제요청시 “LGD_RETURN_MERT_CUSTOM_PARAM” = “Y”일 경우 사용자정의 값이 retunurl 로 그대로 리턴
*주의사항
사용자정의 파라미터는 LGD_ 로 시작될 수 없음.

<input type="hidden" id="LGD_RETURN_MERT_CUSTOM_PARAM"	name="LGD_RETURN_MERT_CUSTOM_PARAM"	value="Y” />
<input type="hidden" id="CUSTOM_PARAMETER1"	name="CUSTOM_PARAMETER1"	value="상점정의 파라미터 값 1번입니다" />
<input type="hidden" id="CUSTOM_PARAMETER2"	name="CUSTOM_PARAMETER2"	value="상점정의 파라미터 값 2번입니다” />
-->

<!-- ISP(국민/BC)결제에만 적용 -->
<input type="hidden" id="LGD_KVPMISPWAPURL"			name="LGD_KVPMISPWAPURL"			value="<?=$LGD_KVPMISPWAPURL ?>"/>
<input type="hidden" id="LGD_KVPMISPCANCELURL"		name="LGD_KVPMISPCANCELURL"			value="<?=$LGD_KVPMISPCANCELURL ?>"/>

<!-- 계좌이체 결제에만 적용 -->
<input type="hidden" id="LGD_MTRANSFERWAPURL"		name="LGD_MTRANSFERWAPURL"			value="<?=$LGD_MTRANSFERWAPURL ?>"/>
<input type="hidden" id="LGD_MTRANSFERCANCELURL"	name="LGD_MTRANSFERCANCELURL"		value="<?=$LGD_MTRANSFERCANCELURL ?>"/>

<!-- 모바일 OS별 ISP(국민/BC)결제/계좌이체 결제 구분 -->
<input type="hidden" id="LGD_KVPMISPAUTOAPPYN"		name="LGD_KVPMISPAUTOAPPYN"			value="<?=$LGD_KVPMISPAUTOAPPYN ?>"/>
<input type="hidden" id="LGD_MTRANSFERAUTOAPPYN"	name="LGD_MTRANSFERAUTOAPPYN"		value="<?=$LGD_MTRANSFERAUTOAPPYN ?>"/>


<input type="hidden" id="LGD_RESPCODE"				name="LGD_RESPCODE"					value=""/>
<input type="hidden" id="LGD_RESPMSG"				name="LGD_RESPMSG"					value=""/>
<input type="hidden" id="LGD_PAYKEY"				name="LGD_PAYKEY"					value=""/>
<input type="hidden" id="LGD_ENCODING"				name="LGD_ENCODING"				value="UTF-8"/>
<input type="hidden" id="LGD_ENCODING_RETURNURL"	name="LGD_ENCODING_RETURNURL"	value="UTF-8"/>
<input type="hidden" id="LGD_ENCODING_NOTEURL"		name="LGD_ENCODING_NOTEURL"		value="UTF-8"/>

</form>
</body>
</html>

