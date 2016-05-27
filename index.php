<?php
require_once("controllers/system.cont.php");
if($SESSION->get_var('username')!=''){ header("location: main.php"); exit; }
?><!DOCTYPE html>
<html lang="en">
   <head>
     <?php $exec->get_includes("header"); ?>
     <link href="css/signin.css" rel="stylesheet">
   </head>
   <body>

      <div class="container">

         <form class="form-signin" method="post">
           <h2 class="form-signin-heading" align="right">Sign in (linkblue)</h2>
           <label for="inputEmail" class="sr-only">Email address</label>
           <input type="username" id="username" class="form-control" placeholder="Username" required autofocus name="username" value="<?php if(isset($_POST["username"])) echo $_POST["username"] ?>">
           <label for="inputPassword" class="sr-only">Password</label>
           <input type="password" id="inputPassword" class="form-control" placeholder="Password" required name="password">
           <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
           <?php echo $ldap->ldap_status(); ?>
         </form>

       </div> <!-- /container -->

       <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
       <script src="js/ie10-viewport-bug-workaround.js"></script>
   </body>
</html>
