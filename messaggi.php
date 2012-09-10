<?PHP
##################################################################################
#    FANTACALCIOBAZAR EVOLUTION
#    Copyright (C) 2003 - 2008 by Antonello Onida
#
#    This program is free software; you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation; either version 2 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with this program; if not, write to the Free Software
#    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
##################################################################################
require_once ("./controlla_pass.php");
include("./header.php");
if ($_SESSION['valido'] == "SI") {

	###cancella mex####
	if ($_SESSION['permessi'] >= 4 and $_SESSION['valido'] == "SI"){
		if($b!=0)
		unlink("./dati/messaggi/$b.php");
	}
	$b=0;
	#####fine cancella mex####

	###modifica mex####
	if ($_SESSION['permessi'] >= 4 and $_SESSION['valido'] == "SI"){
		if($c!=0)
		unlink("./dati/messaggi/$c.php");
	}
	$c=0;
	#####fine cancella mex####

	if ($usa_tinyMCE == "SI") echo '<center><div id="content">
	<script language="javascript" type="text/javascript" src="./inc/tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced"
	});
	</script>';
	############################################################
	### Funzioni messaggi

	function main(){
		global $path, $page, $messxpagina, $argxpagina, $sfondo_tab, $sfondo_tab3, $colore_riga_alt, $otdenom, $otmessaggi;
		if ($page == "") {$page = 0;}
		if ($page != 0) {$page = $page - 1;}
		echo "<div class='articolo_sh'>MESSAGGI DEL TORNEO \"$otdenom\"</div>
		<div class='articolo_s'>";
		echo "<table summary='' width='100%' cellspacing='1' cellpadding='1' bgcolor='$sfondo_tab'>
		<tr bgcolor='#AAAAAA'>
		<td align='center'>Oggetto</td>
		<td align='center'>Risposte</td>
		<td align='center'>Messaggio iniziale</td>
		<td align='center'>Ultimo messaggio</td>";

		if ($_SESSION['permessi'] >= 4 AND $_SESSION['valido'] == "SI"){
			echo "<th class='testa' width='150'>Azioni</th>";
		}
		echo "</tr>";
		$files = array();
		$dhandle = opendir("$path") or die("impossibile aprire la directory dei messaggi");
		while ($file = readdir($dhandle)) {
			if (($file == ".") || ($file == "..") || ($file == "index.php")) {} else {
				array_push($files, $file);
			}
		}

		rsort ($files);
		reset ($files);
		$idx = 0;
		for($x=$page*$messxpagina; $x<$messxpagina*($page+1); $x=$x+1) {
			$file = $files[$x];
			if ($file != "") {
				$topic = file("$path/$file");
				$topicinfo = explode("|", $topic[0]);
				if ($otmessaggi == 1 AND $otdenom == trim($topicinfo[5])) {
					$idx++;
					$topicdate = date("d-m-Y, H:i", $topicinfo[3]);
					$lastpostinfo = explode("|", $topic[count($topic)-1]);
					// generate page links for the topic
					$pages = "";
					if (count($topic) > $argxpagina){
						for ($thispagenum = 1; $thispagenum <= ceil(count($topic) / $argxpagina); $thispagenum = $thispagenum + 1) {
							$pages = $pages . "[<a href='messaggi.php?a=topic&amp;topic=".substr($file, 0, strlen($file)-4)."&amp;page=" . $thispagenum ."' style='color:#666666;font-weight:bold'>".$thispagenum."</a>] ";
						}
					}
					if ($idx % 2) $colore="#FFFFFF"; else $colore="$colore_riga_alt";
					echo "<tr bgcolor='$colore'>";
					?>
					<td align='left'><div class='box-simple' style='float:left;margin-right:10px;margin-left:5px'><span class='conversazione'><?php echo $idx; ?></span></div> <a href="messaggi.php?a=topic&amp;topic=<?php echo substr($file, 0, strlen($file)-4); ?>" class='user'><span class='conversazione'><?php echo $topicinfo[0]; ?></span></a> <span class='conversazione'><?php echo $pages; ?></span></td>
					<td align='center' width='70'>&nbsp;<span class='conversazione'><?php echo count($topic); ?></span>&nbsp;</td>
					<td align='center' width='200'><span class='conversazione'><?php echo $topicdate; ?><br /><?php echo $topicinfo[1]; ?></span></td>
					<td align='center' width='200'><span class='conversazione'><?php echo date("d-m-Y, H:i", $lastpostinfo[3]); ?><br /><?php echo $lastpostinfo[1]; ?></span></td>
					</tr>
					<?php
				}
				elseif ($otmessaggi == 0){
					$topicdate = date("d-m-Y, H:i", $topicinfo[3]);
					$lastpostinfo = explode("|", $topic[count($topic)-1]);
					// generate page links for the topic
					$pages = "";
					if (count($topic) > $argxpagina){
						for ($thispagenum = 1; $thispagenum <= ceil(count($topic) / $argxpagina); $thispagenum = $thispagenum + 1) {
							$pages = $pages . "[<a href='messaggi.php?a=topic&amp;topic=".substr($file, 0, strlen($file)-4)."&amp;page=" . $thispagenum ."' style='color:#666666;font-weight:bold'>".$thispagenum."</a>] ";
						}
					}
					if ($idx % 2) $colore="#FFFFFF"; else $colore="$colore_riga_alt";
					echo "<tr bgcolor='$colore'>";
					?>
					<td align='left'><?php echo $idx; ?> <a href="messaggi.php?a=topic&amp;topic=<?php echo substr($file, 0, strlen($file)-4); ?>" class='user'><?php echo $topicinfo[0]; ?></a> <?php echo $pages; ?></td>
					<td align='center' width='70'><?php echo count($topic)-1; ?></td>
					<td align='center' width='150'><?php echo $topicdate; ?><br /><?php echo $topicinfo[1]; ?></td>
					<td align='center' width='150'><?php echo date("d-m-Y, H:i", $lastpostinfo[3]); ?><br /><?php echo $lastpostinfo[1]; ?></td>
					<?php 
					if ($_SESSION['permessi'] >= 4 and $_SESSION['valido'] == "SI"){
						?>
					<td align='center' width='150'><a href="messaggi.php?b=<?php echo substr($file, 0, strlen($file)-4); ?>" class='user'><?php echo "Cancella"; ?></a><br />
					<a href="a_edita_file.php?mod_file=./dati/messaggi/<?php echo substr($file, 0, strlen($file)-4); ?>.php" class='user'><?php echo "Modifica"; ?></a><br /></td> <?}?>
					</tr>
					<?php
					echo "$b";
				}
			}
		}
		$pages = "";
		for ($thispagenum = 1; $thispagenum <= ceil(count($files) / $messxpagina); $thispagenum = $thispagenum + 1) {
			$pages = $pages . "[<a href='messaggi.php?page=" . $thispagenum ."' style='color:#666666;font-weight:bold'>".$thispagenum."</a>]";
		}
		echo "</table>";
		echo"</div>
		<div class='box-simple' style='background-color:#DDDDDD; margin-top:5px'><div style='float:left;line-height:40px;padding-left:3px'> Pag. $pages</div><div class='button' style='float:right'><a href='messaggi.php' class='button'>Indice messaggi</a></div>
		 <div class='button' style='float:right'><a href='messaggi.php?a=newtopic' class='button'>Nuovo messaggio</a></div>
		<div class='clear'></div></div>";

	}

	function topic () {
		global $topic, $path, $page, $argxpagina, $sfondo_tab, $sfondo_tab1, $sfondo_tab3, $colore_riga_alt, $otmessaggi;
		if ($page == "") {$page = 0;}
		if ($page != 0) {$page = $page - 1;}

		$posts = file("$path/$topic.php");
		for($x=$page*$argxpagina; $x<$argxpagina*($page+1); $x=$x+1) {
			$unsplitpost = $posts[$x];
			if ($unsplitpost != "") {
				$post = explode("|", $unsplitpost);
				?>
				<div class='articolo_sh'><?php echo $post[0]; ?></div>
				<div class='articolo_s'>
				<table>
				<tr>
				<td bgcolor='<? echo $sfondo_tab1 ; ?>' valign='top' width='150'>
				<?php echo"<div class='box-simple' style='margin-right:10px'><table summary='' width='100%' style='padding: 5px'><tr><td align='right'><font size='+6' color='#666666'>".date("d", $post[3])."</font></td><td>".date("m-Y", $post[3])."<br />".date("H:i", $post[3])."</td></tr><tr><td colspan='2' align='center'>Utente: <b>".$post[1]."</b><br />".$post[5]."</td></tr></table></div>";
				?>
				</td>
				<td valign='top' align='justify'><span class='conversazione'><?php echo html_entity_decode($post[2]); ?></span></td>
				</tr>
				</table>
				</div>
				<?php
			}
		}
		$pages = "";
		for ($thispagenum = 1; $thispagenum <= ceil(count($posts) / $argxpagina); $thispagenum = $thispagenum + 1) {
			$pages = $pages . "[<a href='messaggi.php?a=topic&amp;topic=$topic&amp;page=" . $thispagenum ."' style='color:#666666;font-weight:bold'>".$thispagenum."</a>] ";
		}
		
		echo "
		<div class='box-simple' style='background-color:#DDDDDD; margin-top:5px'><div style='float:left;line-height:40px;padding-left:3px'> Pag. $pages</div><div class='button' style='float:right'><a href='messaggi.php' class='button'>Indice messaggi</a></div>
		 <div class='button' style='float:right'><a href='messaggi.php?a=newtopic' class='button'>Nuovo messaggio</a></div>
		 <div class='button' style='float:right'><a href='messaggi.php?a=reply&amp;topic=$topic&amp;' class='button'>Rispondi al messaggio</a></div>
		<div class='clear'></div></div>";
	}


	function reply () {
		global $topic, $priv, $otdenom, $path, $sfondo_tab, $sfondo_tab3, $otmessaggi;
		$file_topic = file("$path/$topic.php");
		$nome_topic = explode ("|",$file_topic[0]);
		echo "<form action='messaggi.php' method='post'>
		<input type='hidden' name='a' value='reply2' />
		<input type='hidden' name='topic' value='$topic' />
		<input type='hidden' name='priv' value='$priv' />
		<table summary='' width='100%' bgcolor='$sfondo_tab'>
		<caption>Risposta</caption><tr>
		<td align='left'>Oggetto:</td>
		<td><input name='oggetto' value='Re: ".$nome_topic[0]."' /></td>
		</tr>
		<tr>
		<td align='center'>Messaggio:</td>
		<td align='left'><textarea name='post' cols='60' rows='5'></textarea></td>
		</tr>
		<tr>
		<td colspan='2' align='center'><input type='submit' value='      Invia      ' /></td>
		</tr>
		<tr bgcolor='$sfondo_tab3'>
		<td colspan='2' align='right'><a href='messaggi.php' class='user1'>Indice messaggi</a> - <a href='messaggi.php?a=newtopic' class='user1'>Nuovo messaggio</a></td>
		</tr>
		</table></form>";
	}

	function reply2 () {
		global $topic, $oggetto, $priv, $post, $path, $sfondo_tab, $sfondo_tab3, $otdenom, $otmessaggi;
		echo "
		<table summary='' width='100%' bgcolor='$sfondo_tab'>
		<caption>Risposta</caption>
		<tr><td>";

		if ($post == "") {
			echo "Umh... testo mancante.";
		} elseif ($oggetto == "") {
			echo "Oggetto non inserito.";
		} else {
			$post = aggiusta_tag($post);
			$post = stripslashes(strip_tags($post,"<b><i><u>"));
			$post = str_replace("\r\n", "<br />", $post);
			$post = str_replace("|", "", $post);
			$post = str_replace("<", "&lt;", $post);
			$post = str_replace(">", "&gt;", $post);
			$oggetto = aggiusta_tag($oggetto);
			$oggetto = stripslashes(strip_tags($oggetto,"<b><i><u>"));
			$oggetto = str_replace("|", "", $oggetto);
			$oggetto = str_replace(">", "&gt;", $oggetto);
			$oggetto = str_replace("<", "&lt;", $oggetto);
			$torneo = $otdenom;
			$messaggio = "$oggetto|".$_SESSION['utente']."|".htmlentities($post, ENT_QUOTES)."|".time()."|$priv|$torneo\r\n";
			$fhandle = fopen("$path/$topic.php", "a");
			fwrite ($fhandle, $messaggio);
			fclose ($fhandle);
			$filetime = time();
			rename ("$path/$topic.php", "$path/".$filetime.".php");
			echo "Risposta aggiunta.<br />Ritorna al <a href='messaggi.php?a=topic&amp;topic=".$filetime."' class='user'>messaggio</a>.";
		}
		echo "</td></tr>
		<tr bgcolor='$sfondo_tab3'>
		<td align='right'><a href='messaggi.php' class='user1'>Indice messaggi</a> - <a href='messaggi.php?a=newtopic' class='user1'>Nuovo messaggio</a></td>
		</tr>
		</table>";

	}

	function newtopic () {
		global $path, $sfondo_tab, $sfondo_tab3, $otdenom, $otmessaggi;
		echo "
		<form action='messaggi.php' method='post'>
		<input type='hidden' name='a' value='newtopic2' />
		<table summary='' width='100%' bgcolor='$sfondo_tab'>
		<caption>Nuovo messaggio</caption>
		<tr>
		<td>Oggetto:</td>
		<td><input name='oggetto' size='30' /></td>
		</tr>
		<tr>
		<td>Messaggio:</td>
		<td><textarea name='post' cols='80' rows='5'></textarea></td>
		</tr>
		<tr>
		<td><input type='checkbox' name='priv' value='SI' /> Selezionare per rendere il messaggio visibile in tutti i tornei! Ancora non perfettamente funzionante.</td>
		<td align='center'><input type='submit' value='      Nuovo messaggio      ' /><br /><br /><br /><br /></td>
		</tr>
		<tr bgcolor='$sfondo_tab3'>
		<td colspan='3' align='right'><a href='messaggi.php' class='user1'>Indice messaggi</a> - <a href='messaggi.php?a=newtopic' class='user1'>Nuovo messaggio</a></td>
		</tr>
		</table></form>";
	}




	function newtopic2 () {
		global $oggetto, $post, $priv, $otdenom, $path, $sfondo_tab, $sfondo_tab3, $otmessaggi;
		echo "
		<table summary='' width='100%' bgcolor='$sfondo_tab'>
		<caption>Risposta</caption>
		<tr>
		<td>";

		if ($post == "") {
			echo "Messaggio non inserito.";
		} elseif ($oggetto == "") {
			echo "Oggetto non inserito.";
		} else {
			$post = aggiusta_tag($post);
			$post = stripslashes(strip_tags($post,"<b><i><u>"));
			$post = str_replace("\r\n", "<br />", $post);
			$post = str_replace("|", "", $post);
			$post = str_replace("<", "&lt;", $post);
			$post = str_replace(">", "&gt;", $post);
			$oggetto = aggiusta_tag($oggetto);
			$oggetto = stripslashes(strip_tags($oggetto,"<b><i><u>"));
			$oggetto = str_replace("|", "", $oggetto);
			$oggetto = str_replace(">", "&gt;", $oggetto);
			$oggetto = str_replace("<", "&lt;", $oggetto);
			$torneo = $otdenom;
			if (!$priv) $priv = "NO";
			$messaggio = "$oggetto|".$_SESSION['utente']."|$post|".time()."|$priv|$torneo\r\n";
			$fhandle = fopen("$path/".time().".php", "w");
			fwrite ($fhandle, $messaggio);
			fclose ($fhandle);
			echo "Messaggio aggiunto.";
		}
		echo" <br /><br /><br /><br />
		</td>
		</tr>
		<tr bgcolor='$sfondo_tab3' class='user1'>
		<td align='right'><a href='messaggi.php' class='user1'>Indice messaggi</a> - <a href='messaggi.php?a=newtopic' class='user1'>Nuovo messaggio</a></td>
		</tr>
		</table>";
	}

	### fine funzioni messaggi
	############################################################

	if ($_SESSION["permessi"] <= 4) require ("./menu.php");
	elseif ($_SESSION["permessi"] == 5) require ("./a_menu.php");

	echo"<table summary='' width='100%' align='center' style='background-color: $sfondo_tab; padding: 5px; border: 1px solid $sfondo_tab2'>
	<caption>MESSAGGERIA UTENTI</caption>
	<tr><td align='left'>Si prega di utilizzare un linguaggio consono allo spirito sportivo;<br />
	non scrivere tutto in maiuscolo (in gergo significa URLARE!);<br />
	non scrivere termini offensivi;<br />
	Il titolo dei messaggi deve essere breve e conciso, indicativo del tema trattato (deprecati: attenzione, chiedo aiuto, etc; consigliati: inserire un cambio, schierare un calciatore, etc)!<br /><br /><br />";

	$path = ereg_replace ("messaggi.php","$percorso_cartella_dati/messaggi",$_SERVER["SCRIPT_FILENAME"]);
	$messxpagina = "20";
	$argxpagina = "15";
	$utentixpagina = "15";

	if ($a == "") {main();}
	if ($a == "topic") {topic();}
	if ($a == "reply") {reply();}
	if ($a == "reply2") {reply2();}
	if ($a == "newtopic") {newtopic();}
	if ($a == "newtopic2") {newtopic2();}
	echo "</td></tr></table>";
}
else header("location: logout.php?logout=2");
include("./footer.php");
?>
