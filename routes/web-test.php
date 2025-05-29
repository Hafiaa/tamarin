<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-images', function () {
    $images = [
        'appetizer' => asset('storage/default-menu-images/appetizer.jpg'),
        'dessert' => asset('storage/default-menu-images/dessert.jpg'),
        'drink' => asset('storage/default-menu-images/drink.jpg'),
        'main-course' => asset('storage/default-menu-images/main-course.jpg'),
    ];

    return view('test-images', ['images' => $images]);
});
