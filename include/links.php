<table class="table table-sm table-striped table-hover">
	<tr class="table-center">
		<th>#</th>
		<th>Flag</th>
		<th>DV Station</th>
		<th>Via</th>
		<th>Last Heard</th>
		<th>Linked for</th>
		<th>Protocol</th>
		<th>Module</th><?php

if ($PageOptions['LinksPage']['IPModus'] != 'HideIP') {
	echo '
	<th>IP</th>';
}

?>
		</tr>
<?php
$Reflector->LoadFlags();

for ($i=0;$i<$Reflector->NodeCount();$i++) {

	echo '<tr class="table-center">
	<td>'.($i+1).'</td>
	<td>';
	list ($Flag, $Name) = $Reflector->GetFlag($Reflector->Nodes[$i]->GetCallSign());
	if (file_exists("./images/flags/".$Flag.".svg")) {
		echo '<a href="#" class="tip"><img src="./images/flags/'.$Flag.'.svg" class="table-flag" alt="'.$Name.'"><span>'.$Name.'</span></a>';
	}
	echo '</td>
	<td>'.$Reflector->Nodes[$i]->GetCallSign();
	if ($Reflector->Nodes[$i]->GetSuffix() != "") { echo '-'.$Reflector->Nodes[$i]->GetSuffix(); }
	echo '</td>
	<td>';
	if ($Reflector->Nodes[$i]->GetPrefix() == 'M17') {
		switch ($Reflector->Nodes[$i]->GetPrefix()) {
			case 'M17'  : echo 'M17-Link'; break;
		}
	}
	else {
		switch ($Reflector->Nodes[$i]->GetSuffix()) {
			case 'A' : echo '23cm'; break;
			case 'B' : echo '70cm'; break;
			case 'C' : echo '2m'; break;
			case 'D' : echo 'DV Client'; break;
			case 'G' : echo 'Internet-Gateway'; break;
			default  : echo '';
		}
	}
	echo '</td>
	<td>'.date("d.m.Y H:i", $Reflector->Nodes[$i]->GetLastHeardTime()).'</td>
	<td>'.FormatSeconds(time()-$Reflector->Nodes[$i]->GetConnectTime()).' s</td>
	<td>'.$Reflector->Nodes[$i]->GetProtocol().'</td>
	<td>'.$Reflector->Nodes[$i]->GetLinkedModule().'</td>';
	if ($PageOptions['LinksPage']['IPModus'] != 'HideIP') {
		echo '<td>';
		$Bytes = explode(".", $Reflector->Nodes[$i]->GetIP());
		$MC = $PageOptions['LinksPage']['MasqueradeCharacter'];
		if ($Bytes !== false && count($Bytes) == 4) {
			switch ($PageOptions['LinksPage']['IPModus']) {
				case 'ShowLast1ByteOfIP':
					echo $MC.'.'.$MC.'.'.$MC.'.'.$Bytes[3];
					break;
				case 'ShowLast2ByteOfIP':
					echo $MC.'.'.$MC.'.'.$Bytes[2].'.'.$Bytes[3]; break;
				case 'ShowLast3ByteOfIP':
					echo $MC.'.'.$Bytes[1].'.'.$Bytes[2].'.'.$Bytes[3];
					break;
				default:
					echo $Reflector->Nodes[$i]->GetIP();
			}
		} else {
			$ipstr = $Reflector->Nodes[$i]->GetIP();
			$count = substr_count($ipstr, ":");
			if ($count > 1) {
				if (1 == substr_count($ipstr, "::")) { $ipstr = str_replace("::", str_repeat(":", 9 - $count), $ipstr); }
				if (7 == substr_count($ipstr, ":")) {
					echo $MC.':'.$MC.':'.$MC.':'.$MC.':'.$MC.':'.$MC;
					$Bytes = explode(":", $ipstr);
					for( $k=6; $k<8; $k++) { echo (0==strlen($Bytes[$k])) ? ':0' : ':'.$Bytes[$k]; }
				}
			}
		}
		echo '</td>';
   }
   echo '</tr>';
   if ($i == $PageOptions['LinksPage']['LimitTo']) { $i = $Reflector->NodeCount()+1; }
}

?>

</table>
