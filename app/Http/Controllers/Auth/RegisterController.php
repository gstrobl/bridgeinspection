<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserVerification;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
            // 'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    public function create(Request $request)
    {
      $validator = $this->validator($request->all());

      if ($validator->fails()) {
        return response()->json($validator->messages(), 200);
      }

      $user = User::create([
        'name' => 'test',
        'email' => $request['email'],
        'password' => bcrypt($request['password']),
        'isActivated' => '0'
      ]);

      $userVerification = UserVerification::create([
        'user_id' => $user->id,
        'activationCode' => hash_hmac('sha256', str_random(20), config('app.key'))
      ]);

      $subject = "Please verify your email address.";
      Mail::send('emails.activation', ['name' => $user->email, 'activationCode' => $userVerification->activationCode],
        function ($mail){
          $mail->from(getenv('FROM_EMAIL_ADDRESS'), "From User/Company Name Goes Here");
          $mail->to($user->email, $user->name);
          $mail->subject($subject);
        });

      return response()->json(['success'=> true, 'message'=> 'Thanks for signing up! Please check your email to complete your registration.']);
    }

    public function verifyUser($activationCode)
    {

      $userVerification = UserVerification::where('activationCode', '=', $activationCode)->get();

      if(!is_null($userVerification)){
          $user = User::find($userVerification->user_id);
          if($user->isActivated == 1){
              return response()->json([
                  'success'=> true,
                  'message'=> 'Account already verified..'
              ]);
          }
          $user->isActivated = 1;
          $user->save();

          $userVerification->delete();

          return response()->json([
              'success'=> true,
              'message'=> 'You have successfully verified your email address.'
          ]);
      }
      return response()->json(['success'=> false, 'error'=> "Verification code is invalid."]);
    }
}
