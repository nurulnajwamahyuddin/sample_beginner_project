<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TemporaryCode;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed','regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function store(Request $request){

        $validator = $this->validator($request->all());

        $error = [];

        if ($validator->fails()) {
            if($validator->errors()->first() == 'The password format is invalid.'){
                $error['msg'] = 'The password must contain at least one letter and one number';
            }else {
                $error['msg'] = $validator->errors()->first();
            }

            return redirect()->back()->withInput()->withErrors($error);
        }

        try {
            $decrypted = Crypt::decryptString($request->code);
        } catch (DecryptException $e) {
            $decrypted = '';
        }

        $existcode = TemporaryCode::where('email',$request->email)->where('code', $decrypted)->first();

        if(!isset($existcode)){
            $error['msg'] = 'Fail To Register!';
            return redirect()->back()->withInput()->withErrors($error);
        }

        return $this->create($request->all());

    }

    public function register(Request $request){

        if(!isset($request->email)){

            return view('auth.register.email');
        }

        if(isset($request->email)){

            if(!isset($request->code)) {
                $validator = Validator::make($request->all(), [
                    'email' => ['string', 'email', 'max:255', 'unique:users'],
                ]);

                if ($validator->fails()) {

                    $error = [];
                    $error['msg'] = $validator->errors()->first();

                    return redirect()->back()->withInput()->withErrors($error);
                }

                $code = rand(100000, 999999);

                try {
                    DB::beginTransaction();

                    $oldtemp = TemporaryCode::where('email', $request->email)->first();

                    if (isset($oldtemp)) {
                        $code = $oldtemp->code;
                        $oldtemp->delete();
                    }

                    $tempcode = new TemporaryCode;
                    $tempcode->email = $request->email;
                    $tempcode->code = $code;
                    $tempcode->save();

                    $email_owner = $request->email;

                    if (!isset($oldtemp)) {
//			send code to email
                        Mail::send('emails.register_code', ['temp' => $tempcode, 'email' => $email_owner], function ($message) use ($email_owner) {
                            $message->to($email_owner);
                            $message->subject('Verification Code');
                        });
                    }

                    DB::commit();

                    return view('auth.register.verify')->with('email', $request->email);
                } catch (\Exception $e) {
                    DB::rollback();
                    $error = [];
                    $error['msg'] = 'Invalid Email!';
//                return redirect()->back()->withInput()->withErrors($e->getMessage());
                    return redirect()->back()->withInput()->withErrors($error);
                }
            }

        }

        try {
            $decrypted = Crypt::decryptString($request->code);
        } catch (DecryptException $e) {
            $decrypted = '';
        }

        $existcode = TemporaryCode::where('email',$request->email)->where('code', $decrypted)->first();

        if(!isset($existcode)){
            return view('auth.register.email');
        }

        return view('auth.register')->with('email', $request->email)->with('code', $request->code);

    }

    public function registerCode(Request $request){

        $validator = $request->validate([
            'code' => ['required'],
        ]);

        $existcode = TemporaryCode::where('email',$request->email)->where('code',$request->code)->first();

        $error = [];

        if(!isset($existcode)){

            $error['msg'] = 'Invalid Code!';
            return redirect()->back()->withInput()->withErrors($error);
        }

        if($existcode->updated_at->diffInSeconds(Carbon::now()) > 900){

            $error['msg'] = 'request time out!';
            return redirect()->back()->withInput()->withErrors($error);
        }

        return redirect('/register?email='.$existcode->email.'&code='.Crypt::encryptString($existcode->code));


    }
}
