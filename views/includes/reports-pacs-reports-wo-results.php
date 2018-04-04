<?php
require_once(SPATH."/classes/oracleqry.class.php"); $OrcQry = new OrcQryCls(OrcDB_USERNAME,OrcDB_PASSWORD,OrcDB_PORT,OrcDB_SERVICENAME,OrcDB_HOSTNAME);
require_once(SPATH."/classes/sydb.class.php"); $SySQLDB_PROD = new SySQLDB(SyDB_HOSTNAME_PROD,SyDB_DATABASE_PROD,SyDB_USERNAME_PROD,SyDB_PASSWORD_PROD);
$swr = $OrcQry->Get_Studies_WO_Results();

?>


	        <div class="table-responsive">
	        	<p>
	        		<a href="/views/ssh/resend_report_pacs.php" rel="modal:open" class="btn btn-warning" role="button" >Resend Reports</a>
	        	</p>
	        	
	        	<table class="table table-striped">
	            	<thead>
				        <tr>
				        	<th>Accession #</th>
				        	<th>Possible Issue</th>
				        	<th>Reported No Result</th>
				          	<th>Patient Name</th>
				          	<th>MRN #</th>
				          	<th>Exam</th>
				          	<th>Exam Date</th>
				          	<th>Reported By</th>
				          	<th>Report Date</th>
				          	<th>Device</th>
				          	<th>Modality</th>
				          	<th>Facility</th>
				        </tr>
				    </thead>
				    <tbody>
					            	
				        <?php 
				        
				        for($i=0;$i<count($swr['ACCESSION']);$i++){
				           	echo "<tr>
				           			<td>{$swr['ACCESSION'][$i]}</td>
				           			<td>{$OrcQry->check_db_conflict($swr['ACCESSION'][$i])}</td>
				           			<td>{$SySQLDB_PROD->check_for_report($swr['ACCESSION'][$i])}</td>
				           		 	<td>{$swr['PATIENT'][$i]}</td>
				           		 	<td>{$swr['MRN'][$i]}</td>
				           		 	<td>{$swr['EXAM'][$i]}</td>
				           		 	<td>{$swr['EXAMDATE'][$i]}</td>
				           		 	<td>{$swr['REPORTEDBY'][$i]}</td>
				           		 	<td>{$swr['REPORTEDDATE'][$i]}</td>
				           		 	<td>{$swr['DEVICE'][$i]}</td>
				           		 	<td>{$swr['MODALITY'][$i]}</td>
				           		 	<td>{$swr['FACILITY'][$i]}</td>
				           		   </tr>\n";
				           		}
				           		
				           	?>
				        </tbody>
				</table>
	        </div>