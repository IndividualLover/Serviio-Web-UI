

<ul id="logsContentTab" class="shadetabs">
	<li><a href="#" rel="logs2" class="selected"><?php echo tr('tab_log_content','Log file content')?></a></li>
</ul>

<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
	<div id="logs2" class="tabcontent">
		<br>
			<?php
				if (!empty($serviio_log)) {
				$log = $serviio_log;
				$file = fopen( $log, "r") or exit('<strong><span style="color:#FF0000;text-align:left;">'.tr('tab_log_open_error','Unable to open Serviio log file!').'</span></strong>');
				//Output a line of the file until the end is reached
				while(!feof($file))
				{
					echo fgets($file). "<br>";
				}
				fclose($file);
				}
				else {
					echo tr('tab_log_empty','Variable "serviio_log" in config.php is empty.');
				}
			?>
		<br>
	</div>
</div>
