<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class DB {

		
    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        error_reporting(E_ERROR | E_PARSE);
        require 'config.php';
        mysql_connect($HOST, $USERNAME, $PASSWORD) or die(mysql_error());
        mysql_select_db($DB) or die(mysql_error());
    }
	
	
	public $prefix = "final_";
    
	public function Execute($query) {
        return mysql_query($query) or die(mysql_error());
    }

    public function LoginUser($user, $pass) {
        $password = md5($pass);
		$prefix = $this->prefix;
        $q = "select * from $prefix"."admin where (email='$user' OR username='$user') AND password='$password'";
        $r = mysql_query($q) or die(mysql_error());
        if (mysql_affected_rows() == 1) {
            if (session_id() == '') {
                session_start();
            }
            $_SESSION['is_logged'] = true;
            $admin = $this->FetchArray($r);
            unset($admin['password']);
            $_SESSION['data'] = $admin;
            return true;
        } else {
            return false;
        }
    }

    public function FetchArray($r) {
        return mysql_fetch_assoc($r);
    }

    public function Is_logged() {
        if (session_id() == '') {
            session_start();
        }
        if (isset($_SESSION['is_logged'])) {
            return $_SESSION['is_logged'];
            return true;
        } else {

            return false;
        }
    }

	


    public function GetUserName() {

        return $_SESSION['data']['username'];
    }

    public function GetUserEmail() {

        return $_SESSION['data']['email'];
    }
	 public function GetUserBid() {
		session_start();
        return $_SESSION['data']['bid'];
    }

    public function LogMeOut() {
        if (session_id() == '') {
            session_start();
        }
		$_SESSION = array();
        $_SESSION['is_logged'] = false;
	
        session_destroy();
    }

    public function getAllUsers($status = NULL) {
        $st = "";
		$prefix = $this->prefix;
        if ($status == "ACTIVE"){
            $st = "WHERE is_active=1 and bid='".$_SESSION['data']['bid']."' group by gcm_id";
		}else{
			$st = "WHERE is_active=1 and bid='".$_SESSION['data']['bid']."' group by gcm_id";
		}
        $q = "select * from $prefix"."users $st";
		
        $r = mysql_query($q) or die(mysql_error());
        $users = array();
        while ($row = mysql_fetch_assoc($r)) {
            $users[] = $row;
        }
        return $users;
    }

    public function getAllNotifications() {
		$prefix = $this->prefix;
        $q = "select * from $prefix"."notifications LIMIT 100";
        $r = mysql_query($q) or die(mysql_error());
        $users = array();
        while ($row = mysql_fetch_assoc($r)) {
            $users[] = $row;
        }
        return $users;
    }
    
   
	public function getAllUsers_withCategories($where = FALSE, $cat) {
        $wc = "WHERE 1 AND ";
        if (!empty($cat)) {
            foreach ($cat as $key => $value) {
                $wc.="FIND_IN_SET('$value',categories) > 0 OR ";
            }
        }

        $pos = strrpos($wc, "OR");

        if ($pos !== false) {
            $wc = substr_replace($wc, "AND", $pos, strlen("OR"));
        }
        if ($where) {
            $wc.=" is_active=1";
        }
        $q = "select * from users $wc";
        
        $r = mysql_query($q) or die(mysql_error());
        $cat = array();
        while ($row = mysql_fetch_assoc($r)) {
            $cat[] = $row;
        }
        return $cat;
    }

	 public function GetUserByType_withCategories($type, $cat) {
        $wc = " ";
        if (!empty($cat)) {
            foreach ($cat as $key => $value) {
                $wc.="FIND_IN_SET('$value',categories) > 0 OR ";
            }
        }

        $pos = strrpos($wc, "OR");

        if ($pos !== false) {
            $wc = substr_replace($wc, "AND", $pos, strlen("OR"));
        }

        $wc.=" is_active=1";


        $q = "select * from users where app_type='$type' AND $wc ";

      
        $r = mysql_query($q) or die(mysql_error());
        $users = array();
        while ($row = mysql_fetch_assoc($r)) {
            $users[] = $row;
        }
        return $users;
    }
	

    

    public function str_lreplace($search, $replace, $subject) {


        return $subject;
    }

    
 
    public function getAllCategories($where = FALSE,$cat_id=NULL) {
        $wc = "";
        if ($where) {
		if($cat_id==null){
			$wc = "WHERE is_active=1";
		}else{
			$wc = "WHERE is_active=1 AND id='$cat_id'";
		}
            
        }
			
		
		
        $q = "select * from categories $wc";
        
     
		
        $r = mysql_query($q) or die(mysql_error());
        $cat = array();
        while ($row = mysql_fetch_assoc($r)) {
            $cat[] = $row;
        }
        
        return $cat;
    }
	
	


    
    public function deleteCat($cat,$table)
{
	 $r=mysql_query("delete from $table where id='$cat'") or die(mysql_error());
	 if($r){
	 	echo "$table Deleted!";
	 }else{
	 	echo "There is some problem please try again later";
	 }
}

	public function updateCat($data){
		$id=$data["cat_id"];
		$name=$data["cat_name"];
		$desc=$data["cat_desc"];
		
		$q="UPDATE categories set cat_name='$name',cat_desc='$desc' WHERE id='$id'";
		 $r=mysql_query($q)or die("Error Updating DB.");
		 if($r){
	 	echo "Category Updated!";
	 }else{
	 	echo "There is some problem please try again later";
	 }
	}
    

	
	   public function getCategories($cat_id) {
        $wc = "";
       
		if($cat_id==null){
			$wc = "WHERE is_active=1";
		}else{
			$wc = "WHERE is_active=1 AND id='$cat_id'";
		}
		
        $q = "select * from categories $wc order by pos_id asc";
		
        $r = mysql_query($q) or die(mysql_error());
        $cat = array();
        while ($row = mysql_fetch_assoc($r)) {
            $cat[] = $row;
        }
        return $cat;
    }

    
    public function changeUserAccess($type, $id) {
		$prefix = $this->prefix;
        $q = "UPDATE $prefix"."users SET is_active=$type where uid='$id'";
        $r = mysql_query($q) or die(mysql_error());

        return mysql_affected_rows();
    }

    public function changeCatAccess($type, $id) {
        $q = "UPDATE categories SET is_active=$type where id='$id'";
        $r = mysql_query($q) or die(mysql_error());

        return mysql_affected_rows();
    }

    public function sendNotification($registatoin_ids, $msg, $nid,$load,$device_type) {

        if (count($registatoin_ids) > 999) {
            $chunk = array_chunk($registatoin_ids, 999);
            $re = array();
            foreach ($chunk as $key => $value) {
                $re[] = $this->sendNoty($value, $msg, $nid, true,$load,$device_type);
            }
        } else {
            $this->sendNoty($registatoin_ids, $msg, $nid, false,$load,$device_type);
        }
    }

	
	
    public function sendNoty($id, $load, $nid, $is_chunk,$msg,$device_type) {

		$prefix = $this->prefix;
        //     print_r($load);
        ////////////////////////////////////////////////////////////////////////////////
        for($kk=0;$kk<count($device_type);$kk++){
			
		if($device_type[$kk]=="ios"){
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'Certificates/ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', PASSPHRASE);
        
        // Open a connection to the APNS server
        $fp = stream_socket_client(
                                   'ssl://gateway.sandbox.push.apple.com:2195', $err,
                                   $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        
       // echo 'Connected to APNS' . PHP_EOL;
        
        // Create the payload body
        $body['aps'] = array(
                             'alert' => $msg["title"],
                             'sound' => 'default',
                             'e'=>$load
                             );
        
        // Encode the payload as JSON
        $payload = json_encode($body);
        

         if(count($id)==1){
             
            $deviceToken=$id[0];
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        
            $result = fwrite($fp, $msg, strlen($msg));
            if($result)
                echo "Notification Sent";
            else
                echo "Error Try Later!";
        }else{
            $count=0;
            $failed=0;
            foreach ($id as $deviceToken) {
              $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
                $result = fwrite($fp, $msg, strlen($msg));
                if($result)
                    $count++;
                else
                    $failed++;

            }
            echo $count." Devices Received.<br/>".$failed." Devices Failed!.";
        }
fclose($fp);

        // Build the binary notification
        
		}
		else if($device_type[$kk]=="android"){
			
			// Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $id,
            'data' => $load,
        );
//print_r(GOOGLE_API_KEY);
        $headers = array(
            'Authorization: key='.GOOGLE_API_KEY,
            'Content-Type: application/json'
        );


        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields, true));

        // Execute post

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $status = "";
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            $status = "FAIL";
        }

        $report = array();
        // Close connection
        curl_close($ch);
