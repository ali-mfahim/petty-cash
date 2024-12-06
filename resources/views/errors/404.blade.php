<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Not Found</title>
    @include("admin.partials.styles")
</head>

<body class="container" style="background-color: #ffffff;">
    <div class="row">
        <div class="col-md-12" style="text-align:center">
            <img src="{{asset('admin/errors/404-page.gif')}}" alt="" style="width: 50%;height:auto;margin-top: 100px;">
            <p class="display-5">Oops! Looks like we took a wrong turn.The page you're looking for isn't here! </p>
        </div>
    </div>
</body>

</html>