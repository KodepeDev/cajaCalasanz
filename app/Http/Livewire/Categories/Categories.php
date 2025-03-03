<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class Categories extends Component
{
    public $categories;
    public function render()
    {

        $this->categories = Category::all();
        return view('livewire.categories.categories');
    }

    public function create(){
        return view('livewire.categories.create');
    }
    public function save(){
        return view('livewire.categories.categories');
    }
}
