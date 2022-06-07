<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Requests;
use App\Models\Connection;
use Auth;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Fetch all Connected users.
     */
    public function index()
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
     * Fetch Connection in common
     */
    public function getConnectionInCommon(Request $request)
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
     * Fetch all Connections on show more
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
        $connection = Connection::where('user_id',Auth::id())->where('connected_user_id',$request->connection_id)->first();
        $connection->delete();
        return response()->json([
            'status' => 'success',
            'statusCode' => 200,
            'message' => "Connection removed"
        ]);
    }
}
