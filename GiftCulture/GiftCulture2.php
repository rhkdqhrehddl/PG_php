<?php
    /*
     * [문화상품권결제 결제결과 페이지]
     *
     * 파라미터 전달시 POST를 사용하세요
     */
	$CST_PLATFORM               = $_POST["CST_PLATFORM"];		//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $LGD_MID                    = $_POST["LGD_MID"];			//상점아이디(자동생성)    
	$LGD_TID              		= $_POST["LGD_TID"];			//토스페이먼츠 TID
	$LGD_AMOUNT              	= $_POST["LGD_AMOUNT"];			//결제금액
	$LGD_AUTHNUMBER            	= $_POST["LGD_CULTPIN"];		//문화상품권번호
	
	$configPath 				= "C:/lgdacom"; 						 		//토스페이먼츠에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정.   
	
	if(PHP_OS === "Linux"){
		$configPath             = "/lgdacom";
	}
    
	require_once($configPath . "/XPayClient.php");
    $xpay = new XPayClient($configPath, $CST_PLATFORM);
    
    try{
        if (!$xpay->Init_TX($LGD_MID)) {
        	echo "토스페이먼츠에서 제공한 환경파일이 정상적으로 설치 되었는지 확인하시기 바랍니다.<br/>";
        	echo "mall.conf에는 Mert Id = Mert Key 가 반드시 등록되어 있어야 합니다.<br/><br/>";
        	echo "문의전화 토스페이먼츠 1544-7772<br/>";
        	exit;
        }
        $xpay->Set("LGD_TXNAME", "GiftCulture");
        $xpay->Set("LGD_METHOD", "APP");
        $xpay->Set("LGD_TID", $LGD_TID);
    	$xpay->Set("LGD_AMOUNT", $LGD_AMOUNT);
        $xpay->Set("LGD_CULTPIN", $LGD_CULTPIN);
	}
	catch (Exception $e){
    	echo "토스페이먼츠 제공 API를 사용할 수 없습니다. 환경파일 설정을 확인해 주시기 바랍니다.";
    	echo "".$e->getMessage();
    	return true;
    } 
    /*
     * 결제 요청 결과
     *
     */
    if ($xpay->TX()) {
    	//1)결제결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
        echo "결제요청이  완료되었습니다. <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
		
		echo "결과코드: " . $xpay->Response("LGD_RESPCODE",0) . "<br>";
        echo "결과메세지: " . $xpay->Response("LGD_RESPMSG",0) . "<p>";
        
		$keys = $xpay->Response_Names();
        foreach($keys as $name) {
        	echo $name . " = " . $xpay->Response($name, 0) . "<br>";
		}
        echo "<p>";
      	
      	if ("0000" == $xpay->Response_Code()){
        	echo "결제가  성공하였습니다.<br>";
        }else{
        	echo "결제가  실패하였습니다.<br>";
        }
 
    }else {		
        //2)API 요청실패 화면처리
        echo "문화상품권결제 실패  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
    }
?>
