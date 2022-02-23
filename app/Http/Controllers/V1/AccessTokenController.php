<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\User;

class AccessTokenController extends Controller
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['issueToken']);
    }

    /**
     * Authorize a client to access the user's account.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @return \Illuminate\Http\Response
     */
    public function issueToken(Request $request)
    {
        try {
            $data = $request->all();

            $validationSecurity = $this->validationSecurity($data);

            if ($validationSecurity == false) {
                throw new \Exception();
            }

            $data['username'] = appencrypt(strtolower($data['username']));

            $user = User::where('username', $data['username']);

            if ($user->exists() == false) {
                throw new \Exception();
            }

            $isUser = $user->first();
            if (\Hash::check($data['password'], $isUser->password) == false) {
                $this->loginAttempt($isUser);
                return response()->json([
                    'message' => trans('message.invalid'),
                    'errors' => [
                        'login_failed' => [
                            trans('message.error.login_failed')
                        ]
                    ]
                ], 422);
            }

            if ($this->notLocked($isUser) == false) {
                $lock_time = '10 menit';

                return response()->json([
                    'status'    => 'error',
                    'message' => trans('message.invalid'),
                    'errors' => [
                        'access_lock' => [
                            trans('message.error.access_lock', ['lock_time', $lock_time])
                        ]
                    ]
                ], 422);
            }

            $isUser->update([
                'last_login' => date('Y-m-d H:i:s'),
                'login_attempt' => null,
                'login_attempt_at' => null,
                'locked_at' => null,
            ]);

            return response()->json([
                'status'        => 'success',
                'access_token'  => $this->storeToken($isUser->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => trans('message.error.login_failed'),
                'errors'    => [
                    'error'     => [trans('message.error.login_failed')],
                    'server'    => [$e->getMessage()]
                ]
            ], 422);
        }
    }

    public function removeToken(AuthManager $auth)
    {
        $token  = Auth::token();

        \DB::table('auth_tokens')
            ->where('token', $token)
            ->delete();

        return $this->response(true);
    }

    protected function loginAttempt($user)
    {
        if (is_null($user->locked_at)) {
            $login_attempt = $user->login_attempt + 1;
            $data['login_attempt'] = $login_attempt;
            $data['login_attempt_at'] = date('Y-m-d H:i:s');

            if ($login_attempt >= 5) {
                $data['locked_at'] = date('Y-m-d H:i:s');
            }

            $user->update($data);
        }
    }

    protected function notLocked($user)
    {
        if (is_null($user->locked_at) == false) {
            $date1 = new \DateTime(date('Y-m-d H:i:s'));
            $date2 = new \DateTime($user->locked_at);
            $diff = $date2->diff($date1);
            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;

            $lock_time = 10;

            if ($minutes >= $lock_time) {
                return $user->update([
                    'login_attempt' => null,
                    'login_attempt_at' => null,
                    'locked_at' => null,
                ]);
            } else {
                return false;
            }
        }

        return true;
    }

    public function validationSecurity($data)
    {
        if ($data['grant_type'] != 'password') {
            return false;
        }

        if ((int) $data['client_id'] != env('AUTH_CLIENT_ID')) {
            return false;
        }

        if ($data['client_secret'] != env('AUTH_CLIENT_SECRET')) {
            return false;
        }

        return true;
    }

    protected function storeToken($userId)
    {
        $token = Auth::generateToken($userId);

        return $token;
    }
}
