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
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];                             //토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];                                  //상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                                      //테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;        //상점아이디(자동생성)
    $LGD_OID                    = $_POST["LGD_OID"];                                  //주문번호(상점정의 유니크한 주문번호를 입력하세요)
    $LGD_BUYER                  = $_POST["LGD_BUYER"];                                //구매자명
    $LGD_PRODUCTINFO            = $_POST["LGD_PRODUCTINFO"];                          //상품명
    $LGD_AMOUNT                 = $_POST["LGD_AMOUNT"];                               //결제금액("," 를 제외한 결제금액을 입력하세요)
	$LGD_BUYEREMAIL             = $_POST["LGD_BUYEREMAIL"];                           //구매자 이메일
    $LGD_TIMESTAMP              = date("YmdHis");                                       //타임스탬프
    $LGD_CUSTOM_SKIN            = "red";                                              //상점정의 결제창 스킨 (red, purple, yellow)
    $LGD_CUSTOM_USABLEPAY       = $_POST["LGD_CUSTOM_USABLEPAY"];                     //상점정의 초기 결제 수단.
	$LGD_CUSTOM_PROCESSTYPE		= $_POST["LGD_CUSTOM_PROCESSTYPE"];                   //결제유형
	$LGD_WINDOW_TYPE			= $_POST["LGD_WINDOW_TYPE"];                          //결제창 호출 방식
    $LGD_WINDOW_VER             = "2.5";                                              //결제창 버젼정보
    
	$configPath 				= "C:/lgdacom"; 						              //토스페이먼츠에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정. 	    
    $LGD_BUYERID                = $_POST["LGD_BUYERID"];                              //구매자 아이디
    $LGD_BUYERIP                = $_POST["LGD_BUYERIP"];                              //구매자IP
	$LGD_CUSTOM_SWITCHINGTYPE	= $_POST["LGD_CUSTOM_SWITCHINGTYPE"];                 // 카드사 호출 방식 (수정불가)
	$LGD_SELF_CUSTOM			= $_POST["LGD_SELF_CUSTOM"];                          // 자체창 사용여부  (자체창 사용시에는 수정불가)
	$LGD_CARDTYPE				= $_POST["LGD_CARDTYPE"];                             // 카드사 코드
	
	$LGD_INSTALL				= $_POST["LGD_INSTALL"];                              // 할부개월
	$LGD_NOINT					= $_POST["LGD_NOINT"];                                // 상점부담무이자할부 적용여부 (상점부담무이자할부 적용여부)
	$LGD_SP_CHAIN_CODE			= $_POST["LGD_SP_CHAIN_CODE"];                        // 간편결제사용여부
	$LGD_SP_ORDER_USER_ID		= $_POST["LGD_SP_ORDER_USER_ID"];                     // 삼성카드 간편결제 쇼핑몰 ID (삼성카드 간편결제는 사전 협의된 가맹점만 사용 가능합니다)
	$LGD_POINTUSE				= $_POST["LGD_POINTUSE"];                             // 포인트 사용여부 (값 Y: 사용, N: 미사용)
    $LGD_RETURNURL				= "https://" . $server_domain . "/CardApp/returnurl.php"; // 반드시 현재 페이지와 동일한 프로트콜 및  호스트이어야 합니다.
   

