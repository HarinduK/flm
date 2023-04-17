$(document).ready(function () {

    loadtable();
});

// function for load all users to datatable
function loadtable() {
    $('#tblFriends').DataTable({
        destroy: true,
        responsive: false,
        processing: true,
        serverSide: true,
        ajax: "/users",
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "action" }
        ],
        columnDefs: [
            { width: 30, targets: 0 },
            { width: 200, targets: 1 },
            { width: 30, targets: 2 },
        ],
    });

}

// function for invite to the new friend
function invite(id) {
    $.ajax({
        type: 'POST',
        url: '/invite/' + id,
        data: {
            id: id,
            _token: $("input[name='_token']").val()
        },
        success: function (response) {
            console.log(response);
            toastr.success("invitation email sent.");
            loadTable();
        },
        error: function (data) {
            console.log('An error occurred.');
            console.log(data);
        }
    });
}