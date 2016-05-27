<?php
require_once("controllers/system.cont.php");
require_once("classes/admin.class.php"); $ADM = new adminsection();
if((is_null($SESSION->get_var('username')) || $SESSION->get_var('username')=='')||(!$SESSION->get_var('admin'))){ header("location: logout.php"); exit; }

if(isset($_GET['action'])&&$_GET['action']=="markdone"){ $ADM->markdone($_GET['uname']); header("location: {$_SERVER['PHP_SELF']}"); exit; }
?><!DOCTYPE html>
<html lang="en">
   <head>
     <?php $exec->get_includes("header"); ?>
     <script>
       // Submit/result forms
       function submitForm() {
          $.ajax({type:'POST', url: 'process-requests.php', dataType: "xml", data:$('#add-user-tbl').serialize(),
          error: function(xhr, status, error) { alert('Error: '+ xhr.status+ ' - '+ error); },
          timeout: 10000,
          beforeSend: function(){ $('#echo-results').html('Processing...'); },
          success: function(response) {
             $('#echo-results').html('');
             // gets and parse each child element in <webpages>
             $(response).find('response').children().each(function() {
                // gets the "id", "title", and "url" of current child element
                var elm = $(this);
                var status = elm.attr('statusfor');
                var progress = elm.find('progress').text();
                var value = elm.find('value').text();
                // displays data
                $('#echo-results').append("<p>" + status + "</p>"+ progress+ '<br />'+ value);
               });
             }});
            return false;
            }
     </script>
   </head>
   <body>

	    <div class="container">
          <?php $exec->get_includes("topmenu"); ?>

          <div class="row marketing">
               <?php
               if(isset($_GET['action'])&&$_GET['action']=="viewlist"){
                  $exec->get_includes("admin-showUserProc");
                  }
               elseif(isset($_GET['action'])&&$_GET['action']=="RIS"){
                  $exec->get_includes("admin-showUserRIS");
                  }
                  # Send to Syngo/RIS Database
               elseif(isset($_GET['action'])&&$_GET['action']=="SendToRIS"){
                  if(!isset($_GET['SyngoSendStatus'])){
                     $syngosend = $ADM->send_to_SyngoDB($_GET['uname']);
                     header("location: {$_SERVER['PHP_SELF']}?action=SendToRIS&uname={$_GET['uname']}&SyngoSendStatus=$syngosend");
                     exit;
                     }
                  else{
                     echo "<h3>Sending to Syngo:<small>".(($_GET['SyngoSendStatus']=='success')?"Success!":"Failed")."</small></h3>";
                     echo '<a class="btn btn-primary" href="'."{$_SERVER['PHP_SELF']}?action=viewlist&uname={$_GET['uname']}".'" role="button">Continue</a>';
                     }
                  }
               else{
                  ?>
                  <div class="col-md-6">
                  <?php
                  $userslist = $ADM->get_user_procedures();
                  foreach($userslist as $v){
                     echo '<a class="btn btn-primary" href="'."{$_SERVER['PHP_SELF']}?action=viewlist&uname={$v['uname']}".
                              '" role="button">'.$v['ANY_VALUE(fullname)'].' [View / Download List]</a>';
                     echo '&nbsp;';
                     echo "<a onClick=\"return confirm('You are about to mark off all requested procedures from {$v['ANY_VALUE(fullname)']}. Continue?')\" ";
                     echo 'class="btn btn-danger" href="'."{$_SERVER['PHP_SELF']}?action=markdone&uname={$v['uname']}".'"" role="button">Marked Complete</a>';
                     ?>
                     <p>&nbsp;</p>
                     <?php
                     }
                  if(!count($userslist)){ echo '<p class="text-center">No procedures to display for any users.</p>'; }
                  ?>
               </div>
               <div class="col-md-6">
                  <div class="jumbotron">
                     <form id="add-user-tbl" class="form-inline" onsubmit="return submitForm();">
                       <div class="form-group">
                         <label for="exampleInputName2">Add User</label>
                         <input type="text" class="form-control" id="exampleInputName2" placeholder="ie: abc123" name="adduserperm">
                       </div>
                       <button type="submit" class="btn btn-info">Add User</button>
                       <input type="hidden" name="submit-type" value="add-user-perm">
                     </form>
                     <p>&nbsp;</p>
                    <div id="echo-results">
                        <div class="form_result-cmd"></div>
                    </div>
                 </div>
               </div>
               <?php  } # else view  ?>




            </div>
         </div>
      </div><!-- /.container -->

      <?php $exec->get_includes("footer"); ?>
   </body>
</html>
