<?php

namespace App\Http\Controllers;

use App\VerificationCode;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    /**
     * The length of the verification code that the server generates and expects back from an external source.
     *
     * @var mixed
     */
    protected $codeLength;

    /**
     * The secret the requester must present to authenticate themselves to verify a user.
     *
     * @var string
     */
    protected $verificationSecret;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->codeLength = config('tekkifyauth.code_length');
        $this->verificationSecret = config('tekkifyauth.secret');
    }

    public function showVerifyPage(Request $request)
    {
        $user = $request->user();

        $code = $user->verificationCode;

        if ($code == null) {
            $code = $user->verificationCode()->create(['code' => str_random($this->codeLength)]);
        }

        return view('verify.index', compact('code'));
    }

    public function verify(Request $request)
    {
        sleep(20);

        if ($request->input('token') !== $this->verificationSecret) {
            return response('Forbidden', 403);
        }

        $code = $request->input('code');
        $mcUuid = $request->input('mc_uuid');
        $mcUsername = $request->input('mc_username');

        if (strlen($code) != $this->codeLength) {
            return response('Invalid verification code', 400);
        }

        // VerificationCode::create(['code' => '1234567890', 'user_id' => 1]);

        $verificationCode = VerificationCode::find($code);

        if ($verificationCode == null) {
            return response('Invalid verification code', 400);
        }

        $verificationCode->delete();

        $user = $verificationCode->user;

        $mc = $user->minecraftAccount;

        if ($mc != null) {
            $mc->uuid = $mcUuid;
            $mc->last_username = $mcUsername;
            $mc->save();
        } else {
            $user->minecraftAccount()->create([
                'uuid' => $mcUuid,
                'last_username' => $mcUsername,
            ]);
        }

        // Return the username of the user the Minecraft account was verified with.
        return response($user->username, 200);
    }

    // Unused as this isn't actually neccesary for verification, but may be useful for updating username when it's changed.
    // However, the plugin could also handle this and update the username from the server.
    public function exampleUsernameRetrieval()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'https://api.mojang.com/']);
        $response = $client->request('GET', 'user/profiles/' . $mcUuid . '/names');

        if ($res->getStatusCode() != 200) {
            return response('Failed to retrieve username', 400);
        }

        $response = $client->request('GET', 'user/profiles/' . $mcUuid . '/names');
        $jsonString = $response->getBody();
        $json = json_decode($jsonString);
        // first object in array (current name)
        $obj = $json[0];
        // name field {"name":"<username>"}
        $name = $obj->name;
    }

    public function hasExpired(VerificationCode $code) {
        
    }
}
