<?php

/* This deals with the management of requests. Yo! */

require_once("core/config.php"); 
require_once("core/keys.php");
require_once("classes/Management.php");


// Gotta get the request class
$management = new Management();

include("views/request_progress.php");

// Show the request view
