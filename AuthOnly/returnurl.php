<?php
/*
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
<p><h1>RETURN_URL (인증결과)</h1></p>
<div>
<p>LGD_RESPCODE (결과코드) : <?= $LGD_RESPCODE ?></p>
<p>LGD_RESPMSG (결과메시지): <?= $LGD_RESPMSG ?></p>
<?php
  
  $LGD_AUTHONLYKEY		= "";	
  $LGD_PAYTYPE			= "";	

  $payReqMap['LGD_RESPCODE'] = $LGD_RESPCODE;
  $payReqMap['LGD_RESPMSG']	=	$LGD_RESPMSG;

  if($LGD_RESPCODE == "0000"){
	  $LGD_AUTHONLYKEY	= $_POST['LGD_AUTHONLYKEY'];	
	  $LGD_PAYTYPE		= $_POST['LGD_PAYTYPE'];		


	  $payReqMap['LGD_AUTHONLYKEY'] = $LGD_AUTHONLYKEY;
	  $payReqMap['LGD_PAYTYPE'] 	= $LGD_PAYTYPE;
?>

<form method="post" name="LGD_PAYINFO" id="LGD_PAYINFO">
<?php
  }

	foreach ($payReqMap as $key => $value) {
    echo "<input type='hidden' name='$key' id='$key' value='$value'>";
  }//end foreach

?>
</form>
</div>
</body>
</html>