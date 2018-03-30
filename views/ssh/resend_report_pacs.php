<?php
error_reporting(E_ALL); ini_set('display_errors', '1');
$actual_link = "http://$_SERVER[HTTP_HOST]/";
$cpath = "../../";
$filename = "web_resend-reports-to-pacs.txt";

require_once($cpath."controllers/system.cont.php");
require_once($cpath."classes/oracleqry.class.php"); $OrcQry = new OrcQryCls(OrcDB_USERNAME,OrcDB_PASSWORD,OrcDB_PORT,OrcDB_SERVICENAME,OrcDB_HOSTNAME);
$sshc = new SSH_CONNECT(SSH_Serv, SSH_Uname, SSH_Pw);

if(is_null($SESSION->get_var('username')) || $SESSION->get_var('username')==''){ header("location: /logout.php"); exit; }
?><!DOCTYPE html>
<html lang="en">
   <body>
		<?php

		# Get all the reported studies without results from PACS Oracle DB
		$swr = $OrcQry->Get_Studies_WO_Results();
		$accs = '';
		for($i=0;$i<count($swr['ACCESSION']);$i++){ $accs .= "{$swr['ACCESSION'][$i]}\n"; }


		# Write them all to file (overwrite) & close file
		$my_file = 'views/dl-csv/'.$filename;
		$handle = fopen($cpath.$my_file, 'w') or die('IO file error:  '.$my_file);
		fwrite($handle, $accs);
		fclose($handle);

		# link to txt file 
		$txt_file_htmlpath = $actual_link.$my_file;
	
	    # issue commands and output 
        echo "<pre>";
        echo $sshc->issue_cmd("curl $txt_file_htmlpath > $filename");
        echo $sshc->issue_cmd("/SMSCLN/tst/bin/resend_main 3 MPACS1 SIGNED ALL Y /home/dialup/$filename");
        echo "</pre>";

		?>
   </body>
</html>

<?php
class SSH_CONNECT{
   private $ssh_conn = null;
   public function __construct($server,$username,$password){
      $this->ssh_conn = ssh2_connect($server);
      ssh2_auth_password($this->ssh_conn, $username, $password);
      }
    
   public function issue_cmd($cmd,$out=''){
      $stream = ssh2_exec($this->ssh_conn, $cmd);
      $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
	        
      # Enable Blocking
      stream_set_blocking($errorStream, true);
      stream_set_blocking($stream, true);

	  # Format output and display
	  $out .= "<p>Output: ".stream_get_contents($stream)."</p>";
      $out .= "<p><em>Error: " . stream_get_contents($errorStream)."</em></p> <hr/>";
		
      # close the streams       
      fclose($errorStream);
	  fclose($stream);
	  return $out;
      }

   }
?>