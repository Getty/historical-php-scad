<?
/**
 * SCAD - Super Cascasding Development
 *
 * PHP version 5
 *
 * Class Generation Class
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
if (!class_exists('SCAD_Analyze')) { require_once(SCAD_PATH.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'SCAD_Analyze.php'); }

class SCAD_Generator {
	
	protected static function prepareProperty($PropertyName,$PropertyScope,$PropertyValue = NULL,$PropertyStatic = false) {
		$Return = "\t";
		if ($PropertyScope == SCAD_Analyze::SCOPE_PRIVATE) {
			$Return .= 'private';
		} elseif ($PropertyScope == SCAD_Analyze::SCOPE_PROTECTED) {
			$Return .= 'protected';
		} elseif ($PropertyScope == SCAD_Analyze::SCOPE_PUBLIC) {
			$Return .= 'public';
		} else {
			throw new Exception('SCAD_Generator->prepareProperty($PropertyName,$PropertyScope,$PropertyValue,$PropertyStatic) unknown $PropertyScope');
		}
		if ($PropertyStatic) {
			$Return .= ' static';
		}
		$Return .= ' $'.$PropertyName.' = '.var_export($PropertyValue,true).";\n";
		return $Return;
	}

	protected static function prepareConstant($ConstantName,$ConstantValue = NULL) {
		return "\tconst ".$ConstantName.' = '.var_export($ConstantValue,true).";\n";
	}
	
	public static function GenerateClass($Definition) {
		$Return = "";
		if (isset($Definition['Abstract']) && $Definition['Abstract']) {
			$Return .= 'abstract ';
		}
		$Return .= 'class '.$Definition['Name'];
		if (isset($Definition['Extends'])) {
			$Return .= ' extends '.$Definition['Extends'];
		}
		if (isset($Definition['Interfaces'])) {
			$Return .= ' implements '.implode(',',$Definition['Interfaces']);
		}
		$Return .= " {\n";
		foreach($Definition['Constants'] as $ConstantName => $ConstantValue) {
			$Return .= self::prepareConstant($ConstantName,$ConstantValue);
		}
		foreach($Definition['Properties'] as $Property) {
			$Return .= self::prepareProperty($Property['Name'],$Property['Scope'],$Property['Value'],$Property['Static']);
		}
		foreach($Definition['Methods'] as $Method) {
			$Return .= $Method['Code'];
		}
		$Return .= "}\n\n";
		return $Return;
	}

}