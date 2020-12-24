<?php
//session_start();
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
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];                        //토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];                             //상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                                 //테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;   //상점아이디(자동생성)
    $LGD_OID                    = $_POST["LGD_OID"];                             //주문번호(상점정의 유니크한 주문번호를 입력하세요)
    $LGD_AMOUNT                 = $_POST["LGD_AMOUNT"];                          //결제금액("," 를 제외한 결제금액을 입력하세요)
    $LGD_BUYER                  = $_POST["LGD_BUYER"];                           //구매자명
    $LGD_PRODUCTINFO            = $_POST["LGD_PRODUCTINFO"];                     //상품명
    $LGD_BUYEREMAIL             = $_POST["LGD_BUYEREMAIL"];                      //구매자 이메일
    $LGD_OSTYPE_CHECK           = "P";                                           //값 P: XPay 실행(PC 결제 모듈): PC용과 모바일용 모듈은 파라미터 및 프로세스가 다르므로 PC용은 PC 웹브라우저에서 실행 필요. 
																				 //"P", "M" 외의 문자(Null, "" 포함)는 모바일 또는 PC 여부를 체크하지 않음
    //$LGD_ACTIVEXYN			= "N";											 //계좌이체 결제시 사용, ActiveX 사용 여부로 "N" 이외의 값: ActiveX 환경에서 계좌이체 결제 진행(IE)
																				 
    $LGD_CUSTOM_SKIN            = "red";                                         //상점정의 결제창 스킨
    $LGD_CUSTOM_USABLEPAY       = $_POST["LGD_CUSTOM_USABLEPAY"];        	     //디폴트 결제수단 (해당 필드를 보내지 않으면 결제수단 선택 UI 가 노출됩니다.)
    $LGD_WINDOW_VER		        = "2.5";										 //결제창 버젼정보
    $LGD_WINDOW_TYPE            = $_POST["LGD_WINDOW_TYPE"];					 //결제창 호출방식 (수정불가)
    $LGD_CUSTOM_SWITCHINGTYPE   = $_POST["LGD_CUSTOM_SWITCHINGTYPE"];            //신용카드 카드사 인증 페이지 연동 방식 (수정불가)  
    $LGD_CUSTOM_PROCESSTYPE     = "TWOTR";                                       //수정불가

    /*
     * 가상계좌(무통장) 결제 연동을 하시는 경우 아래 LGD_CASNOTEURL 을 설정하여 주시기 바랍니다. 
     */    
    $LGD_CASNOTEURL				= "https://localhost:9443/NoSession/cas_noteurl.php";    

    /*
     * LGD_RETURNURL 을 설정하여 주시기 바랍니다. 반드시 현재 페이지와 동일한 프로트콜 및  호스트이어야 합니다. 아래 부분을 반드시 수정하십시요.
     */    
    $LGD_RETURNURL				= "https://localhost:9443/NoSession/returnurl.php";  


    $configPath                 = "C:/lgdacom";                                  //토스페이먼츠에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정.     
	
	
	
    
    /*
     *************************************************
     * 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
     * 
     * MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
     *************************************************
     */
    require_once("C:/lgdacom/XPayClient.php");
    $xpay = new XPayClient($configPath, $CST_PLATFORM);
   	$xpay->Init_TX($LGD_MID);
	$LGD_TIMESTAMP = $xpay->GetTimeStamp(); 
    $LGD_HASHDATA = $xpay->GetHashData($LGD_MID,$LGD_OID,$LGD_AMOUNT,$LGD_TIMESTAMP);
    
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
<title>토스페이먼츠 eCredit서비스 결제테스트</title>
<!-- test -->
<script language="javascript" src="https://pretest.tosspayments.com:9443/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
<!-- 
  service  
<script language="javascript" src="https://xpayvvip.tosspayments.com/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
 -->

<script type="text/javascript">

/*
* 수정불가.
*/
	var LGD_window_type = '<?= $LGD_WINDOW_TYPE ?>';
	
/*
* 수정불가
*/
function launchCrossPlatform(){
	lgdwin = openXpay(document.getElementById('LGD_PAYINFO'), '<?= $CST_PLATFORM ?>', LGD_window_type, null, "", "");
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
		
			document.getElementById("LGD_PAYKEY").value = fDoc.document.getElementById('LGD_PAYKEY').value;
			document.getElementById("LGD_PAYINFO").target = "_self";
			document.getElementById("LGD_PAYINFO").action = "payres.php";
			document.getElementById("LGD_PAYINFO").submit();
	} else {
		alert("LGD_RESPCODE (결과코드) : " + fDoc.document.getElementById('LGD_RESPCODE').value + "\n" + "LGD_RESPMSG (결과메시지): " + fDoc.document.getElementById('LGD_RESPMSG').value);
		closeIframe();
	}
}

</script>
</head>
<body>
<form method="post" name="LGD_PAYINFO" id="LGD_PAYINFO" action="payres.php">
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

