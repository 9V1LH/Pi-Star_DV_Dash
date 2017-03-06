<?php include_once $_SERVER['DOCUMENT_ROOT'].'/config/ircddblocal.php';
$configs = array();

if ($configfile = fopen($gatewayConfigPath,'r')) {
        while ($line = fgets($configfile)) {
                list($key,$value) = split("=",$line);
                $value = trim(str_replace('"','',$value));
                if ($key != 'ircddbPassword' && strlen($value) > 0)
                $configs[$key] = $value;
        }

}
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
$rev="20141101";
$MYCALL=strtoupper($callsign);
?>
<?php
$cpuLoad = sys_getloadavg();
$cpuTemp = exec('awk \'{printf("%.1f\n",$1/1e3)}\' /sys/class/thermal/thermal_zone0/temp');
if ($cpuTemp < 50) { $cpuTempHTML = "<td bgcolor=\"#1d1\">".$cpuTemp."&degC</td>\n"; }
if ($cpuTemp >= 50) { $cpuTempHTML = "<td bgcolor=\"#fa0\">".$cpuTemp."&degC</td>\n"; }
if ($cpuTemp >= 69) { $cpuTempHTML = "<td bgcolor=\"#f00\">".$cpuTemp."&degC</td>\n"; }
?>
<b>Gateway Hardware Information</b>
<table>
  <tr>
    <th><a class=tooltip href="#">Hostname<span><b>Hostname</b></span></a></th>
    <th><a class=tooltip href="#">Kernel<span><b>Release</b></span></a></th>
    <th colspan="2"><a class=tooltip href="#">Platform<span><b>Architecture</b></span></a></th>
    <th><a class=tooltip href="#">CPU Load<span><b>CPU Load</b></span></a></th>
    <th><a class=tooltip href="#">CPU Temp<span><b>CPU Temp</b></span></a></th>
  </tr>
  <tr>
    <td><?php echo php_uname('n');?></td>
    <td><?php echo php_uname('r');?></td>
    <td colspan="2"><?php echo exec('platformDetect.sh');?></td>
    <td><?php echo $cpuLoad[0];?> / <?php echo $cpuLoad[1];?> / <?php echo $cpuLoad[2];?></td>
    <?php echo $cpuTempHTML; ?>
  </tr>
  <tr>
    <th colspan="6">Service Status</th>
  </tr>
  <tr>
    <td width="16.66%" bgcolor="#<?php exec ("pgrep dstarrepeaterd", $dstarrepeaterpid); if (!empty($dstarrepeaterpid)) { echo "1d1"; } else { echo "b55"; } ?>">DStarRepeater</td>
    <td width="16.66%" bgcolor="#<?php exec ("pgrep MMDVMHost", $mmdvmhostpid); if (!empty($mmdvmhostpid)) { echo "1d1"; } else { echo "b55"; } ?>">MMDVMHost</td>
    <td width="16.66%" bgcolor="#<?php exec ("pgrep ircddbgatewayd", $ircddbgatewaypid); if (!empty($ircddbgatewaypid)) { echo "1d1"; } else { echo "b55"; } ?>">ircDDBGateway</td>
    <td width="16.66%" bgcolor="#<?php exec ("pgrep timeserverd", $timeserverpid); if (!empty($timeserverpid)) { echo "1d1"; } else { echo "b55"; } ?>">TimeServer</td>
    <td width="16.66%" bgcolor="#<?php exec ("pgrep -f -a /usr/local/sbin/pistar-watchdog | sed '/pgrep/d'", $watchdogpid); if (!empty($watchdogpid)) { echo "1d1"; } else { echo "b55"; } ?>">PiStar-Watchdog</td>
    <td width="16.66%" bgcolor="#<?php exec ("pgrep -f -a /usr/local/sbin/pistar-keeper | sed '/pgrep/d'", $keeperpid); if (!empty($keeperpid)) { echo "1d1"; } else { echo "b55"; } ?>">PiStar-Keeper</td>
  </tr>
</table>
<br />