/* 인증 이후 자동 채움되는 필드 입니다. (수정불가) */
	$LGD_AUTHTYPE				= $_POST["LGD_AUTHTYPE"];
	$LGD_CURRENCY				= $_POST["LGD_CURRENCY"];
	$KVP_CURRENCY				= $_POST["KVP_CURRENCY"];
	$KVP_OACERT_INF				= $_POST["KVP_OACERT_INF"];
	$KVP_RESERVED1				= $_POST["KVP_RESERVED1"];
	$KVP_RESERVED2				= $_POST["KVP_RESERVED2"];
	$KVP_RESERVED3				= $_POST["KVP_RESERVED3"];
	$KVP_GOODNAME				= $_POST["KVP_GOODNAME"];
	$KVP_CARDCOMPANY			= $_POST["KVP_CARDCOMPANY"];
	$KVP_PRICE					= $_POST["KVP_PRICE"];
	$KVP_PGID					= $_POST["KVP_PGID"];
	$KVP_QUOTA					= $_POST["KVP_QUOTA"];
	$KVP_NOINT					= $_POST["KVP_NOINT"];
	$KVP_SESSIONKEY				= $_POST["KVP_SESSIONKEY"];
	$KVP_ENCDATA				= $_POST["KVP_ENCDATA"];
	$KVP_CARDCODE				= $_POST["KVP_CARDCODE"];
	$KVP_CONAME					= $_POST["KVP_CONAME"];
	$LGD_KVPISP_USER			= $_POST["LGD_KVPISP_USER"];
	$LGD_PAN					= $_POST["LGD_PAN"];
	$VBV_ECI					= $_POST["VBV_ECI"];
	$VBV_CAVV					= $_POST["VBV_CAVV"];
	$VBV_XID					= $_POST["VBV_XID"];
	$VBV_JOINCODE				= $_POST["VBV_JOINCODE"];
	$LGD_EXPYEAR				= $_POST["LGD_EXPYEAR"];
	$LGD_EXPMON					= $_POST["LGD_EXPMON"];
	$LGD_LANGUAGE				= $_POST["LGD_LANGUAGE"];
	$LGD_RESPCODE = "";
	$LGD_RESPMSG = "";

	if(PHP_OS === "Linux"){
		$configPath             = "/lgdacom";
	}
   
 
    /*
     *************************************************
     * 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
     * 
     * MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
     *************************************************
     *
     * 해쉬 암호화 적용( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY )
     * LGD_MID          : 상점아이디
     * LGD_OID          : 주문번호
     * LGD_AMOUNT       : 금액
     * LGD_TIMESTAMP    : 타임스탬프
     * LGD_MERTKEY      : 상점MertKey (mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
     *
     * MD5 해쉬데이터 암호화 검증을 위해
     * 토스페이먼츠에서 발급한 상점키(MertKey)를 환경설정 파일(lgdacom/conf/mall.conf)에 반드시 입력하여 주시기 바랍니다.
     */
    require_once($configPath . "/XPayClient.php");
    $xpay = new XPayClient($configPath, $CST_PLATFORM);
   	if (!$xpay->Init_TX($LGD_MID)) {
        echo "토스페이먼츠에서 제공한 환경파일이 정상적으로 설치 되었는지 확인하시기 바랍니다.<br/>";
        echo "mall.conf에는 Mert Id = Mert Key 가 반드시 등록되어 있어야 합니다.<br/><br/>";
        echo "문의전화 토스페이먼츠 1544-7772<br/>";
        exit;
    }   
    $LGD_HASHDATA = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_TIMESTAMP.$xpay->config[$LGD_MID]);

    /*
     *************************************************
     * 2. MD5 해쉬암호화 (수정하지 마세요) - END
     *************************************************
     */

   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>통합토스페이먼츠 전자결서비스 결제테스트</title>
<!-- test일 경우 -->
<!-- <script language="javascript" src="https://pretest.uplus.co.kr:9443/xpay/js/xpay_crossplatform.js" type="text/javascript"></script> -->
<!-- 
  service일 경우 아래 URL을 사용 
 -->
 <script language="javascript" src="https://xpayvvip.uplus.co.kr/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
 
<script type="text/javascript">

/*
* iframe으로 결제창을 호출하시기를 원하시면 iframe으로 설정 (변수명 수정 불가)
*/
	var LGD_window_type = '<?= $LGD_WINDOW_TYPE ?>';
