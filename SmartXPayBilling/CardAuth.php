<?php
    /*
     * [�������� ��û ������]
     *
     * �Ķ���� ���޽� POST�� ����ϼ���.
     */
    $CST_PLATFORM               = $_POST["CST_PLATFORM"];						//�佺���̸��� ���� ���� ����(test:�׽�Ʈ, service:����)
    $CST_MID                    = $_POST["CST_MID"];							//�������̵�(�佺���̸������� ���� �߱޹����� �������̵� �Է��ϼ���)
                                                                         		//�׽�Ʈ ���̵�� 't'�� �ݵ�� �����ϰ� �Է��ϼ���.
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //�������̵�(�ڵ�����)    
    $LGD_OID                	= $_POST["LGD_OID"];							//�ֹ���ȣ(�������� ����ũ�� �ֹ���ȣ�� �Է��ϼ���)
    $LGD_AMOUNT                	= $_POST["LGD_AMOUNT"];							//�����ݾ�("," �� ������ �����ݾ��� �Է��ϼ���)
    $LGD_PAN		            = $_POST["LGD_PAN"];							//����Ű 
    																			
    $LGD_INSTALL                = $_POST["LGD_INSTALL"];						//�Һΰ�����
    $LGD_EXPYEAR	            = $_POST["LGD_EXPYEAR"];						//��ȿ�Ⱓ��
    $LGD_EXPMON	                = $_POST["LGD_EXPMON"];							//��ȿ�Ⱓ�� 
	$LGD_PIN                	= $_POST["LGD_PIN"];							//��й�ȣ ��2�ڸ�(�ɼ�-�ֹι�ȣ�� �ѱ��� ������ ��й�ȣ�� üũ ����)
    $LGD_PRIVATENO	            = $_POST["LGD_PRIVATENO"];						//������� 6�ڸ� (YYMMDD) or ����ڹ�ȣ(�ɼ�)
    $LGD_PRODUCTINFO            = $_POST["LGD_PRODUCTINFO"];                    //��ǰ��(�ɼ�)
    $LGD_BUYER                  = $_POST["LGD_BUYER"];                          //����(�ɼ�)
    $LGD_BUYERID                = $_POST["LGD_BUYERID"];                        //�� ���̵�(�ɼ�)
    $LGD_BUYERPHONE	            = $_POST["LGD_BUYERPHONE"];						//�� �޴�����ȣ(SMS�߼�:����)
	$VBV_ECI	                = $_POST["VBV_ECI"];							//�������(KeyIn:010, Swipe:020)



	$configPath 				= "C:/lgdacom"; 						 		//�佺���̸������� ������ ȯ������("/conf/lgdacom.conf") ��ġ ����.   
	if(PHP_OS === "Linux"){
		$configPath             = "/lgdacom";
	}

    require_once($configPath . "/XPayClient.php");
    $xpay = new XPayClient($configPath, $CST_PLATFORM);
    
    if (!$xpay->Init_TX($LGD_MID)) {
    	echo "�佺���̸������� ������ ȯ�������� ���������� ��ġ �Ǿ����� Ȯ���Ͻñ� �ٶ��ϴ�.<br/>";
    	echo "mall.conf���� Mert Id = Mert Key �� �ݵ�� ��ϵǾ� �־�� �մϴ�.<br/><br/>";
    	echo "������ȭ �佺���̸��� 1544-7772<br/>";
    	exit;
    }

    $xpay->Set("LGD_TXNAME", "CardAuth");
    $xpay->Set("LGD_OID", $LGD_OID);
    $xpay->Set("LGD_AMOUNT", $LGD_AMOUNT);
    $xpay->Set("LGD_PAN", $LGD_PAN);
    $xpay->Set("LGD_INSTALL", $LGD_INSTALL);
    $xpay->Set("LGD_BUYERPHONE", $LGD_BUYERPHONE);
    $xpay->Set("LGD_PRODUCTINFO", $LGD_PRODUCTINFO);
    $xpay->Set("LGD_BUYER", $LGD_BUYER);
    $xpay->Set("LGD_BUYERID", $LGD_BUYERID);
    $xpay->Set("LGD_BUYERIP", $_SERVER["REMOTE_ADDR"]);
	$xpay->Set("VBV_ECI", $VBV_ECI);
    
	if ($VBV_ECI == "010"){    	 			//Ű�ι���� ��쿡�� �ش�
	
	$xpay->Set("LGD_EXPYEAR", $LGD_EXPYEAR);
    $xpay->Set("LGD_EXPMON", $LGD_EXPMON);
    $xpay->Set("LGD_PIN", $LGD_PIN);
    $xpay->Set("LGD_PRIVATENO", $LGD_PRIVATENO);
	}
  
    if ($xpay->TX()) {
        //1)������� ȭ��ó��(����,���� ��� ó���� �Ͻñ� �ٶ��ϴ�.)
        echo "���� ��û�� �Ϸ�Ǿ����ϴ�.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
        
        echo "�ŷ���ȣ : " . $xpay->Response("LGD_TID",0) . "<br>";
        echo "�������̵� : " . $xpay->Response("LGD_MID",0) . "<br>";
        echo "�����ֹ���ȣ : " . $xpay->Response("LGD_OID",0) . "<br>";
        echo "�����ݾ� : " . $xpay->Response("LGD_AMOUNT",0) . "<br>";
        echo "����ڵ� : " . $xpay->Response("LGD_RESPCODE",0) . "<br>";
        echo "����޼��� : " . $xpay->Response("LGD_RESPMSG",0) . "<p>";
        
    	$keys = $xpay->Response_Names();
            foreach($keys as $name) {
                echo $name . " = " . $xpay->Response($name, 0) . "<br>";
			}
        echo "<p>";
            
    	if( "0000" == $xpay->Response_Code() ) {
           	//����������û ��� ���� DBó��
           	echo "����������û ��� ���� DBó���Ͻñ� �ٶ��ϴ�.<br>";

            //����������û ��� ���� DBó�� ���н� Rollback ó��
            $isDBOK = true; //DBó�� ���н� false�� ������ �ּ���.
            if( !$isDBOK ) {
            	echo "<p>";
            	$xpay->Rollback("���� DBó�� ���з� ���Ͽ� Rollback ó�� [TID:" . $xpay->Response("LGD_TID",0) . ",MID:" . $xpay->Response("LGD_MID",0) . ",OID:" . $xpay->Response("LGD_OID",0) . "]");            		            		
            		
                echo "TX Rollback Response_code = " . $xpay->Response_Code() . "<br>";
                echo "TX Rollback Response_msg = " . $xpay->Response_Msg() . "<p>";
            		
                if( "0000" == $xpay->Response_Code() ) {
                   	echo "�ڵ���Ұ� ���������� �Ϸ� �Ǿ����ϴ�.<br>";
                }else{
            		echo "�ڵ���Ұ� ���������� ó������ �ʾҽ��ϴ�.<br>";
                }
        	}            	
        }else{
           	//����������û ��� ���� DBó��
           	echo "����������û ��� ���� DBó���Ͻñ� �ٶ��ϴ�.<br>";            	            
        }
    }else {
        //2)API ��û���� ȭ��ó��
        echo "������û�� �����Ͽ����ϴ�.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
            
        //����������û ��� ���� DBó��
        echo "����������û ��� ���� DBó���Ͻñ� �ٶ��ϴ�.<br>";            	                        
   }
?>
