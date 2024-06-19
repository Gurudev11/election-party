<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Party Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        #parties-table tbody tr { cursor: move; }
        .butn {
            margin-bottom: 40px;
        }

    </style>
</head>
<body>

<h1>Party Details</h1>
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ url('/parties/details') }}">
    @csrf
    <div class="form-group">
        <label for="rowsPerPage">Rows per page:</label>
        <input type="number" id="rowsPerPage" class="form-control" value="5" min="1" style="width: auto; display: inline-block;">
        <button type="button" id="setRowsPerPage" class="btn btn-primary">Set</button>
    </div>
    <table id="parties-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>Id</th>
                <th>Party Name</th>
                <th>Registration Date</th>
                <th>Address 1</th>
                <th>Address 2</th>
                <th>City</th>
                <th>State</th>
                <th>Mobile Number</th>
                <th>Party Logo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parties as $party)
            <tr>
                <td><input type="checkbox" name="ids[]" value="{{ $party->id }}"></td>
                <td>{{ $party->id }}</td>
                <td>{{ $party->name }}</td>
                <td>{{ $party->registration_date }}</td>
                <td>{{ $party->address->address1 }}</td>
                <td>{{ $party->address->address2 ?? 'N/A'}}</td>
                <td>{{ $party->address->city }}</td>
                <td>{{ $party->address->state }}</td>
                <td>{{ $party->number }}</td>
                <td>
                    <img src="{{ asset('storage/' . $party->party_logo) }}" alt="{{ $party->name }} Logo" width="100">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <div>
        <button class ="butn" type="submit">Delete Selected</button>
        <button class ="butn" type="button" onclick="editSelected()">Edit</button>
    </div>
</form> 

<!-- Pagination Links -->
<!-- <div class="d-flex justify-content-center">
    {{ $parties->links() }}
</div> -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#parties-table').DataTable({
            "paging": true,
            // "info": false,
            // "searching": true, 
            // "ordering": false, 
            "pageLength": parseInt($('#rowsPerPage').val(), 10)
        });

        $('#setRowsPerPage').on('click', function() {
            var value = $('#rowsPerPage').val();
            table.page.len(parseInt(value, 10)).draw();
        });

        $('#parties-table tbody').sortable({
            helper: fixHelperModified,
            stop: updateIndex 
        }).disableSelection();
    });
   
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width());
        });
        return $helper;
    };

    function updateIndex(e, ui) {
        $('td.index', ui.item.parent()).each(function (i) {
            $(this).html(i + 1);
        });
    }

    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.getElementsByName('ids[]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }

    function editSelected() {
        var checkboxes = document.querySelectorAll('input[name="ids[]"]:checked');
        if (checkboxes.length === 1) {
            var id = checkboxes[0].value;
            window.location.href = '/parties/' + id;
        } else if (checkboxes.length > 1) {
            alert('Please select only one party to edit.');
        } else {
            alert('Please select a party to edit.');
        }
    }
</script>
</body>
</html>

