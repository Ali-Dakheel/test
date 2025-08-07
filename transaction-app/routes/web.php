<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/api-docs/specification', function () {
    $yamlPath = public_path('api-docs/accounting-api.yaml');
    
    if (!file_exists($yamlPath)) {
        abort(404, 'API specification not found');
    }
    
    return response(file_get_contents($yamlPath))
        ->header('Content-Type', 'application/yaml')
        ->header('Access-Control-Allow-Origin', '*');
});

Route::get('/api-docs/specification.json', function () {
    $yamlPath = public_path('api-docs/accounting-api.yaml');
    
    if (!file_exists($yamlPath)) {
        abort(404, 'API specification not found');
    }
    
    $yaml = file_get_contents($yamlPath);
    $array = \Symfony\Component\Yaml\Yaml::parse($yaml);
    
    return response()->json($array);
});

Route::get('/api/documentation', function () {
    return view('api-documentation');
})->name('api.documentation');

Route::get('/docs', function () {
    return redirect()->route('api.documentation');
});


require __DIR__.'/auth.php';
