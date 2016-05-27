<?php
require_once("controllers/system.cont.php");
$SESSION->destroy();
header("location:/");
exit;
?>
