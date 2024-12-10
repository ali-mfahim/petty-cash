<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    @include('admin.partials.styles')
</head>

<body class="container" style="background-color: #ffffff;">
    <div class="row">
        <div class="col-md-12" style="text-align:center">
            <img src="{{ asset('admin/errors/404-page.gif') }}" alt=""
                style="width: 50%;height:auto;margin-top: 100px;">
            <p class="display-5">
                {{ $message ?? 'Oops! Looks like we took a wrong turn.The page you are looking for is does not exist!' }}
            </p>
        </div>
    </div>
</body>

</html>
