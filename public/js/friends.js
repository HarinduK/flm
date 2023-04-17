$(document).ready(function () {

    loadtable();
});

// function for load all friends to datatable
function loadtable() {
    $('#tblFriends').DataTable({
        destroy: true,
        responsive: false,
        processing: true,
        serverSide: true,
        ajax: "/friendList",
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
function remove(id) {
    $.ajax({
        type: 'DELETE',
        url: '/remove/' + id,
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
