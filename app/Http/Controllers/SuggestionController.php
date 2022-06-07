<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requests;
use App\Models\Connection;
use Auth;

class SuggestionController extends Controller
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

    /**
     * Fetch all Suggestions for user.
     */
    public function index()
    {
        $suggestions = User::where('id','!=',Auth::id())->select('id','name','email')->doesnthave('invites')->doesnthave('connections')->limit(10)->get();
        $requests = Requests::where('sender_user_id',Auth::id())->with('receivers')->get();
        $receives = Requests::where('receiver_user_id',Auth::id())->with('senders')->get();
        $connections = Connection::where('user_id',Auth::id())->with('users')->get();
        $countConnection = count($connections);
        $countReceive = count($receives);
        $countRequests = count($requests);
        $countSuggestions = count($suggestions);
        return response()->json([
            'users' => $suggestions,
            'totalUsers' => $countSuggestions,
            'totalRequest'=>$countRequests,
            'totalReceive' => $countReceive,
            'totalConnected' => $countConnection,
            'status' => 'success',
            'user_id' => Auth::id(),
            'statusCode' => 200,
        ]);
    }

    /**
     * Fetch all Suggestions for user for show more.
     */
    public function getMoreSuggestions($number)
    {
        $suggestions = User::where('id','!=',Auth::id())->select('id','name','email')->doesnthave('invites')->doesnthave('connections')->limit((int)$number)->get();
        $countSuggestions = count($suggestions);
        return response()->json([
            'users' => $suggestions,
            'totalUsers' => $countSuggestions,
            'user_id' => Auth::id(),
            'status' => 'success',
            'statusCode' => 200,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Saving request of user for connection
     */
    public function create(Request $request)
    {
        $sendRequest = new Requests();
        $sendRequest->sender_user_id = $request->user_id;
        $sendRequest->receiver_user_id = $request->suggestion_id;
        $sendRequest->save();
        return response()->json([
            'status' => 'success',
            'statusCode' => 200,
            'message' => "Request sent"
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
