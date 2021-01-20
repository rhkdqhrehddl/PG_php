<?php
	session_start();
	header('Set-Cookie: PHPSESSID='.session_id().'; SameSite=None; Secure');
    /*
     * 
     *     
     * �⺻ �Ķ���͸� ���õǾ� ������, ������ �ʿ��Ͻ� �Ķ���ʹ� �����޴����� �����Ͻþ� �߰��Ͻñ� �ٶ��ϴ�. 
     *
     */

     
    /*
	 * [����������û ������]
     * 1. �⺻�������� ����
     *
     * �����⺻������ �����Ͽ� �ֽñ� �ٶ��ϴ�. 
     */
	$server_domain = $_SERVER['HTTP_HOST'];
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];                       //�佺���̸��� ���� ���� ����(test:�׽�Ʈ, service:����)
    $CST_MID                    = $_POST["CST_MID"];                            //�������̵�(LG���÷������� ���� �߱޹����� �������̵� �Է��ϼ���)
                                                                                //�׽�Ʈ ���̵�� 't'�� �ݵ�� �����ϰ� �Է��ϼ���.
	$LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //�������̵�(�ڵ�����)
    
    $LGD_BUYER		            = $_POST["LGD_BUYER"];                        	//����
    $LGD_BUYERSSN               = $_POST["LGD_BUYERSSN"];                       //������û�� ������� 6�ڸ� (YYMMDD) or ����ڹ�ȣ 10�ڸ�

	$LGD_NAMECHECKYN		 	= $_POST["LGD_NAMECHECKYN"];					//���½Ǹ�Ȯ�ο���
	$LGD_HOLDCHECKYN 			= $_POST["LGD_HOLDCHECKYN"];					//�޴�������Ȯ�� SMS�߼� ����
	$LGD_MOBILE_SUBAUTH_SITECD 	= $_POST["LGD_MOBILE_SUBAUTH_SITECD"];			//�ſ��򰡻翡�� �ο����� ȸ���� ���� �ڵ�
																				//(CI���� �ʿ��� ��� �ɼ�, DI���� �ʿ��� ��� �ʼ�)
    $LGD_CUSTOM_USABLEPAY  		= $_POST["LGD_CUSTOM_USABLEPAY"];               //[�ݵ�� ���� �ʿ�]�������� �̿밡�� ������������ �� ���� ���� ���� (��:"ASC007")
    $LGD_TIMESTAMP        		= $_POST["LGD_TIMESTAMP"];                		//Ÿ�ӽ�����(YYYYMMDDhhmmss)
    $LGD_CUSTOM_SKIN      		= "SMART_XPAY2";								//����â SKIN
    $LGD_WINDOW_TYPE            = $_POST["LGD_WINDOW_TYPE"];					//����â ȣ�� ��� (�����Ұ�)  
    

    //  LGD_RETURNURL �� �����Ͽ� �ֽñ� �ٶ��ϴ�. �ݵ�� ���� �������� ������ ����Ʈ�� ��  ȣ��Ʈ�̾�� �մϴ�. �Ʒ� �κ��� �ݵ�� �����Ͻʽÿ�.
    $LGD_RETURNURL              = "https://" . $server_domain . "/MobileAuthOnly/returnurl.php";         
	
	$configPath					= "C:/lgdacom"; //LG���÷������� ������ ȯ������("/conf/lgdacom.conf,/conf/mall.conf") ��ġ ����.
	
	if(PHP_OS === "Linux"){
		$configPath             = "/lgdacom";
	}
	
	/*
	*************************************************
	* 2. MD5 �ؽ���ȣȭ (�������� ������) - BEGIN
	* 
	* MD5 �ؽ���ȣȭ�� �ŷ� �������� �������� ����Դϴ�. 
	*************************************************
	*
	* �ؽ� ��ȣȭ ����( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY )
	* LGD_MID          	: �������̵�
	* LGD_BUYERSSN		: ������� / ����ڹ�ȣ
	* LGD_TIMESTAMP  	: Ÿ�ӽ�����
	* LGD_MERTKEY      	: ����MertKey (mertkey�� ���������� -> ������� -> ���������������� Ȯ���ϽǼ� �ֽ��ϴ�)
	*
	* MD5 �ؽ������� ��ȣȭ ������ ����
	* LG���÷������� �߱��� ����Ű(MertKey)�� ȯ�漳�� ����(lgdacom/conf/mall.conf)�� �ݵ�� �Է��Ͽ� �ֽñ� �ٶ��ϴ�.
	*/
	
    require_once($configPath . "/XPayClient.php");
	$xpay = &new XPayClient($configPath, $CST_PLATFORM);
	
	if (!$xpay->Init_TX($LGD_MID)) {
    	echo "LG���÷������� ������ ȯ�������� ���������� ��ġ �Ǿ����� Ȯ���Ͻñ� �ٶ��ϴ�.<br/>";
    	echo "mall.conf���� Mert Id = Mert Key �� �ݵ�� ��ϵǾ� �־�� �մϴ�.<br/><br/>";
    	echo "������ȭ �佺���̸��� 1544-7772<br/>";
    	exit;
    }
    
	$LGD_HASHDATA = md5($LGD_MID.$LGD_BUYERSSN.$LGD_TIMESTAMP.$xpay->config[$LGD_MID]);    
	/*
	*************************************************
	* 2. MD5 �ؽ���ȣȭ (�������� ������) - END
	*************************************************
	*/
     
	
	
	$payReqMap['CST_PLATFORM']              = $CST_PLATFORM;           				// �׽�Ʈ, ���� ����
	$payReqMap['CST_MID']                   = $CST_MID;                				// �������̵�
	$payReqMap['LGD_MID']                   = $LGD_MID;                				// �������̵�
	$payReqMap['LGD_HASHDATA'] 				= $LGD_HASHDATA;      	           		// MD5 �ؽ���ȣ��
	$payReqMap['LGD_BUYER']              	= $LGD_BUYER;							// ��û�� ����
	$payReqMap['LGD_BUYERSSN']              = $LGD_BUYERSSN;           				// ��û�� ������� / ����ڹ�ȣ
	
	$payReqMap['LGD_NAMECHECKYN']           = $LGD_NAMECHECKYN;           			// ���½Ǹ�Ȯ�ο���
	$payReqMap['LGD_HOLDCHECKYN']           = $LGD_HOLDCHECKYN;           			// �޴�������Ȯ�� SMS�߼� ����
	$payReqMap['LGD_MOBILE_SUBAUTH_SITECD'] = $LGD_MOBILE_SUBAUTH_SITECD;           // �ſ��򰡻翡�� �ο����� ȸ���� ���� �ڵ�
	
	$payReqMap['LGD_CUSTOM_SKIN'] 			= $LGD_CUSTOM_SKIN;                		// ����â SKIN
	$payReqMap['LGD_TIMESTAMP'] 			= $LGD_TIMESTAMP;                  		// Ÿ�ӽ�����
	$payReqMap['LGD_CUSTOM_USABLEPAY']      = $LGD_CUSTOM_USABLEPAY;        		// [�ݵ�� ����]�������� �̿밡�� ������������ �� ���� ���� ���� (��:"ASC007")
	$payReqMap['LGD_WINDOW_TYPE']           = $LGD_WINDOW_TYPE;        				// ȣ���� (�����Ұ�)
	$payReqMap['LGD_RETURNURL'] 			= $LGD_RETURNURL;      			   		// �������������
	$payReqMap['LGD_VERSION'] 				= "PHP_Non-ActiveX_SmartXPay_AuthOnly";	// ���Ÿ�� ����(���� �� ���� ����): �� ������ �ٰŷ� � ���񽺸� ����ϴ��� �Ǵ��� �� �ֽ��ϴ�.
	$payReqMap['LGD_CUSTOM_SWITCHINGTYPE'] 	= "SUBMIT";								// SUBMIT: ������ ��ȯ���(���� �������� ����, �����Ұ�)
	$payReqMap['LGD_DOMAIN_URL'] 		 	= "xpayvvip";	
	
	/*Return URL���� ���� ��� ���� �� ���õ� �Ķ���� �Դϴ�.*/
	$payReqMap['LGD_RESPCODE'] 				= "";
	$payReqMap['LGD_RESPMSG'] 				= "";
	$payReqMap['LGD_AUTHONLYKEY'] 			= "";
	$payReqMap['LGD_PAYTYPE'] 				= "";
	
	
	$_SESSION['PAYREQ_MAP'] = $payReqMap;