<input type="hidden" id="CST_PLATFORM"				name="CST_PLATFORM"				value="<?=$CST_PLATFORM ?>"/>
<input type="hidden" id="CST_MID"					name="CST_MID"					value="<?=$CST_MID ?>"/>
<input type="hidden" id="LGD_WINDOW_TYPE"			name="LGD_WINDOW_TYPE"			value="<?=$LGD_WINDOW_TYPE ?>"/>
<input type="hidden" id="LGD_MID"					name="LGD_MID"					value="<?=$LGD_MID ?>"/>
<input type="hidden" id="LGD_OID"					name="LGD_OID"					value="<?=$LGD_OID ?>"/>
<input type="hidden" id="LGD_BUYER"					name="LGD_BUYER"				value="<?=$LGD_BUYER ?>"/>
<input type="hidden" id="LGD_PRODUCTINFO"			name="LGD_PRODUCTINFO"			value="<?=$LGD_PRODUCTINFO ?>"/>
<input type="hidden" id="LGD_AMOUNT"				name="LGD_AMOUNT"				value="<?=$LGD_AMOUNT ?>"/>
<input type="hidden" id="LGD_BUYEREMAIL"			name="LGD_BUYEREMAIL"			value="<?=$LGD_BUYEREMAIL ?>"/>
<input type="hidden" id="LGD_CUSTOM_SKIN"			name="LGD_CUSTOM_SKIN"			value="<?=$LGD_CUSTOM_SKIN ?>"/>
<input type="hidden" id="LGD_CUSTOM_PROCESSTYPE"	name="LGD_CUSTOM_PROCESSTYPE"	value="<?=$LGD_CUSTOM_PROCESSTYPE ?>"/>
<input type="hidden" id="LGD_TIMESTAMP"				name="LGD_TIMESTAMP"			value="<?=$LGD_TIMESTAMP ?>"/>
<input type="hidden" id="LGD_HASHDATA"				name="LGD_HASHDATA"				value="<?=$LGD_HASHDATA ?>"/>
<input type="hidden" id="LGD_RETURNURL"				name="LGD_RETURNURL"			value="<?=$LGD_RETURNURL ?>"/>
<input type="hidden" id="LGD_CUSTOM_USABLEPAY"		name="LGD_CUSTOM_USABLEPAY"		value="<?=$LGD_CUSTOM_USABLEPAY ?>"/>
<input type="hidden" id="LGD_CUSTOM_SWITCHINGTYPE"	name="LGD_CUSTOM_SWITCHINGTYPE" value="<?=$LGD_CUSTOM_SWITCHINGTYPE ?>"/>
<input type="hidden" id="LGD_WINDOW_VER"			name="LGD_WINDOW_VER"			value="<?=$LGD_WINDOW_VER ?>"/>
<input type="hidden" id="LGD_OSTYPE_CHECK"			name="LGD_OSTYPE_CHECK"			value="<?=$LGD_OSTYPE_CHECK ?>"/>
<!--
결제요청시 “LGD_RETURN_MERT_CUSTOM_PARAM” = “Y”일 경우 사용자정의 값이 retunurl 로 그대로 리턴
*주의사항
사용자정의 파라미터는 LGD_ 로 시작될 수 없음.

<input type="hidden" id="LGD_RETURN_MERT_CUSTOM_PARAM"	name="LGD_RETURN_MERT_CUSTOM_PARAM"	value="Y” />
<input type="hidden" id="CUSTOM_PARAMETER1"	name="CUSTOM_PARAMETER1"	value="상점정의 파라미터 값 1번입니다" />
<input type="hidden" id="CUSTOM_PARAMETER2"	name="CUSTOM_PARAMETER2"	value="상점정의 파라미터 값 2번입니다” />
-->
<!-- 
<input type="hidden" id="LGD_ACTIVEXYN"				name="LGD_ACTIVEXYN"			value="<?=$LGD_ACTIVEXYN ?>"/>
-->
<input type="hidden" id="LGD_VERSION"				name="LGD_VERSION"				value="PHP_Non-ActiveX_Standard"/>
<input type="hidden" id="LGD_DOMAIN_URL"			name="LGD_DOMAIN_URL"			value="xpayvvip"/>
<input type="hidden" id="LGD_CASNOTEURL"			name="LGD_CASNOTEURL"			value="<?=$LGD_CASNOTEURL ?>"/>
<input type="hidden" id="LGD_ENCODING"				name="LGD_ENCODING"				value="UTF-8"/>
<input type="hidden" id="LGD_ENCODING_RETURNURL"	name="LGD_ENCODING_RETURNURL"	value="UTF-8"/>
<input type="hidden" id="LGD_ENCODING_NOTEURL"		name="LGD_ENCODING_NOTEURL"		value="UTF-8"/>
<input type="hidden" id="LGD_RESPCODE"				name="LGD_RESPCODE"				value=""/>
<input type="hidden" id="LGD_RESPMSG"				name="LGD_RESPMSG"				value=""/>
<input type="hidden" id="LGD_PAYKEY"				name="LGD_PAYKEY"				value=""/>

</form>
</body>
</html>

