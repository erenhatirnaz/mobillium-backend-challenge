<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $login = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($login)) {
            return response(['message' => 'Wrong email/password!'], 401);
        }

        $client = Client::find(2);
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->email,
            'password' => $request->password,
        ]);

        $oauthTokenRequest = Request::create('oauth/token', "POST");
        $oauthTokenResponse = Route::dispatch($oauthTokenRequest);

        if ($oauthTokenResponse->getStatusCode() == 401) {
            return response()->json()->setData($oauthTokenResponse->getContent())->setStatusCode(401);
        }

        return response()->json()->setData($oauthTokenResponse->getContent())->setStatusCode(200);
    }
}
