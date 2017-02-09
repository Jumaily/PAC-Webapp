<?php
require_once("controllers/system.cont.php");
if(is_null($SESSION->get_var('username')) || $SESSION->get_var('username')==''){ header("location: logout.php"); exit; }
$SESSION->set_var("addprocedure", false);
?><!DOCTYPE html>
<html lang="en">
   <head>
     <?php $exec->get_includes("header"); ?>
     <script src="js/duplicateFields.min.js" type="text/javascript"></script>
     <script  type="text/javascript">
          $(function () {
              $('#additional-field-model').duplicateElement({
                  "class_remove": ".remove-this-field",
                  "class_create": ".create-new-field"
             });
          });
      </script>
   </head>
   <body>

	    <div class="container">
          <?php $exec->get_includes("topmenu");?>
          <div class="jquery-script-clear"></div>
          <p class="text-center"><strong>* All Procedures must be <abbr title="attribute">approved</abbr> by Dr. Hobbs.</strong></p>
          <form name="procedures" class="form-inline-left" method="post" action="useractivity.php">
             <div class="row">
                <div class="col-md-12">
                   <input type="submit" value="Submit Procedures" name="submit" class="btn btn-success" onclick="return confirm('Submit Procedures Request?')" >
                </div>
             </div>
             <div class="radio">
                <label class="radio-inline">
                  <input type="radio" name="Orderable" id="inlineRadio1" value="Y" checked> Orderable Items
                </label>
                <label class="radio-inline">
                  <input type="radio" name="Orderable" id="inlineRadio2" value="N"> None-Orderable Items
                </label>
             </div>
             <fieldset id="additional-field-model">
                <div class="row">
                    <div class="col-md-3">
                       <div class="form-group">
                          <label for="Department">Department:</label>
                          <select class="form-control" name="dept[]">
                             <?php
                             $departments = $UA->get_departments();
                             foreach($departments as $d){ echo "<option value=\"{$d['dept']}\">{$d['description']}</option>\n"; }
                             ?>
                          </select>
                       </div>
                       <div class="form-group">
                          <label for="CPTCode">CPT Code:</label>
                          <input id="CPTCode" name="cpt_code1[]" type="text" value="" maxlength="5" size="5" required>
                       </div>
                    </div>
                    <div class="col-md-2">
                       <div class="form-group">
                          <label for="Mnemonic-4" >Mnemonic:</label>
                          <input id="Mnemonic" name="proc_desc_short[]" type="text" value="" maxlength="4" size="4" required>
                       </div>
                       <div class="form-group">
                          <label for="CDM Code">CDM:</label>
                          <input id="cdmcode" name="cpt_descp2[]" type="text" value="" maxlength="10" size="10" required>
                       </div>
                    </div>
                    <div class="col-md-4">
                       <div class="form-group">
                          <label for="Description">Description:</label>
                          <input id="Description" name="proc_desc_long[]" type="text" placeholder="32 Characters Max" maxlength="32" size="35" value="" required>
                       </div>
                       <div class="form-group">
                          <label for="BodyPart">Body Part:</label>
                          <input id="Body Part" name="body_part_mne[]" type="text" value="" maxlength="10" required>
                       </div>
                    </div>
                    <div class="col-md-3">
                       <div class="form-group">
                          <label for="TMID">TMID (if you have it):</label>
                          <input id="TMID" name="cpt_descp3[]" type="text" value="" maxlength="10" size="10">
                       </div>
                       <div class="form-group">
                          <label for="CostCenter">Cost Center:</label>
                          <input id="CostCenter" name="cpt_descp4[]" type="text" value="" maxlength="11"  size="10" required>
                       </div>
                  </div>
               </div>

               <div class="col-md-12 text-right">
                  <label class="col-xs-12 control-label" for="field-c"><br /></label>
                  <a href="javascript:void(0);"  class="btn btn-danger remove-this-field">
                     <i class="fa fa-remove"></i>
                     <span class="hidden-xs"> Delete </span>
                  </a><br />
                  <a href="javascript:void(0);"  class="btn btn-primary create-new-field">
                     <i class="fa fa-plus"></i>
                     <span class="hidden-xs"> Duplicate </span>
                  </a><br />
               </div>

            </fieldset>
         </form>
      </div><!-- /.container -->

      <?php $exec->get_includes("footer"); ?>
   </body>
</html>
