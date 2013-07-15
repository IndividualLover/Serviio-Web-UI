<ul id="logsFileTab" class="shadetabs">
	<li><a href="#" rel="logs1" class="selected"><?php echo tr('tab_log_file','Serviio log file')?></a></li>
</ul>

<div style="border:1px solid gray; width:98%; margin-bottom: 1em; padding: 10px">
	<div id="logs1" class="tabcontent"><br>
		 
        <div id="dialog-form2" title="<?php echo tr('dialog_select_folder','Select File')?>">
    <form accept-charset="utf-8">
        <fieldset>
            <label for="selValue2"><?php echo tr('dialog_selected_folder','Selected File')?>:&nbsp;</label>
            <input type="text" id="selValue2" name="selValue2" readonly="readonly" size="70" style="width:450px;" />
            <div id="smallbrowser2"></div>
        </fieldset>
    </form>
</div>
		<table>
			<tr>
				<td><?php echo tr('tab_logs_file_location','Location of Serviio log file')?>:&nbsp;</td>
				<td><input type="text" id="logfile" name="logfile" size="60" value="<?php echo $serviio_log?>" disabled>&nbsp;&nbsp; <button type="button" id="addFolder2" name="addFolder2" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
                    <?php echo tr('button_add_local','Select File')?>
                </button></td>
			</tr>
		</table>
		<div align="right">
        <input type="submit" id="submit2" name="save" value="<?php echo tr('button_save','Save')?>" class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
			<span id="savingMsg" class="savingMsg"></span>
			<input type="submit" id="refresh" name="refresh" value="<?php echo tr('button_refresh','Refresh')?>" onclick=indexes.expandit(7) class="ui-button ui-widget ui-state-default ui-corner-all btn-small" />
			<br>
		</div>
  </div>
</div>


