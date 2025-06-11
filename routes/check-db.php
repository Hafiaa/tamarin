<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/check-db', function() {
    $columns = DB::select('SHOW COLUMNS FROM service_items');
    return response()->json($columns);
});
