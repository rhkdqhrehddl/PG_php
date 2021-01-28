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
  $LGD_MID	  = $_REQUEST['LGD_MID'];


  if($LGD_RESPCODE == "0000"){
	  $LGD_PAYKEY = $_REQUEST['LGD_PAYKEY'];
	  
?>
<form method="post" name="LGD_PAYINFO" id="LGD_PAYINFO" action="payres.php">

	<input type="hidden" id="LGD_RESPCODE"	name="LGD_RESPCODE"	value="<?= $LGD_RESPCODE ?>"/>
	<input type="hidden" id="LGD_RESPMSG"	name="LGD_RESPMSG"	value="<?= $LGD_RESPMSG ?>"/>
	<input type="hidden" id="LGD_PAYKEY"	name="LGD_PAYKEY"	value="<?= $LGD_PAYKEY ?>"/>
	<input type="hidden" id="LGD_MID"		name="LGD_MID"		value="<?= $LGD_MID ?>"/>
	
</form>
<?php
  }
  else{
	  echo "LGD_RESPCODE:" . $LGD_RESPCODE . " ,LGD_RESPMSG:" . $LGD_RESPMSG; //인증 실패에 대한 처리 로직 추가
  }
?>
</body>
</html>