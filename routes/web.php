<?php

use App\Http\Controllers\TelegramWebHookController;
use Illuminate\Support\Facades\Route;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;
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

Route::group(['excluded_middleware' => ['web']], function () {
    Route::post('/webhook/telegram', [TelegramWebHookController::class, 'webhook'])->name('webhook.telegram');
});

Route::get('/set/webhook/telegram', [TelegramWebHookController::class, 'setWebhook']);

Route::get('/', function () {
    $parser = new Parser();
    $pdf = $parser->parseFile(storage_path('file.pdf'));
    $pages = $pdf->getPages();
    $texts = [];
    foreach($pages as $page) {
        $texts = array_merge($texts, collect($page->getTextArray())->map(function ($item) {
            return Str::squish($item);
        })->toArray());
    }
    $filteredArrays = array_filter($texts, function($v, $k) {
        return $v == 'Пополнение';
    }, ARRAY_FILTER_USE_BOTH);
    $keys = array_keys($filteredArrays);
    $inputs = [
        1 => [],
        2 => [],
        3 => [],
        4 => [],
        5 => [],
        6 => [],
        7 => [],
        8 => [],
        9 => [],
        10 => [],
        11 => [],
        12 => [],
    ];
    foreach($keys as $key) {
        $date = $texts[$key-2];
        $month = (int) Str::of($date)->explode('.')[1];
        $user = $texts[$key+1];
        $inputs[$month][$user] = 0;
    }
    foreach($inputs as $month => $users)
    {
        $inputs[$month] = count($users);
    }
    $risk = false;
    for($i=3; $i<=12; $i++) {
        if($inputs[$i-2] > 100 && $inputs[$i-1] > 100 && $inputs[$i] > 100)
            $risk = true;
    }
    return response()->json($inputs);
});

Route::get('/halyk', function () {
    $parser = new Parser();
    $pdf = $parser->parseFile(storage_path('halyk.pdf'));
    $pages = $pdf->getPages();
    $texts = [];
    foreach($pages as $page) {
        $texts = array_merge($texts, collect($page->getTextArray())->map(function ($item) {
            return Str::squish($item);
        })->toArray());
    }

    return response()->json($texts);
});
