<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>File Upload</title>
    </head>
    <body>

    <a class="btn btn-success" href="{{ route('microsoft.oAuth') }}">Sign In</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container">
        <div class="card ">
            <div class="card-header">
            Upload Files
            </div>
            <div class="card-body">
                <form action="/files" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label >Contract Id</label>
                        <input name="contract_id" type="text" class="form-control">
                        @error('contract_id')
                            <span class="text-danger">Contract Id Required</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input type="file" name="file" class="form-control">
                        @error('file')
                            <span class="text-danger">File Required</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>

    </body>
</html>