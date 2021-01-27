<?
    /*
     * 1. 기본결제정보 변경
     *
     * 결제기본정보를 변경하여 주시기 바랍니다.     
     */
     $CST_PLATFORM     = "test";     							//토스페이먼츠 결제 서비스 선택(test:테스트, service:서비스)
     $CST_MID          = "mk_KVP_selex";           							//상점아이디(토스페이먼츠으로 부터 발급받으신 상점아이디를 입력하세요)
                                                                //테스트 아이디는 't'를 반드시 제외하고 입력하세요.
     $LGD_MID  = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;   //상점아이디(자동생성)

	 $LGD_OID          = "test_0001";							//[반드시 세팅]주문번호(상점정의 유니크한 주문번호를 입력하세요)
     $LGD_AMOUNT       = "1004"	;								//결제금액("," 를 제외한 결제금액을 입력하세요)    
     $LGD_BUYER        = "홍길동";								//구매자명
     $LGD_PRODUCTINFO  = "테스트상품";							//상품명 
     $LGD_NOTEURL      = "";               						//상점결제결과 처리(DB) 페이지 URL (URL을 입력해 주세요. 예: http://pgweb.tosspayments.com/note_url.php)	
     $LGD_TAXFREEAMOUNT= "";									//면세금액 (면세상품취급 상점의 경우 사용 - 사용전 "부분면세" 계약을 확인하세요)

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>결제요청</title>
<script type="text/javascript">

/*
 * 결제요청 및 결과화면 처리 
 */
function doAnsimKeyin(){
//CST_PLATFORM은 test(테스트) 또는 service(서비스)를 넘겨주시면 됩니다. 
    ret = ansimkeyin_check(document.getElementById('LGD_PAYINFO'), 'test'); 

    if (ret=="00"){ //plug-in 정상 로딩
        var LGD_RESPCODE       = dpop.getData('LGD_RESPCODE');       //결과코드
        var LGD_RESPMSG        = dpop.getData('LGD_RESPMSG');        //결과메세지
	      if( "0000" == LGD_RESPCODE ) { //결제성공 
            var LGD_TID            = dpop.getData('LGD_TID');            //토스페이먼츠 거래KEY
	          var LGD_PAYDATE        = dpop.getData('LGD_PAYDATE');        //결제일자
            var LGD_FINANCECODE    = dpop.getData('LGD_FINANCECODE');    //결제기관코드
            var LGD_FINANCENAME    = dpop.getData('LGD_FINANCENAME');    //결제기관이름
            var LGD_NOTEURL_RESULT = dpop.getData('LGD_NOTEURL_RESULT'); //상점DB처리결과

            alert("결제가 성공하였습니다. 토스페이먼츠 거래ID : " + LGD_TID);
            /*
             * 결제성공 화면 처리
             */        
       
        } else { //결제실패
             alert("결제가 실패하였습니다. " + LGD_RESPMSG);
            /*
             * 결제실패 화면 처리
             */        
        }
    } else {
        alert("토스페이먼츠 전자결제를 위한 ActiveX 설치 실패");
    }     
}

        
</script>
</head>

<body>
<form method="post" id="LGD_PAYINFO">
<input type="hidden" name="LGD_MID"             value="tmk_KVP_selex">									<!-- 상점아이디 -->
<input type="hidden" name="LGD_NOTEURL"         value="https://rhkdqhrehddl.tk:9443/AnsimKeyin/note_url.php">			<!-- 결제결과처리_URL(LGD_NOTEURL) -->
<table>
    <tr>
        <td>주문번호 </td>
        <td><input type="text" name="LGD_OID"     value="<?= $LGD_OID ?>">	<!-- 주문번호 --></td>
    </tr>
    <tr>
        <td>상품정보 </td>
        <td><input type="text" name="LGD_PRODUCTINFO"     value="<?= $LGD_PRODUCTINFO ?>">		<!-- 상품정보 --></td>
    </tr>
    <tr>
        <td>결제금액 </td>
        <td><input type="text" name="LGD_AMOUNT"  value="<?= $LGD_AMOUNT ?>">		<!-- 결제금액 --></td>
    </tr>
    <tr>
        <td>구매자명 </td>
        <td><input type="text" name="LGD_BUYER"   value="<?= $LGD_BUYER ?>">	<!-- 구매자 --></td>
    </tr>
        
    <tr>
        <td colspan="2">* 추가 상세 결제요청 파라미터는 메뉴얼을 참조하세요.</td>
    </tr>
    <tr>
        <td>
        <input type="button" value="결제요청(ActiveX)" onclick="doAnsimKeyin()"/><br>
        </td>
    </tr>
</table>
</form>
</body>
<!--  xpay.js는 반드시 body 밑에 두시기 바랍니다. -->
<script language="javascript" src="https://xpay.tosspayments.com/ansim-keyin/js/ansim-keyin.js" type="text/javascript"></script>
</script>
</html>