?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>�佺���̸��� ���ڰ��� ����Ȯ�μ���  ���� ������</title>
<!-- test�� ��� -->
<script language="javascript" src="https://pretest.tosspayments.com:9443/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
<!-- 
  service�� ��� �Ʒ� URL�� ��� 
<script language="javascript" src="https://xpay.tosspayments.com/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
 -->
<script type="text/javascript">


var LGD_window_type = '<?=$LGD_WINDOW_TYPE?>';
/*

/*
* �����Ұ�
*/
function launchCrossPlatform(){
      lgdwin = open_paymentwindow(document.getElementById('LGD_PAYINFO'), '<?= $CST_PLATFORM ?>', LGD_window_type);
}
/*
* FORM ��  ���� ����
*/
function getFormObject() {
        return document.getElementById("LGD_PAYINFO");
}

</script>
</head>
<body>
<form method="post" id="LGD_PAYINFO">
<table>	
	<tr>
		<td>�������̵�(t�� ������ ���̵�) </td>
		<td><?= $CST_MID ?></td>
	</tr>
	<tr>
	    <td>�������̵�</td>
	    <td><?= $LGD_MID ?></td>
	</tr>			
	<tr>
	    <td>����,�׽�Ʈ </td>
	    <td><?= $CST_PLATFORM ?></td>
	</tr>
	<tr>
	    <td>
			������� <br/>
			�Ǵ� ����ڹ�ȣ
		</td>
	    <td><?= $LGD_BUYERSSN ?></td>
	</tr>
	<tr>
	    <td>����</td>
	    <td><?= $LGD_BUYER ?></td>
	</tr>
	<tr>
	    <td>������Ʈ�ڵ�(�ɼ�)</td>
	    <td><?= $LGD_MOBILE_SUBAUTH_SITECD ?></td>
	</tr>
	<tr>
	    <td>Ÿ�ӽ�����</td>
	    <td><?= $LGD_TIMESTAMP ?></td>
	</tr>
	<tr>
	    <td>����������</td>
	    <td><?= $LGD_HASHDATA ?></td>
	</tr>
	<tr>
	    <td>�޴�������Ȯ��SMS�߼ۿ���</td>
	    <td>
			<?=$LGD_HOLDCHECKYN ?>
		</td>
	</tr>
	
	<tr>
	    <td>����â ��Ų color</td>
	    <td>
			<?=$LGD_CUSTOM_SKIN ?>
		</td>
	</tr>
	<tr>
	    <td>����â ȣ�� ��� </td>
	    <td>
			<?=$LGD_WINDOW_TYPE ?>
		</td>
	</tr>													
	<tr>
		<td>
			<input type="button" value="������û" onclick="launchCrossPlatform();"/>
   		</td>
	</tr>
</table>
<!-- UTF-8 ���ڵ��� ���
<input type="text" name="LGD_ENCODING" value="UTF-8"/>
-->
<?php
  foreach ($payReqMap as $key => $value) {
    echo "<input type='text' name='$key' id='$key' value='$value'/>";
  }
  var_dump($_SESSION);
?>

</form>
</body>
</html>

