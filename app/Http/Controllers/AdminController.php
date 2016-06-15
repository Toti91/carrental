<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
	// Admin frontpage
    public function getIndex(){
    	return view('admin/index');
    }

    //Admin cars page
    public function getCars(){
    	return view('admin/cars');
    }

    public function createCategory(){
    	$cat = new \App\Categories;
    	$cat->category = $_POST['category'];
    	$cat->name = $_POST['name'];
    	$cat->price_min = $_POST['price-min'];
    	$cat->price_max = $_POST['price-max'];
    	$cat->save();

    	return redirect('/admin/cars');
    }

    public function createCar(){
    	$car = new \App\Car;
    	$car->category_id = $_POST['category-id'];
    	$car->name = $_POST['name'];
    	$car->price = $_POST['price'];
    	$car->save();

    	return redirect('/admin/cars');
    }
}