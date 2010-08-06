<?

class Layer3_Class1 extends Layer3_Class1_Parent {

	public $Property3 = 'Property2_Layer3';

	private $PrivateProperty4 = 'PrivateProperty1_Layer3';
	
	public static $StaticProperty3 = 'StaticProperty3_Layer3';

	const CONST4 = 'Layer3';
	
	public function Func1() {
		return 'Layer3';
	}
	
	public function Func3($param = 'param') {
		return $param;
	}

	public static function StaticFunc1() {
		return 'Layer3';
	}
	
}