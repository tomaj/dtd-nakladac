<?php
/**************************************************************
 *	
 *		Zadanie na predmet prekladace
 *
 * 	zadaklny index subor
 *  Autori: Tomas Majer && Michal Lulco
 *
 **************************************************************/

// nainicilizujeme autoload
define('BASE_DIR', dirname(__FILE__) . '/../');
require_once(BASE_DIR . '/misc/Autoload.php');


// vytvorime gramatiku a tabulku
$gramatika = new Gramatika(AppConfig::get('terminaly'), AppConfig::get('empty_symbol'), AppConfig::get('gramatika'));
$table = $gramatika->getTable();

$firsts = $gramatika->getFirsts();
$follows = $gramatika->getFollows();



/**
 * Prints source code.
 * @param  string
 * @param  int
 * @param  int
 * @return void
 */
function _netteDebugPrintCode($file, $line, $count = 15)
{
	if (function_exists('ini_set')) {
		ini_set('highlight.comment', '#999; font-style: italic');
		ini_set('highlight.default', '#000');
		ini_set('highlight.html', '#06b');
		ini_set('highlight.keyword', '#d24; font-weight: bold');
		ini_set('highlight.string', '#080');
	}

	$start = max(1, $line - floor($count / 2));

	$source = explode("\n", @highlight_file($file, TRUE));
	echo $source[0]; // <code><span color=highlight.html>
	$source = explode('<br />', $source[1]);
	array_unshift($source, NULL);

	$i = $start; // find last highlighted block
	while (--$i >= 1) {
		if (preg_match('#.*(</?span[^>]*>)#', $source[$i], $m)) {
			if ($m[1] !== '</span>') echo $m[1];
			break;
		}
	}

	$source = array_slice($source, $start, $count, TRUE);
	end($source);
	$numWidth = strlen((string) key($source));

	foreach ($source as $n => $s) {
		$s = str_replace(array("\r", "\n"), array('', ''), $s);
		if ($n === $line) {
			printf(
				"<span class='highlight'>Line %{$numWidth}s:    %s\n</span>%s",
				$n,
				strip_tags($s),
				preg_replace('#[^>]*(<[^>]+>)[^<]*#', '$1', $s)
			);
		} else {
			printf("<span class='line'>Line %{$numWidth}s:</span>    %s\n", $n, $s);
		}
	}
	echo '</span></span></code>';
}



/**
 * Opens panel.
 * @param  string
 * @param  bool
 * @return void
 */
function _netteOpenPanel($name, $collaped)
{
	static $id;
	$id++;
	?>
	<div class="panel">
		<h2><a href="#" onclick="return !toggle(this, 'pnl<?php echo $id ?>')"><?php echo htmlSpecialChars($name) ?> <span><?php echo $collaped ? '&#x25b6;' : '&#x25bc;' ?></span></a></h2>

		<div id="pnl<?php echo $id ?>" class="<?php echo $collaped ? 'collapsed ' : '' ?>inner">
	<?php
}



/**
 * Closes panel.
 * @return void
 */
function _netteClosePanel()
{
	?>
		</div>
	</div>
	<?php
}

header("Content-type: text/html; charset=utf-8");

if (headers_sent()) {
	echo '</pre></xmp></table>';
}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex,noarchive">
	<meta name="generator" content="Nette Framework">

	<title>DTD Nakladač</title>

	<style type="text/css">
	/* <![CDATA[ */
		body {
			font: 78%/1.5 Verdana, sans-serif;
			background: white;
			color: #333;
			margin: 0 0 2em;
			padding: 0;
		}

		h1 {
			font-weight: normal !important;
			font-size: 18pt;
			margin: .6em 0;
		}

		h2 {
			font-family: sans-serif;
			font-weight: normal;
			font-size: 14pt;
			color: #888;
			margin: .6em 0;
		}

		a {
			text-decoration: none;
			color: #4197E3;
		}

		a span {
			color: #999;
		}

		h3 {
			font-size: 110%;
			font-weight: bold;
			margin: 1em 0;
		}

		p { margin: .8em 0 }

		pre, code, table {
			font-family: Consolas, monospace;
		}

		pre, table {
			background: #ffffcc;
			padding: .4em .7em;
			border: 1px dotted silver;
		}

		table pre {
			padding: 0;
			margin: 0;
			border: none;
			font-size: 100%;
		}

		pre.dump span {
			color: #c16549;
		}

		div.panel {
			border-bottom: 1px solid #eee;
			padding: 1px 2em;
		}

		div.inner {
			padding: 0.1em 1em 1em;
			background: #f5f5f5;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}

		td, th {
			vertical-align: top;
			padding: 2px 3px;
			border: 1px solid #eeeebb;
		}

		ul {
			font-size: 80%;
		}

		.highlight, #error {
			background: #C9D7F1;
			color: black;
			font-weight: bold;
			font-style: normal;
			display: block;
		}

		.line {
			color: #9e9e7e;
			font-weight: normal;
			font-style: normal;
		}
		
		.term {
			font-style:italic;
		}
		.neterm {
			color:gray;
		}
		.resok {
			color:green;
			font-weight:bold;
		}
		.reserror{
			color:red;
			font-weight:bold;
		}
		.hw {
			color:red;
		}

	/* ]]> */
	</style>


	<script type="text/javascript">
	/* <![CDATA[ */
		document.write('<style> .collapsed { display: none; } </style>');

		function toggle(link, panel)
		{
			var span = link.getElementsByTagName('span')[0];
			var div = document.getElementById(panel);
			var collapsed = div.currentStyle ? div.currentStyle.display == 'none' : getComputedStyle(div, null).display == 'none';

			span.innerHTML = String.fromCharCode(collapsed ? 0x25bc : 0x25b6);
			div.style.display = collapsed ? 'block' : 'none';

			return true;
		}
	/* ]]> */
	</script>
