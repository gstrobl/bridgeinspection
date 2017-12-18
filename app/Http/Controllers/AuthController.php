<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\UserVerification;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use App\Mail\ActivationMail;

class AuthController extends Controller
{

    protected function validator(array $data)
    {
      return Validator::make($data, [
          // 'name' => 'required|max:255',
          'email' => 'required|email|max:255|unique:users',
          'password' => 'required|min:6|confirmed',
      ]);
    }

    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
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

      $data = (object) ['name' => $user->name, 'link' => route('user.create', $userVerification->activationCode)];
      Mail::to($user->email)->send(new ActivationMail($data));

      return response()->json(['success'=> true, 'message'=> 'Thanks for signing up! Please check your email to complete your registration.']);
    }


    /**
     * API Verify User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $input = $request->only('email', 'password');

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            $error = $validator->messages()->toJson();
            return response()->json(['success'=> false, 'error'=> $error]);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
            // 'isActivated' => 1
        ];

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['success' => false, 'error' => 'Invalid Credentials. Please make sure you entered the right information and you have verified your email address.'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(['success' => true, 'data'=> [ 'token' => $token ]]);
    }


    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);

        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['success' => true]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }


    /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json(['success' => false, 'error' => ['email'=> $error_message]], 401);
        }

        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Your Password Reset Link');
            });

        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['success' => false, 'error' => $error_message], 401);
        }

        return response()->json([
            'success' => true, 'data'=> ['msg'=> 'A reset email has been sent! Please check your email.']
        ]);
    }
}
