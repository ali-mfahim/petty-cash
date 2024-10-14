@extends("admin.layouts.master")
@push("title" , $title ?? '')
@push("styles")

<style>
    label {
        font-size: 20px;
        font-weight: bold;
    }
</style>


@endpush
@section("content")
<section id="dashboard-ecommerce">
    <div class="content-wrapper">



        <!-- Breadcrumbs -->
        <div class="row">
            <div class="col-md-6">
                <h3>{{$title ?? ''}}</h3>
                <p>This page helps you manage this project</p>
            </div>
        </div>
        <!-- Breadcrumbs -->

        <div class="card input-checkbox">
            <div class="card-body">
                <form action="{{route('settings.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="margin-top:20px">
                        <div class="col-md-4" style="text-align: right !important;">
                            <label for="cron_enable">Support Email</label>
                        </div>
                        <div class="col-md-4" style="">
                            <input type="email" class="form-control" value="{{$settings->support_email ?? ''}}" name="support_email" placeholder="Enter your support email" />
                        </div>
                    </div>

                    <div class="row" style="margin-top:20px">
                        <div class="col-md-4" style="text-align: right !important;">
                            <label for="status_switch">Scheduler Active</label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch form-check-primary">
                                <!-- style="width: 127px;height: 45px;" -->
                                <input type="checkbox" class="form-check-input " name="cron_enable" data-app-id="" value="1" @if(isset($settings) && !empty($settings) && $settings->cron_enable == 1) checked @endif id="status_switch" />
                            </div>
                        </div>
                    </div>

                    <div class="row d-none" style="margin-top:20px">
                        <div class="col-md-4" style="text-align: right !important;">
                            <label for="cron_enable">White Logo</label>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail-container">
                                <img id="thumbnail" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 40%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                                <input type="file" id="image" name="logo" class="form-control" style="display: none;" />
                            </div>
                            <div id="image_error" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="row d-none" style="margin-top:20px">
                        <div class="col-md-4" style="text-align: right !important;">
                            <label for="cron_enable">Black Logo</label>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail-container">
                                <img id="thumbnail" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 40%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                                <input type="file" id="image" name="logo" class="form-control" style="display: none;" />
                            </div>
                            <div id="image_error" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="row d-none" style="margin-top:20px">
                        <div class="col-md-4" style="text-align: right !important;">
                            <label for="cron_enable">Fav Icon</label>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail-container">
                                <img id="thumbnail" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 20%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                                <input type="file" id="image" name="logo" class="form-control" style="display: none;" />
                            </div>
                            <div id="image_error" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-8" style="text-align: center;">
                            <button class="btn btn-success " style="width: 200px;margin-left: 220px;">Save</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push("scripts")
<script>
    $(document).on("click", "#thumbnail", function() {
        $('#image').click();
    });
    $(document).on("change", "#image", function(event) {
        var reader = new FileReader();


        // Show the spinner
        if ($("#image_spinner").hasClass("d-none")) {
            $("#image_spinner").removeClass("d-none")
        }

        reader.onload = function(e) {

            // Hide the spinner
            setTimeout(() => {
                $('#thumbnail').attr('src', e.target.result);
                if (!$("#image_spinner").hasClass("d-none")) {
                    $("#image_spinner").addClass("d-none")
                }
            }, 1000);
        }

        // If an error occurs, hide the spinner
        reader.onerror = function() {
            setTimeout(() => {
                if (!$("#image_spinner").hasClass("d-none")) {
                    $("#image_spinner").addClass("d-none")
                }
            }, 1000);
        }

        reader.readAsDataURL(event.target.files[0]);
    });
</script>
@endpush