</head>



<body>
	<div id="error" class="panel">
		<h1>DTD Nakladač</h1>
		<p>silná aplikácia nakladacia DTD schému skrz astrálne aspektý prekladačové...</p>
	</div>

	<?php _netteOpenPanel('Gramatika', TRUE) ?>
		<?php $gramatika = AppConfig::get('gramatika'); ?>
		<table id="pnl-env-const">
		<?php
		foreach ($gramatika as $line) {
			$parts = explode(' -> ', $line);
			$replace = array(' | ' => ' <strong>|</strong> ', 'nil' => '<i>nil</i>');
			$parts[1] = str_replace(array_keys($replace), array_values($replace), htmlspecialchars($parts[1]));
			echo '<tr><td><strong>', htmlspecialchars($parts[0]), "</strong> -> </td><td>", $parts[1] ,"</td></tr>\n";
		}
		?>
		</table>
	<?php _netteClosePanel() ?>
	
	<?php _netteOpenPanel('Tabuľka / First&Follow', TRUE) ?>
		<h3>Tabuľka</h3>
		<?php echo $table->getHTMLTable(); ?>
		<h3>First</h3>
		<table>
			<?php foreach ($firsts as $key => $data): ?>
				<tr><td><?php echo $key; ?></td><td><?php echo htmlspecialchars(implode(' ', $data)); ?></td></tr>
			<?php endforeach; ?>
		</table>
		<h3>Follow</h3>
		<table>
			<?php foreach ($follows as $key => $data): ?>
				<tr><td><?php echo $key; ?></td><td><?php echo htmlspecialchars(implode(' ', $data)); ?></td></tr>
			<?php endforeach; ?>
		</table>
	<?php _netteClosePanel() ?>
	
	<?php _netteOpenPanel('Vstup', FALSE) ?>
		<form method="post" action="">
			<h3>Sem zadajte vašu DTD schému</h3>
			<textarea rows="10" cols="60" name="vstup"><?php if (isset($_POST['vstup'])) echo htmlspecialchars($_POST['vstup']); ?></textarea>
			<br/>
			<input name="submit" type="submit" value="Odošli" />
		</form>
	<?php _netteClosePanel() ?>

	
	<?php
		if (isset($_POST['vstup']))
		{
			_netteOpenPanel('Výstup', FALSE);
			
			$input = $_POST['vstup'];
			$codeAnalyzer = new CodeAnalyzer($input);
			$analyzedCode = $codeAnalyzer->getAnalyzedCode();
			
			// vytvorit kram pre akceptaciu
			$kram = new Kram($table);
			$result = $kram->validateInput($analyzedCode, new Symbol('S', Symbol::NETERMINAL));
			
			
			// vvysledok
			if ($result)
			{
				echo "<h3 class=\"resok\">Vstup je korektný</h3>";
			}
			else
			{
				echo "<h3 class=\"reserror\">CHYBA!</h3>";
				
				//print_r($kram->getError());
				$error = $kram->getError();
				//var_dump($error);
				
				
				//echo "<code style=\"display:block; padding:3px; border:1px solid #EEEEEE\"><span><span>";
				echo "<pre><span><span>";
				$vstup = $_POST['vstup'];
				$vstup = str_replace("\r", "\n", $vstup);
				$vstup = str_replace("\n\n", "\n", $vstup);
				$lines = explode("\n", $vstup);
				$l = 1;
				foreach ($lines as $line)
				{
					$line = htmlspecialchars($line);
					if ($l-1 == $error['line'])
					{
					
						$words = explode(' ', $line);
						$al = array();
						for ($i = 0; $i < count($words); $i++)
						{
							if ($i+1 == $error['word']) $al[] = '<span class="hw">'.$words[$i].'</span>';
							else $al[] = $words[$i];
						}
						$line = implode(' ', $al);
						
						echo "<span class=\"highlight\">Line:\t$l: $line</span>";
					}
					else
					{
						echo "<span class=\"line\">Line:\t$l: $line</span><br/>";
					}
					
					$l++;
				}
				//$vstup = str_replace("\r", "\n", $vstup);
				
				//echo htmlspecialchars($vstup);
				echo "</span></span></pre><br/><br/>";
			}
			
			
			echo "<table>";
			echo "<thead><tr><td width=\"40%\"><b>Vstup</b></td><td width=\"40%\"><b>Zasobnik</b></td><td width=\"20%\"><b>Kroky</b></td></tr></thead>";
			
			foreach ($kram->getResult() as $item)
			{
				echo "<tr>";
				echo "<td>".htmlspecialchars($item['input'])."</td>";
				echo "<td>".$item['stack']."</td>";
				echo "<td>".htmlspecialchars($item['path'])."</td>";
				echo "</tr>";
			}
			
			echo "</table>";
			
			_netteClosePanel();
		}
	?>

</body>
</html>