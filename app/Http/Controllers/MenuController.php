<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display the cafe menu.
     *
     * @return \Illuminate\View\View
     */
    public function cafeMenu(Request $request)
    {
        $categoryId = $request->query('category');
        
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        $query = MenuItem::where('is_available', true)
            ->orderBy('sort_order');
            
        if ($categoryId) {
            $query->where('menu_category_id', $categoryId);
        }
        
        $menuItems = $query->with('menuCategory')->get();
        
        return view('menu.cafe', compact('categories', 'menuItems', 'categoryId'));
    }
    
    /**
     * Display the event menu options.
     *
     * @return \Illuminate\View\View
     */
    public function eventMenu(Request $request)
    {
        // Debug: Log request
        \Log::info('Event Menu Request:', $request->all());
        
        $categoryId = $request->query('category');
        
        // Debug: Log categories query
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        \Log::info('Categories:', $categories->toArray());
        
        // Debug: Log menu items query
        $query = MenuItem::where('is_available', true)
            ->whereHas('menuCategory', function($q) {
                $q->where('name', 'Event Specials');
            })
            ->orderBy('sort_order');
            
        if ($categoryId) {
            $query->where('menu_category_id', $categoryId);
        }
        
        $menuItems = $query->with('menuCategory')->get();
        
        // Debug: Log menu items
        \Log::info('Menu Items:', $menuItems->toArray());
        
        return view('menu.event', [
            'categories' => $categories,
            'menuItems' => $menuItems,
            'categoryId' => $categoryId,
            'debug' => [
                'categories_count' => $categories->count(),
                'menu_items_count' => $menuItems->count(),
                'has_event_specials' => $categories->contains('name', 'Event Specials')
            ]
        ]);
    }
}
