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
?>
<html>
<head>
	<script type="text/javascript">
		function setLGDResult() {
			document.getElementById('LGD_PAYINFO').submit();
		}
	</script>
</head>
<body onload="setLGDResult()">
<?php
  $LGD_RESPCODE = $_REQUEST['LGD_RESPCODE'];
  $LGD_RESPMSG 	= $_REQUEST['LGD_RESPMSG'];
  $LGD_BILLKEY	= "";

  if($LGD_RESPCODE == "0000"){
	  $LGD_BILLKEY = $_REQUEST['LGD_BILLKEY'];
	  $payReqMap['LGD_RESPCODE'] = $LGD_RESPCODE;
	  $payReqMap['LGD_RESPMSG']	=	$LGD_RESPMSG;
	  $payReqMap['LGD_BILLKEY'] = $LGD_BILLKEY;
?>
<form method="post" name="LGD_PAYINFO" id="LGD_PAYINFO" action="SmartXpay_CardBillingAuth_payres.php">
<?php
	  foreach ($payReqMap as $key => $value) {
      echo "<input type='hidden' name='$key' id='$key' value='$value'>";
    }
?>
</form>
<?php
  }
  else{
	  echo "LGD_RESPCODE:" + $LGD_RESPCODE + " ,LGD_RESPMSG:" + $LGD_RESPMSG; //인증 실패에 대한 처리 로직 추가
  }
?>
</body>
</html>