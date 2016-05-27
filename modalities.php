<?php
require_once("controllers/system.cont.php");
if(is_null($SESSION->get_var('username')) || $SESSION->get_var('username')==''){ header("location: logout.php"); exit; }

$DicomDevices = $SySQLDB_PROD->get_DicomDevices();
?><!DOCTYPE html>
<html lang="en">
   <head>
     <?php $exec->get_includes("header"); ?>
   </head>
   <body>

	    <div class="container">
          <?php $exec->get_includes("topmenu");?>
          <div class="row marketing">
              <div class="col-lg-12">
                <h4>Listing All Modalities:</h4>
                <table class="table table-hover">
                   <thead>
                      <tr>
                        <th></th>
                        <th>Hostname</th>
                        <th>Type mne</th>
                        <th>Device mne</th>
                      </tr>
                   </thead>
                   <tbody>
                   <?php
                   $i=0;
                   foreach($DicomDevices as $item){
                    echo"<tr>
                        <td>".++$i."</td>
                        <td>{$item['dicom_appl_enty_name']}</td>
                        <td>{$item['appl_enty_type_mne']}</td>
                        <td>{$item['device_mne']}</td>
                      </tr>";
                      }
                      ?>
                   </tbody>
                </table>

              </div>
            </div>
	    </div><!-- /.container -->

      <?php $exec->get_includes("footer"); ?>
   </body>
</html>
