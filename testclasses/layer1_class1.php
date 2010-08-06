<?

require_once(realpath(dirname(__FILE__)).'/something.php');

class Layer1_Class1 extends Something {

	public $Property1;
	public $Property2 = 'Property2';
	private $PrivateProperty1;
	private $PrivateProperty2 = 'PrivateProperty2';
	protected $ProtectedProperty1;
	protected $ProtectedProperty2 = 'ProtectedProperty2';

	public static $StaticProperty1;
	public static $StaticProperty2 = 'StaticProperty2';
	public static $StaticProperty3 = 'StaticProperty3';

	const CONST1 = 'Layer1';
	const CONST2 = 'Layer1';
	
	public function Func1() {
		return 'Layer1';
	}
	
	public function Func2($param) {
		return $param;
	}

	public function Func3($param = 'param') {
		return $param;
	}

	public static function StaticFunc1() {
		return 'Layer1';
	}
	
	public static function StaticFunc2($param) {
		return $param;
	}

	public static function StaticFunc3($param = 'param') {
		return $param;
	}

}