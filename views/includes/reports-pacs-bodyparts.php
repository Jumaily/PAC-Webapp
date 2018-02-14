<?php
require_once(SPATH."/classes/oracleqry.class.php"); $OrcQry = new OrcQryCls(OrcDB_USERNAME,OrcDB_PASSWORD,OrcDB_PORT,OrcDB_SERVICENAME,OrcDB_HOSTNAME);
$body_parts = $OrcQry->Get_Body_Parts();
?>
	        <div class="table-responsive">
	        	<table class="table table-striped">
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
				</table>
	        </div>