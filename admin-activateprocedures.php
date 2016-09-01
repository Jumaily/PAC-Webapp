<?php
require_once("controllers/system.cont.php");
require_once("classes/admin.class.php"); $ADM = new adminsection();
if((is_null($SESSION->get_var('username')) || $SESSION->get_var('username')=='')||(!$SESSION->get_var('admin'))){ header("location: logout.php"); exit; }

if(isset($_GET['action'])&&$_GET['action']=="markdone"){ $ADM->markdone($_GET['uname']); header("location: {$_SERVER['PHP_SELF']}"); exit; }
?><!DOCTYPE html>
<html lang="en">
   <head>
     <?php $exec->get_includes("header"); ?>
   </head>
   <body>

      <div class="container">
          <?php $exec->get_includes("topmenu"); ?>

                 <div class="row">
                   <div class="col-sm-12">
                     <blockquote>
                        <p>Activate/Deactivate Procedures in Syngo Workflow Database (DTL SVC CD/Rcode), enter one Rcode per line.</p>
                     </blockquote>
                     <div class="row">
                       <div class="col-xs-8 col-sm-6">
                          <form method="post">

                             <div class="radio">
                                <label class="radio-inline">
                                  <input type="radio" name="AD_ctivate" id="inlineRadio1" value="Y" checked> Activate
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="AD_ctivate" id="inlineRadio2" value="N"> Deactivate
                                </label>
                             </div>

                            <div class="form-group">
                              <textarea class="form-control" name="activateprocedures" style="width: 300px; height: 150px;"></textarea>
                            </div>

                            <div class="checkbox">
                              <label>
                                <input type="checkbox" value="1" name="env_prod"> Prod Env (DRAD)
                              </label>
                              <label>
                                <input type="checkbox" value="1" name="env_test"> Test Env (tstukhsynap0)
                              </label>
                            </div>

                            <button type="submit" class="btn btn-default" name="SubmitActiveP">Submit</button>
                          </form>
                       </div>

                       <div class="col-xs-4 col-sm-6">
                          <p>
                             <?php
                             if(isset($_POST['SubmitActiveP']) && $_POST['activateprocedures']!=''){
                                if(!isset($_POST["env_prod"]) && !isset($_POST["env_test"])){
                                   echo "<strong>* Check one or both database environment</strong>";
                                   }
                                else{
                                   # Create env array to edit/modify 2 databases accordingly
                                   $envs = array('tst'=>isset($_POST["env_test"]), 'prd'=>isset($_POST["env_prod"]));
                                   echo "Environment(s): ".(isset($_POST["env_prod"])?"Production":'').
                                       (($envs['tst'] && $envs['prd'])?" and ":'').
                                       (isset($_POST["env_test"])?"Test":'')."<hr></ br>";
                                   $str = $_POST['activateprocedures'];
                                   $arr = explode("\n", $str);

                                   # send settinging to DB
                                   $list = $ADM->ProceduresStatus_SyngoDB($arr,$_POST['AD_ctivate'],$envs);
                                   foreach($list as $a){ print "$a set to Active: {$_POST['AD_ctivate']}<br />"; }
                                   }
                                }
                                elseif(isset($_POST['SubmitActiveP']) && $_POST['activateprocedures']==''){ echo "<strong>* Nothing entered for Rcode values</strong>"; }
                             ?>
                          </p>
                       </div>

                     </div>
                   </div>
                 </div>

      </div><!-- /.container -->

      <?php $exec->get_includes("footer"); ?>
   </body>
</html>
