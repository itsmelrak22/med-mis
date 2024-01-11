<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Document</title> -->
    
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid black;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: lightgray;
    }
</style>

</head>
<body>

<div>
    <h2>Stock in Request - APRROVED</h2>
</div>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Unit Price</th>
            <th>Unit Selling</th>
            <th>Request Quantity</th>
            <th>Supplier</th>
            <th>Status</th>
            <th>Stock In Request Date</th>
            <th>Requested By</th>
        </tr>
    </thead>
    <tbody>
        <!-- Loop through the data and display each row -->
        @foreach ($data as $row)
        <tr>
            <td>{{ $row->name }}</td>
            <td>{{ $row->unit_price }}</td>
            <td>{{ $row->unit }}</td>
            <td>{{ $row->quantity }}</td>
            <td>{{ $row->supplier }}</td>
            <td>{{ $row->status }}</td>
            <td>{{ $row->created_at }}</td>
            <td>{{ $row->requested_by }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


    
</body>
</html>