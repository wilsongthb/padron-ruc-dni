<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

const CORS_HEADERS = [
  'Access-Control-Allow-Origin' => '*',
];


$router->options('/{a}/{b}', function () {
  return response(null, 200, CORS_HEADERS);
});
$router->get('/', function () use ($router) {
  return $router->app->version();
});
$router->get('/dni/{dni}', function ($dni) {
  $persona = DB::table('personas')->where('dni', $dni)->first();
  if (isset($persona)) {
    return response()->json($persona, 200, CORS_HEADERS);
  } else {
    return response()->json(['message' => "No se encontro a la persona"], 404);
  }
});
