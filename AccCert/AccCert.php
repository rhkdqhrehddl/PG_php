<?php
    /*
     * [계좌본인인증 요청 페이지]
     *
     * 파라미터 전달시 POST를 사용하세요.
     */
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];						//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];							//상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                         		//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)    
    $LGD_GUBUN		            = $_POST["LGD_GUBUN"];							//거래구분
    $LGD_BANKCODE	            = $_POST["LGD_BANKCODE"];						//은행코드
    $LGD_ACCOUNTNO	            = $_POST["LGD_ACCOUNTNO"];						//계좌번호 
	$LGD_NAME	       			= $_POST["LGD_NAME"];							//성명
    $LGD_PRIVATENO	            = $_POST["LGD_PRIVATENO"];						//생년월일 6자리 (YYMMDD) or 사업자번호 10자리

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

    $xpay->Set("LGD_TXNAME", "AccCert");
    $xpay->Set("LGD_GUBUN", $LGD_GUBUN);
    $xpay->Set("LGD_BANKCODE", $LGD_BANKCODE);
    $xpay->Set("LGD_ACCOUNTNO", $LGD_ACCOUNTNO);
    $xpay->Set("LGD_NAME", $LGD_NAME);
    $xpay->Set("LGD_PRIVATENO", $LGD_PRIVATENO);
    $xpay->Set("LGD_BUYERIP", $_SERVER["REMOTE_ADDR"]);
       
    if ($xpay->TX()) {
        //1)인증결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
        echo "인증요청이 완료되었습니다.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
        
        //아래는 인증 결과 파라미터를 모두 찍어 줍니다.
        $keys = $xpay->Response_Names();
            foreach($keys as $name) {
                echo $name . " = " . $xpay->Response($name, 0) . "<br>";
			}
        echo "<p>";
 
    }else {
        //2)API 요청실패 화면처리
        echo "인증요청이 실패하였습니다.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
            
        //인증요청 결과 실패 DB처리
        echo "인증요청 결과 실패 DB처리하시기 바랍니다.<br>";            	                        
   }
?>
