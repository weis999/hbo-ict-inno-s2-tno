<?php

namespace App\Http\Controllers;

use App\CredentialVerifyRequest;
use Illuminate\Http\Request;

class CredentialVerifyRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {

        $credentialVerifyRequest = CredentialVerifyRequest::all();
        return response()->json($credentialVerifyRequest);
    }

    public function create(Request $request)
    {
        $credentialVerifyRequest = new CredentialVerifyRequest;
        $credentialVerifyRequest->requestId= $request->requestId;
        $credentialVerifyRequest->callbackURL = $request->callbackURL;
        $credentialVerifyRequest->credentialTypes= $request->credentialTypes;

        $credentialVerifyRequest->save();
        return response()->json($credentialVerifyRequest);
    }

    public function show($id)
    {
        $credentialVerifyRequest = CredentialVerifyRequest::find($id);
        if ($credentialVerifyRequest->id == 1)
            $return_data = array('id' => $credentialVerifyRequest->id,
                'callbackURL' => $credentialVerifyRequest->callbackURL,
                    "credentialData" => array(
                        "type" => "https://schema.org/PostalAddress",
                        "data" => array(
                            "postcalCode" => "1234 AA",
                            "streetAddress" => "Streetname 123"
                        )
                    )
                );
        else
            $return_data = response()->json($credentialVerifyRequest);
        return response()->json($return_data);
    }

    public function update(Request $request, $id)
    {
        $credentialVerifyRequest= CredentialVerifyRequest::find($id);

        $credentialVerifyRequest->requestId = $request->input('requestId');
        $credentialVerifyRequest->callbackURL = $request->input('callbackURL');
        $credentialVerifyRequest->credentialTypes = $request->input('credentialTypes');
        $credentialVerifyRequest->save();
        return response()->json($credentialVerifyRequest);
    }

    public function destroy($id)
    {
        $credentialVerifyRequest = CredentialVerifyRequest::find($id);
        $credentialVerifyRequest->delete();
        return response()->json('credentialVerifyRequest removed successfully');
    }

}
