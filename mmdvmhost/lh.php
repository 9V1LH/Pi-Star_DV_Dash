<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';          // MMDVMDash Config
include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/tools.php';        // MMDVMDash Tools
include_once $_SERVER['DOCUMENT_ROOT'].'/mmdvmhost/functions.php';    // MMDVMDash Functions
include_once $_SERVER['DOCUMENT_ROOT'].'/config/language.php';	      // Translation Code
?>
<input type="hidden" name="lh-autorefresh" value="OFF" />
<b><?php echo $lang['last_heard_list'];?></b>
<div>
    <div>
	<table>
	    <tr>
		<th style="width: 125px;"><a class="tooltip" href="#"><?php echo $lang['time'];?> (<?php echo date('T')?>)<span><b>Time in <?php echo date('T')?> time zone</b></span></a></th>
		<th style="width: 50px;"><a class="tooltip" href="#"><?php echo $lang['mode'];?><span><b>Transmitted Mode</b></span></a></th>
		<th style="width: 93px;"><a class="tooltip" href="#"><?php echo $lang['callsign'];?><span><b>Callsign</b></span></a></th>
		<th style="width: 164px;"><a class="tooltip" href="#"><?php echo $lang['target'];?><span><b>Target, D-Star Reflector, DMR Talk Group etc</b></span></a></th>
		<th style="width: 30px;"><a class="tooltip" href="#"><?php echo $lang['src'];?><span><b>Received from source</b></span></a></th>
		<th style="width: 57px;"><a class="tooltip" href="#"><?php echo $lang['dur'];?>(s)<span><b>Duration in Seconds</b></span></a></th>
		<th style="width: 45px;"><a class="tooltip" href="#"><?php echo $lang['loss'];?><span><b>Packet Loss</b></span></a></th>
		<th style="width: max-content;"><a class="tooltip" href="#"><?php echo $lang['ber'];?><span><b>Bit Error Rate</b></span></a></th>
	    </tr>
	</table>
    </div>
    <div style="max-height:255px; overflow-y:auto;">
	<table>
	    <tbody>
