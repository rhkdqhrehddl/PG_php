<?php
	$LGD_RESPCODE         = $_POST["LGD_RESPCODE"];
    $LGD_RESPMSG          = $_POST["LGD_RESPMSG"];        	                        
	$LGD_PAYKEY           = $_POST["LGD_PAYKEY"];
	$LGD_NOINT			  = $_POST["LGD_NOINT"];
	$LGD_MID              = $_POST["LGD_MID"];
	$VBV_CAVV             = $_POST["VBV_CAVV"];
	$LGD_EXPMON           = $_POST["LGD_EXPMON"];
	$KVP_GOODNAME         = $_POST["KVP_GOODNAME"];
	$LGD_DELIVERYINFO     = $_POST["LGD_DELIVERYINFO"];
	$LGD_BUYER            = $_POST["LGD_BUYER"];
	$LGD_CARDTYPE         = $_POST["LGD_CARDTYPE"];
	$KVP_CARDCOMPANY      = $_POST["KVP_CARDCOMPANY"];
	$LGD_BUYERID          = $_POST["LGD_BUYERID"];
	$LGD_OID              = $_POST["LGD_OID"];
	$LGD_PAN              = $_POST["LGD_PAN"];
	$KVP_SESSIONKEY       = $_POST["KVP_SESSIONKEY"];
	$LGD_EXPYEAR          = $_POST["LGD_EXPYEAR"];
	$LGD_RECEIVERPHONE    = $_POST["LGD_RECEIVERPHONE"];
	$CUSTOM_PROCESSTYPE   = $_POST["CUSTOM_PROCESSTYPE"];
	$KVP_QUOTA            = $_POST["KVP_QUOTA"];
	$LGD_CLOSEDATE        = $_POST["LGD_CLOSEDATE"];
	$LGD_TIMESTAMP        = $_POST["LGD_TIMESTAMP"];
	$KVP_NOINT            = $_POST["KVP_NOINT"];
	$LGD_BUYERPHONE       = $_POST["LGD_BUYERPHONE"];
	$LGD_INSTALL          = $_POST["LGD_INSTALL"];
	$LGD_ESCROWYN         = $_POST["LGD_ESCROWYN"];
	$LGD_RETURNURL        = $_POST["LGD_RETURNURL"];
	$KVP_PRICE            = $_POST["KVP_PRICE"];
	$LGD_PAYTYPE          = $_POST["LGD_PAYTYPE"];
	$LGD_AMOUNT           = $_POST["LGD_AMOUNT"];
	$KVP_CONAME           = $_POST["KVP_CONAME"];
	$LGD_BUYERSSN         = $_POST["LGD_BUYERSSN"];
	$LGD_RES_CARDPOINTYN  = $_POST["LGD_RES_CARDPOINTYN"];
	$LGD_CURRENCY         = $_POST["LGD_CURRENCY"];
	$KVP_CARDCODE         = $_POST["KVP_CARDCODE"];
	$LGD_PRODUCTINFO      = $_POST["LGD_PRODUCTINFO"];
	$VBV_JOINCODE         = $_POST["VBV_JOINCODE"];
	$LGD_PRODUCTCODE      = $_POST["LGD_PRODUCTCODE"];
	$VBV_XID              = $_POST["VBV_XID"];
	$LGD_HASHDATA         = $_POST["LGD_HASHDATA"];
	$VBV_ECI              = $_POST["VBV_ECI"];
	$LGD_BUYERADDRESS     = $_POST["LGD_BUYERADDRESS"];
	$LGD_BUYERIP          = $_POST["LGD_BUYERIP"];
	$LGD_RECEIVER         = $_POST["LGD_RECEIVER"];
	$KVP_ENCDATA          = $_POST["KVP_ENCDATA"];
	$KVP_PGID             = $_POST["KVP_PGID"];
	$LGD_BUYEREMAIL       = $_POST["LGD_BUYEREMAIL"];
	$LGD_AUTHTYPE         = $_POST["LGD_AUTHTYPE"];
	$KVP_CURRENCY         = $_POST["KVP_CURRENCY"];
	$LGD_KVPISP_USER      = $_POST["LGD_KVPISP_USER"];

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
	<form method="post" name="LGD_RETURNINFO" id="LGD_RETURNINFO">
		<input type='hidden' name='CUSTOM_PROCESSTYPE' id='CUSTOM_PROCESSTYPE' value='<?= $CUSTOM_PROCESSTYPE ?>'/>
		<input type="hidden" name="LGD_RESPCODE" id="LGD_RESPCODE" value='<?= $LGD_RESPCODE ?>' />
		<input type="hidden" name="LGD_RESPMSG" id="LGD_RESPMSG" value='<?= $LGD_RESPMSG ?>' />
		<input type="hidden" name="LGD_PAYKEY" id="LGD_PAYKEY" value='<?= $LGD_PAYKEY ?>' />
		<input type='hidden' name='LGD_NOINT' id='LGD_NOINT'value='<?= $LGD_NOINT ?>'/>
		<input type='hidden' name='LGD_MID' id='LGD_MID' value='<?= $LGD_MID ?>'/>
		<input type='hidden' name='LGD_BUYERADDRESS' id='LGD_BUYERADDRESS' value='<?= $LGD_BUYERADDRESS ?>'/>
		<input type='hidden' name='LGD_BUYERIP' id='LGD_BUYERIP' value='<?= $LGD_BUYERIP ?>'/>
		<input type='hidden' name='LGD_RECEIVER' id='LGD_RECEIVER' value='<?= $LGD_RECEIVER ?>'/>
		<input type='hidden' name='LGD_EXPMON' id='LGD_EXPMON' value='<?= $LGD_EXPMON ?>'/>
		<input type='hidden' name='LGD_DELIVERYINFO' id='LGD_DELIVERYINFO' value='<?= $LGD_DELIVERYINFO ?>'/>
		<input type='hidden' name='LGD_BUYER' id='LGD_BUYER' value='<?= $LGD_BUYER ?>'/>
		<input type='hidden' name='LGD_CARDTYPE' id='LGD_CARDTYPE' value='<?= $LGD_CARDTYPE ?>'/>
		<input type='hidden' name='LGD_BUYERID' id='LGD_BUYERID' value='<?= $LGD_BUYERID ?>'/>
		<input type='hidden' name='LGD_OID' id='LGD_OID' value='<?= $LGD_OID ?>'/>
		<input type='hidden' name='LGD_PAN' id='LGD_PAN' value='<?= $LGD_PAN ?>'/>
		<input type='hidden' name='LGD_EXPYEAR' id='LGD_EXPYEAR' value='<?= $LGD_EXPYEAR ?>'/>
		<input type='hidden' name='LGD_RECEIVERPHONE' id='LGD_RECEIVERPHONE' value='<?= $LGD_RECEIVERPHONE ?>'/>
		<input type='hidden' name='LGD_CLOSEDATE' id='LGD_CLOSEDATE' value='<?= $LGD_CLOSEDATE ?>'/>
		<input type='hidden' name='LGD_TIMESTAMP' id='LGD_TIMESTAMP' value='<?= $LGD_TIMESTAMP ?>'/>
		<input type='hidden' name='LGD_BUYERPHONE' id='LGD_BUYERPHONE' value='<?= $LGD_BUYERPHONE ?>'/>
		<input type='hidden' name='LGD_INSTALL' id='LGD_INSTALL' value='<?= $LGD_INSTALL ?>'/>
		<input type='hidden' name='LGD_ESCROWYN' id='LGD_ESCROWYN' value='<?= $LGD_ESCROWYN ?>'/>
		<input type='hidden' name='LGD_RETURNURL' id='LGD_RETURNURL' value='<?= $LGD_RETURNURL ?>'/>
		<input type='hidden' name='LGD_PAYTYPE' id='LGD_PAYTYPE' value='<?= $LGD_PAYTYPE ?>'/>
		<input type='hidden' name='LGD_AMOUNT' id='LGD_AMOUNT' value='<?= $LGD_AMOUNT ?>'/>
		<input type='hidden' name='LGD_BUYERSSN' id='LGD_BUYERSSN' value='<?= $LGD_BUYERSSN ?>'/>
		<input type='hidden' name='LGD_RES_CARDPOINTYN' id='LGD_RES_CARDPOINTYN' value='<?= $LGD_RES_CARDPOINTYN ?>'/>
		<input type='hidden' name='LGD_CURRENCY' id='LGD_CURRENCY' value='<?= $LGD_CURRENCY ?>'/>
		<input type='hidden' name='LGD_PRODUCTINFO' id='LGD_PRODUCTINFO' value='<?= $LGD_PRODUCTINFO ?>'/>
		<input type='hidden' name='LGD_PRODUCTCODE' id='LGD_PRODUCTCODE' value='<?= $LGD_PRODUCTCODE ?>'/>
		<input type='hidden' name='LGD_BUYEREMAIL' id='LGD_BUYEREMAIL' value='<?= $LGD_BUYEREMAIL ?>'/>
		<input type='hidden' name='LGD_AUTHTYPE' id='LGD_AUTHTYPE' value='<?= $LGD_AUTHTYPE ?>'/>
		<input type='hidden' name='LGD_HASHDATA' id='LGD_HASHDATA' value='<?= $LGD_HASHDATA ?>'/>
		<input type='hidden' name='KVP_QUOTA' id='KVP_QUOTA' value='<?= $KVP_QUOTA ?>'/>
		<input type='hidden' name='KVP_NOINT' id='KVP_NOINT' value='<?= $KVP_NOINT ?>'/>
		<input type='hidden' name='KVP_PRICE' id='KVP_PRICE' value='<?= $KVP_PRICE ?>'/>
		<input type='hidden' name='KVP_CONAME' id='KVP_CONAME' value='<?= $KVP_CONAME ?>'/>
		<input type='hidden' name='KVP_CARDCODE' id='KVP_CARDCODE' value='<?= $KVP_CARDCODE ?>'/>
		<input type='hidden' name='KVP_SESSIONKEY' id='KVP_SESSIONKEY' value='<?= $KVP_SESSIONKEY ?>'/>
		<input type='hidden' name='KVP_CARDCOMPANY' id='KVP_CARDCOMPANY' value='<?= $KVP_CARDCOMPANY ?>'/>
		<input type='hidden' name='KVP_ENCDATA' id='KVP_ENCDATA' value='<?= $KVP_ENCDATA ?>'/>
		<input type='hidden' name='KVP_PGID' id='KVP_PGID' value='<?= $KVP_PGID ?>'/>
		<input type='hidden' name='KVP_CURRENCY' id='KVP_CURRENCY' value='<?= $KVP_CURRENCY ?>'/>
		<input type='hidden' name='KVP_GOODNAME' id='KVP_GOODNAME' value='<?= $KVP_GOODNAME ?>'/>
		<input type='hidden' name='VBV_CAVV' id='VBV_CAVV' value='<?= $VBV_CAVV ?>'/>
		<input type='hidden' name='VBV_ECI' id='VBV_ECI' value='<?= $VBV_ECI ?>'/>
		<input type='hidden' name='VBV_JOINCODE' id='VBV_JOINCODE' value='<?= $VBV_JOINCODE ?>'/>
		<input type='hidden' name='VBV_XID' id='VBV_XID' value='<?= $VBV_XID ?>'/>
		<input type='hidden' name='LGD_KVPISP_USER' id='LGD_KVPISP_USER' value='<?= $LGD_KVPISP_USER ?>'/>
	</form>
</div>
</body>
</html>
