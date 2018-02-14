<?php
require_once(SPATH."/classes/oracleqry.class.php"); $OrcQry = new OrcQryCls(OrcDB_USERNAME,OrcDB_PASSWORD,OrcDB_PORT,OrcDB_SERVICENAME,OrcDB_HOSTNAME);
?>

				   							   
		<div class="row marketing">
	    	<h4>Device Type:</h4>
			<ul class="nav nav-pills">
			  <li class="nav-item <?php echo (isset($_GET["MY_DEVICE_TYPE"])&&$_GET["MY_DEVICE_TYPE"]=='0')?'alert-info':''; ?>">
			    <a href="?rqid=modalities&MY_DEVICE_TYPE=0" class="alert-link">Undefined</a>
			  </li>
			  <li class="nav-item <?php echo (isset($_GET["MY_DEVICE_TYPE"])&&$_GET["MY_DEVICE_TYPE"]=='1')?'alert-info':''; ?>">
			    <a href="?rqid=modalities&MY_DEVICE_TYPE=1">McKesson Server</a>
			  </li>
			  <li class="nav-item <?php echo (isset($_GET["MY_DEVICE_TYPE"])&&$_GET["MY_DEVICE_TYPE"]=='2')?'alert-info':''; ?>">
			    <a href="?rqid=modalities&MY_DEVICE_TYPE=2">McKesson Workstation</a>
			  </li>
			  <li class="nav-item <?php echo (isset($_GET["MY_DEVICE_TYPE"])&&$_GET["MY_DEVICE_TYPE"]=='3')?'alert-info':''; ?>">
			    <a href="?rqid=modalities&MY_DEVICE_TYPE=3">Modality</a>
			  </li>
			  <li class="nav-item <?php echo (isset($_GET["MY_DEVICE_TYPE"])&&$_GET["MY_DEVICE_TYPE"]=='4')?'alert-info':''; ?>">
			    <a href="?rqid=modalities&MY_DEVICE_TYPE=4">External</a>
			  </li>
			  <li class="nav-item <?php echo (isset($_GET["MY_DEVICE_TYPE"])&&$_GET["MY_DEVICE_TYPE"]=='5')?'alert-info':''; ?>">
			    <a href="?rqid=modalities&MY_DEVICE_TYPE=5">HIS/RIS HL7</a>
			  </li>
			</ul>
		</div>
		<div class="table-responsive">

  		<?php # --------------- Get Body Parts ----------------------#
   		if(isset($_GET["MY_DEVICE_TYPE"])){	$modalities = $OrcQry->Get_Modalites($_GET["MY_DEVICE_TYPE"]);	
   		?>

       		<table class="table table-striped">
        		<thead>
		            <tr>
		            	<th>Hostname</th>
		            	<th>Description</th>
		            	<th>Work Group</th>
		            	<th>Ordering System</th>
		            	<th>Make this Ordering</th>
		            	<th>Lock-Down Time</th>
		            	<th>Track Health On/off</th>
		            </tr>
		        </thead>
		        <tbody>
		            <?php 
		            for($i=0;$i<count($modalities['MY_CODE']);$i++){														
		            	echo "<tr>
		            			<td>{$modalities['MY_CODE'][$i]}</td>
		            			<td>{$modalities['MY_DESCRIPTION'][$i]}</td>
		            			<td>{$modalities['FACILITY_ID_Desc'][$i]}</td>
		            			<td>{$modalities['OrdSys_MY_NAME'][$i]}</td>
		            			<td>{$modalities['QUERY_CONTEXT_ID'][$i]}</td>
		            			<td>".(($modalities['MY_TIMEOUT_MINS'][$i]>0)?"{$modalities['MY_TIMEOUT_MINS'][$i]} Min":"Never")."</td>
		            			<td>{$modalities['MY_STATUS'][$i]}</td>
		            		   </tr>\n";
		            		}
		            	?>
		        </tbody>
		    </table>
		    <?php } ?>
      	</div>