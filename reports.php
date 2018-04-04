<?php
require_once("controllers/system.cont.php");
if(is_null($SESSION->get_var('username')) || $SESSION->get_var('username')==''){ header("location: logout.php"); exit; }
?><!DOCTYPE html>
<html lang="en">
   <head>
     <?php $exec->get_includes("header"); ?>
     <script src="js/jquery.modal.min.js"></script>
	 <link rel="stylesheet" href="css/jquery.modal.min.css" />
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
	          <ul class="nav nav-pills">
	            <li <?php echo (isset($_GET["rqid"])&&$_GET["rqid"]=='reports-wo-results')?'class="active"':''; ?> >
	              <a href="?rqid=reports-wo-results">Reports W\No Results</a>
	            </li>
	          </ul>	          
	        </nav>


	        <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
	        <?php echo (!isset($_GET["rqid"]))?'<h3>Select Report To Run</h3>':''; ?>

	        <?php 
	        # --------------- Get Body Parts & Display Report ----------------------#
	        if(isset($_GET["rqid"])&&($_GET["rqid"]=="bodyparts")){ $exec->get_includes("reports-pacs-bodyparts"); }	 

	        # --------------- Get Modalities Report of whats in PACS Device tabele -#
   			elseif(isset($_GET["rqid"])&&($_GET["rqid"]=="modalities")){ $exec->get_includes("reports-pacs-modalities"); } 

   			# --------------- Get Reports in PACS that has no results --------------#
   			elseif(isset($_GET["rqid"])&&($_GET["rqid"]=="reports-wo-results")){ $exec->get_includes("reports-pacs-reports-wo-results"); }
   			?>


	        </main>
     	 </div>
      </div><!-- /.container -->
      <?php $exec->get_includes("footer"); ?> 
   </body>
</html>
