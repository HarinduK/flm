<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * return all users with paginated.
     *
     * @return \app\Models\User
     */
    public function getUsers(Request $request)
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
            $totalRecords = User::where('id', '!=', Auth::user()->id)
                ->select('count(*) as allcount')
                ->count();
            $totalRecordswithFilter = User::where('id', '!=', Auth::user()->id)
                ->select('count(*) as allcount')->where('first_name', 'like', '%' . $searchValue . '%')->count();

            // Fetch records
            $records = User::orderBy($columnName, $columnSortOrder)
                ->where('id', '!=', Auth::user()->id)
                ->where('users.first_name', 'like', '%' . $searchValue . '%')
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
                    "action" => '<button class="btn btn-primary" onclick="invite(' . $id . ')">Invite</button>'
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
}
