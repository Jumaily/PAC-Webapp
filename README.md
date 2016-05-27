# Radiology Information System (RIS)
### Integrating with its Sybase Database
#### By: Taha Al-jumaily



##Overview
This is a PHP web app that let users submit procedures and send tasks to the RIS system (by Siemens Healthcare). With this app, users can easily use jQuery forms and submit procedures, remove technical charges, etc... 

Using:

* [jQuery](https://jquery.com/) 
* [CSS framework: Bootstrap](http://getbootstrap.com/)



###Authentications (LDAP):
> 2 type of users, admins and users (stored in MySql table).  Both are authenticated against our University of Kentucky LDAP server. Search LDAP & get firstname and lastname.
     
      # connect to ldap server (Using MC domains, Not AD)
      $server="LDAP://$domain.uky.edu";
      $ldapconn = ldap_connect($server) or die("Could not connect to LDAP server.");
      ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

      # LDAP auth
      if($ldapconn){
         # binding to ldap server, using only MC domain accounts
         $ldapbind = @ldap_bind($ldapconn,"$domain\\$u",$p);
         
         # Verify binding & Session Logins
         if($ldapbind){
            $filter = "(&(objectClass=*)(cn=$u))";
            $dn = "CN=$u,OU=Accounts,DC=$domain,DC=uky,DC=edu";
            $nds_all = array("*"); # Show Everything
            $nds = array("sn","givenname");

            # Get Firstname and Lastanme from University of Kentucky's LDAP server
            $result = ldap_search($ldapconn, $dn, $filter, $nds);
            $info = ldap_get_entries($ldapconn, $result);

            # Firstname: $info[0]['givenname'][0]
            # Lastname: $info[0]['sn'][0]
            }
        }
        ldap_close($ldapconn);
            
      
### Sybase Connections:

      # connect to ldap server (Using MC domains, Not AD)
      class SySQLDB{
		   private $connectionHandle;
		   public function __construct($HOST,$DB,$USER,$PASS){
		      $this->connectionHandle =  new PDO ("dblib:host=$HOST:10000;dbname=$DB",$USER,$PASS);
		      }
		   public function __destruct(){
		      $this->connectionHandle = null;;
		      }
		   # Get all the procedures from RIS (Radiology Inforamtion System)
		   public function get_DicomDevices(){
		      $sql = "SELECT .... ";
		      $stmt = $this->connectionHandle->prepare($sql);
		      $stmt->execute();
		      $result = $stmt->fetchAll();
		      return $result;
		      }
		   }
