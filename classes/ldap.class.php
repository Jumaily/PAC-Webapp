<?php

class ldap_auth{
   private $msg='';

   function __construct(){
      if((isset($_POST['password']) && isset($_POST['password']))&&($_POST['password']!='' && $_POST['password']!='')){
   	   $u = strtolower($_POST['username']);
   		$p = ($_POST['password']);
         # Only Medical Center (MC) Accounts
         $this->msg = $this->ldap_valiate($u,$p,"MC");
         }
      }

   function ldap_status(){ return $this->msg; }

   function ldap_valiate($u,$p,$domain,$v='',$Successful=false){
      global $SESSION;
      global $UA;
      $server="gc.$domain.uky.edu";

      # connect to ldap server
      $ldapconn = ldap_connect($server) or die("Could not connect to LDAP server.");
      ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

      # Local DB auth
      if(($UA->isadmin($u) || $UA->isuser($u))){
         # LDAP auth
         if($ldapconn){
            # binding to ldap server, using only MC domain accounts
            $ldapbind = @ldap_bind($ldapconn,"$domain\\$u",$p);
            # Verify binding & Session Login
            if($ldapbind){
               $filter = "(&(objectClass=*)(cn=$u))";
               $dn = "CN=$u,OU=Accounts,DC=$domain,DC=uky,DC=edu";
               $nds_all = array("*"); # Show Everything
               $nds = array("sn","givenname");

               # Get Firstname and Lastanme from University of Kentucky's LDAP
               $result = ldap_search($ldapconn, $dn, $filter, $nds);
               $info = ldap_get_entries($ldapconn, $result);

               $SESSION->set_var('username', $u);
               $SESSION->set_var('firstname', $info[0]['givenname'][0]);
               $SESSION->set_var('lastname', $info[0]['sn'][0]);
               $SESSION->set_var('admin',$UA->isadmin($_SESSION['username']));

               # Update logs
               $UA->updateweblogs("Successful Login");
               $Successful = true;
               }
            else{
               $v = '<br/><div class="alert alert-danger" role="alert">Login Failed: <strong>'.$u.'</strong></div>';
               }
            }
         }
      else{
         $v = '<br/><div class="alert alert-danger" role="alert"> No Access For This User <strong>'.$u.'</strong></div>';
         }
      ldap_close($ldapconn);
      if($Successful){ header("location: main.php"); exit; }
      return $v;
      }

   }

?>
