<?php
    /*
     * [최종결제요청 페이지(STEP2-2)]
     *
     * 토스페이먼츠으로 부터 내려받은 LGD_PAYKEY(인증Key)를 가지고 최종 결제요청.(파라미터 전달시 POST를 사용하세요)
     */
	
	/* ※ 중요
	* 환경설정 파일의 경우 반드시 외부에서 접근이 가능한 경로에 두시면 안됩니다.
	* 해당 환경파일이 외부에 노출이 되는 경우 해킹의 위험이 존재하므로 반드시 외부에서 접근이 불가능한 경로에 두시기 바랍니다. 
	* 예) [Window 계열] C:\inetpub\wwwroot\lgdacom ==> 절대불가(웹 디렉토리)
	*/
	
	$configPath = "C:/lgdacom"; //토스페이먼츠에서 제공한 환경파일("/conf/lgdacom.conf,/conf/mall.conf") 위치 지정. 

    /*
     *************************************************
     * 1.최종결제 요청 - BEGIN
     *  (단, 최종 금액체크를 원하시는 경우 금액체크 부분 주석을 제거 하시면 됩니다.)
     *************************************************
     */

	 
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];       		//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];            		//상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                         		//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)    
    $LGD_OID                	= $_POST["LGD_OID"];					//주문번호(상점정의 유니크한 주문번호를 입력하세요)
    $LGD_AMOUNT                	= $_POST["LGD_AMOUNT"];			 	//결제금액("," 를 제외한 결제금액을 입력하세요)
    $LGD_BUYER                	= $_POST["LGD_BUYER"];			 		//구매자명
    $LGD_BUYEREMAIL             = $_POST["LGD_BUYEREMAIL"];			//구매자 이메일
    $LGD_PRODUCTINFO            = $_POST["LGD_PRODUCTINFO"];			//상품명
    $LGD_AUTHTYPE               = $_POST["LGD_AUTHTYPE"];			 	//인증유형(ISP인경우만  'ISP')
    $LGD_CARDTYPE               = $_POST["LGD_CARDTYPE"];			 	//카드사코드
	$LGD_POINTUSE               = $_POST["LGD_POINTUSE"];			 	//카드포인트사용여부
    $LGD_CURRENCY				= $_POST["LGD_CURRENCY"];				//통화코드
    
    //안심클릭 또는 해외카드
    $LGD_PAN		            = $_POST["LGD_PAN"];	 				//카드번호
    $LGD_INSTALL                = $_POST["LGD_INSTALL"];	 			//할부개월수
    $LGD_NOINT                	= $_POST["LGD_NOINT"];	 				//무이자할부여부('1':상점부담무이자할부,'0':일반할부)
    $LGD_EXPYEAR	            = $_POST["LGD_EXPYEAR"]; 				//유효기간년 (YY)
    $LGD_EXPMON	                = $_POST["LGD_EXPMON"];		 		//유효기간월 (MM)
	$VBV_ECI	                = $_POST["VBV_ECI"];		 			//안심클릭ECI
	$VBV_CAVV	                = $_POST["VBV_CAVV"];		 			//안심클릭CAVV
	$VBV_XID	                = $_POST["VBV_XID"];		 			//안심클릭XID

	//ISP
	$KVP_QUOTA                	= $_POST["KVP_QUOTA"];	 				//할부개월수
    $KVP_NOINT	            	= $_POST["KVP_NOINT"]; 				//무이자할부여부('1':상점부담무이자할부,'0':일반할부)
    $KVP_CARDCODE	            = $_POST["KVP_CARDCODE"];		 		//ISP카드코드
	$KVP_SESSIONKEY	            = $_POST["KVP_SESSIONKEY"];		 	//ISP세션키
	$KVP_ENCDATA	            = $_POST["KVP_ENCDATA"];		 		//ISP암호화데이터

	echo "CST_PLATFORM = " . $CST_PLATFORM . "<br>";
    echo "CST_MID = " . $CST_MID . "<br>";
	echo "LGD_MID = " . $LGD_MID . "<br>";

	require_once("C:/lgdacom/XPayClient.php");
    $xpay = new XPayClient($configPath, $CST_PLATFORM);
    if (!$xpay->Init_TX($LGD_MID)) {
        echo "토스페이먼츠에서 제공한 환경파일이 정상적으로 설치 되었는지 확인하시기 바랍니다.<br/>";
        echo "mall.conf에는 Mert Id = Mert Key 가 반드시 등록되어 있어야 합니다.<br/><br/>";
        echo "문의전화 토스페이먼츠 1544-7772<br/>";
        exit;
    }     
    
	echo "결제요청을 시작합니다.  <br>";

    $xpay->Set("LGD_TXNAME", "CardAuth");
    $xpay->Set("LGD_OID", $LGD_OID);
    $xpay->Set("LGD_AMOUNT", $LGD_AMOUNT);
    $xpay->Set("LGD_BUYER", $LGD_BUYER);
    $xpay->Set("LGD_PRODUCTINFO", $LGD_PRODUCTINFO);
    $xpay->Set("LGD_BUYEREMAIL", $LGD_BUYEREMAIL);
    $xpay->Set("LGD_AUTHTYPE", $LGD_AUTHTYPE);
	$xpay->Set("LGD_CARDTYPE", $LGD_CARDTYPE);
	$xpay->Set("LGD_POINTUSE", $LGD_POINTUSE);
    $xpay->Set("LGD_BUYERIP", $_SERVER["REMOTE_ADDR"]);
    $xpay->Set("LGD_CURRENCY", $LGD_CURRENCY);
    
  	if ($LGD_AUTHTYPE == "ISP"){    	 			//ISP 결제
		$xpay->Set("KVP_QUOTA", $KVP_QUOTA);
    	$xpay->Set("KVP_NOINT", $KVP_NOINT);
    	$xpay->Set("KVP_CARDCODE", $KVP_CARDCODE);
    	$xpay->Set("KVP_SESSIONKEY", $KVP_SESSIONKEY);
    	$xpay->Set("KVP_ENCDATA", $KVP_ENCDATA);
    	
	} else {									//안심클릭 또는 해외카드
		echo "안심클릭 시작.  <br>";
		$xpay->Set("LGD_PAN", $LGD_PAN);
    	$xpay->Set("LGD_INSTALL", $LGD_INSTALL);
    	$xpay->Set("LGD_NOINT", $LGD_NOINT);
		$xpay->Set("LGD_EXPYEAR", $LGD_EXPYEAR);
   		$xpay->Set("LGD_EXPMON", $LGD_EXPMON);
    	$xpay->Set("VBV_ECI", $VBV_ECI);
    	$xpay->Set("VBV_CAVV", $VBV_CAVV);
    	$xpay->Set("VBV_XID", $VBV_XID);
	}

    /*
     *************************************************
     * 1.최종결제 요청(수정하지 마세요) - END
     *************************************************
     */

    /*
     * 2. 최종결제 요청 결과처리
     *
     * 최종 결제요청 결과 리턴 파라미터는 연동메뉴얼을 참고하시기 바랍니다.
     */
    if ($xpay->TX()) {
        //1)결제결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
        echo "결제요청이 완료되었습니다.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
            
        echo "거래번호 : " . $xpay->Response("LGD_TID",0) . "<br>";
        echo "상점아이디 : " . $xpay->Response("LGD_MID",0) . "<br>";
        echo "상점주문번호 : " . $xpay->Response("LGD_OID",0) . "<br>";
        echo "결제금액 : " . $xpay->Response("LGD_AMOUNT",0) . "<br>";
        echo "결과코드 : " . $xpay->Response("LGD_RESPCODE",0) . "<br>";
        echo "결과메세지 : " . $xpay->Response("LGD_RESPMSG",0) . "<p>";
            
        $keys = $xpay->Response_Names();
        foreach($keys as $name) {
            echo $name . " = " . $xpay->Response($name, 0) . "<br>";
        }
          
        echo "<p>";
           
        if( "0000" == $xpay->Response_Code() ) {
         	//최종결제요청 결과 성공 DB처리
           	echo "최종결제요청 결과 성공 DB처리하시기 바랍니다.<br>";

            //최종결제요청 결과 성공 DB처리 실패시 Rollback 처리
          	$isDBOK = true; //DB처리 실패시 false로 변경해 주세요.
          	if( !$isDBOK ) {
           		echo "<p>";
           		$xpay->Rollback("상점 DB처리 실패로 인하여 Rollback 처리 [TID:" . $xpay->Response("LGD_TID",0) . ",MID:" . $xpay->Response("LGD_MID",0) . ",OID:" . $xpay->Response("LGD_OID",0) . "]");            		            		
            		
                echo "TX Rollback Response_code = " . $xpay->Response_Code() . "<br>";
                echo "TX Rollback Response_msg = " . $xpay->Response_Msg() . "<p>";
            		
                if( "0000" == $xpay->Response_Code() ) {
                  	echo "자동취소가 정상적으로 완료 되었습니다.<br>";
                }else{
          			echo "자동취소가 정상적으로 처리되지 않았습니다.<br>";
                }
          	}            	
        }else{
          	//최종결제요청 결과 실패 DB처리
         	echo "최종결제요청 결과 실패 DB처리하시기 바랍니다.<br>";            	            
        }
    }else {
        //2)API 요청실패 화면처리
        echo "결제요청이 실패하였습니다.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
            
        //최종결제요청 결과 실패 DB처리
        echo "최종결제요청 결과 실패 DB처리하시기 바랍니다.<br>";            	                        
    }
?>
