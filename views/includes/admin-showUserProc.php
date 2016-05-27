<?php
require_once("classes/useractivity.class.php"); $UA = new UserActivity();
$myproc = $UA->show_submitted_procedures($_GET['uname']);
?>
   <p>
      <a href="<?php echo $_SERVER["PHP_SELF"]; ?>" class="btn btn-default" role="button">Back To Admin Section</a>
   </p>
<?php if(count($myproc)){ ?>
         <div class="table-responsive">
            <table class="table">
               <thead>
                  <tr>
                     <th></th>
                     <th>Department</th>
                     <th>Procedure<br/>Number</th>
                     <th>dtl_svc_cd</th>
                     <th>Mnemonic</th>
                     <th>Description</th>
                     <th>Body Part</th>
                     <th>TMID code</th>
                     <th>CDM Code</th>
                     <th>CPT Code</th>
                     <th>Cost Center</th>
                     <th>Orderable<br />(Y/N)</th>
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
                             <th>{$c['proc_no']}</th>
                             <th>{$c['dtl_svc_cd']}</th>
                             <th>{$c['proc_desc_short']}</th>
                             <th>{$c['proc_desc_long']}</th>
                             <th>{$c['body_part_mne']}</th>
                             <th>{$c['cpt_descp3']}</th>
                             <th>{$c['cpt_descp2']}</th>
                             <th>{$c['cpt_code1']}</th>
                             <th>{$c['cpt_descp4']}</th>
                             <th>{$c['orderable']}</th>
                           </tr>";
                     }
                     ?>
                  </tbody>
            </table>
         </div>

         <p class="text-right">
            <a href="views\dl-csv\admin-dl-procedures.php?uname=<?php echo $_GET['uname']; ?>" class="btn btn-success" role="button">Download CSV/Excel File</a>
            <a href="<?php echo "{$_SERVER['PHP_SELF']}?action=RIS&uname={$_GET['uname']}";?>" class="btn btn-warning" role="button">Edit And Send to RIS</a>
         </p>
      <?php }
      else{ echo '<p class="text-center">No more procedures to display for this user.</p>'; } ?>
