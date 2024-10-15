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
                    <div class="row " style="margin-top:20px">
                        <div class="col-md-4" style="text-align: right !important;">
                            <label for="cron_enable">White Logo</label>
                            <div class=" d-none " id="image_spinner_white_logo" style="text-align: right;">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail-container">
                                @if(isset($settings->logo_white) && !empty($settings->logo_white) && checkFileExists($settings->logo_white, config('project.upload_path.store_logo')) == true)
                                <img id="thumbnail_white_logo" src="{{asset(config('project.upload_path.store_logo') . $settings->logo_white)}}" alt="Thumbnail" style="cursor: pointer;max-width: 50%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                                @else
                                <img id="thumbnail_white_logo" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 40%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                                @endif
                                <input type="file" id="image_white_logo" name="white_logo" class="form-control" style="display: none;" />
                            </div>
                            <div id="image_error_white_logo" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="row " style="margin-top:20px">
                        <div class="col-md-4" style="text-align: right !important;">
                            <label for="cron_enable">Black Logo</label>
                            <div class=" d-none " id="image_spinner_black_logo" style="text-align: right;">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail-container">
                                @if(isset($settings->logo_black) && !empty($settings->logo_black) && checkFileExists($settings->logo_black, config('project.upload_path.store_logo_black')) == true)
                                <img id="thumbnail_black_logo" src="{{asset(config('project.upload_path.store_logo_black') . $settings->logo_black)}}" alt="Thumbnail" style="cursor: pointer;max-width: 50%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                                @else
                                <img id="thumbnail_black_logo" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 40%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                                @endif
                                <input type="file" id="image_black_logo" name="black_logo" class="form-control" style="display: none;" />
                            </div>
                            <div id="image_error_black_logo" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="row " style="margin-top:20px">
                        <div class="col-md-4" style="text-align: right !important;">
                            <label for="cron_enable">Fav Icon</label>
                            <div class=" d-none " id="image_spinner_fav_icon" style="text-align: right;">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail-container">
                                @if(isset($settings->fav_icon) && !empty($settings->fav_icon) && checkFileExists($settings->fav_icon, config('project.upload_path.store_fav_icon')) == true)
                                <img id="thumbnail_fav_icon" src="{{asset(config('project.upload_path.store_fav_icon') . $settings->fav_icon)}}" alt="Thumbnail" style="cursor: pointer;max-width: 20%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                                @else
                                <img id="thumbnail_fav_icon" src="{{asset('upload-icon.png')}}" alt="Thumbnail" style="cursor: pointer;max-width: 20%;box-shadow: 0px 0px 15px -3px black;margin-top: 20px;margin-bottom: 20px;" />
                                @endif

                                <input type="file" id="image_fav_icon" name="fav_icon" class="form-control" style="display: none;" />
                            </div>
                            <div id="image_error_fav_icon" class="text-danger"></div>
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
    // white logo
    $(document).on("click", "#thumbnail_white_logo", function() {
        $('#image_white_logo').click();
    });
    $(document).on("change", "#image_white_logo", function(event) {
        var reader = new FileReader();


        // Show the spinner
        if ($("#image_spinner_white_logo").hasClass("d-none")) {
            $("#image_spinner_white_logo").removeClass("d-none")
        }

        reader.onload = function(e) {

            // Hide the spinner
            setTimeout(() => {
                $('#thumbnail_white_logo').attr('src', e.target.result);
                if (!$("#image_spinner_white_logo").hasClass("d-none")) {
                    $("#image_spinner_white_logo").addClass("d-none")
                }
            }, 1000);
        }

        // If an error occurs, hide the spinner
        reader.onerror = function() {
            setTimeout(() => {
                if (!$("#image_spinner_white_logo").hasClass("d-none")) {
                    $("#image_spinner_white_logo").addClass("d-none")
                }
            }, 1000);
        }

        reader.readAsDataURL(event.target.files[0]);
    });
    // white logo





    // black Logo
    $(document).on("click", "#thumbnail_black_logo", function() {
        $('#image_black_logo').click();
    });
    $(document).on("change", "#image_black_logo", function(event) {
        var reader = new FileReader();


        // Show the spinner
        if ($("#image_spinner_black_logo").hasClass("d-none")) {
            $("#image_spinner_black_logo").removeClass("d-none")
        }

        reader.onload = function(e) {

            // Hide the spinner
            setTimeout(() => {
                $('#thumbnail_black_logo').attr('src', e.target.result);
                if (!$("#image_spinner_black_logo").hasClass("d-none")) {
                    $("#image_spinner_black_logo").addClass("d-none")
                }
            }, 1000);
        }

        // If an error occurs, hide the spinner
        reader.onerror = function() {
            setTimeout(() => {
                if (!$("#image_spinner_black_logo").hasClass("d-none")) {
                    $("#image_spinner_black_logo").addClass("d-none")
                }
            }, 1000);
        }

        reader.readAsDataURL(event.target.files[0]);
    });
    // black Logo








    // fav Icon
    $(document).on("click", "#thumbnail_fav_icon", function() {
        $('#image_fav_icon').click();
    });
    $(document).on("change", "#image_fav_icon", function(event) {
        var reader = new FileReader();


        // Show the spinner
        if ($("#image_spinner_fav_icon").hasClass("d-none")) {
            $("#image_spinner_fav_icon").removeClass("d-none")
        }

        reader.onload = function(e) {

            // Hide the spinner
            setTimeout(() => {
                $('#thumbnail_fav_icon').attr('src', e.target.result);
                if (!$("#image_spinner_fav_icon").hasClass("d-none")) {
                    $("#image_spinner_fav_icon").addClass("d-none")
                }
            }, 1000);
        }

        // If an error occurs, hide the spinner
        reader.onerror = function() {
            setTimeout(() => {
                if (!$("#image_spinner_fav_icon").hasClass("d-none")) {
                    $("#image_spinner_fav_icon").addClass("d-none")
                }
            }, 1000);
        }

        reader.readAsDataURL(event.target.files[0]);
    });
    // fav Icon
</script>
@endpush