//        echo $result;
        //print_r($id);
        $jsonText = preg_replace('/\s+/','' , $result);
        $decodedText = html_entity_decode($jsonText);
        $myArray = json_decode($decodedText, true);
        //echo $myArray["success"] . "  <==== success<br>";
        //echo $myArray["failure"] . " <====failed<br>";
        //print_r($myArray);
        $report['nid'] = $nid;

        $report['passed'] = $myArray["success"];
        $report['failed'] = $myArray["failure"];
        $report['status'] = $status;

        if ($myArray["failure"] > 0 && $myArray["success"] == 0) {
            $status = "FAIL";
        } elseif ($myArray["failure"] > 0 && $myArray["success"] > 0) {
            $status = "PARTIAL SUCCESS";
        } elseif ($myArray["failure"] == 0 && $myArray["success"] > 0) {
            $status = "SUCCESS";
        }

        $cnt = 0;
        $upd = 0;
        if (is_array($myArray)) {
            foreach ($myArray['results'] as $key => $value12) {
                //print_r($value);
                if (array_key_exists("error", $value12)) {
                    $del = $id[$key];
                    //echo $del." may be deleted!<br>";
                    mysql_query("delete from $prefix"."users where gcm_id='$del'") or die(mysql_error());
                    $cnt++;
                }
                if (array_key_exists("registration_id", $value12)) {
                    $del = $id[$key];
                    $up = $value12['registration_id'];
                    //echo $value12['registration_id']." may be updated!<br>";
                    mysql_query("UPDATE $prefix"."users SET gcm_id='$up' where gcm_id='$del'") or die(mysql_error());
                    $upd++;
                }
            }
        }

        $report['updated'] = $upd;
        $report['removed'] = $cnt;

        if ($is_chunk) {
            return $report;
        } else {
            $this->AddReport($report);


            echo $report['passed'] . " Users Succees<br>";
            echo $report['failed'] . " Users Failed<br>";
            echo $report['updated'] . " Users ID updated<br>";
            echo $report['removed'] . " Users removed your Application.<br>";
        }
		}
    
 
    }
	}

    function setUserCategories($param) {
        $email = $param['email'];
        $cat = $param['cat'];

        $q = "UPDATE users SET categories='$cat' where gcm_id='$email'";
        $r = mysql_query($q) or die(mysql_error());

        if ($r) {
            return array('status' => 'SUCCESS');
        } else {
            return array('status' => 'FAIL');
        }
    }

    public function GetAppList() {
        $q = "select distinct app_type from users";
        $r = mysql_query($q) or die(mysql_error());
        $apps = array();
        while ($row = mysql_fetch_assoc($r)) {
            $apps[] = $row;
        }
        return $apps;
    }

    public function GetUserByType($type) {
        $q = "select * from users where app_type='$type' AND is_active=1 group by gcm_id";
        $r = mysql_query($q) or die(mysql_error());
        $users = array();
        while ($row = mysql_fetch_assoc($r)) {
            $users[] = $row;
        }
        return $users;
    }

    public function AddNotification($data) {
		$prefix = $this->prefix;
        $type = $data['type'];
        $title = mysql_real_escape_string($data['title']);
        $msg = mysql_real_escape_string($data['message']);
        $link = mysql_real_escape_string($data['link']);
        $emotion = mysql_real_escape_string($data['emotion']);

        $query = "INSERT INTO $prefix"."notifications (type,title,message,link,emotion) VALUES ('$type','$title','$msg','$link','$emotion');";
        $r = mysql_query($query) or die(mysql_error());
        if (!$r) {
            return false;
        } else {
            return mysql_insert_id();
        }
    }

    public function AddCategory($data) {

        $title = mysql_real_escape_string($data['cat_name']);
        $msg = mysql_real_escape_string($data['cat_desc']);



        $query = "INSERT INTO categories (cat_name,cat_desc) VALUES ('$title','$msg');";
        $r = mysql_query($query) or die(mysql_error());
        if (!$r) {
            return false;
        } else {
            return mysql_insert_id();
        }
    }

    public function AddReport($data) {
        $nid = $data['nid'];
        $status = $data['status'];
        $passed = $data['passed'];
        $failed = $data['failed'];
        $updated = $data['updated'];

        $query = "INSERT INTO reports (nid,status,passed,failed,updated) VALUES ('$nid','$status','$passed','$failed','$updated');";
        $r = mysql_query($query) or die(mysql_error());
        if (!$r) {
            return false;
        } else {
            return true;
        }
    }

    public function GetGraphsByRange($from, $to) {
        $q = "SELECT * from notifications as noty JOIN reports as report ON noty.nid = report.nid where DATE(noty.time) >= '$from' AND DATE(noty.time) <= '$to'";

        $r = mysql_query($q) or die(mysql_error());
        $data = array();

        while ($row = mysql_fetch_assoc($r)) {
            switch ($row['type']) {
                case 1:
                    $row['type'] = "Simple Notification";
                    break;
                case 2:
                    $row['type'] = "Dialoge Notification";
                    break;
                case 3:
                    $row['type'] = "WebActivity Notification";
                    break;
                case 4:
                    $row['type'] = "Toast Notification";
                    break;
                case 5:
                    $row['type'] = "News Notification";
                    $row['is_news'] = true;
                    break;

                default:
                    break;
            }
            //$row['time']=strtotime($row['time']) * 1000;
            $data[] = $row;
        }

        if (empty($data)) {
            return "NO DATA";
        } else {
            return $data;
        }
    }

    function AddUserGCM($data) {
      
		$prefix = $this->prefix;
		$email = $data["email"];
		$dtype = $data["device_type"];
		if($dtype=="android"){
		$apptypeAndBid=explode("#",$data["app_type"]);
        $app = $apptypeAndBid[0];
        $bid=$apptypeAndBid[1];
		
		}elseif($dtype=="ios"){
			
			$app = $data["app_type"];
			$bid = $data["bid"];
		}
		$gcm = $data["regid"];
		
		
        $q = "INSERT INTO $prefix"."users (email,gcm_id,app_type,bid,device_type) VALUES('$email','$gcm','$app','$bid','$dtype')";

        $r = mysql_query($q) or die(mysql_error());
        if (!$r) {
            return false;
        } else {
            return mysql_insert_id();
        }
		
		
    }

    function ChangePassword($param) {
        $param = md5($param);
        $which = $this->GetUserName();

        $q = "UPDATE admin set password='$param' where username='$which'";
        $r = mysql_query($q) or die(mysql_error());
        if ($r) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function CheckPassword($param) {
        $param = md5($param);
        $which = $this->GetUserName();
        $q = "SELECT * from admin where password='$param' AND username='$which'";

        mysql_query($q) or die(mysql_error());
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

}

?>