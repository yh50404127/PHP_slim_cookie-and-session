<?php
declare(strict_types=1);

//session 範例 -------------------------------------------------------------------------
session_cache_limiter(null);
session_start();
//session 範例 -------------------------------------------------------------------------

use Slim\Psr7\Response;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use App\Application\Middleware\EBM;
use App\Application\Middleware\EAM;



require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->add(EAM::class);
$app->add(EBM::class);

$app->get('/', function (RequestInterface $request, ResponseInterface $response) {
    $response->getBody()->write(" Hello, world! ");
    return $response;
});

$app->get('/hello/{name}', function (RequestInterface $request, ResponseInterface $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write(" Hello, $name ~~~ ");
    return $response;
});

//cookie 範例
$app->get('/cookie', function (RequestInterface $request, ResponseInterface $response) {

    $cookie = '';
    if(!isset($_COOKIE['aaa'])){
        setcookie('aaa', '0', time() + 600);
    }
    if((int)$_COOKIE['aaa'] > 9){
        setcookie('aaa', '0', time() + 600);
        $cookie = $_COOKIE['aaa'];
    }else{
        $i = (int)$_COOKIE['aaa'] + 1;
        setcookie('aaa', (string)$i, time() + 600);
        $cookie = $_COOKIE['aaa'];
    }

    $response->getBody()->write(" Hello, $cookie ~~~ ");
    return $response;
});

//session 範例 -------------------------------------------------------------------------
$app->get('/session', function (RequestInterface $request, ResponseInterface $response) {

    $session = 'null';
    if(isset($_SESSION['sss'])){
        $session = $_SESSION['sss'];    
    }
    $response->getBody()->write(" Hello, $session ~~~ ");
    return $response;
});

$app->get('/addSession', function (RequestInterface $request, ResponseInterface $response) {

    $_SESSION['sss'] = 'i am ok';
    $response->getBody()->write(" is add ");
    return $response;
});

$app->get('/delSession', function (RequestInterface $request, ResponseInterface $response) {

    session_destroy();
    $response->getBody()->write(" is del ");
    return $response;
});
// session 範例 -------------------------------------------------------------------------


$app->run();
