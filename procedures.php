<?php
require_once("controllers/system.cont.php");
if(is_null($SESSION->get_var('username')) || $SESSION->get_var('username')==''){ header("location: logout.php"); exit; }

# Get list of departments
$Departments = $SySQLDB_PROD->get_dept();
$url_dept = (isset($_GET['dept']))?strtoupper(preg_replace('/[^a-z]/i','',$_GET['dept'])):'';
$url_active_dept = (isset($_GET['active']))?strtoupper(preg_replace('/[^a-z]/i','',$_GET['active'])):'';
?><!DOCTYPE html>
<html lang="en">
      <head>
        <?php $exec->get_includes("header"); ?>
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <script src="js/bootstrap-select.js"></script>
      </head>
      <body>
		    <div class="container">
            <?php $exec->get_includes("topmenu"); ?>

		      <div class="starter-template">
               <div class="page-header">
                    <form class="form-horizontal" role="form" method="get">
                      <div class="form-group">
                        <div class="row">
                           <div class="col-md-7">
                              <select id="basic" class="selectpicker show-tick form-control" data-live-search="true" name="dept">
                                 <option value="">Search By Department</option>
                                  <?php
                                  foreach($Departments as $r){
                                     echo "<option value=\"{$r['dept']}\"".(($r['dept']==$url_dept)?"selected":'').">{$r['descp']}</option>\n";
                                     }
                                  ?>
                              </select>
                           </div>
                           <div class="col-md-3">
                              <select id="basic" class="selectpicker show-tick form-control" name="active">
                                 <option value="Y" <?php echo (isset($_GET['active']) && ($_GET['active']=='Y')?"selected":''); ?> >Active Procedures</option>
                                 <option value="N" <?php echo (isset($_GET['active']) && ($_GET['active']=='N')?"selected":''); ?> > Inactive Procedures</option>
                              </select>
                           </div>
                           <div class="col-md-2">
                              <input type="submit" value="Get List" class="form-control">
                           </div>
                        </div>
                      </div>
                    </form>
               </div>

                  <?php
                  # Get All procedures by Seelcted department
                  if($url_dept!='' && $url_active_dept!=''){
                     $items = $SySQLDB_PROD->get_items($url_dept,$url_active_dept);
                  ?>
                     <table class="table table-hover">
                        <thead>
                           <tr>
                              <th>Procedure Number</th>
                              <th>Mnemonic</th>
                              <th>Description</th>
                              <th>DTL SVC CD</th>
                              <th>CPT Code</th>
                              <th>CDM Code</th>
                              <th>TMID</th>
                              <th>Cost Center</th>
                           </tr>
                        </thead>
                        <tbody>

                        <?php
                        foreach($items as $item){
                         echo"<tr>
                              <td>{$item['proc_no']}</td>
                              <td>{$item['proc_desc_short']}</td>
                              <td>{$item['proc_desc_long']}</td>
                              <td>{$item['dtl_svc_cd']}</td>
                              <td>{$item['cpt_code1']}</td>
                              <td>{$item['cpt_descp2']}</td>
                              <td>{$item['cpt_descp3']}</td>
                              <td>{$item['cpt_descp4']}</td>
                           </tr>";
                           }
                           ?>

                        </tbody>
                     </table>
                  <?php } ?>
		      </div>
		    </div><!-- /.container -->


      <?php $exec->get_includes("footer"); ?>

      <script src="js/bootstrap.min.js"></script>
      <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
      <script>
        $(document).ready(function () {
          var mySelect = $('#first-disabled2');

          $('#special').on('click', function () {
            mySelect.find('option:selected').prop('disabled', true);
            mySelect.selectpicker('refresh');
          });

          $('#special2').on('click', function () {
            mySelect.find('option:disabled').prop('disabled', false);
            mySelect.selectpicker('refresh');
          });

          $('#basic2').selectpicker({
            liveSearch: true,
            maxOptions: 1
          });
        });
      </script>
   </body>
</html>
