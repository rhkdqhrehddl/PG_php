<?php
    /* 
	 * [ 인증결과 화면페이지]
     *
     */

	$LGD_RESPCODE                = $_POST["LGD_RESPCODE"];		//토스페이먼츠 응답코드
	$LGD_RESPMSG                 = $_POST["LGD_RESPMSG"];		//토스페이먼츠 응답메세지

	$LGD_BILLKEY                 = $_POST["LGD_BILLKEY"];		//토스페이먼츠 빌링키
	

    if ( "0000" == $LGD_RESPCODE ){ //인증성공시
		echo "SmartXpay-Billing (화면)결과리턴페이지 예제입니다.  <br>";
		
		echo "결과코드 : " . $LGD_RESPCODE . "<br>";
        echo "결과메세지 : " . $LGD_RESPMSG . "<br>";
        echo "빌링키 : " . $LGD_BILLKEY . "<br>";
	}

?>
