<?

class Layer3_Class1_Parent {

	public $OtherProperty = 'Property_Layer3_Class_Parent';

	private $OtherPrivateProperty = 'PrivateProperty_Layer3_Class_Parent';
	
	public static $OtherStaticProperty = 'StaticProperty_Layer3_Class_Parent';

	public function OtherFunc1() {
		return 'Func_Layer3_Class_Parent';
	}
	
	public function OtherFunc2($param = 'Func_Layer3_Class_Parent') {
		return $param;
	}

	public static function OtherStaticFunc1() {
		return 'Func_Layer3_Class_Parent';
	}
	
}