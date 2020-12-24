<?php 
$configPath = "C:/lgdacom";
$LGD_MID = "tlgdacomxpay";
require_once("C:/lgdacom/XPayClient.php");
$xpay = new XPayClient($configPath,"test");
$xpay->Init_TX($LGD_MID);
$xpay->Set("LGD_TXNAME","Ping"); 
$xpay->Set("LGD_RESULTCNT","3");
if ($xpay->TX()) 
{
	echo "response code = " . $xpay->Response_Code() . "<br>";
	echo "response msg = " . $xpay->Response_Msg() . "<br>";
	echo "response count = " . $xpay->Response_Count() . "<p>";
	$keys = $xpay->Response_Names();
	for ($i = 0; $i < $xpay->Response_Count(); $i++) 
	{
		echo "count = " . $i . "<br>";
		foreach($keys as $name) 
		{
			echo $name . " = " . $xpay->Response($name, $i) . "<br>";
		}
	}
}
else 
{
	echo "[TX_PING error] <br>";
	echo "response code = " . $xpay->Response_Code() . "<br>";
	echo "response msg = " . $xpay->Response_Msg() . "<p>";
}
?>
