# Radiology Information System (RIS)
### Integrating with its Sybase/McKesson Oracle Database
#### By: Taha Al-jumaily



##Overview
This is a PHP web app that let users submit procedures and send tasks to the RIS system (by Siemens Healthcare). With this app, users can easily use jQuery forms and submit procedures, remove technical charges, etc... 

PACS Admins: Can view procedures submitted, edit/delete them, and/or submit them to RIS Databases.  Can also activate/deactivate procedures from test and production servers.

Back end admin tools (and reporting) that connects to Siemens Sybase DB2 & McKesson PACS Oracle Databases.

Queries McKesson Oracle DB - generate reports (PHI Code/Sections Omitted)

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
            
### Oracle DB Connection:

   private $orc_conn = null;
   public function __construct($username,$password,$port,$servicename,$hostname){
      $str = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$hostname)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$servicename)))";
      $this->orc_conn = oci_connect($username,$password,$str);
      if(!$this->orc_conn){
         $e = oci_error();
         trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
         }
      }
	public function __destruct(){ oci_close($this->orc_conn); }
	
### Sybase DB Connections:

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
		   


## Screenshots
![Login](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/Login.JPG?raw=true)
![Remove Tech Charge](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/Main-Delete-Technical-Charge.JPG?raw=true)
![Search Procedures](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/Search-Procedures.JPG?raw=true)
![Modalities](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/Modalities.JPG?raw=true)
## Adding Procedures
![Add Procedures](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/Add-Procedures-techworkflow.JPG?raw=true)
![See Procedures Tech](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/See-Procedures-techworkflow.JPG?raw=true)
## Admin Area
![Admin 1](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/admin1.JPG?raw=true)
![Admin 2](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/admin2.JPG?raw=true)
![Admin 2](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/DeActivate.JPG?raw=true)
![Admin 2](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/pacs-reports.JPG?raw=true)

## Logs
![Database Logs](https://github.com/Jumaily/UKY-Radiology-Webapp/blob/master/Screenshots/DB-Logs.jpg?raw=true)





