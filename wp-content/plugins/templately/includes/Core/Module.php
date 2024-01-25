<?php
namespace Templately\Core;

use Templately\Utils\Base;
use function is_subclass_of;

class Module extends Base {
    private $modules = [];

    public function active( $module, $type = 'Platform' ){
        $_type = \strtolower( $type );
        if( array_key_exists( $_type, $this->modules ) &&
            array_key_exists( $module, $this->modules[ $_type ] ) &&
            is_subclass_of( $this->modules[ $_type ][ $module ]->object, __NAMESPACE__ . '\\' . $type ) ) {
            return $this->modules[ $_type ][ $module ]->object;
        }

        return null;
    }

    public function add( $module, $type = 'Platform' ){
        $_type = \strtolower( $type );
        if( ! array_key_exists( $_type, $this->modules) ) {
            $this->modules[ $_type ] = [];
        }

		if( isset( $module->id ) ) {
			$this->modules[ $_type ][ $module->id ] = $module;
		} else {
			$this->modules[ $_type ][] = $module;
		}
    }

	public function get( $type = 'Platform' ){
		$_type = \strtolower( $type );
		if( array_key_exists( $_type, $this->modules ) ) {
			return $this->modules[ $_type ];
		}

		return [];
	}
}