<?php
require_once './JSMin.php';
require_once './CssMin.php';

error_reporting(0);
$dir = '../editor';
$ateditorjs = '
/*
 * In the name of GOD
 *
 *
 * ATEditor
 * By: AliReza_Tofighi
 * Version: 0.0001 Alfa
 */
';

$ateditorcss = '
/*
 * In the name of GOD
 *
 *
 * ATEditor
 * By: AliReza_Tofighi
 * Version: 0.0001 Alfa
 */
';

$replacements = 0;
$fixes = 0;
$skipped = 0;

$tabs = '';
fix_file($dir.'/editor.js');
fix_file($dir.'/stylesheet.css');
while(($msg = browse($dir.'/plugins', $tabs)) === true);

echo $msg;


file_put_contents('./minify/editor.min.js', $ateditorjs);
file_put_contents('./minify/stylesheet.min.css', $ateditorcss);

exit;

function browse($dir, &$tabs)
{
	$tabs .= '+';
	
	// Directory?
	if (is_dir($dir))
	{
		if ($directory = opendir($dir))
		{
			echo "<br /><strong>$tabs Entering directory $dir</strong>";
			while (($file = readdir($directory)) !== false)
			{
				if($file == '..' || $file == '.')
					continue;
				
				if(is_dir($dir.'/'.$file))
				{
					browse($dir.'/'.$file, $tabs);
					$tabs = substr_replace($tabs, '', strlen($tabs)-1, 1);
				}
				else
				{
					if(substr($file, -3) != '.js' && substr($file, -4) != '.css')
						continue;
					
					fix_file($dir.'/'.$file, $tabs);
				}
			}
			closedir($directory);
		}
	}
	else
		return "Error: $dir is not a directory.";
		
}

function fix_file($filepath, $tabs)
{
	echo "<br />$tabs Minify $filepath...";
	global $ateditorjs, $ateditorcss;
	if(substr($filepath, -3) == '.js')
	{
		$output = JSMin::minify(file_get_contents($filepath));
		$ateditorjs .= $output;
	}
	elseif(substr($filepath, -4) == '.css')
	{
		$output = CssMin::minify(file_get_contents($filepath));
		$ateditorcss .= $output;
	}

	return true;
}