<?php
class SySQLDB extends SQLite3{
   private $connectionHandle;
   private $sqliteDB = sqliteDB;
   private $connect;



   public function __construct($HOST,$DB,$USER,$PASS){
      $this->connectionHandle =  new PDO ("dblib:host=$HOST:10000;dbname=$DB",$USER,$PASS);
      $this->open($this->sqliteDB);
      $this->connect = new SQLite3($this->sqliteDB);
      }

   public function __destruct(){
      $this->connectionHandle = null;
      $this->connect->close();
      unset($this->connect);
      }


   # Check if there is a report in RIS
   function check_for_report($acc){
      $sql = "SELECT COUNT(*) as report FROM activity_result WHERE acc_itn=$acc";
      $stmt = $this->connectionHandle->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      $report = $result[0]['report'];
      return ($report)?"Yes":"No";
      }


   function update_procedure_status($dtl_svc_cd,$active_flag){
      $sql = "UPDATE item SET active_flag='$active_flag' WHERE dtl_svc_cd='$dtl_svc_cd'";
      $query = $this->connectionHandle->prepare($sql);
      $query->execute();
      }


   # Insert Rows into Sybase DB
   function addprocedures_SB($d,$active,$status='fail'){
      $sql = "INSERT INTO item (dept, proc_desc_short, proc_desc_long, cpt_code1, cpt_descp2, ".
                           "cpt_descp3, cpt_descp4, body_part_mne, proc_no, dtl_svc_cd, ".
                           "hosp, mammography_flag, active_flag, view_reactions, pacs_flag)".
                           " values ('{$d['dept']}', '{$d['proc_desc_short']}', '{$d['proc_desc_long']}', '{$d['cpt_code1']}', '{$d['cpt_descp2']}',".
                                    "'{$d['cpt_descp3']}', '{$d['cpt_descp4']}', '{$d['body_part_mne']}', '{$d['proc_no']}', '{$d['dtl_svc_cd']}',".
                                    "'{$d['hosp']}', '{$d['mammography_flag']}', '$active', '{$d['view_reactions']}', '{$d['pacs_flag']}')";
      if(!$d['dtl_svc_cd'] || $d['dtl_svc_cd']=="ERROR"){ $staus = 'fail'; }
      else{
         $query = $this->connectionHandle->prepare($sql);
         $query->execute();
         $status = ($query->rowCount())?'success':'fail';
         }
      return $status;
      }


   # this string must be unique in not only RIS, but EMR
   # will return true if same code found
   function check_dtl_svc_cd($dtl_svc_cd){
      $sql = "SELECT TOP 1 dtl_svc_cd FROM item WHERE dtl_svc_cd='$dtl_svc_cd'";
      $query = $this->connectionHandle->prepare($sql);
      $query->execute();
      $no = $query->rowCount();
      return($no)?TRUE:FALSE;
      }

   function get_last_procnum($dept){
      $sql = "SELECT TOP 1 proc_no FROM item WHERE dept='$dept' AND proc_no<'9000' ORDER BY proc_no DESC";
      $stmt = $this->connectionHandle->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      $prcnumber = $result[0]['proc_no'];
      $prcnumber = $prcnumber + 1;
      return $prcnumber;
      }


   # Reset Docuemtn_itn ITN & set it to 0
   public function reset_document_itn($acc_itn){
      $result = array('statusfor' => 'Resetting document_itn for: ', 'value'=>"Accession# $acc_itn", 'progress'=>'');
      $itns = explode("\n", $acc_itn);
      $list = array();

      foreach($itns as $v){
         $v = preg_replace("/[^A-Za-z0-9 ]/", '', $v);
         if($v!=''){ array_push($list, $v); }
         }

      if(count($list)<1){ $result['progress'] = 'Invalid Number'; }
      else{
         $acs = "";
         foreach($list as $v){
            if($v>1){
               # Backup first before reset
               $this->backup_doc_itn($v);
               $sql = "UPDATE activity_result SET document_itn=0 WHERE acc_itn=$v";
               $query = $this->connectionHandle->prepare($sql);
               $query->execute();
               $acs .= "$v ";
               }
            }

         $result['progress'] = "Resetting Docuemtn_itn...";
         }
      return $result;
      }

   # Make backup for resetting document itn - and store in DB.
   private function backup_doc_itn($acc){
      $stmt = $this->connectionHandle->prepare("SELECT TOP 1 document_itn FROM activity_result WHERE acc_itn=$acc");
      $stmt->execute();
      $result = $stmt->fetchAll();
      $doc_itn = $result[0]['document_itn'];

      $this->connect->exec("INSERT INTO document_itn_log (acc_itn, document_itn) VALUES ('$acc','$doc_itn')");
      }


   # Remove Technical charge xml
   public function remove_tech_charge_xml($acc_itn){
      $result = array('statusfor' => 'Deleting Technical Charge...', 'value'=>"Accession# $acc_itn", 'progress'=>'');
      if($acc_itn<1){ $result['progress'] = 'Invalid Number'; }
      else{
         # Note that acc_itn no need qoutes
         $sql = "DELETE FROM activity_usr_flds WHERE type='TECH' AND acc_itn=$acc_itn";
         $query = $this->connectionHandle->prepare($sql);
         $query->execute();
         $no = $query->rowCount();
         $result['progress'] = ($no)?"Records deleted = $no, Success!":"Error: No Matching Record Found";
         }
      return $result;
      }


   public function test(){
      $sql = "SELECT top 3 acc_itn,type FROM activity_usr_flds WHERE type='TECH' ORDER BY acc_itn";
      $stmt = $this->connectionHandle->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
   }


   # Get all the departments in UK Healthcare that is part of Radiology services
   public function get_dept(){
      $sql = "SELECT dept,descp FROM dept WHERE dept!='ALL' ORDER BY dept";
      $stmt = $this->connectionHandle->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
      }


   # Get all the procedures from RIS (Radiology Inforamtion System)
   public function get_items($dept,$flag){
      $sql = "SELECT proc_no, proc_desc_short, proc_desc_long, dtl_svc_cd, cpt_code1, cpt_descp2, cpt_descp3, cpt_descp4 ";
         $sql .= "FROM item WHERE dept='$dept' AND active_flag='$flag' ORDER BY proc_no";
      $stmt = $this->connectionHandle->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
      }

      # Get all the procedures from RIS (Radiology Inforamtion System)
   public function get_DicomDevices(){
      $sql = "SELECT dicom_appl_enty_name, appl_enty_type_mne, device_mne FROM dicom_device ORDER BY dicom_appl_enty_name";
      $stmt = $this->connectionHandle->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
      }

   }
?>
