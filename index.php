<?php

namespace WBB;
require_once "vendor/autoload.php";

include "Controllers/ViewsController.php";
$controller = new ViewsController();

$controller->RenderPartialView("Header");