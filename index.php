<?php

require __DIR__ . '/src/bootstrap.php';

use App\Commands\GetCommissionBulk\GetCommissionBulkCommand;

$command = new GetCommissionBulkCommand();
$command->execute();