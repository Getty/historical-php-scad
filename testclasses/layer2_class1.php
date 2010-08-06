<?

class Layer2_Class1 extends Layer2_Class1_Parent {

	public $Property2 = 'Property2_Layer2';

	private $PrivateProperty1 = 'PrivateProperty1_Layer2';
	
	public static $StaticProperty3 = 'StaticProperty3_Layer2';

	const CONST2 = 'Layer2';
	
	public function Func1() {
		return 'Layer2';
	}
	
	public function Func3($param = 'param') {
		return $param;
	}

	public static function StaticFunc1() {
		return 'Layer2';
	}
	
}