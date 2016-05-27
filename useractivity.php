<?php
require_once("controllers/system.cont.php");
if(is_null($SESSION->get_var('username')) || $SESSION->get_var('username')==''){ header("location: logout.php"); exit; }

# Delete procedures
if(isset($_POST['deleterow'])){ $UA->delete_row($SESSION->get_var('username'),$_POST['deleterow']); header("location: {$_SERVER['PHP_SELF']}"); exit;  }
?><!DOCTYPE html>
<html lang="en">
   <head>
     <?php $exec->get_includes("header"); ?>
   </head>
   <body>

	    <div class="container">
          <?php $exec->get_includes("topmenu");?>

          <?php
          if((isset($_POST['submit']) && $_POST['submit'] = "Submit Procedures")&&(!$SESSION->get_var('addprocedure'))){
             $UA->add_user_procedures($_POST);
             echo '<p class="lead"><kbd>Saving requested Procedures... Done!</kbd></p>';
             }

          $myproc = $UA->show_submitted_procedures($SESSION->get_var('username'));
          $cp = count($myproc);
          if($cp){
             ?>
             <div class="table-responsive">
               <table class="table">
                  <thead>
                    <tr>
                        <th></th>
                        <th>Department</th>
                        <th>Mnemonic</th>
                        <th>Description</th>
                        <th>Body Part</th>
                        <th>TMID code</th>
                        <th>CDM Code</th>
                        <th>CPT Code</th>
                        <th>Cost Center</th>
                        <th>Orderable (Y/N)</th>
                        <th></th>
                     </tr>
                  </thead>
                <tbody>
                <?php
                $i=1;
                foreach ($myproc as $c){
                  echo "<tr>
                           <th scope=\"row\">".$i++.".</th>
                           <th>{$c['dept']}</th>
                           <th>{$c['proc_desc_short']}</th>
                           <th>{$c['proc_desc_long']}</th>
                           <th>{$c['body_part_mne']}</th>
                           <th>{$c['cpt_descp3']}</th>
                           <th>{$c['cpt_descp2']}</th>
                           <th>{$c['cpt_code1']}</th>
                           <th>{$c['cpt_descp4']}</th>
                           <th>{$c['orderable']}</th>".
                           '<th>
                              <form method="post">
                                 <input type="submit" value="Delete Procedure"  class="btn btn-warning btn-xs">
                                 <input type="hidden" value="'.$c['id'].'" name="deleterow">
                              </form>
                           </th>'.
                        "</tr>";
                  }
                 ?>
                  </tbody>
               </table>
            </div>
             <?php
            }
          else{
             ?><p class="text-center">You don't have unprocessed procedures to display.</p><?php
             }
          ?>

      </div><!-- /.container -->


      <?php $exec->get_includes("footer"); ?>
   </body>
</html>
