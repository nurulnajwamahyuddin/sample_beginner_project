<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function index()
    {
        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $state = State::get();

        return view('address.create')->with('state', $state);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'address' => ['required'],
            'postal_code' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
        ]);


        if($validator->errors()->first()){
            return redirect()->back()->withInput()->withErrors($validator->errors()->first());
        }


        try{
            DB::beginTransaction();

            $address = new UserAddress;
            $address->user_id = Auth::user()->id;
            $address->address = $request->address;
            $address->postal_code = $request->postal_code;
            $address->state_id = $request->state;
            $address->city_id = $request->city;
            $address->save();

            DB::commit();

            return redirect('/address');

        }catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withInput()->withErrors('Failed to add address');
        }

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
        $state = State::get();

        $address = UserAddress::find($id);

        return view('address.create')
            ->with('state', $state)
            ->with('address', $address);
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
        $validator =  Validator::make($request->all(), [
            'address' => ['required'],
            'postal_code' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
        ]);


        if($validator->errors()->first()){
            return redirect()->back()->withInput()->withErrors($validator->errors()->first());
        }

        $address = UserAddress::find($id);

        try{
            DB::beginTransaction();

            $address->address = $request->address;
            $address->postal_code = $request->postal_code;
            $address->state_id = $request->state;
            $address->city_id = $request->city;
            $address->save();

            DB::commit();

            return redirect('/address');

        }catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withInput()->withErrors('Failed to edit address');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = UserAddress::find($id);

        if(isset($address)){
            $address->delete();
        }

        return redirect()->back();
    }

    public function getCity(Request $request){

        $city = City::where('state_id',$request->state_id)->get();


        return response()->json(array('cities'=>$city));

    }
}
