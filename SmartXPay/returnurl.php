<?php
/*
  payreq_crossplatform ���� ���ǿ� �����ߴ� �Ķ���� ���� ��ȿ���� üũ
  ���� ���� �ð�(�α��� �����ð�)�� ������ ���� �ϰų� ������ ������� �ʴ� ��� DBó�� �Ͻñ� �ٶ��ϴ�.
*/
  session_start();
  if(!isset($_SESSION['PAYREQ_MAP'])){
  	echo "������ ���� �Ǿ��ų� ��ȿ���� ���� ��û �Դϴ�.";
  	return;
  }
  $payReqMap = $_SESSION['PAYREQ_MAP'];//���� ��û��, Session�� �����ߴ� �Ķ���� MAP
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
  $LGD_PAYKEY	  = "";

  if($LGD_RESPCODE == "0000"){
	  $LGD_PAYKEY = $_REQUEST['LGD_PAYKEY'];
	  $payReqMap['LGD_RESPCODE'] = $LGD_RESPCODE;
	  $payReqMap['LGD_RESPMSG']	=	$LGD_RESPMSG;
	  $payReqMap['LGD_PAYKEY'] = $LGD_PAYKEY;
?>
<form method="post" name="LGD_PAYINFO" id="LGD_PAYINFO" action="payres.php">
<?php
	foreach ($payReqMap as $key => $value) {
      echo "<input type='hidden' name='$key' id='$key' value='$value'>";
    }
?>
</form>
<?php
  }
  else{
	  echo "LGD_RESPCODE:" + $LGD_RESPCODE + " ,LGD_RESPMSG:" + $LGD_RESPMSG; //���� ���п� ���� ó�� ���� �߰�
  }
?>
</body>
</html>