<?php
$i = 0;
$maxCount = min(40, count($lastHeard)); // last 40 calls
for ($i = 0; $i < $maxCount; $i++) {
    $listElem = $lastHeard[$i];
    if ( $listElem[2] ) {
	$utc_time = $listElem[0];
        $utc_tz =  new DateTimeZone('UTC');
        $local_tz = new DateTimeZone(date_default_timezone_get ());
        $dt = new DateTime($utc_time, $utc_tz);
        $dt->setTimeZone($local_tz);
        $local_time = $dt->format('H:i:s M jS');
	echo"<tr>";
	echo"<td align=\"left\" style=\"width: 125px;\">$local_time</td>"; // Time
	echo"<td align=\"left\" style=\"width: 50px;\">$listElem[1]</td>"; // Mode
	// Callsign
	if (is_numeric($listElem[2]) || strpos($listElem[2], "openSPOT") !== FALSE) {
	    echo "<td align=\"left\" style=\"width: 93px;\">$listElem[2]</td>";
	} elseif (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $listElem[2])) {
            echo "<td align=\"left\" style=\"width: 93px;\">$listElem[2]</td>";
	} else {
	    if (strpos($listElem[2],"-") > 0) { $listElem[2] = substr($listElem[2], 0, strpos($listElem[2],"-")); }
	    if ( $listElem[3] && $listElem[3] != '    ' ) {
		//echo "<td align=\"left\"><a href=\"http://www.qrz.com/db/$listElem[2]\" data-featherlight=\"iframe\" data-featherlight-iframe-min-width=\"90%\" data-featherlight-iframe-max-width=\"90%\" data-featherlight-iframe-width=\"2000\" data-featherlight-iframe-height=\"2000\">$listElem[2]</a>/$listElem[3]</td>";
		echo "<td align=\"left\" style=\"width: 93px;\"><a href=\"http://www.qrz.com/db/$listElem[2]\" target=\"_blank\">$listElem[2]</a>/$listElem[3]</td>";
	    } else {
		//echo "<td align=\"left\"><a href=\"http://www.qrz.com/db/$listElem[2]\" data-featherlight=\"iframe\" data-featherlight-iframe-min-width=\"90%\" data-featherlight-iframe-max-width=\"90%\" data-featherlight-iframe-width=\"2000\" data-featherlight-iframe-height=\"2000\">$listElem[2]</a></td>";
		echo "<td align=\"left\" style=\"width: 93px;\"><a href=\"http://www.qrz.com/db/$listElem[2]\" target=\"_blank\">$listElem[2]</a></td>";
	    }
	}
	
	// Target
	if (substr($listElem[4], 0, 6) === 'CQCQCQ' ) {
	    echo "<td align=\"left\" style=\"width: 164px;\">$listElem[4]</td>";
	} else {
	    echo "<td align=\"left\" style=\"width: 164px;\">".str_replace(" ","&nbsp;", $listElem[4])."</td>";
	}
	
	// Src
	if ($listElem[5] == "RF"){
	    echo "<td style=\"background:#1d1; width: 30px;\">RF</td>";
	} else {
	    echo "<td style=\"width: 30px;\">$listElem[5]</td>";
	}
	// Duration
	if ($listElem[6] == null) {
	    echo "<td style=\"background:#f33; width: 57px;\">TX</td><td style=\"width: 45px;\"></td><td style=\"width: max-content;\"></td>";
	} else if ($listElem[6] == "SMS") {
	    echo "<td style=\"background:#1d1; width: 57px;\">SMS</td><td style=\"width: 45px;\"></td style=\"width: max-content;\"><td></td>";
	} else {
	    echo "<td style=\"width: 57px;\">$listElem[6]</td>";
	    
	    // Colour the Loss Field
	    if (floatval($listElem[7]) < 1) {
		echo "<td style=\"width: 45px;\">$listElem[7]</td>"; }
	    elseif (floatval($listElem[7]) == 1) {
		echo "<td style=\"background:#1d1; width: 45px;\">$listElem[7]</td>"; }
	    elseif (floatval($listElem[7]) > 1 && floatval($listElem[7]) <= 3) {
		echo "<td style=\"background:#fa0; width: 45px;\">$listElem[7]</td>"; }
	    else {
		echo "<td style=\"background:#f33; width: 45px;\">$listElem[7]</td>";
	    }
	    
	    // Colour the BER Field
	    if (floatval($listElem[8]) == 0) {
		echo "<td style=\"width: max-content;\">$listElem[8]</td>"; }
	    elseif (floatval($listElem[8]) >= 0.0 && floatval($listElem[8]) <= 1.9) {
		echo "<td style=\"background:#1d1; width: max-content;\">$listElem[8]</td>"; }
	    elseif (floatval($listElem[8]) >= 2.0 && floatval($listElem[8]) <= 4.9) {
		echo "<td style=\"background:#fa0; width: max-content;\">$listElem[8]</td>"; }
	    else {
		echo "<td style=\"background:#f33; width: max-content;\">$listElem[8]</td>";
	    }
	}
	echo"</tr>\n";
    }
}

?>
	    </tbody>
	</table>
    </div>
    <div style="float: right; vertical-align: bottom; padding-top: 5px;">
	<div class="grid-container" style="display: inline-grid; grid-template-columns: auto 40px; padding: 1px; grid-column-gap: 5px;">
	    <div class="grid-item" style="padding-top: 3px;" >Auto Refresh
	    </div>
	    <div class="grid-item" >
		<div> <input id="toggle-lh-autorefresh" class="toggle toggle-round-flat" type="checkbox" name="lh-autorefresh" value="ON" checked="checked" aria-checked="true" aria-label="Auto Refresh" onchange="setLHAutorefresh(this)" /><label for="toggle-lh-autorefresh" ></label>
		</div>
	    </div>
	</div>
    </div>
    <br />
</div>
