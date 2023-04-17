<?php

namespace App\Http\Controllers;

use App\Mail\InviteMail;
use App\Models\User;
use Illuminate\Auth\Recaller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserFriendController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }


    /**
     * Show the friend page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('friends');
    }

    /**
     * send invitation email.
     *
     * @return \app\Models\User
     */
    public function inviteFriend($id, Request $request)
    {
        try {
            $inserted_id = DB::table('user_friends')->insertGetId(
                [
                    'user_id' => Auth::user()->id,
                    'friend_id' => $id,
                    'status' => config(
                        'global.friend_status.PENDING'
                    )
                ]
            );
            $sender = Auth::user();
            $receiver = User::where('id', '=', $id)->first();
            $mailData = ['senderName' => $sender->first_name . " " . $sender->last_name, 'receiverName' => $receiver->first_name . " " . $receiver->last_name, 'id' => $id];
            Mail::to($receiver->email)->send(new InviteMail($mailData));

            return $inserted_id;
        } catch (\Exeption $exeption) {
            return $exeption;
        }
    }

    /**
     * invitation confirm.
     *
     * @return 
     */
    public function confirm($id)
    {
        DB::table('user_friends')->where('id', '=', $id)->update(['status' => config('global.friend_status.CONFIRMED')]);
        return redirect()->route('home');
    }

    /**
     * return friend list with paginated.
     *
     * @return \app\Models\User
     */
    public function getFriendList(Request $request)
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // Rows display per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
            $searchValue = $search_arr['value']; // Search value

            // Total records
            $totalRecords = DB::table('user_friends')
                ->where('status', '=', config('global.friend_status.CONFIRMED'))
                ->where('user_id', '=', Auth::user()->id)
                ->orWhere('friend_id', '=', Auth::user()->id)
                ->select('count(*) as allcount')->count();
            $totalRecordswithFilter = DB::table('user_friends')
                ->where('status', '=', config('global.friend_status.CONFIRMED'))
                ->where('user_id', '=', Auth::user()->id)
                ->orWhere('friend_id', '=', Auth::user()->id)
                ->select('count(*) as allcount')->count();

            // Fetch records
            $records = DB::table('user_friends')
                ->join('users', 'user_friends.friend_id', '=', 'users.id')
                ->orderBy($columnName, $columnSortOrder)
                ->where('users.first_name', 'like', '%' . $searchValue . '%')
                ->where('status', '=', config('global.friend_status.CONFIRMED'))
                ->where('user_id', '=', Auth::user()->id)
                ->orWhere('friend_id', '=', Auth::user()->id)
                ->select('users.*')
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();
            $sno = $start + 1;
            foreach ($records as $record) {
                $id = $record->id;
                $name = $record->first_name . " " . $record->last_name;
                $email = $record->email;

                $data_arr[] = array(
                    "id" => $id,
                    "name" => $name,
                    "email" => $email,
                    "action" => '<button class="btn btn-danger" onclick="remove(' . $id . ')">remove</button>'
                );
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr
            );

            return  json_encode($response);
        } catch (\Exeption $exception) {
            return  $exception;
        }
    }

    /**
     * friend remove from list.
     *
     * @return 
     */
    public function delete($id)
    {
        try {
            DB::table('user_friends')->delete('id', '=', $id);
        } catch (\Exeption $exception) {
            return  $exception;
        }
    }
}
