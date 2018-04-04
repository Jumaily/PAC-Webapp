<?php
# database configs  Test Server
define('SyDB_USERNAME',"UserName");
define('SyDB_PASSWORD',"p@ssw0rd");
define('SyDB_DATABASE',"TST_DB");
define('SyDB_HOSTNAME',"testServer.domain.uky.edu");

# database configs  Production Server
define('SyDB_USERNAME_PROD',"UserName");
define('SyDB_PASSWORD_PROD',"p@ssw0rd");
define('SyDB_DATABASE_PROD',"PRD_DB");
define('SyDB_HOSTNAME_PROD',"prdServer.domain.uky.edu");

# Oracle DB Connection
define('OrcDB_USERNAME',"UserName");
define('OrcDB_PASSWORD',"p@ssw0rd");
define('OrcDB_PORT',"1521");
define('OrcDB_SERVICENAME',"pacs");
define('OrcDB_HOSTNAME',"PrdOrcServer.domain.uky.edu");

# RIS SSH
define('SSH_Uname',"UserName");
define('SSH_Pw',"p@ssw0rd");
define('SSH_Serv','Server.domain.uky.edu');

date_default_timezone_set('America/New_York');
?>
