<?php
require_once("controllers/system.cont.php");
if(is_null($SESSION->get_var('username')) || $SESSION->get_var('username')==''){ header("location: logout.php"); exit; }
?><!DOCTYPE html>
<html lang="en">
   <head>
     <?php $exec->get_includes("header"); ?>
     <script>
        // Submit/result forms
        function submitForm() {
          $.ajax({type:'POST', url: 'process-requests.php', dataType: "xml", data:$('#rem_techcharge').serialize(),
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
          <div class="jumbotron">
             Output Logs: <hr>
             <div id="echo-results">
                 <div class="form_result-cmd"></div>
             </div>
             <span id="typed" style="white-space:pre;"></span>
          </div>

          <div class="row marketing">
              <div class="col-lg-12"> </div>

               <div class="col-md-4">
                  <form id="rem_techcharge" onsubmit="return submitForm();">
                    <div class="form-group">
                      <label for="rem_techcharge">Remove Technical Charge</label>
                      <input type="text" class="form-control" id="exampleInputName2" placeholder="Accession #" name="acc_itn">
                    </div>
                    <button type="submit" class="btn btn-default" value="remove-tech-charge">Remove</button>
                    <input type="hidden" name="submit-type" value="remove-tech-charge">
                  </form>

               </div>
               <div class="col-md-4">
                  <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                     Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur
                     ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.
                  </p>
                  <p><button type="button" class="btn btn btn-default">Run Something 2</button></p>
               </div>
               <div class="col-md-4">
                  <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
                     Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur
                     ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.
                  </p>
                  <p><button type="button" class="btn btn btn-default">Run Something 3</button></p>
               </div>

            </div>
	    </div><!-- /.container -->

      <?php $exec->get_includes("footer"); ?>
   </body>
</html>
