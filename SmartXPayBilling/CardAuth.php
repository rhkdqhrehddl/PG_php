<?php
    /*
     * [결제승인 요청 페이지]
     *
     * 파라미터 전달시 POST를 사용하세요.
     */
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];						//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];							//상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                         		//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)    
    $LGD_OID                	= $_POST["LGD_OID"];							//주문번호(상점정의 유니크한 주문번호를 입력하세요)
    $LGD_AMOUNT                	= $_POST["LGD_AMOUNT"];							//결제금액("," 를 제외한 결제금액을 입력하세요)
    $LGD_PAN		            = $_POST["LGD_PAN"];							//빌링키 
    																			
    $LGD_INSTALL                = $_POST["LGD_INSTALL"];						//할부개월수
    $LGD_EXPYEAR	            = $_POST["LGD_EXPYEAR"];						//유효기간년
    $LGD_EXPMON	                = $_POST["LGD_EXPMON"];							//유효기간월 
	$LGD_PIN                	= $_POST["LGD_PIN"];							//비밀번호 앞2자리(옵션-주민번호를 넘기지 않으면 비밀번호도 체크 안함)
    $LGD_PRIVATENO	            = $_POST["LGD_PRIVATENO"];						//생년월일 6자리 (YYMMDD) or 사업자번호(옵션)
    $LGD_PRODUCTINFO            = $_POST["LGD_PRODUCTINFO"];                    //상품명(옵션)
    $LGD_BUYER                  = $_POST["LGD_BUYER"];                          //고객명(옵션)
    $LGD_BUYERID                = $_POST["LGD_BUYERID"];                        //고객 아이디(옵션)
    $LGD_BUYERPHONE	            = $_POST["LGD_BUYERPHONE"];						//고객 휴대폰번호(SMS발송:선택)
	$VBV_ECI	                = $_POST["VBV_ECI"];							//결제방식(KeyIn:010, Swipe:020)



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

    $xpay->Set("LGD_TXNAME", "CardAuth");
    $xpay->Set("LGD_OID", $LGD_OID);
    $xpay->Set("LGD_AMOUNT", $LGD_AMOUNT);
    $xpay->Set("LGD_PAN", $LGD_PAN);
    $xpay->Set("LGD_INSTALL", $LGD_INSTALL);
    $xpay->Set("LGD_BUYERPHONE", $LGD_BUYERPHONE);
    $xpay->Set("LGD_PRODUCTINFO", $LGD_PRODUCTINFO);
    $xpay->Set("LGD_BUYER", $LGD_BUYER);
    $xpay->Set("LGD_BUYERID", $LGD_BUYERID);
    $xpay->Set("LGD_BUYERIP", $_SERVER["REMOTE_ADDR"]);
	$xpay->Set("VBV_ECI", $VBV_ECI);
    
	if ($VBV_ECI == "010"){    	 			//키인방식인 경우에만 해당
	
	$xpay->Set("LGD_EXPYEAR", $LGD_EXPYEAR);
    $xpay->Set("LGD_EXPMON", $LGD_EXPMON);
    $xpay->Set("LGD_PIN", $LGD_PIN);
    $xpay->Set("LGD_PRIVATENO", $LGD_PRIVATENO);
	}
  
    if ($xpay->TX()) {
        //1)결제결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
        echo "결제 요청이 완료되었습니다.  <br>";
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
