<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

(new \rollun\Callables\TaskExample\FileSummary())->runTaskProcess((int)$argv[1]);
