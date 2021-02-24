<?php
    /*
     * [현금영수증 사용 가맹 등록/조회 요청 페이지]
     *
     * 파라미터 전달시 POST를 사용하세요
     */
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];						//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];							//상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                         		//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)    
    $LGD_TID                	= $_POST["LGD_TID"];							//토스페이먼츠으로 부터 내려받은 거래번호(LGD_TID)
    
	$LGD_METHOD   		    	= $_POST["LGD_METHOD"];							//메소드('REG_REQUEST':등록요청, 'REG_RESULT' 등록결과확인)
	$LGD_REG_BUSINESSNUM 		= $_POST["LGD_REG_BUSINESSNUM"];				//현금영수증 가맹 사업자 등록번호
    $LGD_REG_MERTNAME 			= $_POST["LGD_REG_MERTNAME"];					//현금영수증 가맹 사업자명
	$LGD_REG_MERTPHONE 			= $_POST["LGD_REG_MERTPHONE"];					//현금영수증 가맹 사업자 전화번호
    $LGD_REG_CEONAME 			= $_POST["LGD_REG_CEONAME"];					//현금영수증 가맹 사업자 대표자명
	$LGD_REG_MERTADDRESS 		= $_POST["LGD_REG_MERTADDRESS"];				//현금영수증 가맹 사업장주소
 
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
    $xpay->Set("LGD_TXNAME", "CashReceipt");
    $xpay->Set("LGD_METHOD", $LGD_METHOD);
    $xpay->Set("LGD_REG_BUSINESSNUM", $LGD_REG_BUSINESSNUM);
    $xpay->Set("LGD_REG_MERTNAME", $LGD_REG_MERTNAME);
    $xpay->Set("LGD_REG_MERTPHONE", $LGD_REG_MERTPHONE);
    $xpay->Set("LGD_REG_CEONAME", $LGD_REG_CEONAME);
    $xpay->Set("LGD_REG_MERTADDRESS", $LGD_REG_MERTADDRESS);
    $xpay->Set("LGD_ENCODING", "UTF-8");

       
    /*
     * 1. 현금영수증 사용 가맹 등록/조회 요청 결과처리
     *
     * 결과 리턴 파라미터는 연동메뉴얼을 참고하시기 바랍니다.
     */
    if ($xpay->TX()) {
        //1)현금영수증 사업자 등록요청/결과확인  화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
        echo "현금영수증 가맹 사업자 등록요청/결과확인 요청처리가 완료되었습니다. <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
        
        echo "결과코드 : " . $xpay->Response("LGD_RESPCODE",0) . "<br>";
        echo "결과메세지 : " . $xpay->Response("LGD_RESPMSG",0) . "<br>";
        echo "사업자 번호 : " . $xpay->Response("LGD_REG_BUSINESSNUM",0) . "<br>";
        echo "사업자명 : " . $xpay->Response("LGD_REG_MERTNAME",0) . "<br>";
        echo "사업자전화번호 : " . $xpay->Response("LGD_REG_MERTPHONE",0) . "<br>";
        echo "대표자명 : " . $xpay->Response("LGD_REG_CEONAME",0) . "<br>";
        echo "사업장 주소 : " . $xpay->Response("LGD_REG_MERTADDRESS",0) . "<br>";
        echo "등록요청일자 : " . $xpay->Response("LGD_REG_REQDATE",0) . "<p>";
  
    }else {
        //2)API 요청 실패 화면처리
        echo "현금영수증 가맹 사업자 등록요청/결과확인 요청처리가 실패되었습니다. <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
    }
?>