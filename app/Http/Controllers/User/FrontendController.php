<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer_auth');
    }
    public function index()
    {
        $customer_id = Auth::guard('customer')->user()->id;
        // print_r($customer_id);
        // exit;
        $today_count=Order::whereDate('pooja_start','=', Carbon::today())->where('created_by',$customer_id)->count();
        $total_count=Order::where('created_by',$customer_id)->count();
        $bookings=Order::whereDate('orders.pooja_start','>=', Carbon::today())->where('orders.created_by',$customer_id)->join('members','members.id','=','orders.member_id')->join('poojas','poojas.id','=','orders.pooja')
        ->join('birth_stars','birth_stars.id','=','members.star')->select('members.full_name','poojas.pooja_name','orders.pooja_start','birth_stars.star_name')->get();
        // echo "<pre>";
        // print_r($bookings);
        // exit;
        return view('user.home',compact('today_count','total_count','bookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
