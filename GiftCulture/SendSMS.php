<?php
    /*
     * [문화상품권 인증번호 SMS 발송 페이지]
     *
     * 파라미터 전달시 POST를 사용하세요
     */
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];						//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];							//상점아이디(토스페이먼츠로 부터 발급받으신 상점아이디를 입력하세요)
																				//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)    
    
	$LGD_TID					= $_POST["LGD_TID"];							//토스페이먼츠 거래번호
	$LGD_CULTID             	= $_POST["LGD_CULTID"];							//휴대폰번호
	
	$configPath 				= "C:/lgdacom"; 						 		//토스페이먼츠에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정.   
	
	if(PHP_OS === "Linux"){
		$configPath             = "/lgdacom";
	}
    
	require_once($configPath . "/XPayClient.php");
    $xpay = new XPayClient($configPath, $CST_PLATFORM);
    if (!$xpay->Init_TX($LGD_MID)) {
    	echo "토스페이먼츠에서 제공한 환경파일이 정상적으로 설치 되었는지 확인하시기 바랍니다.<br/>";
    	echo "mall.conf에는 Mert Id = Mert Key 가 반드시 등록되어 있어야 합니다.<br/><br/>";
    	echo "문의전화 토스페이먼츠 1544-7772<br/>";
    	exit;
    }
    $xpay->Set("LGD_TXNAME", "GiftCulture");
    $xpay->Set("LGD_METHOD", "SMS");
    $xpay->Set("LGD_TID", $LGD_TID);
	$xpay->Set("LGD_CULTID", $LGD_CULTID);
    
    /*
     * SMS 발송요청 결과
     *
     */
    if ($xpay->TX()) {
        echo "인증번호 발송요청 성공<br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
    }else {
        echo "인증번호 발송요청 실패  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
    }
	
	if ($xpay->Response_Code() == '0000') {
?>
		<script type="text/javascript">
			alert("인증번호 SMS 발송 성공");
		</script>
<?php
}
?>

