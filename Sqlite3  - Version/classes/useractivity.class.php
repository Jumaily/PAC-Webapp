<?php
class UserActivity extends SQLite3{
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

   # Get list of submitted procedures
   function show_submitted_procedures($u){
      $d = array();
      $sql =  $this->connect->query("SELECT * FROM addprocedures WHERE uname LIKE '$u' AND processed='N' ORDER by id ASC");
      while($row = $sql->fetchArray(SQLITE3_ASSOC)){ array_push($d, $row); }
      return $d;
      }

   function delete_row($uname,$id){
      $sql = "DELETE FROM addprocedures WHERE id='$id' AND uname LIKE'$uname' ";
      $this->connect->exec($sql);
      $this->updateweblogs("mysql -> $sql");
      }

   function add_user_procedures($p){
      global $SESSION;

      # to avaoid page refresh db inserts
      $SESSION->set_var("addprocedure", true);

      for($i=0;$i<count($p['dept']);$i++){
         $sql = "INSERT INTO addprocedures (uname, datetimestamp, orderable, locationsentfrom, dept, proc_desc_short, ";
         $sql .= "proc_desc_long, cpt_code1, cpt_descp2, cpt_descp3, cpt_descp4, body_part_mne) VALUES ('{$SESSION->get_var('username')}', '";
         $sql .= date("Y-m-d H:i:s")."', '{$p['Orderable']}', '{$_SERVER['REMOTE_ADDR']}', '{$p['dept'][$i]}', '{$p['proc_desc_short'][$i]}', ";
         $sql .= "'{$p['proc_desc_long'][$i]}', '{$p['cpt_code1'][$i]}', '{$p['cpt_descp2'][$i]}', '{$p['cpt_descp3'][$i]}', ";
         $sql .= "'{$p['cpt_descp4'][$i]}', '{$p['body_part_mne'][$i]}')";
         $this->connect->exec($sql);
         }

      # update logs
      $this->updateweblogs("Submit Procedures on ".date("Y-m-d H:i:s"));
      }


   function isadmin($u){
      $count = $this->connect->querySingle("SELECT count(*) FROM admins WHERE admins LIKE '$u' LIMIT 1");
      return ($count)?true:false;
      }

   function isuser($u){
      return false;
      }

   # udpate database logs
   function updateweblogs($action){
      global $SESSION;
      $sql = "INSERT INTO weblogs (uname, tstamp, action, location) VALUES ('{$SESSION->get_var('username')}','".date("Y-m-d H:i:s")."','$action','{$_SERVER['REMOTE_ADDR']}')";
      $this->connect->exec($sql);
      }
   
   function get_departments(){
      $d = array();
      $dept = $this->connect->query("SELECT * FROM departments WHERE 1");
      while($row = $dept->fetchArray(SQLITE3_ASSOC)){ array_push($d,$row); }
      return $d;
      }

   # end class
   }

?>
