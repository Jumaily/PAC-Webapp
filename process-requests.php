<?php
require_once("controllers/system.cont.php");
if(is_null($SESSION->get_var('username')) || $SESSION->get_var('username')==''){ header("location: logout.php"); exit; }

# remove Technical Charge
if(isset($_POST['submit-type'])){
   switch ($_POST['submit-type']) {
      case "remove-tech-charge":
         $prgs = $SySQLDB_PROD->remove_tech_charge_xml(preg_replace("/[^0-9]/",'',$_POST['acc_itn']));
         break;
      case "add-user-perm":
         $prgs = $UA->addusertoDB(preg_replace("/[^0-9a-zA-Z_\s]/",'',$_POST['adduserperm']));
         break;
      default: ;
      }

   #Update Logs:
   $UA->updateweblogs("{$prgs['statusfor']} {$prgs['value']} {$prgs['progress']}");
   }

else{
   $prgs['value'] = 'Invalid Request';
   $prgs['progress'] = "Nothing to do";
   $prgs['statusfor'] = "Unknown...";
   }
?>
<?php
header('Content-Type: application/xml;  charset=utf-8"');
$output =
'<?xml version="1.0" encoding="UTF-8"?>
<response>
   <status statusfor="'.$prgs['statusfor'].'">
      <progress>'.$prgs['progress'].'</progress>
      <value>'.$prgs['value'].'</value>
   </status>
</response>';
echo $output;
?>
