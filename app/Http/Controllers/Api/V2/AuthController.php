<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api\V2;

// use App\Http\Controllers\OTPVerificationController;
use App\Models\BusinessSetting;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\AppEmailVerificationNotification;
use Hash;
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use Socialite;
use App\Models\Cart;
use App\Rules\Recaptcha;
use App\Services\SocialRevoke;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;



class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $messages = array(
            'name.required' => translate('Name is required'),
            'email_or_phone.required' => $request->register_by == 'email' ? translate('Email is required') : translate('Phone is required'),
            'email_or_phone.email' => translate('Email must be a valid email address'),
            'email_or_phone.numeric' => translate('Phone must be a number.'),
            'email_or_phone.unique' => $request->register_by == 'email' ? translate('The email has already been taken') : translate('The phone has already been taken'),
            'password.required' => translate('Password is required'),
            'password.confirmed' => translate('Password confirmation does not match'),
            'password.min' => translate('Minimum 6 digits required for password'),
            'phone.required'=>translate('Phone Number is required'),
            'phone.min'=>translate('Phone Number is minimmum 10 values'),
            // 'phone.max'=>translate('Phone Number is maximum 10 values'),
            // 'phone.unique'=>translate('Phone Number is already exist'),
            'phone.numeric'=>translate('Phone Number is must be nember'),
        );


        

        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|min:6|max:999999999999999|numeric',
            'email_or_phone' => [
                'required',
                Rule::when($request->register_by === 'email', ['email', 'unique:users,email']),
                Rule::when($request->register_by === 'phone', ['numeric', 'unique:users,phone']),
            ],
            'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha') == 1, ['required', new Recaptcha()], ['sometimes'])
            ]
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ],404);
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->register_by == 'email' ? $request->email_or_phone : '',
            'phone' =>  $request->phone ,
            'password' => bcrypt($request->password),
            'verification_code' => rand(100000, 999999)
        ]);

        $user->email_verified_at = null;
        if ($user->email != null) {
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
            }
        }

        
        if ($user->email_verified_at == null) {
            
            if ($request->register_by == 'email') {
              
                try {
                    $user->notify(new AppEmailVerificationNotification());
                } catch (\Exception $e) {
                    return $request->email_or_phone;
                }
            } 
            else {
                // $otpController = new OTPVerificationController();
                // $otpController->send_code($user);
                $user->notify(new AppEmailVerificationNotification());
            }
          
        }

        
        $user->save();



        //create token
        $user->createToken('tokens')->plainTextToken;
        
        return response()->json([
            'result' => true,
            'message' => get_setting('email_verification') == 1 ?
                translate('Registration Successful! Please verify and log in to your account.') :
                translate('Registration Successful! Please log in to your account.'),
            'user_id' => $user->id
        ]);
    }

    public function resendCode(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->verification_code = rand(100000, 999999);

        if ($request->verify_by == 'email') {
            $user->notify(new AppEmailVerificationNotification());
        } 
        // else {
        //     $otpController = new OTPVerificationController();
        //     $otpController->send_code($user);
        // }

        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate('Verification code is sent again'),
        ], 200);
    }

    public function confirmCode(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user->verification_code == $request->verification_code) {
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_code = null;
            $user->save();
            return response()->json([
                'result' => true,
                'message' => translate('Your account is now verified.Please login'),
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => translate('Code does not match, you can request for resending the code'),
            ], 400);
        }
    }

    public function login(Request $request)
    {
        /*$request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);*/

        $delivery_boy_condition = $request->has('user_type') && $request->user_type == 'delivery_boy';
        $seller_condition = $request->has('user_type') && $request->user_type == 'seller';

        if ($delivery_boy_condition) {
            $user = User::whereIn('user_type', ['delivery_boy'])
                ->where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();
        } elseif ($seller_condition) {
            $user = User::whereIn('user_type', ['seller'])
                ->where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();
        } else {
            $user = User::whereIn('user_type', ['customer'])
                ->where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();
        }

        // if (!$delivery_boy_condition) {
        // if (!$delivery_boy_condition && !$seller_condition) {
        //     if (\App\Utility\PayhereUtility::create_wallet_reference($request->identity_matrix) == false) {
        //         return response()->json(['result' => false, 'message' => 'Identity matrix error', 'user' => null], 401);
        //     }
        // }

        if ($user != null) {
            if (!$user->banned) {
                if (Hash::check($request->password, $user->password)) {

                    if ($user->email_verified_at == null) {
                        return response()->json(['result' => false, 'message' => translate('Please verify your account'), 'user' => null], 401);
                    }
                    return $this->loginSuccess($user);
                } else {
                    return response()->json(['result' => false, 'message' => translate('Unauthorized'), 'user' => null], 401);
                }
            } else {
                return response()->json(['result' => false, 'message' => translate('User is banned'), 'user' => null], 401);
            }
        } else {
            return response()->json(['result' => false, 'message' => translate('User not found'), 'user' => null], 401);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {

        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json([
            'result' => true,
            'message' => translate('Successfully logged out')
        ]);
    }

    public function socialLogin(Request $request)
    {
        if (!$request->provider) {
            return response()->json([
                'result' => false,
                'message' => translate('User not found'),
                'user' => null
            ],401);
        }

        switch ($request->social_provider) {
            case 'facebook':
                $social_user = Socialite::driver('facebook')->fields([
                    'name',
                    'first_name',
                    'last_name',
                    'email'
                ]);
                break;
            case 'google':
                $social_user = Socialite::driver('google')
                    ->scopes(['profile', 'email']);
                break;
            case 'twitter':
                $social_user = Socialite::driver('twitter');
                break;
            case 'apple':
                $social_user = Socialite::driver('sign-in-with-apple')
                    ->scopes(['name', 'email']);
                break;
            default:
                $social_user = null;
        }
        if ($social_user == null) {
            return response()->json(['result' => false, 'message' => translate('No social provider matches'), 'user' => null],401);
        }

        if ($request->social_provider == 'twitter') {
            $social_user_details = $social_user->userFromTokenAndSecret($request->access_token, $request->secret_token);
        } else {
            $social_user_details = $social_user->userFromToken($request->access_token);
        }

        if ($social_user_details == null) {
            return response()->json(['result' => false, 'message' => translate('No social account matches'), 'user' => null],401);
        }

        $existingUserByProviderId = User::where('provider_id', $request->provider)->first();

        if ($existingUserByProviderId) {
            $existingUserByProviderId->access_token = $social_user_details->token;
            if ($request->social_provider == 'apple') {
                $existingUserByProviderId->refresh_token = $social_user_details->refreshToken;
                if (!isset($social_user->user['is_private_email'])) {
                    $existingUserByProviderId->email = $social_user_details->email;
                }
            }
            $existingUserByProviderId->save();
            return $this->loginSuccess($existingUserByProviderId);
        } else {
            $existing_or_new_user = User::firstOrNew(
                [['email', '!=', null], 'email' => $social_user_details->email]
            );

            $existing_or_new_user->user_type = 'customer';
            $existing_or_new_user->provider_id = $social_user_details->id;

            if (!$existing_or_new_user->exists) {
                if ($request->social_provider == 'apple') {
                    if ($request->name) {
                        $existing_or_new_user->name = $request->name;
                    } else {
                        $existing_or_new_user->name = 'Apple User';
                    }
                } else {
                    $existing_or_new_user->name = $social_user_details->name;
                }
                $existing_or_new_user->email = $social_user_details->email;
                $existing_or_new_user->email_verified_at = date('Y-m-d H:m:s');
            }

            $existing_or_new_user->save();

            return $this->loginSuccess($existing_or_new_user);
        }
    }

    protected function loginSuccess($user)
    {
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'result' => true,
            'message' => translate('Successfully logged in'),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => null,
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_original' => uploaded_asset($user->avatar_original),
                'phone' => $user->phone
            ]
        ]);
    }


    public function account_deletion()
    {
        if (auth()->user()) {
            Cart::where('user_id', auth()->user()->id)->delete();
        }

        // if (auth()->user()->provider && auth()->user()->provider != 'apple') {
        //     $social_revoke =  new SocialRevoke;
        //     $revoke_output = $social_revoke->apply(auth()->user()->provider);

        //     if ($revoke_output) {
        //     }
        // }

        $auth_user = auth()->user();
        $auth_user->tokens()->where('id', $auth_user->currentAccessToken()->id)->delete();
        $auth_user->customer_products()->delete();

        User::destroy(auth()->user()->id);

        return response()->json([
            "result" => true,
            "message" => translate('Your account deletion successfully done')
        ]);
    }
}
