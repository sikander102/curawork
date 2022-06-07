<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requests;
use App\Models\Connection;
use Auth;
class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    /**
     * Fetch all Suggestions for user.
     */
    public function suggestedUser()
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
    public function suggestedMoreUser($number)
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
     * Saving request of user for connection
     */
    public function suggestSendRequest(Request $request)
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
     * Fetch all Requests for user.
     */
    public function requestedUser()
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
    public function requestedMoreUser($number)
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
    * Remove Request for user.
    */
    public function deleteRequest(Request $request)
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

    /**
     * Fetch all Received Requests for user.
     */
    public function receivedUser()
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
    * Accept Received Request for user and save in connections.
    */
    public function acceptRequest(Request $request)
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
     * Fetch all Connected users.
     */
    public function connectedUser()
    {
        $connections = Connection::where('user_id',Auth::id())->with('users')->get();
        $countConnection = count($connections);
        return response()->json([
            'users' => $connections,
            'totalUsers' => $countConnection,
            'status' => 'success',
            'user_id' => Auth::id(),
            'statusCode' => 200,
        ]);
    }

    /**
     * Remove Connected user.
     */
    public function removeConnection(Request $request)
    {
        $connection = Connection::where('user_id',Auth::id())->where('connected_user_id',$request->connection_id)->first();
        $connection->delete();
        return response()->json([
            'status' => 'success',
            'statusCode' => 200,
            'message' => "Connection removed"
        ]);
    }

    /**
     * Fetch Connection in common
     */
    public function connectionInCommon(Request $request)
    {
        $ids = Connection::where('user_id',$request->connection_id)->pluck('connected_user_id');
        $connection = Connection::with('users');
        foreach ($ids as $key => $value) {
            $connection->whereHas('users', function($query) use ($value){
                $query->where('connected_user_id', $value);
            });
        }
        $connection = $connection->get();
        $countConnection = count($connection);
        return response()->json([
            'users' => $connection,
            'totalUsers' => $countConnection,
            'status' => 'success',
            'user_id' => Auth::id(),
            'statusCode' => 200,
        ]);
    }

    /**
     * Fetch all Connections
     */
    public function getMoreConnection($number)
    {
        $connections = Connection::where('user_id',Auth::id())->with('users')->limit((int)$number)->get();
        $countConnection = count(Connection::where('user_id',Auth::id())->with('users')->get());
        return response()->json([
            'users' => $connections,
            'totalUsers' => $countConnection,
            'status' => 'success',
            'user_id' => Auth::id(),
            'statusCode' => 200,
        ]);
    }
}
