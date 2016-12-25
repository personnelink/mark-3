<?php

    include_once("core/robot.class.php");
    System:init();

    $core = new System();

    // main helper bot
    $jarvice = new Robot();

    // create a receptionist
    $marcela = new Receptionist();

    // create a payroll supervisor
    $joy = new PayrollSupervisor();

    // for security, hide all scripts out of _public, then pass in script name as a qs value
    $jarvice->checkPermission(core->load(scriptname); //to retrieve from database. 

    //perform scheduled tasks

    // get all currently open orders and get all new applications since last check, and call system notifications service
    jarvice->sendNotices($customers->getOpenOrders(), $marcela->checkApplications("new"));

?>