/*
* 수정불가
*/
function launchCrossPlatform(){
      lgdwin = openXpay(document.getElementById('LGD_PAYINFO'), '<?=  $CST_PLATFORM ?>', LGD_window_type, null, "", "");
}
/*
* FORM 명만  수정 가능
*/
function getFormObject() {
        return document.getElementById("LGD_PAYINFO");
}

/*
 * 인증결과 처리
 */
function payment_return() {
	var fDoc;
	
		fDoc = lgdwin.contentWindow || lgdwin.contentDocument;
	
	if (fDoc.document.getElementById('LGD_RESPCODE').value == "0000") {
		
			document.getElementById("LGD_AUTHTYPE").value = fDoc.document.getElementById('LGD_AUTHTYPE').value;
			if (document.getElementById("LGD_AUTHTYPE").value != "ISP") {
				document.getElementById("VBV_XID").value = fDoc.document.getElementById('VBV_XID').value;
				document.getElementById("VBV_ECI").value = fDoc.document.getElementById('VBV_ECI').value;
				document.getElementById("VBV_CAVV").value = fDoc.document.getElementById('VBV_CAVV').value;
				if (document.getElementById("VBV_JOINCODE") != null) {
					document.getElementById("VBV_JOINCODE").value = fDoc.document.getElementById('VBV_JOINCODE').value;
				}
				document.getElementById("LGD_PAN").value = fDoc.document.getElementById('LGD_PAN').value;
				document.getElementById("LGD_EXPYEAR").value = fDoc.document.getElementById('LGD_EXPYEAR').value;
				document.getElementById("LGD_EXPMON").value = fDoc.document.getElementById('LGD_EXPMON').value;
				document.getElementById("LGD_INSTALL").value = fDoc.document.getElementById('LGD_INSTALL').value;
				document.getElementById("LGD_NOINT").value = fDoc.document.getElementById('LGD_NOINT').value;
			} else {
				document.getElementById("KVP_QUOTA").value = fDoc.document.getElementById('KVP_QUOTA').value;
				document.getElementById("KVP_NOINT").value = fDoc.document.getElementById('KVP_NOINT').value;
				document.getElementById("KVP_CARDCODE").value = fDoc.document.getElementById('KVP_CARDCODE').value;
				document.getElementById("KVP_SESSIONKEY").value = fDoc.document.getElementById('KVP_SESSIONKEY').value;
				document.getElementById("KVP_ENCDATA").value = fDoc.document.getElementById('KVP_ENCDATA').value;
				if (fDoc.document.getElementById('LGD_KVPISP_USER') != null) {
					document.getElementById("LGD_KVPISP_USER").value = fDoc.document.getElementById('LGD_KVPISP_USER').value;
				}
			}
			document.getElementById("LGD_PAYINFO").target = "_self";
			document.getElementById("LGD_PAYINFO").action = "payres.php";
			document.getElementById("LGD_PAYINFO").submit();
		
	} else {
		alert("LGD_RESPCODE (결과코드) : " + fDoc.document.getElementById('LGD_RESPCODE').value + "\n" + "LGD_RESPMSG (결과메시지): " + fDoc.document.getElementById('LGD_RESPMSG').value);
		closeIframe();
	}
}

//-->
</script>

