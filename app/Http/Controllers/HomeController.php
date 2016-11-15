<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rental = Auth::user()->rental;
        $users = \App\User::get();
        $followers = Auth::User()->followers;
        return view('home')->with('rental', $rental)->with('users', $users)->with('followers', $followers);
    }

    public function newRental(){
        return view('create');
    }

    public function makeAdmin($id)
    {
        $user = \App\User::find($id);
        $user->access = 1;
        $user->save();

        return redirect('/admin');
    }

    public function postRentalInfo($id){
        $user = DB::table('users')
            ->where('users.id', '=', $id)
            ->join('car_rentals', 'car_rentals.user_id', '=', 'users.id')
            ->select('users.*', 'car_rentals.name as rental', 'car_rentals.stock as stock', 'car_rentals.icon as rental_image', 'car_rentals.created_at as created')
            ->first();
        return view('windows/userinfo')->with('user', $user);

    }

    public function getDealership(){
        //$cars = \App\Car::get();
        $cars = DB::table('car')
            ->join('categories', 'categories.id', '=', 'car.category_id')
            ->select('car.*', 'categories.category')
            ->get();
        $categories = \App\Category::get();
        $rental = Auth::user()->rental;
        return view('dealership')->with('cars', $cars)->with('rental', $rental)->with('categories', $categories);
    }

    public function postCarInfo($id, $rent_id = 0){
        $car = DB::table('car')
            ->join('categories', 'categories.id', '=', 'car.category_id')
            ->select('car.*', 'categories.category')
            ->where('car.id', '=', $id)->first();
        $rental = Auth::user()->rental;

        if($rent_id){
            $rent_car = \App\userCar::find($rent_id);
            $histories = $rent_car->histories()->limit(5)->orderBy('start', 'DESC')->get();
            $avarage = $rent_car->histories()->avg('total_rent');
            $avarage_time = $rent_car->histories()->avg('time');
            return view('windows/carsale')->with('car', $car)->with('rental', $rental)->with('rent_car', $rent_car)->with('histories', $histories)->with('avarageIncome', $avarage)->with('avarageTime', $avarage_time);
        }
        else {
            return view('windows/carsale')->with('car', $car)->with('rental', $rental);
        }
    }

    public function buyCar($id, $amount){
        $car = \App\Car::find($id);
        $rental = Auth::user()->rental;
        $total = $car->price * $amount;
        $total_value = $this->totalCompanyValue();
        if($rental->money >= $total){
            $i = 0;
            while($i < $amount){
                $stock_change = ((0.05 * $car->price) / $total_value) + 1;
                $newCar = new \App\userCar;
                $newCar->car_id = $id;
                $newCar->user_id = Auth::user()->id;
                $newCar->plate = $this->generatePlate();
                $newCar->km_count = 0;
                $newCar->price = (0.85*$car->price);
                $newCar->rented = 0;
                $newCar->save();

                $rental->stock = $stock_change * $rental->stock;
                $rental->save();

                $i++;
            }
            $rental->money = $rental->money - $total;
            $rental->save();
            session()->flash('flash_success', 'Congratulations on the expansion!');
            return redirect('/garage');
        }
        else {
            session()->flash('flash_error', 'You couldn\'t afford that!');
            return redirect('/garage');
        }
    }

    public function getGarage(){
        //$cars = \App\Car::get();
        $cars = DB::table('car_user')
            ->join('car', 'car.id', '=', 'car_user.car_id')
            ->join('categories', 'categories.id', '=', 'car.category_id')
            ->select('car.*', 'car_user.km_count', 'car_user.price as new_price', 'car_user.rented', 'car_user.start', 'car_user.end', 'categories.category', 'car_user.id as rent_id', 'car_user.maint_count', 'car_user.plate')
            ->where('car_user.user_id', '=', Auth::user()->id)
            ->orderBy('car_user.end')
            ->orderBy('car.name')
            ->paginate(15);
        $categories = \App\Category::get();
        $rental = Auth::user()->rental;
        return view('garage')->with('cars', $cars)->with('rental', $rental)->with('categories', $categories);
    }

    public function rentCar($rent_id){
        $return = array();

        $lease = \App\userCar::find($rent_id);
        $message = '';
        if($lease){
            if(time() > $lease->end){
                //check if user owns car
                if(Auth::user()->id == $lease->user_id){
                    $car = \App\Car::find($lease->car_id);
                    $category = \App\Category::find($car->category_id);
                    $rental = Auth::user()->rental;
                    //generate rent amount/day
                    $rent = $this->weightedrand($category->price_min, $category->price_max, $car->gamma);
                    //generate time on lease
                    $new_gamma = (((100 / $car->popularity) - 1)*3) + $car->gamma;
                    $time = $this->weightedrand(6, 24, $new_gamma);
                    $total_hours = $time + $lease->km_count;
                    //Calculate total rent, days * rent
                    $total_rent = round(($time / 12) * $rent);
                    //Calculate drop in car value
                    $new_value = $car->price - ($car->price * ((0.0001 * $total_hours) + 0.15));
                    //Check if car sustained a breakdown
                    $probability = null;
                    ($lease->maint_count >= 100) ? $probability = 20 : $probability = 5;
                    $malfunction = false;
                    if(rand(1, 100) <= $probability){
                        // Adds random malfunction to car
                        $this->carMalfunctioned($lease->id);
                        $message = ' - This car broke down';
                        $malfunction = true;
                    }
                    $now = time();
                    $time = $time + 0;
                    $later = $now + ($time*60)*60;
                    //update user_car
                    $lease->km_count = $total_hours;
                    $lease->price = $new_value;
                    $lease->rented = 1;
                    $lease->start = $now;
                    $lease->end = $later;
                    $lease->maint_count = $lease->maint_count + $time;
                    $lease->save();

                    //Stock change
                    $total_value = $this->totalCompanyValue();
                    $stock_change = ($total_rent / $total_value) + 1;

                    $rental->money = $rental->money + $total_rent;
                    $rental->stock = $stock_change * $rental->stock;
                    $rental->save();

                    //Insert rent into history
                    $history = new \App\History;
                    $history->rent_id = $lease->id;
                    $history->total_rent = $total_rent;
                    $history->time = $time;
                    $history->start = $lease->start;
                    $history->end = $lease->end;
                    $history->malfunction = $malfunction;
                    $history->trigger_maintenance = ($lease->maint_count >= 100) ? true : false;
                    $history->save();


                    session()->flash('flash_success', 'Car was rented for '.$time.' hours and you earned $'.$total_rent.$message);

                    $return['error'] = false;
                    $return['new_gamma'] = $new_gamma;
                    $return['total_hours'] = $total_hours;
                    $return['total_rent'] = $total_rent;
                    $return['time'] = $time;
                    $return['new_value'] =  '<small>$</small>'.number_format($new_value, 0, ',', '.');
                    $return['malfunction'] = $malfunction;
                    $return['end_time'] = date("Y-m-d H:i:s", $lease->end);
                    $return['probability'] = $probability;
                    $return['maintenance'] = ($lease->maint_count >= 100) ? true : false;
                    $return['maint_count'] = $lease->maint_count;

                    return $return;
                }
            }
            $return['error'] = true;
            $return['error_message'] = 'This car is already leased!';
            
            return $return;
        }
        $return['error'] = true;
        $return['error_message'] = 'Something went wrong, try again!';
        
        return $return;
    }

    public function weightedrand($min, $max, $gamma) {
        $offset= $max-$min+1;
        return floor($min+pow(lcg_value(), $gamma)*$offset);
    }

    public function sellCar($id){
        $car = \App\userCar::find($id);
        if($car){
            if(Auth::user()->id == $car->user_id){
                if($car->end < time() || !$car->rented){
                    $rental = Auth::user()->rental;
                    $price = $car->price;
                    $rental->money = $rental->money + $price;
                    $rental->save();

                    $car->delete();

                    session()->flash('flash_success', 'Car was sold for $'.$price);
                    return redirect('/garage');
                }
                else {
                    session()->flash('flash_error', 'Car is leased!');
                    return redirect('/garage');
                }
            }   
        }
        session()->flash('flash_error', 'Something went wrong, try again!');
        return redirect('/garage');
    }

    public function maintainCar($id){
        $return = array();

        $lease = \App\userCar::find($id);
        $car = \App\Car::find($lease->car_id);
        $rental = Auth::user()->rental;
        if($rental->money >= $car->maint_cost){
            if($lease->maint_count >= 100){
                $lease->maint_count = 0;
                $lease->save();

                $rental->money = $rental->money - $car->maint_cost;
                $rental->save();

                $return['error'] = false;
                $return['cost'] = $car->maint_cost;
                $return['maintenance'] = true;

                return $return;
            }
            else {
                $return['error'] = true;
                $return['error_message'] = 'This car didnt need maintenance!';

                return $return;
            }
        }
        else {
            $return['error'] = true;
            $return['error_message'] = 'You could\'nt afford it!';
            
            return $return;
        }
    }

    public function fixCar($id){
        $return = array();

        $lease = \App\userCar::find($id);
        $malfunction = $lease->malfunctions->first();
        if($malfunction){
            $rental = Auth::user()->rental;
            if($rental->money >= $malfunction->cost){
                $rental->money = $rental->money - $malfunction->cost;
                $rental->save();

                $lease->malfunctions()->detach($malfunction->id);

                $return['error'] = false;
                $return['cost'] = $malfunction->cost;

                $return['maintenance'] = false;
                if($lease->maint_count >= 100){
                    $return['maintenance'] = true;
                }

                return $return;
            }
            else {
                $return['error'] = true;
                $return['error_message'] = 'You could\'nt afford it!';
                
                return $return;
            }
        }
        $return['error'] = true;
        $return['error_message'] = 'Something went wrong, try again!';
            
        return $return;
    }

    function generateFirstPart($length = 2) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function generateSeconPart($length = 4) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generatePlate() {
        $plate = $this->generateFirstPart() . '-' . $this->generateSeconPart();
        return $plate;
    }

    function carMalfunctioned($id){
        $malfunction = \App\Malfunction::inRandomOrder()->first();
        $lease = \App\userCar::find($id);

        $lease->malfunctions()->attach($malfunction->id);

        return true;
    }

    public function totalCompanyValue(){
        $rental = Auth::user()->rental;
        $cars = Auth::user()->cars()->sum('price');

        $total_value = $cars + $rental->money;

        return $total_value;
    }

    public function follow($id){
        $return = array();
        if($id != \Auth::user()->id){
            $user = \App\User::find($id);
            if($user){
                if(!\App\Follow::ifFollower(Auth::user()->id, $id)){
                    \Auth::user()->followers()->attach($id);

                    $return['error'] = false;
                    $return['success_message'] = 'You are now following '. $user->name;
                    $return['button'] = '<a href="/follow/'.$id.'" class="trigger-follow unfollow"><i class="fa fa-user-times"></i> Unfollow</a>';
                    return $return;
                } else {
                    \Auth::user()->followers()->detach($id);
                    $return['error'] = false;
                    $return['success_message'] = 'You are no longer following '. $user->name;
                    $return['button'] = '<a href="/follow/'.$id.'" class="trigger-follow"><i class="fa fa-user-plus"></i> Follow</a>';
                    return $return;
                }
            }
            $return['error'] = true;
            $return['error_message'] = 'Can\'t find user.';
            return $return;
        }
        $return['error'] = true;
        $return['error_message'] = 'Can\'t follow your self.';

        return $return;
    }
}
