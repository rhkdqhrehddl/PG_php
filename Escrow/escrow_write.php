<?php
    //	return value
	//  true  : 결과연동이 성공
	//  false : 결과연동이 실패

	function write_success($noti){
        //결제에 관한 log남기게 됩니다. log path수정 및 db처리루틴이 추가하여 주십시요.	
	    write_log("C:\\Temp\\php_escrow_write_success.log", $noti);
	    return true;
	}

	function write_failure($noti){
        //결제에 관한 log남기게 됩니다. log path수정 및 db처리루틴이 추가하여 주십시요.	
	    write_log("C:\\Temp\\php_escrow_write_failure.log", $noti);
	    return true;
	}

    function write_hasherr($noti) {
        //결제에 관한 log남기게 됩니다. log path수정 및 db처리루틴이 추가하여 주십시요.	
	    write_log("C:\\Temp\\php_escrow_write_hasherr.log", $noti);
		return true;
    }

	function write_log($file, $noti) {
		$fp = fopen($file, "a+");
		ob_start();
		print_r($noti);
		$msg = ob_get_contents();
		ob_end_clean();
		fwrite($fp, $msg);
		fclose($fp);
	}
  
      
	function get_param($name){
		global $_POST, $_GET;
		if (!isset($_POST[$name]) || $_POST[$name] == "") {
			if (!isset($_GET[$name]) || $_GET[$name] == "") {
				return false;
			} else {
                 return $_GET[$name];
			}
		}
		return $_POST[$name];
	}

?>

