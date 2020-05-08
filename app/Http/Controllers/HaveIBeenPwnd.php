<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
class HaveIBeenPwnd extends Controller
{
    use ValidatesRequests;
    //
    public function breaches(Request $request){

        $client = new Client();
        $res = $client->request('POST', 'https://url_to_the_api', [
            'form_params' => [
                'client_id' => 'test_id',
                'secret' => 'test_secret',
            ]
        ]);
        echo $res->getStatusCode();
    }
    public function Password(Request $request){
        $this->validate($request,[
            'hash' => 'required|string',
        ]);

        $client = new Client();
        $res = $client->request('GET', 'https://api.pwnedpasswords.com/range/'.$request->hash, [
            'form_params' => [
            ]
        ]);
    $body = $res->getBody();

        return response()->json(explode("\r\n",$body->getContents()));
    }
}
