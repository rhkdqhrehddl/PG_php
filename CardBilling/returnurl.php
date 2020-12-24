<?php
/*
  payreq_crossplatform 에서 세션에 저장했던 파라미터 값이 유효한지 체크
  세션 유지 시간(로그인 유지시간)을 적당히 유지 하거나 세션을 사용하지 않는 경우 DB처리 하시기 바랍니다.
*/
  session_start();

  if(!isset($_SESSION['PAYREQ_MAP'])){
  	echo "세션이 만료 되었거나 유효하지 않은 요청 입니다.";
  	return;
  }

  $payReqMap = $_SESSION['PAYREQ_MAP'];//결제 요청시, Session에 저장했던 파라미터 MAP

  $LGD_RESPCODE = $_POST['LGD_RESPCODE'];
  $LGD_RESPMSG 	= $_POST['LGD_RESPMSG'];
?>
<html>
<head>
	<script type="text/javascript">
	
		function setLGDResult() {
			parent.payment_return();
			try {
			} catch (e) {
				alert(e.message);
			}
		}
		
	</script>
</head>
<body onload="setLGDResult()">
<?php
  
  $LGD_BILLKEY			= "";	
  $LGD_PAYTYPE			= "";	
  $LGD_PAYDATE			= "";	
  $LGD_FINANCECODE		= "";	
  $LGD_FINANCENAME		= "";	

  $payReqMap['LGD_RESPCODE']= $LGD_RESPCODE;
  $payReqMap['LGD_RESPMSG']	= $LGD_RESPMSG;

  if($LGD_RESPCODE == "0000"){
	  $LGD_BILLKEY		= $_POST['LGD_BILLKEY'];		//추후 빌링시 카드번호 대신 입력할 값입니다.
	  $LGD_PAYTYPE		= $_POST['LGD_PAYTYPE'];		//인증수단
	  $LGD_PAYDATE		= $_POST['LGD_PAYDATE'];		//인증일시
	  $LGD_FINANCECODE	= $_POST['LGD_FINANCECODE'];	//인증기관코드
	  $LGD_FINANCENAME	= $_POST['LGD_FINANCENAME'];	//인증기관이름 


	  $payReqMap['LGD_BILLKEY']		= $LGD_BILLKEY;
	  $payReqMap['LGD_PAYTYPE']		= $LGD_PAYTYPE;
	  $payReqMap['LGD_PAYDATE']		= $LGD_PAYDATE;
	  $payReqMap['LGD_FINANCECODE'] = $LGD_FINANCECODE;
	  $payReqMap['LGD_FINANCENAME'] = $LGD_FINANCENAME;
  }
  else{
	  echo "LGD_RESPCODE:" . $LGD_RESPCODE . " ,LGD_RESPMSG:" . $LGD_RESPMSG; //인증 실패에 대한 처리 로직 추가
  }
?>
<form method="post" name="LGD_RETURNINFO" id="LGD_RETURNINFO">
<?php
	  foreach ($payReqMap as $key => $value) {
      echo "<input type='hidden' name='$key' id='$key' value='$value'>";
    }
?>
</form>
</body>
</html>