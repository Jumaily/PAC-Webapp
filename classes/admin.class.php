<?php
class adminsection{

   function get_user_procedures(){
      global $MysqlDB;
      return $MysqlDB->rawQuery("SELECT uname,ANY_VALUE(fullname) FROM addprocedures WHERE processed='N' GROUP BY uname");
      }

   # Get data from mysql and send it to sybase
   function send_to_SyngoDB($u){
      global $MysqlDB;
      global $SySQLDB;
      global $SySQLDB_PROD;
      global $UA;

      $MysqlDB->where('uname', $u);
      $MysqlDB->where('processed', 'N');
      $MysqlDB->where('syngo_sent','N');
      $MysqlDB->orderBy("dept","Asc");
      $msqldata = $MysqlDB->get('addprocedures');

      # Insert row by row, if succcess then mark it off as sent
      foreach ($msqldata as $row){
	      # Test ; active = Yes "Y"
         $status = $SySQLDB->addprocedures_SB($row,'Y');
         # Production ; active = No "N"
         $prod_insert = $SySQLDB_PROD->addprocedures_SB($row,'N');

         if($status == 'success'){
            # Update logs
            $UA->updateweblogs("Syngo insert database: Success. Row ID: {$row['id']}");
            # Mark sent
            $MysqlDB->where('id',$row['id']);
            $MysqlDB->update('addprocedures', array('syngo_sent'=>'Y'));
            }
         else{ $UA->updateweblogs("Syngo insert database: Failed. Row ID: {$row['id']}"); }
         }
      return $status;
      }


   function check_send_ris($u,$disabled=''){
      global $MysqlDB;
      if($u!=''){
         $MysqlDB->where('uname',$u);
         $MysqlDB->where('syngo_sent','N');
         $disabled=(count($MysqlDB->get('addprocedures')))?"":'disabled';
         }
      return $disabled;
      }

   function markdone($u){
      global $MysqlDB;
      if($u!=''){
         $MysqlDB->where('uname',$u);
         $MysqlDB->update('addprocedures', array('processed'=>'Y'));
         }
      }

   function update_userrows($p){
      global $MysqlDB;
      foreach ($p['ids'] as $key => $value) {
         $data = Array ( "proc_no" => $p[$value.'_proc_no'],
                         "dtl_svc_cd" => $this->get_dtl_svc_cd($p[$value.'_proc_no'],$p[$value.'_ABC'],$p[$value.'_dept']),
                         "mammography_flag" => $p[$value.'_mammography_flag'],
                         "active_flag" => $p[$value.'_active_flag'],
                         "view_reactions" => $p[$value.'_view_reactions'],
                         "dept" => $p[$value.'_dept'],
                         "proc_desc_long" => $p[$value.'_proc_desc_long']);
         $MysqlDB->where ('id', $value);
         $MysqlDB->update ('addprocedures', $data);
         }
      }

   private function get_dtl_svc_cd($proc_no,$abc,$dept,$dtl_svc_cd=''){
      global $SySQLDB;
      global $SySQLDB_PROD;
      global $MysqlDB;

      # MySql get Rcode
      $MysqlDB->where('dept', $dept);
      $rcode = $MysqlDB->getone("departments", Array("rcode"));
      $dtl_svc_cd = $rcode['rcode'].$proc_no.$abc;

      # this string must be unique in not only RIS, but EMR. Checking prod and test
      if($SySQLDB->check_dtl_svc_cd($dtl_svc_cd) || $SySQLDB_PROD->check_dtl_svc_cd($dtl_svc_cd_prod)){
         return "ERROR";
	}
      else{
	return $dtl_svc_cd;
	}
      }


   }
?>
