<?php
class adminsection extends SQLite3{
   private $sqliteDB = sqliteDB;
   private $connect;

   public function __construct(){
      $this->open($this->sqliteDB);
      $this->connect = new SQLite3($this->sqliteDB);
      }

   public function __destruct(){
      $this->connect->close();
      unset($this->connect);
      }

   function get_user_procedures(){
      $d = array();
      $sql = $this->connect->query("SELECT uname FROM addprocedures WHERE processed='N' GROUP BY uname");
      while($row = $sql->fetchArray(SQLITE3_ASSOC)){ array_push($d, $row); }
      return $d;
      }

   # Get data from mysql and send it to sybase
   function send_to_SyngoDB($u){
      global $SySQLDB;
      global $SySQLDB_PROD;
      global $UA;

      # Insert row by row, if succcess then mark it off as sent
      $sql = $this->connect->query("SELECT * FROM addprocedures WHERE processed='N' AND syngo_sent='N' AND uname LIKE '$u'");
      while($row = $sql->fetchArray(SQLITE3_ASSOC)){ 
	      # Test ; active = Yes "Y"
         $status = $SySQLDB->addprocedures_SB($row,'Y');
         
         # Production ; active = No "N"
         $prod_insert = $SySQLDB_PROD->addprocedures_SB($row,'N');

         if($status == 'success'){
            # Update logs
            $UA->updateweblogs("Syngo insert database: Success. Row ID: {$row['id']}");
            # Mark sent
            $this->connect->exec("UPDATE addprocedures SET syngo_senT='Y' WHERE id='{$row['id']}'");
            }
         else{ $UA->updateweblogs("Syngo insert database: Failed. Row ID: {$row['id']}"); }
         }
      return $status;
      }


   function check_send_ris($u,$disabled=''){
      if($u!=''){
         $count = $this->connect->querySingle("SELECT count(*) FROM addprocedures WHERE syngo_sent='N' AND uname LIKE '$u' ");
         $disabled=($count)?'':'disabled';
         }
      return $disabled;
      }

   function markdone($u){
      if($u!=''){
         $this->connect->query("UPDATE addprocedures SET processed='Y' WHERE uname='$u'");
         }
      }

   function update_userrows($p){
      foreach ($p['ids'] as $key => $value) {
         $sql = "UPDATE addprocedures SET proc_no='".$p[$value.'_proc_no']."', dtl_svc_cd='".$this->get_dtl_svc_cd($p[$value.'_proc_no'],$p[$value.'_ABC'],$p[$value.'_dept'])."', ";
         $sql .= "mammography_flag='".$p[$value.'_mammography_flag']."', active_flag='".$p[$value.'_active_flag']."', view_reactions='".$p[$value.'_view_reactions']."',";
         $sql .= "dept='".$p[$value.'_dept']."', proc_desc_long='".$p[$value.'_proc_desc_long']."' WHERE id='$value'";
         $this->connect->query($sql);
         }
      }

   private function get_dtl_svc_cd($proc_no,$abc,$dept,$dtl_svc_cd=''){
      global $SySQLDB;
      global $SySQLDB_PROD;
     
      $sql = $this->connect->query("SELECT rcode FROM departments WHERE dept='$dept'");
      $row = $sql->fetchArray(SQLITE3_ASSOC);
      $dtl_svc_cd = $row['rcode'].$proc_no.$abc;

      # this string must be unique in not only RIS, but EMR. Checking prod and test
      if($SySQLDB->check_dtl_svc_cd($dtl_svc_cd) || $SySQLDB_PROD->check_dtl_svc_cd($dtl_svc_cd)){
         return "ERROR";
	      }
	   else{
		   return $dtl_svc_cd;
		   }
      }



   # Get data from mysql and send it to sybase
   function ProceduresStatus_SyngoDB($slist,$active_flag,$envs){
      global $SySQLDB;
      global $SySQLDB_PROD;
      $list = array();

      # clean up list first
      foreach($slist as $v){
         $v = preg_replace("/[^A-Za-z0-9 ]/", '', $v);
         if($v!=''){ array_push($list, $v); }
         }

      # Process List
      if($envs['tst']){ foreach($list as $v){ $SySQLDB->update_procedure_status($v,$active_flag); } }
      if($envs['prd']){ foreach($list as $v){ $SySQLDB_PROD->update_procedure_status($v,$active_flag); }  }

      return $list;
      }



   }
?>
