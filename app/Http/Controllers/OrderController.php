<?php

namespace App\Http\Controllers;

use App\DataTables\OrdersDataTable;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrdersDataTable $dataTable)
    {

        return $dataTable->render('orders.index');

    // return $dataTable->before(function () {
    //     if (Auth::user()->hasRole('admin')) {
    //         dd('hi');
    //         DataTables::eloquent($order)->removeColumn('id');
    //     }
    // })->render('orders.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $doctors = User::Role('doctor')->get();
        $medicine = Medicine::all();
        $pharmacy = Pharmacy::all();
        return view('orders.create' ,['users'=>$users , 'medicine'=>$medicine , 'pharmacy'=>$pharmacy , 'doctors'=>$doctors]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->all();

        $UserId = User::all()->where('name' , $data['userName'] )->first()->id;
        $DocId = User::all()->where('name' , $data['DocName'] )->first()->id;
        $PharmacyId = Pharmacy::all()->where('name' , $data['PharmacyName'] )->first()->id;
        
        $med = $data['med'];
        $qty = $data['qty'];
        
        $order = Order::Create([
            'status'=> 3,
            'pharmacy_id'=> $PharmacyId,
            'user_id'=> $UserId,
            'doctor_id'=> $DocId,
            'is_insured'=> $data['insured'],

        ]);

        Order::createOrderMedicine($order , $med , $qty);

        // send email to user to notify him by price and change status to waiting (3)

        return to_route('orders.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
       
        return view('orders.show' , ['order' =>$order]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {

        $users = User::all();
        $doctors = User::Role('doctor')->get();
        $pharmacy = Pharmacy::all();
        return view('orders.edit' , ['order' =>$order ,'users'=>$users , 'pharmacy'=>$pharmacy , 'doctors'=>$doctors]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrderRequest $request, Order $order)
    {

        $data = $request->all();

        if(isset($data['status'])){

            $order->status = $data['status'];

        }

        $order->update([

            'is_insured'=>$data['is_insured'],
            'pharmacy_id'=>$data['pharmacy_id'],
            'user_id'=>$data['user_id'],

        ]);

        return to_route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        
        $order->delete();
        return to_route('orders.index');
    }
}
