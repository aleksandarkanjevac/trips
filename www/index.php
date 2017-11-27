<?php
    include '../core/init.php';

$req = array_key_exists('r', $_GET) ? $_GET['r'] : 'index';

$controller = new \core\Controller();
if (method_exists($controller, $req)) {
    $controller->{$req}();
} else {
    $controller->error(404,'Page not found');
}
