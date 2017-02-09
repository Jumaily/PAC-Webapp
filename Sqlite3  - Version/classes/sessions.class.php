<?php


class SessionCls{

   // Start a new session
   function __construct(){ session_start(); }

   // Set a variable in a current session
   function set_var($varname,$varvalue){
      if(!isset($varname) || !isset($varvalue) ){ die('Function setvar( String $varname, mixed $value ) expects two parameters!'); }
         if(phpversion() >= "4.1.0"){ $_SESSION[$varname] = $varvalue; }
         if(!isset($GLOBALS[$varname]) ){ $GLOBALS[$varname] = $varvalue; }
      else{
         global $HTTP_SESSION_VARS;
         $_SESSION[$varname];
         $GLOBALS['HTTP_SESSION_VARS'][$varname] = $varvalue;
         if(!isset($GLOBALS[$varname]) ){ $GLOBALS[$varname] = $varvalue; }
         }
      }

	// Get a variable from current session
	function get_var($varname){
      if(!isset($varname)){ die('Function getvar( String $varname ) expects a parameter!'); }
		if( phpversion() >= "4.1.0" ){
			if (isset($GLOBALS[$varname])){ return $GLOBALS[$varname]; }
			elseif (isset($GLOBALS['_SESSION'][$varname])){
				$GLOBALS[$varname] = $GLOBALS['_SESSION'][$varname];
				return $GLOBALS['_SESSION'][$varname];
            }
         }
		else{
         if (isset($GLOBALS[$varname])){ return $GLOBALS[$varname]; }
         elseif (isset($GLOBALS['HTTP_SESSION_VARS'][$varname])){
				$GLOBALS[$varname] = $GLOBALS['HTTP_SESSION_VARS'][$varname];
				return $GLOBALS['HTTP_SESSION_VARS'][$varname];
            }
         }
      }


	// Get a current session string
	function get_sid_string(){	return session_name() . "=" . session_id(); }

	// Get current session ID
	function get_sid(){	return session_id(); }

	// Unset a current session variable
	function var_unset($varname){
		if(!isset($varname)){ die('Function var_unset( String $varname ) expects a parameter!'); }
		if( phpversion() >= '4.1.0'){
			if(isset($GLOBALS[$varname])){ unset($GLOBALS[$varname] ); }
			if (isset($GLOBALS['_SESSION'][$varname])){ unset($GLOBALS['_SESSION'][$varname]); }
            }
		else{
			if (isset($GLOBALS[$varname])){ unset($GLOBALS[$varname] ); }
			if (isset($GLOBALS['HTTP_SESSION_VARS'][$varname]))	{unset($GLOBALS['HTTP_SESSION_VARS'][$varname]); }
            }
        }

   // Unset every variable in the current session
   function ses_unset(){
      if(phpversion() >= '4.1.0'){
         if(isset($GLOBALS['_SESSION'])) $a = $GLOBALS['_SESSION'];
			{
			while(list($key,) = each($a)){ $this->var_unset($key); }
            }
         }
		else{
            if(isset($GLOBALS['HTTP_SESSION_VARS'])){
               $a = $GLOBALS['HTTP_SESSION_VARS'];
               while(list($key,) = each($a)){ $this->var_unset($key); }
               }
            }
         }


	// This will delete your current session and delete every session variable created
	function destroy(){
        $this->ses_unset();
        session_destroy();
        }


	// Display all variables in a current session
	function show(){
		echo 'Variables set in current session:<br>';
		if(phpversion() >= '4.1.0')	{
			if(isset($GLOBALS['_SESSION'])){
				$a = $GLOBALS['_SESSION'];
				while(list($key,$value) = each($a)){ echo '<b>Variable:</b> '.$key.' - <b>Value:</b> '.$value.'<br>'; }
            }
         }
		else {
			if(isset($GLOBALS['HTTP_SESSION_VARS'])){
				$a = $GLOBALS['HTTP_SESSION_VARS'];
				while(list($key,$value) = each($a)){ echo '<b>Variable:</b> '.$key.' - <b>Value:</b> '.$value.'<br>'; }
            }
         }
      }


	function CreateTokenKey($length=5,$token=""){
	   $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ1234567890";
	   $validCharNumber = strlen($validCharacters);
	   for ($i=0; $i<$length; $i++) {
	      $index = mt_rand(0, $validCharNumber - 1);
	      $token .= $validCharacters[$index];
	      }
	   $token .= "-".date("ymdHi");
	   return $token;
	   }


   // Create & Return Random string/key
   function Create_SessionKey(){
      list($usec, $sec) = explode(' ', microtime());
      $randval = $usec * 10000000;
      $randvalx = $randval.date('ziyG').mt_rand();
      $randvaly = hash('sha256', sha1(md5($randvalx)));
      return $randvaly;
      }


   }
?>
