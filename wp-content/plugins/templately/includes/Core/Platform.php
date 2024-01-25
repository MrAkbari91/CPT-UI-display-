<?php
namespace Templately\Core;

use Templately\Utils\Base;

abstract class Platform extends Base {
    // protected $module;

//    public function __construct() {
//        // $this->module = Module::get_instance();
//    }

    // abstract public function get_saved_templates( $params = [] );
    // abstract public function delete( $params = [] );
    abstract public function create_page( $id, $title, $importer = null );
}