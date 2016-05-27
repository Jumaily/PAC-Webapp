<?php
require_once("classes/useractivity.class.php"); $UA = new UserActivity();
require_once("classes/admin.class.php"); $ADM = new adminsection();
require_once(SPATH."/classes/sydb.class.php"); $SySQLDB = new SySQLDB(SyDB_HOSTNAME,SyDB_DATABASE,SyDB_USERNAME,SyDB_PASSWORD);

# Delete Rows
if(isset($_GET['deleterow'])){
   $UA->delete_row($_GET['uname'],$_GET['deleterow']);
   header("location: {$_SERVER['PHP_SELF']}?action=RIS&uname={$_GET['uname']}");
   exit;
   }

# Update Rows
if(isset($_POST['saveprocedures'])){
   $ADM->update_userrows($_POST);
   header("location: {$_SERVER['PHP_SELF']}?action=RIS&uname={$_GET['uname']}");
   exit;
   }

$myproc = $UA->show_submitted_procedures($_GET['uname']);
?>
   <p>
      <a href="<?php echo "{$_SERVER['PHP_SELF']}?action=viewlist&uname={$_GET['uname']}"; ?>" class="btn btn-default" role="button">Back To User List</a>
   </p>

      <?php if(count($myproc)){ ?>
         <div class="table">
            <table class="table-condensed">
               <thead>
                  <tr>
                     <th>Department</th>
                     <th>Description</th>
                     <th>Procedure<br/>Number</th>
                     <th>dtl_svc_cd</th>
                     <th>Left | Right</th>
                     <th>Mammography</th>
                     <th>active_flag</th>
                     <th>view_reactions</th>
                     <th></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $ar_procN = array();
                  foreach ($myproc as $c){
                     # keep track of procedure numbers in sybase db. if there isn't set then keep array to tally
                     if ($c['proc_no']==''){
                        if(!isset($ar_procN[$c['dept']]) || ($ar_procN[$c['dept']]<1)){
                           $proc_no = $SySQLDB->get_last_procnum($c['dept']);
                           $ar_procN[$c['dept']] = $proc_no + 1;
                           }
                        else{ $proc_no = $ar_procN[$c['dept']]++; }
                        }
                     else{ $proc_no = $c['proc_no']; }

                     # Left, Right , or C
                     if($c['dtl_svc_cd'] != ''){
                        $abc = substr($c['dtl_svc_cd'], -1);
                        $abc=(($abc == "B")||($abc == "A"))?$abc:'C';
                        }
                     else { $abc = ''; }
                  ?>
                     <tr>
                        <form method="post">
                        <th>
                           <input type="text" value="<?php echo "{$c['dept']}"; ?>" name="<?php echo "{$c['id']}_"?>dept" name="<?php echo "{$c['id']}_"?>dept" required size="3">
                           <input type="hidden" value="<?php echo "{$c['id']}"?>" name="ids[]">
                        </th>
                        <th><input type="text" value="<?php echo "{$c['proc_desc_long']}"; ?>" name="<?php echo "{$c['id']}_"?>proc_desc_long" required size="32" maxlength="32"></th>
                        <th><input type="text" class="form-control" name="<?php echo "{$c['id']}_"?>proc_no" value="<?php echo $proc_no ?>" size="5"></th>
                        <th><input type="text" class="form-control" name="<?php echo "{$c['id']}_"?>dtl_svc_cd" value="<?php echo $c['dtl_svc_cd'] ?>" size="8" readonly></th>
                        <th>
                           <label class="radio-inline">
                             <input type="radio" name="<?php echo "{$c['id']}_"?>ABC" id="inlineRadio1" value="B" <?php echo ($abc=="B")?'checked':''; ?>>Left
                           </label>
                           <br />
                           <label class="radio-inline">
                             <input type="radio" name="<?php echo "{$c['id']}_"?>ABC" id="inlineRadio3" value="A" <?php echo ($abc=="A")?'checked':''; ?>>Right
                           </label>
                           <br />
                           <label class="radio-inline">
                             <input type="radio" name="<?php echo "{$c['id']}_"?>ABC" id="inlineRadio3" value="C" <?php echo ($abc=="C")?'checked':''; ?>>N/A
                           </label>
                        </th>
                        <th>
                           <label class="radio-inline">
                             <input type="radio" name="<?php echo "{$c['id']}_"?>mammography_flag" id="inlineRadio1" value="Y" <?php echo ($c['mammography_flag']=="Y")?'checked':''; ?> >Yes
                           </label>
                           <br />
                           <label class="radio-inline">
                             <input type="radio" name="<?php echo "{$c['id']}_"?>mammography_flag" id="inlineRadio3" value="N" <?php echo ($c['mammography_flag']=="N")?'checked':''; ?>>No
                           </label>
                        </th>
                        <th>
                           <label class="radio-inline">
                             <input type="radio" name="<?php echo "{$c['id']}_"?>active_flag" id="inlineRadio1" value="Y" <?php echo ($c['active_flag']=="Y")?'checked':''; ?>>Yes
                           </label>
                           <br />
                           <label class="radio-inline">
                             <input type="radio" name="<?php echo "{$c['id']}_"?>active_flag" id="inlineRadio3" value="N" <?php echo ($c['active_flag']=="N")?'checked':''; ?>>No
                           </label>
                        </th>
                        <th>
                           <label class="radio-inline">
                             <input type="radio" name="<?php echo "{$c['id']}_"?>view_reactions" id="inlineRadio1" value="Y" <?php echo ($c['view_reactions']=="Y")?'checked':''; ?>>Yes
                           </label>
                           <br />
                           <label class="radio-inline">
                             <input type="radio" name="<?php echo "{$c['id']}_"?>view_reactions" id="inlineRadio3" value="N" <?php echo ($c['view_reactions']=="N")?'checked':''; ?>>No
                           </label>
                        </th>
                        <th>
                           <a href="<?php echo "{$_SERVER['PHP_SELF']}?action=RIS&uname={$_GET['uname']}&deleterow={$c['id']}"; ?>" onClick="return confirm('Delete This Procedures?')" role="button" class="btn btn-warning btn-xs"> Delete Procedure </a>
                        </th>
                     </tr>
                     <?php } ?>
                  </tbody>
               </table>
            </div>

            <?php
            if(count($myproc)){
            $disabled_button = $ADM->check_send_ris($_GET['uname']);
            ?>
               <p class="text-right">
                  <input type="submit" value="Save First" name="saveprocedures" class="btn btn-primary btn" <?php echo $disabled_button; ?>>
               </form>
                  <a href="<?php echo "{$_SERVER['PHP_SELF']}?action=SendToRIS&uname={$_GET['uname']}"; ?>" onClick="return confirm('You Are About To Send These to Syngo DB?')" class="btn btn-danger <?php echo $disabled_button; ?>" role="button" >Send to RIS</a>
               </p>
            <?php
               }
            }
         else{
               ?>
            <p class="text-center">
               No more procedures to display for this user.
            </p>
         <?php } ?>
