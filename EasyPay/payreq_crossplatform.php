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
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];                       //토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];                            //상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                                //테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)
    $LGD_OID                    = $_POST["LGD_OID"];                            //주문번호(상점정의 유니크한 주문번호를 입력하세요)
    $LGD_AMOUNT                 = $_POST["LGD_AMOUNT"];                         //결제금액("," 를 제외한 결제금액을 입력하세요)
    $LGD_BUYER            		= $_POST["LGD_BUYER"];                    		//구매자명
    $LGD_PRODUCTINFO      		= $_POST["LGD_PRODUCTINFO"];              		//상품명
    $LGD_BUYEREMAIL       		= $_POST["LGD_BUYEREMAIL"];               		//구매자 이메일
	$LGD_BUYERPHONE       		= $_POST["LGD_BUYERPHONE"]; 
    $LGD_TIMESTAMP              = date("YmdHis");                                 //타임스탬프
    $LGD_CUSTOM_SKIN            = "red";                                        //상점정의 결제창 스킨
    $LGD_CUSTOM_USABLEPAY       = $_POST["LGD_CUSTOM_USABLEPAY"];        	    //디폴트 결제수단 (해당 필드를 보내지 않으면 결제수단 선택 UI 가 노출됩니다.)
    $LGD_WINDOW_VER		        = "2.5";										//결제창 버젼정보
    $LGD_WINDOW_TYPE            = $_POST["LGD_WINDOW_TYPE"];      				//결제창 호출방식 (수정불가)
    $LGD_CUSTOM_SWITCHINGTYPE   = $_POST["LGD_CUSTOM_SWITCHINGTYPE"];           //신용카드 카드사 인증 페이지 연동 방식 (수정불가)
	$configPath 				= "C:/lgdacom"; 						        //토스페이먼츠에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정. 	    
    $LGD_CUSTOM_PROCESSTYPE     = "TWOTR";                                      //수정불가
    $LGD_BUYERID          		= $_POST["LGD_BUYERID"];       					//구매자 아이디
    $LGD_BUYERIP          		= $_POST["LGD_BUYERIP"];       					//구매자IP
	$LGD_DISPLAY_BUYEREMAIL   	= $_POST["LGD_DISPLAY_BUYEREMAIL"];				//구매자이메일
	$LGD_EASYPAY_ONLY			= "PAYNOW";										//페이나우사용여부(값:PAYNOW, 수정불가]
	$LGD_ONEPAY_VIEW_VERSION    = "02";											//페이나우 창 크기 설정
	$LGD_USABLECARD				= $_POST["LGD_USABLECARD"];						//사용을 원하는 카드사만 표시
	$LGD_CASHRECEIPTYN			= $_POST["LGD_CASHRECEIPTYN"];					//현금영수증발급 사용여부

	if(PHP_OS === "Linux"){
		$configPath             = "/lgdacom";
	}
	
    /*
     * 가상계좌(무통장) 결제 연동을 하시는 경우 아래 LGD_CASNOTEURL 을 설정하여 주시기 바랍니다. 
     */    
    $LGD_CASNOTEURL				= "https://" . $server_domain . "/XPayClient/cas_noteurl.php";    

    /*
     * LGD_RETURNURL 을 설정하여 주시기 바랍니다. 반드시 현재 페이지와 동일한 프로트콜 및  호스트이어야 합니다. 아래 부분을 반드시 수정하십시요.
     */    
    $LGD_RETURNURL				= "https://" . $server_domain . "/EasyPay/returnurl.php";  
	
	
	
    
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

    $payReqMap['CST_PLATFORM']           = $CST_PLATFORM;				// 테스트, 서비스 구분
    $payReqMap['LGD_WINDOW_TYPE']        = $LGD_WINDOW_TYPE;			// 수정불가
    $payReqMap['CST_MID']                = $CST_MID;					// 상점아이디
    $payReqMap['LGD_MID']                = $LGD_MID;					// 상점아이디
    $payReqMap['LGD_OID']                = $LGD_OID;					// 주문번호
    $payReqMap['LGD_BUYER']              = $LGD_BUYER;            		// 구매자
    $payReqMap['LGD_PRODUCTINFO']        = $LGD_PRODUCTINFO;     		// 상품정보
    $payReqMap['LGD_AMOUNT']             = $LGD_AMOUNT;					// 결제금액
    $payReqMap['LGD_BUYEREMAIL']         = $LGD_BUYEREMAIL;				// 구매자 이메일
    $payReqMap['LGD_CUSTOM_SKIN']        = $LGD_CUSTOM_SKIN;			// 결제창 SKIN
    $payReqMap['LGD_CUSTOM_PROCESSTYPE'] = $LGD_CUSTOM_PROCESSTYPE;		// 트랜잭션 처리방식
    $payReqMap['LGD_TIMESTAMP']          = $LGD_TIMESTAMP;				// 타임스탬프
    $payReqMap['LGD_HASHDATA']           = $LGD_HASHDATA;				// MD5 해쉬암호값
    $payReqMap['LGD_RETURNURL']   		 = $LGD_RETURNURL;      		// 응답수신페이지
    $payReqMap['LGD_VERSION']         	 = "PHP_Non-ActiveX_Paynow";	// 사용타입 정보(수정 및 삭제 금지): 이 정보를 근거로 어떤 서비스를 사용하는지 판단할 수 있습니다.
    $payReqMap['LGD_CUSTOM_USABLEPAY']  	= $LGD_CUSTOM_USABLEPAY;		// 디폴트 결제수단
	$payReqMap['LGD_CUSTOM_SWITCHINGTYPE']  = $LGD_CUSTOM_SWITCHINGTYPE;	// 신용카드 카드사 인증 페이지 연동 방식
	$payReqMap['LGD_WINDOW_VER']			= $LGD_WINDOW_VER;
	$payReqMap['LGD_DOMAIN_URL'] 		 	= "xpayvvip";	
	
	$payReqMap['LGD_BUYERID']  				= $LGD_BUYERID;
	$payReqMap['LGD_BUYERIP']  				= $LGD_BUYERIP;
	$payReqMap['LGD_DISPLAY_BUYEREMAIL']  	= $LGD_DISPLAY_BUYEREMAIL;
	$payReqMap['LGD_EASYPAY_ONLY']  		= $LGD_EASYPAY_ONLY;			//PAYNOW
	$payReqMap['LGD_ONEPAY_VIEW_VERSION']  	= $LGD_ONEPAY_VIEW_VERSION;			//PAYNOW
	$payReqMap['LGD_USABLECARD']  			= $LGD_USABLECARD;
	$payReqMap['LGD_CASHRECEIPTYN']  		= $LGD_CASHRECEIPTYN;
	
	
    $payReqMap['LGD_ENCODING'] 			        = "UTF-8";
	$payReqMap['LGD_ENCODING_RETURNURL'] 		= "UTF-8";
	$payReqMap['LGD_ENCODING_NOTEURL'] 			= "UTF-8";
	
    
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
<script language="javascript" src="https://xpay.tosspayments.com/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
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
<?php
  foreach ($payReqMap as $key => $value) {
    echo "<input type='hidden' name='$key' id='$key' value='$value'>";
  }
  var_dump($_SESSION);
?>
</form>
</body>
</html>

