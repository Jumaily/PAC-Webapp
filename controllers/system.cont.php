<?php
error_reporting(E_ALL); ini_set('display_errors', '1');

# run system paths
$exec = new SYSTEM_GET_PATHS($_SERVER['PHP_SELF']);

# path configs
define('SPATH',realpath($_SERVER["DOCUMENT_ROOT"]));

# run system basic classes & essential core files
require_once(SPATH."/config/site.conf.php");
require_once(SPATH."/classes/sessions.class.php"); $SESSION = new SessionCls();
# Sybase DB Class
require_once(SPATH."/classes/sydb.class.php");
   $SySQLDB = new SySQLDB(SyDB_HOSTNAME,SyDB_DATABASE,SyDB_USERNAME,SyDB_PASSWORD);
   $SySQLDB_PROD = new SySQLDB(SyDB_HOSTNAME_PROD,SyDB_DATABASE_PROD,SyDB_USERNAME_PROD,SyDB_PASSWORD_PROD);
# Mysql DB Class
require_once(SPATH."/classes/mysqlidb.class.php"); $MysqlDB = new Mysqlidb(MySDB_HOSTNAME, MySDB_USERNAME, MySDB_PASSWORD, MySDB_DATABASE);
require_once(SPATH."/classes/useractivity.class.php"); $UA = new UserActivity();

require_once(SPATH."/classes/ldap.class.php"); $ldap = new ldap_auth();


# Configure Path Names
class SYSTEM_GET_PATHS{
   private $path;
   public function __construct($p,$b=''){
      $a = explode('/',$p);
      array_shift($a);array_shift($a);
      if(count($a)>1){
         array_shift($a);
         foreach($a as $c){ $b .= "../"; }
         }
      else{ $b = './'; }
      $this->path = $b;
      }

   public function get_includes($file){ include_once("{$this->path}views/includes/$file.php"); }
   public function get_forms($file){ include_once("{$this->path}views/forms/$file.php"); }
   public function URL_GET_PATH(){ return $this->path; }
   public function show_views($file){ $this->Link_up_Paths($file,'system/views'); }

   private function Link_up_Paths($file,$inc){
      global $SESSION;
      $a = explode('.',$file);
      foreach($a as $b)$inc .= "/$b";
      @include_once("{$this->path}$inc.php");
      }

   }

?>
