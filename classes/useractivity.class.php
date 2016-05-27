<?php
class UserActivity{

   # Add user to table
   function addusertoDB($u){
      global $MysqlDB;
      $MysqlDB->where('user',$u);
      if (count($MysqlDB->get('users'))){
         $result = array('statusfor' => 'Adding User To List...', 'value'=>$u, 'progress'=>'User Already Has Access');
         }
      else{
         $id = $MysqlDB->insert('users', array('user' => $u));
         if($id){ $result = array('statusfor' => 'Adding User To List...', 'value'=>$u, 'progress'=>"User Added, id: $id"); }
         else{ $result = array('statusfor' => 'Adding User To List...', 'value'=>$u, 'progress'=>"Error, didn't add"); }
         }
      return $result;
      }

   # Get list of submitted procedures
   function show_submitted_procedures($u){
      global $MysqlDB;
      $MysqlDB->where('uname', $u);
      $MysqlDB->where('processed', 'N');
      $MysqlDB->orderBy("id","Asc");
      return $MysqlDB->get('addprocedures');
      }

   function delete_row($uname,$id){
      global $MysqlDB;
      $del = "DELETE FROM addprocedures WHERE (id='$id') && (uname='$uname')";
      $MysqlDB->rawQuery($del);
      $this->updateweblogs("mysql -> $del");
      }

   function add_user_procedures($p){
      global $SESSION;
      global $MysqlDB;

      # Unique key for procedure list
      $key=$SESSION->CreateTokenKey();

      # to avaoid page refresh db inserts
      $SESSION->set_var("addprocedure", true);

      for($i=0;$i<count($p['dept']);$i++){
         $data = Array ("uname" => $SESSION->get_var('username'),
                         "fullname"  => "{$SESSION->get_var('firstname')} {$SESSION->get_var('lastname')}",
                         "sessionkey" => $key,
                         "locationsentfrom" => $_SERVER['REMOTE_ADDR'],
                         "dept" => $p['dept'][$i],
                         "proc_desc_short" => $p['proc_desc_short'][$i],
                         "proc_desc_long" => $p['proc_desc_long'][$i],
                         "cpt_code1" => $p['cpt_code1'][$i],
                         "cpt_descp2" => $p['cpt_descp2'][$i],
                         "cpt_descp3" => $p['cpt_descp3'][$i],
                         "cpt_descp4" => $p['cpt_descp4'][$i],
                         "body_part_mne" => $p['body_part_mne'][$i],
                         "orderable"=> $p['Orderable']);
         $MysqlDB->insert('addprocedures', $data);
         }

      # update logs
      $this->updateweblogs("Submit Procedures - Sessionkey: $key");
      }


   function isadmin($u){
      global $MysqlDB;
      $MysqlDB->where('admins', $u);
      return (count($MysqlDB->get('admins')))?true:false;
      }

   function isuser($u){
      global $MysqlDB;
      $MysqlDB->where('user', $u);
      return (count($MysqlDB->get('users')))?true:false;
      }

   # udpate database logs
   function updateweblogs($action){
      global $SESSION;
      global $MysqlDB;
      $data = Array ("uname" => $SESSION->get_var('username'),
                     "firstname" => $SESSION->get_var('firstname'),
                     "lastname" => $SESSION->get_var('lastname'),
                     "action" => $action,
                     "location" => $_SERVER['REMOTE_ADDR']);
      $MysqlDB->insert('weblogs', $data);
      }
   }
?>
