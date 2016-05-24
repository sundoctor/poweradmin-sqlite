<?php

/*  Poweradmin, a friendly web-based admin tool for PowerDNS.
 *  See <https://www.poweradmin.org> for more details.
 *
 *  Copyright 2007-2009  Rejo Zenger <rejo@zenger.nl>
 *  Copyright 2010-2012  Poweradmin Development Team
 *      <https://www.poweradmin.org/trac/wiki/Credits>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function doAuthenticate() {
	global $db;
	global $iface_expire;
	global $syslog_use, $syslog_ident, $syslog_facility;
	global $session_key;
	global $password_encryption;

	if (isset($_SESSION['userid']) && isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] == "logout") {
		logout( _('You have logged out.'), 'success');
	}

	// If a user had just entered his/her login && password, store them in our session.
	if (isset($_POST["authenticate"]))
	{
		$_SESSION["userpwd"] = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($session_key), $_POST['password'], MCRYPT_MODE_CBC, md5(md5($session_key))));;
		$_SESSION["userlogin"] = $_POST["username"];
	}

	// Check if the session hasnt expired yet.
	if ((isset($_SESSION["userid"])) && ($_SESSION["lastmod"] != "") && ((time() - $_SESSION["lastmod"]) > $iface_expire))
	{
		logout( _('Session expired, please login again.'), 'error');
	}

	// If the session hasn't expired yet, give our session a fresh new timestamp.
	$_SESSION["lastmod"] = time();

	if (isset($_SESSION["userlogin"]) && isset($_SESSION["userpwd"]))
	{
		//Username and password are set, lets try to authenticate.
		$session_pass = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($session_key), base64_decode($_SESSION["userpwd"]), MCRYPT_MODE_CBC, md5(md5($session_key))), "\0");

		if ($password_encryption == 'md5salt') {
			$result = $db->query("SELECT id, fullname, password FROM users WHERE username=". $db->quote($_SESSION["userlogin"], 'text')  ." AND active=1");
		} else {
			$result = $db->query("SELECT id, fullname, password FROM users WHERE username=". $db->quote($_SESSION["userlogin"], 'text')  ." AND active=1");
		}
		
		if($result->numRows() > 0)
		{
			$rowObj = $result->fetchRow();
			//echo ":::"; print_r($rowObj);die;
			
			if ($password_encryption == 'md5salt') {
				$session_password = mix_salt(extract_salt($rowObj["password"]), $session_pass);
			} else {
				$session_password = md5($session_pass);
			}

			if ($session_password == $rowObj["password"]) {
				
				$_SESSION["userid"] = $rowObj["id"];
				$_SESSION["name"] = $rowObj["fullname"];
				
				if(isset($_POST["authenticate"]))
				{
					// Log to syslog if it's enabled
					if($syslog_use)
					{
						openlog($syslog_ident, LOG_PERROR, $syslog_facility);
						$syslog_message = sprintf('Successful authentication attempt from [%s] for user \'%s\'', $_SERVER['REMOTE_ADDR'], $_SESSION["userlogin"]);
						syslog(LOG_INFO, $syslog_message);
						closelog();
					}
					//If a user has just authenticated, redirect him to index with timestamp, so post-data gets lost.
					session_write_close();
					clean_page("index.php");
					exit;
				}
			} else if (isset($_POST['authenticate'])) {
//				auth( _('Authentication failed! - <a href="reset_password.php">(forgot password)</a>'),"error");
                auth( _('Authentication failed!'),"error");
			} else {
				auth();
			}		
			
		} else if (isset($_POST['authenticate'])) {
			// Log to syslog if it's enabled
			if ($syslog_use)
			{
				openlog($syslog_ident, LOG_PERROR, $syslog_facility);
				$syslog_message = sprintf('Failed authentication attempt from [%s]', $_SERVER['REMOTE_ADDR']);
				syslog(LOG_WARNING, $syslog_message);
				closelog();
			}

			//Authentication failed, retry.
//			auth( _('Authentication failed! - <a href="reset_password.php">(forgot password)</a>'),"error");
            auth( _('Authentication failed!'),"error");
		} else {
			auth();
		}
		
	} else {
		//No username and password set, show auth form (again).
		auth();
	}
}

/*
 * Print the login form.
 */

function auth($msg="",$type="success")
{
	include_once('inc/header.inc.php');
	if ( $msg )
	{
		print "<div class=\"$type\">$msg</div>\n";
	}
	?>
	<h2><?php echo _('Log in'); ?></h2>
	<?php
	?>
	<form method="post" action="<?php echo htmlentities($_SERVER["PHP_SELF"], ENT_QUOTES); ?>">
	 <table border="0">
	  <tr>
	   <td class="n" width="100"><?php echo _('Username'); ?>:</td>
	   <td class="n"><input type="text" class="input" name="username" id="username"></td>
	  </tr>
	  <tr>
	   <td class="n"><?php echo _('Password'); ?>:</td>
	   <td class="n"><input type="password" class="input" name="password"></td>
	  </tr>
	  <tr>
	   <td class="n">&nbsp;</td>
	   <td class="n">
	    <input type="submit" name="authenticate" class="button" value=" <?php echo _('Go'); ?> ">
	   </td>
	  </tr>
	 </table>
	</form>
        <script type="text/javascript">
         <!--
          document.getElementById('username').focus();
         //-->
        </script>
	<?php
	include_once('inc/footer.inc.php');
	exit;
}


/*
 * Logout the user and kickback to login form.
 */

function logout($msg="",$type="")
{
	unset($_SESSION["userid"]);
	unset($_SESSION["name"]);
	session_unset();
	session_destroy();
	session_write_close();
	auth($msg, $type);
	exit;
}

?>
