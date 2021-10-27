<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>My Generator</title>
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/128/3143/3143500.png"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"/>
    </head>
    <body class="bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-6 mt-5">
                    <h1 class="text-center mb-4">My Generator</h1>

                    <form method="post" action="/insertFile" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Fichier CSV</label>
                            <input type="file" name="csv" class="form-control" aria-describedby="insertFile">
                        </div>
                        <div class="mb-3">
                            <label>Fichier PDF</label>
                            <input type="file" name="pdf" class="form-control" aria-describedby="insertFile">
                        </div>
                        @isset($error)
                            {{ $error }}
                        @endisset
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger text-white btn-block my-3">Générer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
