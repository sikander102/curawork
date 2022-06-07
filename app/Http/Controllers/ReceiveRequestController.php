<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requests;
use App\Models\Connection;
use Auth;

class ReceiveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Fetch all Received Requests for user.
     */
    public function index()
    {
        $receives = Requests::where('receiver_user_id',Auth::id())->with('senders')->get();
        $countReceivedRequests = count($receives);
        return response()->json([
            'users' => $receives,
            'totalUsers' => $countReceivedRequests,
            'status' => 'success',
            'user_id' => Auth::id(),
            'statusCode' => 200,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //remove from requests
        $removeRequest = Requests::where('receiver_user_id',Auth::id())->where('sender_user_id',$request->request_id)->first();
        $removeRequest->delete();
        //save in connections
        $connection = new Connection();
        $connection->user_id = Auth::id();
        $connection->connected_user_id = $request->request_id;
        $connection->save();
        return response()->json([
            'status' => 'success',
            'statusCode' => 200,
            'message' => "Connection with user successfully"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
