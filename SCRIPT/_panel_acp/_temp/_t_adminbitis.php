<?php
if (!defined('yakusha')) die('...');
$endtime = microtime(true); 
$bitistime = substr(($endtime - $starttime),0,6); 

//$kullanim = memory_get_usage();
$kullanim = memory_get_peak_usage(true);
$kullanim = round($kullanim / 1024 / 1024, 2);
?>
</div>
<span class="corners-bottom"><span></span></span>
</div>
</div>
</div>

<div id="page-footer">
SÜS: <?=$bitistime?> sayine. USG: <?=$kullanim?>  MB.<br><a class="vitrinler" href="http://www.libreajans.com">Libre Ajans</a>
</div>
</body>
</html>