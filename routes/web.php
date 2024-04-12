<?php

use App\Http\Controllers\DeployController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/test', [TestController::class, 'index']);
Route::get('/test/import', [TestController::class, 'import']);
Route::get('/test/importProject', [TestController::class, 'importProject']);
Route::get('/test/phpinfo', [TestController::class, 'phpInfo']);
Route::get('/test/send-email', [TestController::class, 'sendEmail']);
Route::get('/test/aws-upload', [TestController::class, 'awsUpload']);
Route::get('/test/aws-list', [TestController::class, 'awsList']);
Route::get('/test/sms-send', [TestController::class, 'smsSend']);
Route::get('/test/pay', [TestController::class, 'pay']);
Route::get('/deploy', [DeployController::class, 'index']);

Route::get('/echo-permission', function () {
    $routes = Route::getRoutes();
    foreach ($routes as $route) {
        $name = $route->getName();
        $nameUpper = str($name)->upper()->replace(['-', '.'], '_');
        if (str($name)->startsWith('admin.')) {
            echo str($name).'<br>';
            echo $nameUpper.'<br>';
            echo '<hr>';
        }
    }
});

Route::post('/broadcasting/auth/web', function (Illuminate\Http\Request $request) {
    return Broadcast::auth($request);
})->middleware(['auth:admin']);
