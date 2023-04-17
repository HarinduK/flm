@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{ csrf_field() }}
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Friend List</h6>
                    <table id="tblFriends" class="table table-striped table-bordered dataTable dtr-inline collapsed"
                        role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                <th class="id">Id</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
   <script type="text/javascript" src="{{ URL::asset('js/friends.js') }}"></script>
@endsection

