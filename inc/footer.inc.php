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

include_once('version.inc.php');

global $db;
if(is_object($db))
{
	 $db->disconnect();
}

?>
  </div> <!-- /content -->
  <div class="footer">
   <a href="https://www.poweradmin.org/">a complete(r) <strong>poweradmin</strong><?php if (isset($_SESSION["userid"])) { echo " v $VERSION"; } ?></a> - <a href="https://www.poweradmin.org/trac/wiki/Credits">credits</a>
  </div>
<?php
if(file_exists('inc/custom_footer.inc.php')) 
{
	include('inc/custom_footer.inc.php');
}
?>
 </body>
</html>

<?php 
if (isset($db_debug) && $db_debug == true) {
	$debug = $db->getDebugOutput(); 
	$debug = str_replace("query(1)", "", $debug);
	$lines = explode(":", $debug);

	foreach ($lines as $line) {
		echo "$line<br>";
	}
}
?>
