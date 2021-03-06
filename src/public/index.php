<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../config/config.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $pdo;
};

require '../middleware/authenticate.php';

$app->get('/', function (Request $request, Response $response, array $args) {
    echo 'imgboard server =)';
});

require '../routes/users.routes.php';
require '../routes/threads.routes.php';

$app->run();