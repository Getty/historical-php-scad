<?

if (!defined('HERE')) { define('HERE',realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR); }

function block($text) {
	echo "\n##################################################################################################################\n";
	echo "##################################################################################################################\n";
	echo "##################################################################################################################\n";
	echo "##\n";
	echo "## ".$text."\n";
	echo "##\n";
	echo "##################################################################################################################\n";
	echo "##################################################################################################################\n";
	echo "##################################################################################################################\n\n";
}

echo "<pre>";

block("Base Classes");

echo str_replace('<?','',file_get_contents(HERE.'testclasses/layer1_class1.php'));
echo "\n----------------------------------------------------------------------------------------------------------------------\n";
echo str_replace('<?','',file_get_contents(HERE.'testclasses/layer2_class1_parent_parent.php'));
echo "\n----------------------------------------------------------------------------------------------------------------------\n";
echo str_replace('<?','',file_get_contents(HERE.'testclasses/layer2_class1_parent.php'));
echo "\n----------------------------------------------------------------------------------------------------------------------\n";
echo str_replace('<?','',file_get_contents(HERE.'testclasses/layer2_class1.php'));
echo "\n----------------------------------------------------------------------------------------------------------------------\n";
echo str_replace('<?','',file_get_contents(HERE.'testclasses/layer3_class1_parent.php'));
echo "\n----------------------------------------------------------------------------------------------------------------------\n";
echo str_replace('<?','',file_get_contents(HERE.'testclasses/layer3_class1.php'));

require_once(HERE.'classes/SCAD_Hive.php');
require_once(HERE.'testclasses/layer1_class1.php');
require_once(HERE.'testclasses/layer2_class1_parent_parent.php');
require_once(HERE.'testclasses/layer2_class1_parent.php');
require_once(HERE.'testclasses/layer2_class1.php');
require_once(HERE.'testclasses/layer3_class1_parent.php');
require_once(HERE.'testclasses/layer3_class1.php');

block("SCAD Code");

echo "\$SCAD = new SCAD_Hive('Test');\n";
echo "\$SCAD->AddClass('Layer1_Class1')->AddClass('Layer2_Class1')->AddClass('Layer3_Class1');\n";
echo "echo \$SCAD->GetCode();\n";

block("SCAD Result");

$SCAD = new SCAD_Hive('Test');
$SCAD->AddClass('Layer1_Class1')->AddClass('Layer2_Class1')->AddClass('Layer3_Class1');
echo $SCAD->GetCode();

echo "</pre>";
