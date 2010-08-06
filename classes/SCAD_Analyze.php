<?
/**
 * SCAD - Super Cascasding Development
 *
 * PHP version 5
 *
 * Class Analyze
 * 
 * LICENSE:
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * 
 * @author     Torsten Raudssus <torsten@raudssus.de>
 * @copyright  2007 Torsten Raudssus
 * @license    GPL-2 
 * 
 */

if (!defined('SCAD_PATH')) { define('SCAD_PATH',realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..')); }

class SCAD_Analyze {

	const MAGICINSTANCE_PREFIX_CLASS = '___SCAD_magicinstance_';

	const SCOPE_PRIVATE = 1;
	const SCOPE_PROTECTED = 2;
	const SCOPE_PUBLIC = 3;

	protected static $AnalyzeClassCache = Array();
	
	private function __construct() {}

	public static function AnalyzeClass($ClassName) {
		if (!isset(self::$AnalyzeClassCache[$ClassName])) {
			$RefClass = new ReflectionClass($ClassName);
			if ($RefClass->isInternal()) {
				throw new Exception('SCAD_Analyze: cant analyze internal classes');
			}
			$RefClassMethods = $RefClass->getMethods();
			$RefClassInterfaces = $RefClass->getInterfaces();
			$Constants = $RefClass->getConstants();
			$RefClassProperties = $RefClass->getProperties();
			if ($RefClassParent = $RefClass->getParentClass()) {
				$ParentMethods = $RefClassParent->getMethods();
				$ParentMethodsList = Array();
				foreach($ParentMethods as $ParentMethod) {
					$ParentMethodsList[] = $ParentMethod->getName();
				}
				foreach($RefClassMethods as $Key => $RefClassMethod) {
					if (in_array($RefClassMethod->getName(),$ParentMethodsList)) {
						unset($RefClassMethods[$Key]);
					}
				}
				$ParentConstants = $RefClassParent->getConstants();
				foreach($Constants as $ConstantName => $ConstantValue) {
					if (key_exists($ConstantName,$ParentConstants)) {
						unset($Constants[$ConstantName]);
					}
				}
				$ParentProperties = $RefClassParent->getProperties();
				$ParentPropertiesList = Array();
				foreach($ParentProperties as $ParentProperty) {
					$ParentPropertiesList[] = $ParentProperty->getName();
				}
				foreach($RefClassProperties as $Key => $RefClassProperty) {
					if (in_array($RefClassProperty->getName(),$ParentPropertiesList)) {
						unset($RefClassProperties[$Key]);
					}
				}
				$ParentInterfaces = $RefClassParent->getInterfaces();
				$ParentInterfacesList = Array();
				foreach($ParentInterfaces as $ParentInterface) {
					$ParentInterfacesList[] = $ParentInterface->getName();
				}
				foreach($RefClassInterfaces as $Key => $RefClassInterface) {
					if (in_array($RefClassInterface->getName(),$ParentInterfacesList)) {
						unset($RefClassInterface[$Key]);
					}
				}
			}
			$ClassFileName = $RefClass->getFileName();
			$ClassFileArray = file($ClassFileName);
			$Methods = Array();
			foreach($RefClassMethods as $RefMethod) {
				$NewMethod = Array();
				$Code = "";
				foreach(range($RefMethod->getStartLine(),$RefMethod->getEndLine()) as $Line) {
					$Code .= $ClassFileArray[$Line-1];
				}
				$NewMethod['Code'] = $Code;
				$NewMethod['Name'] = $RefMethod->getName();
				$NewMethod['Constructor'] = $RefMethod->isConstructor();
				$NewMethod['Destructor'] = $RefMethod->isDestructor();
				$NewMethod['Final'] = $RefMethod->isFinal();
				$NewMethod['Start'] = $RefMethod->getStartLine();
				$NewMethod['End'] = $RefMethod->getEndLine();
				if ($RefMethod->isPrivate()) {
					$NewMethod['Scope'] = self::SCOPE_PRIVATE;
				} elseif ($RefMethod->isProtected()) {
					$NewMethod['Scope'] = self::SCOPE_PROTECTED;
				} else {
					$NewMethod['Scope'] = self::SCOPE_PUBLIC;
				}
				$Methods[$RefMethod->getName()] = $NewMethod;
			}
			$Properties = Array();
			if (!empty($RefClassProperties)) {
				$MagicInstance = self::generateMagicInstance($ClassName,$RefClass->getStartLine()-1,$RefClass->getEndLine()-1,$Methods,$RefClassProperties,$ClassFileArray);
				foreach($RefClassProperties as $RefProperty) {
					$NewProperty = Array();
					if ($RefProperty->isPrivate()) {
						$NewProperty['Scope'] = self::SCOPE_PRIVATE;
					} elseif ($RefProperty->isProtected()) {
						$NewProperty['Scope'] = self::SCOPE_PROTECTED;
					} else {
						$NewProperty['Scope'] = self::SCOPE_PUBLIC;
					}
					// This value is totally useless for our situation
					// $NewProperty['Default'] = $RefProperty->isDefault();
					$NewProperty['Static'] = $RefProperty->isStatic();
					$NewProperty['Value'] = $MagicInstance->get($RefProperty->getName());
					$NewProperty['Name'] = $RefProperty->getName();
					$Properties[$RefProperty->getName()] = $NewProperty;
				}
			}
			if (!empty($RefClassInterfaces)) {
				$Interfaces = Array();
				foreach($RefClassInterfaces as $RefClassInterface) {
					$Interfaces[] = $RefClassInterface->getName();
				}
				$Result['Interfaces'] = $Interfaces;
			}
			$Result = Array();
			$Result['Methods'] = $Methods;
			$Result['Properties'] = $Properties;
			$Result['Constants'] = $Constants;
			$Result['FileName'] = $RefClass->getFileName();
			$Result['Start'] = $RefClass->getStartLine();
			$Result['End'] = $RefClass->getEndLine();
			if ($RefClassParent) {
				$Result['Extends'] = $RefClassParent->getName();
			}
			$Result['Abstract'] = $RefClass->isAbstract();
			$Result['Final'] = $RefClass->isFinal();
			$Result['Interface'] = $RefClass->isInterface();
			$Result['Instantiable'] = $RefClass->isInstantiable();
			$Result['Name'] = $RefClass->getName();
			self::$AnalyzeClassCache[$ClassName] = $Result;
		}
		return self::$AnalyzeClassCache[$ClassName];
	}
	
	protected function generateMagicInstance($ClassName,$Start,$End,$Methods,$RefClassProperties,$ClassFileArray) {
		$MagicClassName = self::MAGICINSTANCE_PREFIX_CLASS.$ClassName;
		if (!class_exists($MagicClassName)) {
			$HideLines = Array();
			foreach($Methods as $Method) {
				foreach(range($Method['Start'],$Method['End']) as $Line) {
					$HideLines[] = $Line-1;
				}
			}
			$ClassDef = 'class '.$MagicClassName." {\n";
			for($i = $Start+1; $i < $End; $i++) {
				if (!in_array($i,$HideLines)) {
					$ClassDef .= $ClassFileArray[$i];
				}
			}
			$ClassDef .= 'public function get($var) {'."\n";
			$ClassDef .= '$func = "get".$var;'."\n";		
			$ClassDef .= 'return $this->$func(); }'."\n";
			foreach($RefClassProperties as $RefProperty) {
				$ClassDef .= 'public function get'.$RefProperty->getName()."() {\n";
				if ($RefProperty->isStatic()) {
					$ClassDef .= 'return self::$'.$RefProperty->getName()."; }\n";					
				} else {
					$ClassDef .= 'return $this->'.$RefProperty->getName()."; }\n";
				}
			}
			$ClassDef .= "public function __construct() {} \n";
			$ClassDef .= "public function __destruct() {} \n";
			$ClassDef .= "}\n";
			eval($ClassDef);
		}
		return new $MagicClassName();
	}

}