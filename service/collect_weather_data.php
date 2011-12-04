#!/usr/bin/php
<?php
require_once 'common.php';

function check_email_inbox(){
    $mbox = imap_open('{'.SMTP_SERVER.':143/novalidate-cert}INBOX', EMAIL_USERNAME, EMAIL_PASSWORD);
    $total = imap_num_msg($mbox);
    if($total) {
        for($n=1;$n<=$total;$n++) {
            $header = imap_header($mbox,$n);
            $fromaddress = $header->fromaddress;
            $toaddress = $header->toaddress;
            $subject = $header->subject;
            $message_id = $header->message_id;
            $date    = date('Y-m-d H:i:s', $header->udate);
            $body = "";
            
            $st = imap_fetchstructure($mbox,$n);
            if (!empty($st->parts)) {
                for ($i=0,$j=count($st->parts);$i<$j;$i++) {
                    $parts = $st->parts[$i];
                    if ($parts->subtype == 'PLAIN') {
                        $body = imap_fetchbody($mbox,$n,$i+1); 
                        if ($parts->encoding == 4)
                            $body = quoted_printable_decode($body);
                        elseif ($parts->encoding == 3)
                            $body = base64_decode($body);
                    }
                }
            } else {
                $body = imap_body($mbox,$n);
                if ($st->encoding == 4)
                    $body = quoted_printable_decode($body);
                elseif ($st->encoding == 3)
                    $body = base64_decode($body);
            }

            list($from_name,$from_email) = split_email_string($fromaddress);
            list($to_name,$to_email) = split_email_string($toaddress);
            
            insert_to_process_email($from_email,$from_name,$subject,$to_email,$to_name,$body);
            
            imap_delete($mbox, $n);
        }
    }
    
    imap_expunge($mbox);
    imap_close($mbox);
}

// Process email and collect weather data
function process_email(){
	$result = mysql_query("select * from process_email WHERE processed = 0 limit ".EMAILPROCESS_LIMIT) or die (mysql_error());
	while ($row = mysql_fetch_assoc($result)){
		$from_email = $row['from_email'];
		$from_name = $row['from_name'];
		$number = get_number($row['to_email']);
		$text = trim(preg_replace('/[^(\x20-\x7F)]*/','', $row['body']));

        if(is_authorized_email($from_email) && preg_match(DATA_REGEX, $text) && preg_match(PHONE_REGEX,$number) && is_authorized_number($number)) {
		    insert_to_weatherlog($number,$text);
			$rowid=intval($row['id']);
			if(!empty($from_name))
				logmsg("From: ".$from_name."<".$from_email."> Added ".$number." to queue");
			else
				logmsg("From: ".$from_email." Added ".$number." to queue");
		} else {
			logmsg("Sender ".$number." not authorized.");
		}
		
		mysql_query('UPDATE process_email set processed = 1 WHERE id = '.intval($row['id']));
	}
	mysql_free_result($result);
}

// Process sms inbox and collect weather data
function process_sms_inbox(){
    $result = mysql_query("select * from inbox WHERE processed = 0 limit ".SMSPROCESS_LIMIT) or die (mysql_error());
    while ($row = mysql_fetch_assoc($result)){
        $number = preg_replace('/^\+'.COUNTRYCODE.'/','',$row['number']);
        $text = trim(preg_replace('/[^(\x20-\x7F)]*/','', $row['text']));
        
        if(is_authorized_number($number) && preg_match(DATA_REGEX, $text)) {
            insert_to_weatherlog($number,$text);
            logmsg("Added ".$number." to queue");
        } else {
            logmsg("Sender ".$number." not authorized.");
        }
        
        $rowid=intval($row['id']);
        mysql_query('UPDATE inbox set processed = 1 WHERE id = '.$rowid);
        
    }
    mysql_free_result($result);
}

// Uncomment the two lines below to enable data collection through email 
check_email_inbox();
process_email();
process_sms_inbox();

?>
