<?php

include_once "../src/App.php";
include_once "../src/Layout.php";
include_once "../src/Database.php";
include_once "../lib/fpdf.php";

use App\App;
$app = new App();
$app->run();