<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requests;
use App\Models\Connection;
use Auth;

class SentRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = Requests::where('sender_user_id',Auth::id())->with('receivers')->limit(10)->get();
        $countRequests = count($requests);
        return response()->json([
            'users' => $requests,
            'totalUsers' => $countRequests,
            'status' => 'success',
            'user_id' => Auth::id(),
            'statusCode' => 200,
        ]);
    }

    /**
     * Fetch all requests for user for show more.
     */
    public function getMoreRequests($number)
    {
        $requests = Requests::where('sender_user_id',Auth::id())->with('receivers')->limit((int)$number)->get();
        $countRequests = count($requests);
        return response()->json([
            'users' => $requests,
            'totalUsers' => $countRequests,
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
    public function create()
    {
        //
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
    public function destroy(Request $request)
    {
        //remove from requests
        $removeRequest = Requests::where('sender_user_id',Auth::id())->where('receiver_user_id',$request->request_id)->first();
        $removeRequest->delete();
        return response()->json([
            'status' => 'success',
            'statusCode' => 200,
            'message' => "Request deleted successfully"
        ]);
    }
}
