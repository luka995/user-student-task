<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

include __DIR__ . '/../vendor/autoload.php';

try {
    $controller = \luka\controller\Controller::create();
    $main = $controller->run();
} catch (\Throwable $ex) {
    $main = $ex->getMessage();
}
?>

<?= $main ?>

