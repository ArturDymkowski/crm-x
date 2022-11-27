<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Orders Worker Importer</title>
</head>
<body>

<div class="container">
    <div class="row mt-5">
        <div class="col-12">
            <a href="{{ route('workerOrdersImporter') }}" class="btn btn-success">Importuj dane z worker_orders.html</a>
        </div>
    </div>

    @if(!$logs->isEmpty())
        <div class="row mt-3">
            <div class="col-12">
                <h3>Logs</h3>

                <table class="table">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Type</th>
                        <th>Run at</th>
                        <th>Entries processed</th>
                        <th>Entries created</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($logs->items() as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->type }}</td>
                                <td>{{ $log->run_at }}</td>
                                <td>{{ $log->entries_processed }}</td>
                                <td>{{ $log->entries_created }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {!! $logs->links('pagination::bootstrap-4') !!}
            </div>
        </div>
    @endif
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