</head>
<body>
<form method="post" name ="LGD_PAYINFO" id="LGD_PAYINFO" action="payres.php">
	<table>
		<tr>
			<td>윈도우 호출방식 </td>
			<td><?= $LGD_WINDOW_TYPE ?></td>
		</tr>
		<tr>
			<td>아이디 </td>
			<td><?= $LGD_MID ?></td>
		</tr>
		<tr>
			<td>구매자 이름 </td>
			<td><?= $LGD_BUYER ?></td>
		</tr>
		<tr>
			<td>구매자 IP </td>
			<td><?= $LGD_BUYERIP ?></td>
		</tr>
		<tr>
			<td>구매자 ID </td>
			<td><?= $LGD_BUYERID ?></td>
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
	<br>

	<input type="hidden" name="CST_PLATFORM"                id="CST_PLATFORM"			value="<?= $CST_PLATFORM ?>" />           	<!-- 테스트, 서비스 구분 -->
	<input type="hidden" name="CST_MID"                     id="CST_MID"				value="<?= $CST_MID ?>" />                	<!-- 상점아이디 -->
	<input type="hidden" name="LGD_WINDOW_TYPE"             id="LGD_WINDOW_TYPE"		value="<?= $LGD_WINDOW_TYPE ?>" />        	<!-- 윈도우 타입 -->
	<input type="hidden" name="LGD_MID"                     id="LGD_MID"				value="<?= $LGD_MID ?>" />                	<!-- 상점아이디 -->
	<input type="hidden" name="LGD_OID"                     id="LGD_OID"				value="<?= $LGD_OID ?>" />                	<!-- 주문번호 -->
	<input type="hidden" name="LGD_BUYER"                   id="LGD_BUYER"				value="<?= $LGD_BUYER ?>" />              	<!-- 구매자 -->
	<input type="hidden" name="LGD_PRODUCTINFO"             id="LGD_PRODUCTINFO"		value="<?= $LGD_PRODUCTINFO ?>" />        	<!-- 상품정보 -->
	<input type="hidden" name="LGD_AMOUNT"                  id="LGD_AMOUNT"				value="<?= $LGD_AMOUNT ?>" />             	<!-- 결제금액 -->
	<input type="hidden" name="LGD_BUYEREMAIL"              id="LGD_BUYEREMAIL"			value="<?= $LGD_BUYEREMAIL ?>" />         	<!-- 구매자 이메일 -->
	<input type="hidden" name="LGD_CUSTOM_SKIN"             id="LGD_CUSTOM_SKIN"   		value="<?= $LGD_CUSTOM_SKIN ?>"  />       	<!-- 결제창 SKIN -->
	<input type="hidden" name="LGD_WINDOW_VER"         	    id="LGD_WINDOW_VER"	    	value="<?= $LGD_WINDOW_VER ?>"  />        	<!-- 결제창버전정보 (삭제하지 마세요) -->
	<input type="hidden" name="LGD_CUSTOM_PROCESSTYPE"      id="LGD_CUSTOM_PROCESSTYPE"	value="<?= $LGD_CUSTOM_PROCESSTYPE ?>">		<!-- 트랜잭션 처리방식 -->
	<input type="hidden" name="LGD_TIMESTAMP"               id="LGD_TIMESTAMP"			value="<?= $LGD_TIMESTAMP ?>" />            <!-- 타임스탬프 -->
	<input type="hidden" name="LGD_HASHDATA"                id="LGD_HASHDATA"			value="<?= $LGD_HASHDATA ?>" />             <!-- MD5 해쉬암호값 -->
	<input type="hidden" name="LGD_PAYKEY"                  id="LGD_PAYKEY"	 />														<!-- 토스페이먼츠 PAYKEY(인증후 자동셋팅)-->
	<input type="hidden" name="LGD_VERSION"         		id="LGD_VERSION"			value="PHP_Non-ActiveX_CardApp" />			<!-- 버전정보 (삭제하지 마세요) -->
	<input type="hidden" name="LGD_BUYERIP"                 id="LGD_BUYERIP"			value="<?= $LGD_BUYERIP ?>" />           	<!-- 구매자IP -->
	<input type="hidden" name="LGD_BUYERID"                 id="LGD_BUYERID"			value="<?= $LGD_BUYERID ?>" />           	<!-- 구매자ID -->
	<input type='hidden' name='LGD_POINTUSE'				id='LGD_POINTUSE'			value='<?= $LGD_POINTUSE ?>'/>   			<!--승인시 사용될 포인트 사용여부 -->
	<input type="hidden" name="LGD_DOMAIN_URL"         		id="LGD_DOMAIN_URL"			value="xpayvvip" />	
	
	<!--LGD_RETURNURL  => 응답 수신 페이지 . -->
	<input type="hidden" name="LGD_RETURNURL"          	id="LGD_RETURNURL"				value="<?= $LGD_RETURNURL ?>">				<!-- 응답 수신 페이지 -->  
	<input type="hidden" name="LGD_CUSTOM_USABLEPAY"    id="LGD_CUSTOM_USABLEPAY"		value="<?= $LGD_CUSTOM_USABLEPAY ?>">		<!-- 디폴트 결제수단 --> 
	<input type="hidden" name="LGD_CUSTOM_SWITCHINGTYPE" id="LGD_CUSTOM_SWITCHINGTYPE"	value="<?= $LGD_CUSTOM_SWITCHINGTYPE ?>">	<!-- 신용카드 카드사 인증 페이지 연동 방식 -->  
	<input type="hidden" name="LGD_RESPCODE"          	id="LGD_RESPCODE"				value="<?= $LGD_RESPCODE ?>">				<!-- 응답 수신 코드 -->  
	<input type="hidden" name="LGD_RESPMSG"          	id="LGD_RESPMSG"				value="<?= $LGD_RESPMSG ?>">				<!-- 응답 수신 메시지 -->
	<input type='hidden' name='LGD_SELF_CUSTOM'			id='LGD_SELF_CUSTOM'    		value="<?= $LGD_SELF_CUSTOM ?>"/>       	<!--자체창 사용여부 -->
	
	<input type='hidden' name='LGD_NOINT'				id='LGD_NOINT'          		value="<?= $LGD_NOINT ?>"/>    				<!-- 상점부담무이자할부 적용여부 -->
	<input type='hidden' name='LGD_SP_CHAIN_CODE'		id='LGD_SP_CHAIN_CODE'        	value='<?= $LGD_SP_CHAIN_CODE ?>'/>   		<!--간편결제사용여부 -->
	<input type='hidden' name='LGD_SP_ORDER_USER_ID'	id='LGD_SP_ORDER_USER_ID'  		value='<?= $LGD_SP_ORDER_USER_ID ?>'/>   	<!--삼성카드 간편결제 쇼핑몰 KEY_ID -->
	<input type='hidden' name='LGD_CANCELURL'			id='LGD_CANCELURL'				value='<?= $LGD_CANCELURL ?>'/>          	<!--상점결과값 취소URL -->
	<input type='hidden' name='LGD_AUTHTYPE'			id='LGD_AUTHTYPE'				value='<?= $LGD_AUTHTYPE ?>'/>           	<!--인증유형 -->
	<input type='hidden' name='LGD_CURRENCY'			id='LGD_CURRENCY'		value='<?= $LGD_CURRENCY ?>'/>          <!-- -->
	<input type='hidden' name='KVP_CURRENCY'			id='KVP_CURRENCY'		value='<?= $KVP_CURRENCY ?>'/>          <!--통화코드 (won) -->
	<input type='hidden' name='KVP_OACERT_INF'			id='KVP_OACERT_INF'		value='<?= $KVP_OACERT_INF ?>'/>     	<!-- -->
	<input type='hidden' name='KVP_RESERVED1'			id='KVP_RESERVED1'		value='<?= $KVP_RESERVED1 ?>'/>         <!-- -->
	<input type='hidden' name='KVP_RESERVED2'			id='KVP_RESERVED2'		value='<?= $KVP_RESERVED2 ?>'/>         <!-- -->
	<input type='hidden' name='KVP_RESERVED3'			id='KVP_RESERVED3'		value='<?= $KVP_RESERVED3 ?>'/>         <!-- -->
	<input type='hidden' name='LGD_KVPISP_USER'			id='LGD_KVPISP_USER'	value='<?= $LGD_KVPISP_USER ?>'/>    	<!--Speed ISP USER정보 -->
	<input type='hidden' name='LGD_EXPMON'				id='LGD_EXPMON'			value='<?= $LGD_EXPMON ?>'/>            <!--카드유효기간 (월) -->
	<input type='hidden' name='LGD_CARDTYPE'			id='LGD_CARDTYPE'		value='<?= $LGD_CARDTYPE ?>'/>           
	<input type='hidden' name='LGD_PAN'					id='LGD_PAN'			value='<?= $LGD_PAN ?>'/>               <!--신용카드번호 -->
	<input type='hidden' name='LGD_EXPYEAR'				id='LGD_EXPYEAR'		value='<?= $LGD_EXPYEAR ?>'/>           <!-- 카드유효기간 (년)-->
	<input type='hidden' name='LGD_INSTALL'				id='LGD_INSTALL'		value='<?= $LGD_INSTALL ?>'/>           <!--안심클릭 할부 개월 -->
	<input type='hidden' name='KVP_QUOTA'				id='KVP_QUOTA'  		value='<?= $KVP_QUOTA ?>'/>             <!--할부개월 -->
	<input type='hidden' name='KVP_NOINT'				id='KVP_NOINT'			value='<?= $KVP_NOINT ?>'/>             <!--상점부담무이자할부 적용여부-->
	<input type='hidden' name='KVP_PRICE'				id='KVP_PRICE'			value='<?= $KVP_PRICE ?>'/>             <!--결제금액 -->
	<input type='hidden' name='KVP_CONAME'				id='KVP_CONAME'			value='<?= $KVP_CONAME ?>'/>            <!--ISP 서비스명 “안전결제서비스” -->
	<input type='hidden' name='KVP_CARDCODE'			id='KVP_CARDCODE'		value='<?= $KVP_CARDCODE ?>'/>          <!--ISP 카드코드 -->
	<input type='hidden' name='KVP_SESSIONKEY'			id='KVP_SESSIONKEY'		value='<?= $KVP_SESSIONKEY ?>'/>        <!--ISP 세션키-->
	<input type='hidden' name='KVP_CARDCOMPANY'			id='KVP_CARDCOMPANY'	value='<?= $KVP_CARDCOMPANY ?>'/>    	<!--카드사 코드 -->
	<input type='hidden' name='KVP_ENCDATA'				id='KVP_ENCDATA'		value='<?= $KVP_ENCDATA ?>'/>           <!--ISP 암호화데이터  -->
	<input type='hidden' name='KVP_PGID'				id='KVP_PGID'			value='<?= $KVP_PGID ?>'/>              <!--PG사 ID -->
	<input type='hidden' name='KVP_GOODNAME'			id='KVP_GOODNAME'		value='<?= $KVP_GOODNAME ?>'/>          <!--상품명 -->
	<input type='hidden' name='VBV_CAVV'				id='VBV_CAVV'			value='<?= $VBV_CAVV ?>'/>              <!--안심클릭 CAVV -->
	<input type='hidden' name='VBV_ECI'					id='VBV_ECI'			value='<?= $VBV_ECI ?>'/>               <!--안심클릭 ECI  -->
	<input type='hidden' name='VBV_JOINCODE'			id='VBV_JOINCODE'		value='<?= $VBV_JOINCODE ?>'/>          <!--안심클릭 JOINCODE -->
	<input type='hidden' name='VBV_XID'					id='VBV_XID'			value='<?= $VBV_XID ?>'/>               <!--안심클릭 XID  -->
	<input type='hidden' name='LGD_LANGUAGE'			id='LGD_LANGUAGE'		value='<?= $LGD_LANGUAGE ?>'/>          <!-- 결제창내 표시할 언어     -->
	<input type="hidden" id="LGD_ENCODING"				name="LGD_ENCODING"				value="UTF-8"/>
	<input type="hidden" id="LGD_ENCODING_RETURNURL"	name="LGD_ENCODING_RETURNURL"	value="UTF-8"/>
</form>
</body>
</html>

