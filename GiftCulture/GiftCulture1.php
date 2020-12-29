<?php
    /*
     * [문화상품권결제 인증요청 페이지]
     *
     * 파라미터 전달시 POST를 사용하세요
     */
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];				//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
    $CST_MID                    = $_POST["CST_MID"];					//상점아이디(토스페이먼츠로 부터 발급받으신 상점아이디를 입력하세요)
																		//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)    
	$LGD_OID             		= $_POST["LGD_OID"];					//주문번호
	$LGD_AMOUNT             	= $_POST["LGD_AMOUNT"];					//결제금액    
	$LGD_CULTID              	= $_POST["LGD_CULTID"];					//컬쳐랜드 아이디
	$LGD_CULTPASSWD             = $_POST["LGD_CULTPASSWD"];				//컬쳐랜드 비밀번호
	$LGD_BUYER             		= $_POST["LGD_BUYER"];					//구매자명
	$LGD_PRODUCTINFO          	= $_POST["LGD_PRODUCTINFO"];			//상품정보
	$LGD_BUYEREMAIL            	= $_POST["LGD_BUYEREMAIL"];				//이메일
	$LGD_BUYERID             	= $_POST["LGD_BUYERID"];				//구매자아이디
	$LGD_BUYERIP             	= $_POST["LGD_BUYERIP"];				//구매자IP
	$LGD_CULTAUTHTYPE           = "";									//인증타입
	

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
        $xpay->Set("LGD_METHOD", "AUTH");
    	$xpay->Set("LGD_OID", $LGD_OID);
    	$xpay->Set("LGD_AMOUNT", $LGD_AMOUNT);
        $xpay->Set("LGD_CULTID", $LGD_CULTID);
    	$xpay->Set("LGD_CULTPASSWD", $LGD_CULTPASSWD);
    	$xpay->Set("LGD_CULTVERSION", "2");     //삭제하지 마세요 (컬쳐랜드 신규변경전문적용, 2012-6-1)
    	$xpay->Set("LGD_BUYER", $LGD_BUYER);
    	$xpay->Set("LGD_BUYERID", $LGD_BUYERID);
    	$xpay->Set("LGD_PRODUCTINFO", $LGD_PRODUCTINFO);
    	$xpay->Set("LGD_BUYEREMAIL", $LGD_BUYEREMAIL);
    	$xpay->Set("LGD_BUYERIP", $_SERVER["REMOTE_ADDR"]);
    //	$xpay->Set("LGD_BUYERIP", $LGD_BUYERIP);
    }
    catch (Exception $e){
    	echo "토스페이먼츠 제공 API를 사용할 수 없습니다. 환경파일 설정을 확인해 주시기 바랍니다.";
    	echo "" .$e->getMessage();
    	return true;
    }
    
    /*
     * 문화상품권결제 인증요청
     *
     */
    if ($xpay->TX()) {
    	//1)인증결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
        echo "문화상품권결제 인증요청이  완료되었습니다.<br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
        
        echo "결과코드: " . $xpay->Response("LGD_RESPCODE",0) . "<br>";
        echo "결과메세지: " . $xpay->Response("LGD_RESPMSG",0) . "<br>";
        echo "잔 액: " . $xpay->Response("LGD_CULTBALANCE",0) . "<p>";
        
		$LGD_CULTAUTHTYPE = $xpay->Response("LGD_CULTAUTHTYPE", 0);

        $keys = $xpay->Response_Names();
            foreach($keys as $name) {
                echo $name . " = " . $xpay->Response($name, 0) . "<br>";
			}
        echo "<p>";
        
        if ("0000" == $xpay->Response_Code()){
        	echo "인증이  성공하였습니다.<br>";
        }else{
        	echo "인증이  실패하였습니다.<br>";
        }
        
    }else {
    	//2)API 요청실패 화면처리
        echo "문화상품권결제 인증요청이 실패되었습니다.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
    }
	
	if ($xpay->Response_Code() == '0000') {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>토스페이먼츠 전자결제 문화상품권결제  샘플 페이지</title>
<script type="text/javascript">
<!--
function goCultureland(){
	
	//서비스계
	popupWin = window.open("http://bill.cultureland.co.kr/mcash/custom/authFind.asp","popWinName","menubar=no,toolbar=0,scrollbars=no,width=500,height=520,resize=1,left=100,top=200")
	
	//테스트계
	//popupWin = window.open("http://testserver.cultureland.co.kr:82/mcash/custom/authFind.asp","popWinName","menubar=no,toolbar=0,scrollbars=no,width=500,height=520,resize=1,left=100,top=200")

}

function goCultureHome(){
	
	popupWin = window.open("http://www.cultureland.co.kr/mypage/SecurityCenter/PayCertification_creat_user.asp","popWinName","menubar=no,toolbar=0,scrollbars=no,width=1024,height=800,resize=1,left=100,top=200")

}
function sendSMS(){
	document.sendSMSForm.target = "hiddenFrame";
	document.sendSMSForm.submit();
}
-->
</script>
</head>
<body>
    <form method="post" id="LGD_PAYINFO" action="GiftCulture2.php">
    	<input type="hidden" name="CST_PLATFORM" value="<?php echo $CST_PLATFORM; ?>"/>
    	<input type="hidden" name="LGD_MID" value="<?php echo $LGD_MID; ?>"/>
		<input type="hidden" name="LGD_TID" value="<?php echo $xpay->Response("LGD_TID", 0); ?>"/>
		<input type="hidden" name="LGD_AMOUNT" value="<?php echo $LGD_AMOUNT; ?>"/>
		<input type="hidden" name="LGD_CULTAUTHTYPE" value="<?php echo $LGD_CULTAUTHTYPE; ?>"/>
        <table>	
            <tr>
                <td>인증타입명</td>
                <td><?php echo $xpay->Response("LGD_CULTAUTHNAME", 0); ?></td>
            </tr>
            <?php if ($LGD_CULTAUTHTYPE != "P"){ ?>
            <tr>
                <td>인증번호</td>
                <td><input type="text" name="LGD_CULTAUTHNO" value=""/></td>
            </tr>
            <?php	if ($LGD_CULTAUTHTYPE == "0" ){ ?>     
        	<tr>
                <td>인증번호 생성 </td>
                <td><input type="button" value="인증번호 생성" onclick="javascript:goCultureland();"/></td>
            </tr>
            <?php	} else if ( $LGD_CULTAUTHTYPE == "1" ){ ?>     
        	<tr>
                <td>인증번호  찾기</td>
                <td><input type="button" value="인증번호 찾기" onclick="javascript:goCultureHome();"/></td>
            </tr>
	    	<?php	} else if ($LGD_CULTAUTHTYPE == "2" ){ ?>
			<tr>
                <td>인증번호 발송</td>
                <td><input type="button" value="인증번호 발송" onclick="javascript:sendSMS();"/></td>
            </tr>
	    	<?php	}
	    		}
	    	?>
			<tr>
                <td>문화상품권번호</td>
                <td><input type="text" name="LGD_CULTPIN" value=""/></td>
            </tr>
            <tr>
                <td>
                <input type="submit" value="결제요청"/><br/>
                </td>
            </tr>
        </table>
    </form>
			<!--###### 인증번호 SMS 발송 요청 (시작) #####-->
	<form name="sendSMSForm" method="POST" action="SendSMS.php">
	<input type="hidden" name="CST_PLATFORM" value="<?php echo $CST_PLATFORM; ?>">
	<input type="hidden" name="CST_MID" value="<?php echo $CST_MID; ?>">
	<input type="hidden" name="LGD_MID" value="<?php echo $LGD_MID; ?>">
	<input type="hidden" name="LGD_TID" value="<?php echo $xpay->Response("LGD_TID", 0); ?>">
	<input type="hidden" name="LGD_CULTID" value="<?php echo $LGD_CULTID; ?>">
	</form>						
	<iframe name="hiddenFrame" style="display:none;width:0;height:0" ></iframe>
	<!--###### 인증번호 SMS 발송 요청 (끝) #####-->
</body>
</html>
<?php } ?>