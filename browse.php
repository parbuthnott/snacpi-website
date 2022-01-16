<?php
	session_start();
	$pagetype='home';
	$subpagetype='browse';
	include "connect_up.php";

	$rootname = 'browse.php';
	include "lhs.html"; ?>

	<div id="main">
		<h1>Browse.</h1>

		<?php
			// #extension# View historical playing?
			// #option# use mbrainz track listing & hope that they match the PI version?
			// #option# use mbrainz track listing then Jukebox (or similar) library function?

			$band = '0';
			if (isset($_GET['band'])) $band = $_GET['band'];
			if (isset($_POST['band'])) $band = $_POST['band'];

			if ($band == '0') {
				echo "<h2>Pick a band from the list ...</h2>";
				$musicFolder = opendir($rootmusicdir);
				while (false !== ($banddir = readdir($musicFolder))) {
					if ($banddir != "." && $banddir != "..") { // && is_dir($bands)) {
						$bands[] = $banddir;
					}
				}
				closedir($musicFolder);
				natcasesort ($bands);
				$bandnum = '0'; $dun = '0';
				$halftotalbands = count($bands)/2;
				echo "<!-- halftotalbands: $halftotalbands -->";
				echo "<ul class='side'>\n";
				foreach ($bands as $banditem) {
					$bandnum++;
					if ($bandnum > $halftotalbands && $dun == '0') {
						echo "</ul><ul class='side'>\n";
						$dun++;
					}
					echo "<li><a href=\"browse.php?band=".urlencode($banditem)."\" title=\"browse all by this band\">$banditem</a></li>\n";
				}
				echo "</ul>\n";
			} else {
				echo "<h2>Band: $band</h2>";
				$bandFolder = opendir($rootmusicdir."/".$band);
				while (false !== ($dir = readdir($bandFolder))) {
					echo "<!-- looking at : ".$rootmusicdir."/".$band."/".$dir." -->";
					if (is_dir($rootmusicdir."/".$band."/".$dir) && $dir != "." && $dir != "..") {
						echo "<!-- isdir : ".$dir." -->";
						if ($albumFolder = opendir($rootmusicdir."/".$band."/".$dir)) {
							echo "<!-- opened : ".$rootmusicdir."/".$band."/".$dir." -->";
							$albums[] = $dir;
							closedir($albumFolder);
						} else {
							echo "<!-- cant read dir: ".$dir." -->";
						}
					}
				}
				closedir($bandFolder);

				foreach ($albums as $cover) {
					showBrowseItem($band, $cover);
				}
				echo "<p>Alternatively, just <a onclick=\"history.go(-1);\" title=\"go back\">go back</a>.</p>";
			}

		?>

	</div>

	<?php include "foot.html"?>
