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


   # Modalities
   public function Get_Modalites($device){
      $strSQL = "SELECT SOURCE.MY_CODE, SOURCE.MY_DESCRIPTION, SOURCE.QUERY_CONTEXT_ID, SOURCE.MY_TIMEOUT_MINS,
                        SOURCE.MY_STATUS, SOURCE.QUERY_CONTEXT_ID,
                        FACILITY.MY_DESCRIPTION \"FACILITY_ID_Desc\", ID_CONTEXT.MY_NAME \"OrdSys_MY_NAME\",

                  /* Tracking Health */
                  CASE
                  WHEN SOURCE.MY_STATUS = '0' THEN 'Off'
                  WHEN SOURCE.MY_STATUS = '1' THEN 'On'
                  ELSE CAST (SOURCE.MY_STATUS AS VARCHAR (2))
                  END MY_STATUS,

                  /* Ordering system indicator */
                  CASE
                  WHEN SOURCE.QUERY_CONTEXT_ID = '2' THEN 'Yes'
                  WHEN SOURCE.QUERY_CONTEXT_ID IS NULL THEN 'No'
                  ELSE CAST (SOURCE.QUERY_CONTEXT_ID AS VARCHAR (21))
                  END QUERY_CONTEXT_ID

                  FROM SOURCE
                  LEFT JOIN FACILITY ON SOURCE.FACILITY_ID = FACILITY.MY_FACILITY_ID
                  LEFT JOIN ID_CONTEXT ON SOURCE.ACCESSOR_CONTEXT_ID = ID_CONTEXT.MY_CONTEXT_ID

                  WHERE SOURCE.MY_DEVICE_TYPE=$device ORDER BY SOURCE.MY_CODE";

      $stmt=oci_parse($this->orc_conn,$strSQL);
      if(!oci_execute($stmt)){
         $err = oci_error($stmt);
         trigger_error('Query failed: ' . $err['message'], E_USER_ERROR);
         }
      oci_fetch_all($stmt, $res);
      oci_free_statement($stmt);
      return $res;
      }


   # Check for Possible Conflicts.
   public function check_db_conflict($acc,$cause="Unknown"){
      if($this->check_db_conflict_lila($acc)){ $cause = "LILA"; } 
      elseif($this->check_db_conflict_schedstudy($acc)){ $cause = "Scheduled Study"; }
      return $cause;
      }


   # Check if its in scheduled status conflicts
   private function check_db_conflict_schedstudy($acc){
      $strSQL = "SELECT COUNT(*) SCHEDS FROM STUDY WHERE (MY_ACCESSION_NUMBER = '$acc' AND MY_STATUS=0) ";

      $stmt=oci_parse($this->orc_conn,$strSQL);
      if(!oci_execute($stmt)){
         $err = oci_error($stmt);
         trigger_error('Query failed: ' . $err['message'], E_USER_ERROR);
         }
      oci_fetch_all($stmt, $res);
      oci_free_statement($stmt);

      
      # Return true if there is results
      return ($res['SCHEDS'][0])?true:false;
      }


   # Check LILA Conflicts
   private function check_db_conflict_lila($acc){
      $strSQL = "SELECT COUNT(*) LILA FROM STUDY WHERE (MY_ACCESSION_NUMBER = '$acc' AND FACILITY_ID=20) ";

      $stmt=oci_parse($this->orc_conn,$strSQL);
      if(!oci_execute($stmt)){
         $err = oci_error($stmt);
         trigger_error('Query failed: ' . $err['message'], E_USER_ERROR);
         }
      oci_fetch_all($stmt, $res);
      oci_free_statement($stmt);

      # Return true if there is results
      return ($res['LILA'][0])?true:false;
      }



   # Studies that are reported status w\out results
   public function Get_Studies_WO_Results(){
      $strSQL = "SELECT
                ST.MY_ACCESSION_NUMBER ACCESSION,
                P.MY_LAST_NAME || ', ' || P.MY_FIRST_NAME || ' ' || P.MY_MIDDLE_NAME PATIENT,
                PPI.MY_PUBLIC_ID MRN,
                PT.MY_DESCRIPTION EXAM,
                ST.MY_EXAM_DATE_TIME \"EXAMDATE\",
                PE.MY_LAST_NAME || ', ' || PE.MY_FIRST_NAME || ' ' || PE.MY_MIDDLE_NAME \"REPORTEDBY\",
                ST.MY_REPORTED_DATE_TIME \"REPORTEDDATE\",
                S.MY_DESCRIPTION DEVICE,
                M.MY_NAME MODALITY,
                F.MY_DESCRIPTION FACILITY

            FROM
                FACILITY F,
                ID_CONTEXT ID,
                MODALITY M,
                PATIENT P,
                PATIENT_PUBLIC_ID PPI,
                PERSON PE,
                PROCEDURE_TYPE PT,
                READING_PHYSICIAN RP,
                SOURCE S,
                STUDY ST,
                STUDY_PROC_TYPE SPT,
                STUDY_BODYPART_PROCTYPE_STR SBPS

            WHERE
                ST.MODALITY_ID = M.MY_MODALITY_ID
                AND ST.MY_STUDY_ID = SPT.STUDY_ID
                AND ST.FACILITY_ID = F.MY_FACILITY_ID
                AND ST.MY_STUDY_ID = RP.STUDY_ID
                AND ST.SOURCE_ID = S.MY_SOURCE_ID
                AND ST.PATIENT_ID = PPI.PATIENT_ID
                AND ST.CONTEXT_ID = PPI.CONTEXT_ID
                AND ST.CONTEXT_ID = ID.MY_CONTEXT_ID
                AND ST.PATIENT_ID = P.MY_PATIENT_ID
                AND RP.RADIOLOGIST_ID = PE.MY_PERSON_ID
                AND SPT.PROCEDURE_TYPE_ID = PT.MY_PROCEDURE_TYPE_ID
                AND ST.MY_STUDY_ID = SBPS.STUDY_ID
                AND ST.MY_STATUS = '3'
                AND ST.SERIES_COUNT > '0'
                AND ST.MY_EXTENDED_ATTRIBUTES IN ('8192','8200') /* 8192 is reported with no result or documents. 8200 is reported but has scanned documents */
                AND F.MY_FACILITY_ID NOT IN ('22','21','18','19','20','27','33','25','34','24','17','1','23','32','31','30') /* Filter out facilities that we don't need to see */
                AND SBPS.BODY_PART_STR not like '%NR%' /* Filter out studies that are flagged with NR (non-resultable) body region in PACS Admin */
                AND P.MY_LAST_NAME != 'Trash' /* Ignore trash patients */
                AND ST.MY_REPORTED_DATE_TIME between sysdate-7 AND sysdate-1 /* Filter by date range - last 7 days */
                ORDER BY ST.MY_REPORTED_DATE_TIME";

      $stmt=oci_parse($this->orc_conn,$strSQL);
      if(!oci_execute($stmt)){
         $err = oci_error($stmt);
         trigger_error('Query failed: ' . $err['message'], E_USER_ERROR);
         }
      oci_fetch_all($stmt, $res);
      oci_free_statement($stmt);
      return $res;
      }


   public function __destruct(){ oci_close($this->orc_conn); }

   }
?>