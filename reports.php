<?php
require_once("controllers/system.cont.php");
if(is_null($SESSION->get_var('username')) || $SESSION->get_var('username')==''){ header("location: logout.php"); exit; }
require_once(SPATH."/classes/oracleqry.class.php"); $OrcQry = new OrcQryCls(OrcDB_USERNAME,OrcDB_PASSWORD,OrcDB_PORT,OrcDB_SERVICENAME,OrcDB_HOSTNAME);
?><!DOCTYPE html>
<html lang="en">
   <head>
     <?php $exec->get_includes("header"); ?>
   </head>
   <body>

	    <div class="container-fluid">
          <?php $exec->get_includes("topmenu");?>

      

 		<div class="row">
	        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
	          <ul class="nav nav-pills">
	            <li <?php echo (isset($_GET["rqid"])&&$_GET["rqid"]=='bodyparts')?'class="active"':''; ?> >
	              <a href="?rqid=bodyparts">Body Parts</a>
	            </li>
	          </ul>
	          <ul class="nav nav-pills">
	            <li <?php echo (isset($_GET["rqid"])&&$_GET["rqid"]=='modalities')?'class="active"':''; ?> >
	              <a href="?rqid=modalities">Modalities</a>
	            </li>
	          </ul>
	        </nav>

	        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
	          <?php echo (!isset($_GET["rqid"]))?'<h3>Select Report To Run</h3>':''; ?>
	          <div class="table-responsive">

	            <table class="table table-striped">

	            	<?php 
	            		# --------------- Get Body Parts ----------------------#
	            		if(isset($_GET["rqid"])&&($_GET["rqid"]=="bodyparts")){
	            			$body_parts = $OrcQry->Get_Body_Parts();
	            			?>
	            			<thead>
				            	<tr>
				            		<th>Body Part ID</th>
				            		<th>Code</th>
				            		<th>Description</th>
				            	</tr>
				            </thead>
				            <tbody>
				            	
				            	<?php 
				            	for($i=0;$i<count($body_parts['MY_BODY_PART_ID']);$i++){
				            		echo "<tr>
				            				<td>{$body_parts['MY_BODY_PART_ID'][$i]}</td>
				            		 		<td>{$body_parts['MY_CODE'][$i]}</td>
				            				<td>{$body_parts['MY_DESCRIPTION'][$i]}</td>
				            			  </tr>\n";
				            		}
				            	?>
				            </tbody>
	            	<?php 
	            			# End of Body Parts Reports
	            			}	 


	            		# --------------- Get Current Modalities Report  ----------------------#
	            	?>


	            </table>
	          </div>
	        </main>
     	 </div>



      </div><!-- /.container -->
      <?php $exec->get_includes("footer"); ?>
   </body>
</html>
