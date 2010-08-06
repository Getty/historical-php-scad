<?

class Layer2_Class1_Parent extends Layer2_Class1_Parent_Parent {

	public $SomeProperty = 'Property_Layer2_Class_Parent';

	private $SomePrivateProperty = 'PrivateProperty_Layer2_Class_Parent';
	
	public static $SomeStaticProperty = 'StaticProperty_Layer2_Class_Parent';

	public function SomeFunc1() {
		return 'Func_Layer2_Class_Parent';
	}
	
	public function SomeFunc2($param = 'Func_Layer2_Class_Parent') {
		return $param;
	}

	public static function SomeStaticFunc1() {
		return 'Func_Layer2_Class_Parent';
	}
	
}