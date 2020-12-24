<?php

	/*
	 * [본인확인 인증결과처리 페이지]
	 * 매뉴얼 "4. 본인확인 서비스 이용을 위한 개발"의 "단계 5. 본인확인 최종 인증" 참조
	 *
	 * 토스페이먼츠으로 부터 내려받은 LGD_AUTHONLYKEY(인증Key)를 가지고 최종 인증요청.(파라미터 전달시 POST를 사용하세요)
	 */
	
	/* ※ 중요
	 * 환경설정 파일의 경우 반드시 외부에서 접근이 가능한 경로에 두시면 안됩니다.
	 * 해당 환경파일이 외부에 노출이 되는 경우 해킹의 위험이 존재하므로 반드시 외부에서 접근이 불가능한 경로에 두시기 바랍니다.
	 * 예) [Window 계열] C:\inetpub\wwwroot\lgdacom ==> 절대불가(웹 디렉토리)
	 */

	$configPath = "C:/lgdacom"; //토스페이먼츠에서 제공한 환경파일("/conf/lgdacom.conf,/conf/mall.conf") 위치 지정.
	
	/*
	 *************************************************
	 * 1.최종인증 요청 - BEGIN
	 *************************************************
	 */
	$CST_PLATFORM               = $_POST["CST_PLATFORM"];
	$CST_MID                    = $_POST["CST_MID"];
	$LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;
	
	$LGD_AUTHONLYKEY            = $_POST["LGD_AUTHONLYKEY"];            //토스페이먼츠으로부터 부여받은 인증키	
	$LGD_PAYTYPE    			= $_POST["LGD_PAYTYPE"];                //인증요청타입 (신용카드:ASC001, 휴대폰 대체인증:ASC007, 계좌:ASC004)
	
	
	// PHP용 XpayClient 모듈
	require_once("C:/lgdacom/XPayClient.php");

	// (1) XpayClient의 사용을 위한 xpay 객체 생성
	// (2) Init: XPayClient 초기화(환경설정 파일 로드) 
	// configPath: 설정파일
	// CST_PLATFORM: - test, service 값에 따라 lgdacom.conf의 test_url(test) 또는 url(srvice) 사용
	//				- test, service 값에 따라 테스트용 또는 서비스용 아이디 생성
	$xpay = new XPayClient($configPath, $CST_PLATFORM);

	// (3) Init_TX: 메모리에 mall.conf, lgdacom.conf 할당 및 트랜잭션의 고유한 키 TXID 생성
	if (!$xpay->Init_TX($LGD_MID)) {
    	echo "토스페이먼츠에서 제공한 환경파일이 정상적으로 설치 되었는지 확인하시기 바랍니다.<br/>";
    	echo "mall.conf에는 Mert Id = Mert Key 가 반드시 등록되어 있어야 합니다.<br/><br/>";
    	echo "문의전화 토스페이먼츠 1544-7772<br/>";
    	exit;
    }
	$xpay->Set("LGD_TXNAME"		, "AuthOnlyByKey");
	$xpay->Set("LGD_AUTHONLYKEY", $LGD_AUTHONLYKEY);
	$xpay->Set("LGD_PAYTYPE"	, $LGD_PAYTYPE);
	
	
	/*
	 *************************************************
	 * 1.최종인증 요청(수정하지 마세요) - END
	 *************************************************
	 */
	
	/*
	 * 2. 최종 인증요청 결과처리
	 *
	 * 최종 인증요청 결과 리턴 파라미터는 연동매뉴얼을 참고하시기 바랍니다.
	 */
	// (4) TX: lgdacom.conf에 설정된 URL로 소켓 통신하여 최종 인증요청, 결과값으로 true, false 리턴
	if ($xpay->TX()) {
		//1)인증결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
		echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
		
		// 인증요청 결과 데이터 출력	
		$keys = $xpay->Response_Names();
		foreach($keys as $name) {
			echo $name . " = " . $xpay->Response($name, 0) . "<br>";
		}
		
		echo "<p>";
		
		// (5) DB에 인증요청 결과 처리
		if( "0000" == $xpay->Response_Code() ) {
			//인증요청 결과 성공 DB처리
			echo "인증요청 결과 성공 DB처리하시기 바랍니다.<br>";
			
		}else{
			
			//인증요청 결과 실패 DB처리
			echo "인증요청 결과 실패 DB처리하시기 바랍니다.<br>";
			
		}//end if
		
	}else{
		//2)API 요청실패 화면처리
		echo "인증요청이 실패하였습니다.  <br>";
		echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
		
        //최종인증요청 결과 실패 DB처리
        echo "최종인증요청 결과 실패 DB처리하시기 바랍니다.<br>";
	}//end if
?>