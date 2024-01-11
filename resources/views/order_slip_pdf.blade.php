<div class="container">
    <h1>Sales Order Request</h1>
    <p>Customer Name: {{ $data->customer_name }}</p>
    <p>Requested By: {{ $data->requested_by }}</p>
    <p>Requested Date: {{ $data->requested_date }}</p>
    <p>Status: {{ $data->status }}</p>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $data->name }}</td>
                <td>{{ $data->unit }}</td>
                <td>{{ $data->quantity }}</td>
                <td>{{ $data->unit_price }}</td>
                <td>{{ $data->supplier }}</td>
            </tr>
        </tbody>
    </table>
</div>

<style>
    .container {
        margin: 20px;
    }

    h1 {
        text-align: center;
    }

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

