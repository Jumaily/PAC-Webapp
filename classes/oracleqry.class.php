<?php
class OrcQryCls{
   private $orc_conn = null;

   public function __construct($username,$password,$port,$servicename,$hostname){
      $str = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$hostname)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$servicename)))";
      $this->orc_conn = oci_connect($username,$password,$str);
      if(!$this->orc_conn){
         $e = oci_error();
         trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
         }
      }



   public function Get_Body_Parts(){
      $strSQL = "SELECT MY_BODY_PART_ID, MY_CODE, MY_DESCRIPTION FROM BODY_PART ORDER BY MY_DESCRIPTION";
      $stmt=oci_parse($this->orc_conn,$strSQL);
      if(!oci_execute($stmt)){
         $err = oci_error($stmt);
         trigger_error('Query failed: ' . $err['message'], E_USER_ERROR);
         }
      oci_fetch_all($stmt, $res);
      oci_free_statement($stmt);
      return $res;
      }


   public function __destruct(){
      oci_close($this->orc_conn);
      }
   
   }
?>