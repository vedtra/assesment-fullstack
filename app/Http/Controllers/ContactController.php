<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;

class ContactController extends Controller
{
    public function index(){        
        $user_id= JWTAuth::parseToken()->toUser()->id;

        $contacts = User::select('users.*', \DB::raw('count(messages.id) as unread'))
            ->leftJoin('messages', function($join){
                $join->on('messages.from_id', '=', 'users.id');
                $join->where('messages.read', '0');
                $join->where('messages.to_id', auth()->id());
            })
            ->where('users.id', '!=', $user_id)
            ->groupBy('users.id')
            ->get();          

        $contacts = $contacts->map( function($contact){
            $latest_message = $contact->getLatestMessage();
            $contact->latest_message = $latest_message ? $latest_message->message : NULL;
            return $contact;
        });

        return response()->json($contacts);       
    }
}
