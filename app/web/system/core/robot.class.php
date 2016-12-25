<?php


/**
 * Base robot class
 *
 * Used later for building additional robots and assigning roles -- these to be 
 * identified from the reconstructed business plan, swapping out human staff with
 * "robot" staff where possible, and where not dreaming up procedures to segway
 * "people to people" interactions through the system, for example, interview
 * "script" can be handled by the Customer, with the checklist being reframed as as
 * "quality survey" as at this point if the customer is doing the interview, if they dont
 * as the right questions, that's on them. Just covers our butts in case of audits and
 * to fullfill the human requirements of the forms and process.
 *
 *
 * @category   Core System
 * @author     Gus <gus.haner@gmail.com>
 * @version    SVN: $Id$
 */

/*
* Place includes, constant defines and $_GLOBAL settings here.
* Make sure they have appropriate docblocks to avoid phpDocumentor
* construing they are documented by the page-level docblock.
*/

class System {

    public static $script_name = basename($_SERVER['PHP_SELF']);
    public static $http_host   = $_SERVER['HTTP_HOST'];
    public static $dev_mode    = true;
    public static $db_host     = 127.0.0.1;
    public static $db_user     = "none";
    public static $db_pass     = "changeme";

    public static function init() {
        
        // ... system initilizations
    }
}

System::init();

function HelloWorld() {

  print System::$script_name;

}

class Robot {

    public $name;

    function __construct() {
        // instantiation defaults
    }

    public function GetData() {
        /**
         wire in database connection stuff here
        **/
        return($this->name);

    }

    public function EnterName($TName) {
        
        $this->name = $TName;
        /**
        database code here
        **/
    }
}

class Receptionist extends Robot {
   function __construct() {
       parent::__construct();
       // extend and redefine receptionist defaults
   }
}

class PayrollSupervisor extends Robot {
    // inherits Robots's constructor
}

?>
