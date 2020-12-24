
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
  $LGD_RESPCODE = $_POST['LGD_RESPCODE'];
  $LGD_RESPMSG 	= $_POST['LGD_RESPMSG'];
  $LGD_PAYKEY	  = "";
 

  if($LGD_RESPCODE == "0000"){
	  $LGD_PAYKEY = $_POST['LGD_PAYKEY'];
	  
  }
  else{
	echo "LGD_RESPCODE:" . $LGD_RESPCODE . "<br/>LGD_RESPMSG:" . $LGD_RESPMSG; //인증 실패에 대한 처리 로직 추가
  }
?>
<form method="post" name="LGD_RETURNINFO" id="LGD_RETURNINFO">
	
	<input type="hidden" id="LGD_RESPCODE"	name="LGD_RESPCODE"	value="<?= $LGD_RESPCODE ?>"/>
	<input type="hidden" id="LGD_RESPMSG"	name="LGD_RESPMSG"	value="<?= $LGD_RESPMSG ?>"/>
	<input type="hidden" id="LGD_PAYKEY"	name="LGD_PAYKEY"	value="<?= $LGD_PAYKEY ?>"/>	

</form>
</body>
</html>