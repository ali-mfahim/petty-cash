<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thankyou</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card-body img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover !important;
            border-radius: 15px !important;
            /* Ensures the image covers the container without stretching */
        }
    </style>
</head>

<body class="container" style="background:#d3d3d3;max-width:700px">
    @if (Session::has('success'))
        <input type="hidden" name="" value="{{ Session::get('success') }}" id="success_msg_global">
    @endif
    @if (Session::has('error'))
        <input type="hidden" name="" value="{{ Session::get('error') }}" id="error_msg_global">
    @endif

    <div class="row  mt-3" style="margin-bottom: 200px">
        <div class="col-md-12">
            <div class="card" style="padding: 0px !important;margin-bottom:20px">
                <div class="card-body" style="padding: 0px !important">
                    <img src="{{ $logos->logo_black }}" alt="">
                </div>
            </div>
            @if (isset($session) && !empty($session))
                <div class="alert alert-success" style="text-align:center;font-size:20px;font-weight:bold">
                    {{ $session ?? '' }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    <a href="{{ $link->link ?? '' }}" class="btn btn-success" style="width:50%">Submit another response</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>
