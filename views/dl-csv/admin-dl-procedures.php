<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=proceduresfor_{$_GET['uname']}.csv");
require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/controllers/system.cont.php");
if((is_null($SESSION->get_var('username')) || $SESSION->get_var('username')=='')||(!$SESSION->get_var('admin'))){ header("location: logout.php"); exit; }
require_once(SPATH."/classes/useractivity.class.php"); $UA = new UserActivity();
$myproc = $UA->show_submitted_procedures($_GET['uname']);

echo "dept,proc_no,proc_desc_short,proc_desc_long,sub_folder,hosp,proc_left_right,";
echo "dtl_svc_cd,user_cd_1,user_cd1_descp,user_cd_2,user_cd2_descp,mammography_flag,";
echo "active_flag,view_reactions,cpt_code1,cpt_descp2,cpt_descp3,cpt_descp4,body_part_mne,pacs_flag\r\n";

   foreach ($myproc as $c){
      echo "{$c['dept']},{$c['proc_no']},{$c['proc_desc_short']},{$c['proc_desc_long']},";
      echo "{$c['sub_folder']},{$c['hosp']},{$c['proc_left_right']},{$c['dtl_svc_cd']},";
      echo "{$c['user_cd_1']},{$c['user_cd1_descp']},{$c['user_cd_2']},{$c['user_cd2_descp']},";
      echo "{$c['mammography_flag']},{$c['active_flag']},{$c['view_reactions']},{$c['cpt_code1']},";
      echo "{$c['cpt_descp2']},{$c['cpt_descp3']},{$c['cpt_descp4']},{$c['body_part_mne']},{$c['pacs_flag']}\r\n";
      }
      ?>
