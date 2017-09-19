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

    // api call
    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'same:' . $this->verificationSecret,
            'code' => 'required|size:' . $this->codeLength . '|exists:verification_codes',
            'mc_uuid' => 'required|size:36',
            'mc_username' => 'required|alpha_dash|between:1,16',
        ]);

        $code = $request->input('code');
        $mcUuid = $request->input('mc_uuid');
        $mcUsername = $request->input('mc_username');

        $verificationCode = VerificationCode::find($code);
        $verificationCode->delete();

        $user = $verificationCode->user;

        if ($user->minecraftAccount != null) {
            return response('User already has a verified Minecraft account', 400);
        }

        $user->minecraftAccount()->create([
            'uuid' => $mcUuid,
            'last_username' => $mcUsername,
        ]);

        // Return the username of the user the Minecraft account was verified with.
        return response($user->username, 200);
    }

    public function unlink(Request $request)
    {
        $mc = $user->minecraftAccount;

        if ($mc == null) {
            return redirect('/'); // go back with error
        }

        $mc->delete();

        return redirect('/verify'); // with success messsage
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
