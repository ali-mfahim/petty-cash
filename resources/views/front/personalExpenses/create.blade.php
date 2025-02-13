<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? '-' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        input {
            border: none !important;
            border-bottom: 1px solid #b5aaaa !important;
            margin-top: 30px !important;
            max-width: 50% !important;
            color: black !important
        }

        .form-control:focus {
            border-bottom: 2px solid black !important;
            transition: .3s ease;
            outline: 0;
            box-shadow: none;
        }

        .card {
            padding: 25px !important;
            border-radius: 15px !important;
        }

        .card-body img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover !important;
            border-radius: 15px !important;
            /* Ensures the image covers the container without stretching */
        }

        label {
            font-size: 20px;
        }

        /* Hide the default checkbox */
        .custom-checkbox input[type="checkbox"] {
            display: none;
        }

        /* Create a custom checkmark */
        .custom-checkbox {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            font-size: 16px;
        }

        .custom-checkbox .checkmark {
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 4px;
            margin-right: 8px;
            display: inline-block;
            position: relative;
            transition: all 0.2s;
            background-color: white;
        }

        /* The checkmark (hidden by default) */
        .custom-checkbox .checkmark::after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .custom-checkbox input[type="checkbox"]:checked+.checkmark::after {
            display: block;
        }

        /* Create the checkmark using the ::after pseudo-element */
        .custom-checkbox .checkmark::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            width: 6px;
            height: 13px;
            border: solid #007bff;
            border-width: 0 2px 2px 0;
            transform: translate(-50%, -50%) rotate(45deg);
            animation: checkmark-animation 0.1s ease-in-out;
        }

        /* Animate the checkmark */
        @keyframes checkmark-animation {
            0% {
                width: 0;
                height: 0;
            }


            100% {
                width: 6px;
                height: 13px;
            }
        }

        .error-card {
            border: 1px solid rgb(217, 48, 37) !important
        }

        .error-input {
            border-bottom: 1px solid rgb(217, 48, 37) !important
        }

        .error-text-message {
            color: rgb(217, 48, 37);
            margin-left: 10px !important;
            margin-top: 10px !important;
            font-size: 14px;
        }


        .bg-grey {
            background: grey
        }


        .custom-select {
            border-top: none;
            border-right: none;
            border-left: none;
            width: 50%
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
            <form id="expense-form">
                @csrf
                <input type="hidden" name="link_id" value="{{ $findLink->id ?? '' }}">
                <input type="hidden" name="paid_by" value="{{ $findLink->user_id ?? '' }}">
                <div class="card" style="padding: 0px !important;margin-bottom:20px">
                    <div class="card-body" style="padding: 0px !important">
                        <img src="{{ $logos->logo_black }}" alt="">
                    </div>
                </div>
                <div id="validation_alert_error" class="d-none">
                    <div class="alert alert-danger">
                        <ul id="validation_alert_error_ul">
                        </ul>
                    </div>
                </div>
                <div class="card mb-2 ">
                    <div class="card-header">
                        <h3 style="font-weight: bold;font-family: sans-serif; ">{{ $title ?? '-' }} </h3>
                    </div>
                    <div class="card-body">
                        <label style="margin-top:30px">User:</label>
                        <h5 style="font-size:20px  !important">{{ getUserName($user) }} - {{ $user->email ?? '' }}</h5>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <label for="food_item">Title</label>
                        <input type="text" name="food_item" id="food_item" class="form-control custom_input  "
                            placeholder="Your answer">
                        @if (isset($defaultKeywords) && !empty($defaultKeywords))
                            <div style="width:75%;margin-top:20px">
                                @foreach ($defaultKeywords as $v)
                                    <a href="javascript:;" class="click_default_keyword"
                                        data-keyword="{{ $v->title ?? '' }}">
                                        <span
                                            class="badge bg-grey">{{ isset($v->title) && !empty($v->title) ? strtoupper($v->title) : '-' }}</span></a>
                                @endforeach
                            </div>
                        @endif
                        <span class="error_food_item error-text-message d-none"></span>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" class="form-control custom_input"
                            placeholder="Your answer">
                        <span class="error_date error-text-message d-none"></span>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <label for="type">Transaction Type</label>
                        <select name="type" id="type" class="form-select custom-select">
                            <option value="1">Credit</option>
                            <option value="2">Debit</option>
                        </select>
                        <span class="error_type error-text-message d-none"></span>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control custom_input"
                            placeholder="Your answer">
                        <span class="error_amount error-text-message d-none"></span>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" cols="30" rows="4" class="form-control  "></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        <button type="submit" class="btn btn-success" style="width:30%"
                            id="submit_btn">Submit</button>
                        <button type="button" class="btn btn-warning" style="width:30%">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            var submitUserId = "{{ $user->id }}";
            $(document).on("input", "#food_item", function() {
                displayOrHideError(".error_food_item", "#food_item", 0)
            });
            $(document).on("input", "#date", function() {
                displayOrHideError(".error_date", "#date", 0)
            });
            $(document).on("input", "#amount", function() {
                displayOrHideError(".error_amount", "#amount", 0)
            });

            function displayOrHideError(input_error_element, input_element, errors) {
                // Ensure both parameters are jQuery objects
                var $input_error_element = $(input_error_element);
                var $input_element = $(input_element);
                $input_error_element.addClass("d-none");

                if ($input_element.hasClass("error-input")) {
                    $input_element.removeClass("error-input");
                }
                var amount = $input_element.val();
                var closesFoodItemCard = $input_element.closest('.card');
                closesFoodItemCard.removeClass("error-card");

                if (!amount) {
                    closesFoodItemCard.addClass("error-card");
                    $input_error_element.html("⚠️ This is required field");
                    if ($input_error_element.hasClass("d-none")) {
                        $input_error_element.removeClass("d-none");
                    }
                    if (!$input_element.hasClass("error-input")) {
                        $input_element.addClass("error-input");
                    }
                    var errors = 1;
                    // console.log("Errors From:" + input_element + " = " + errors)
                }
                return errors;
            }
            $(document).on("submit", "#expense-form", function(event) {
                event.preventDefault();
                $("#submit_btn").attr("disabled", true)
                var errors = 0;
                var food_item = $("#food_item").val();
                var date = $("#date").val();
                var amount = $("#amount").val();
                var foodResponse = displayOrHideError(".error_food_item", "#food_item", errors)
                var dateResponse = displayOrHideError(".error_date", "#date", errors)
                var amountResponse = displayOrHideError(".error_amount", "#amount", errors)
                // if (foodResponse == 1 || dateResponse == 1 || amountResponse == 1) {
                //     return false;
                // }



                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('front.expenseForm.submit') }}",
                    data: formData,
                    beforeSend: function() {
                        console.log("working")
                    },
                    success: function(res) {
                        if (res.success == true) {
                            if (!$("#validation_alert_error").hasClass("d-none")) {
                                $("#validation_alert_error").addClass("d-none")
                            }
                            $("#validation_alert_error_ul").empty()
                            if (res.data.redirect != null) {
                                window.location.href = res.data.redirect
                            }

                        }
                        if (res.success == false) {
                            $("#submit_btn").attr("disabled", false)
                            $('html, body').animate({
                                scrollTop: 0
                            }, 'fast');
                            if (res.data && res.message == "Validation Errors") {
                                $("#validation_alert_error_ul").empty()
                                $.each(res.data, function(key, value) {
                                    if ($("#validation_alert_error").hasClass(
                                            "d-none")) {
                                        $("#validation_alert_error").removeClass(
                                            "d-none")
                                    }
                                    var li = "<li>" + value + "</li>";
                                    $("#validation_alert_error_ul").append(li);

                                });
                            }
                            if (res.data == "Create Error") {
                                if ($("#validation_alert_error").hasClass("d-none")) {
                                    $("#validation_alert_error").removeClass("d-none")
                                }

                                $("#validation_alert_error_ul").empty()
                                var li = "<li>" + res.message + "</li>";
                                $("#validation_alert_error_ul").append(li);
                            }
                            if (res.message == "Invalid Paying User") {
                                if ($("#validation_alert_error").hasClass("d-none")) {
                                    $("#validation_alert_error").removeClass("d-none")
                                }
                                $("#validation_alert_error_ul").empty()
                                var li = "<li>" + res.message + "</li>";
                                $("#validation_alert_error_ul").append(li);
                            }

                        }
                    },
                    error: function(xhr, status, error) {
                        $("#submit_btn").attr("disabled", false)
                        console.log(xhr)
                        console.log(status)
                        console.log(error)
                    }
                });

            });
            $(document).on("click", ".click_default_keyword", function() {
                var keyword = $(this).attr("data-keyword").trim().toUpperCase(); // Normalize
                var inputField = $("#food_item");
                var currentVal = inputField.val().trim();

                // Convert input value into an array & trim each keyword
                var keywords = currentVal ? currentVal.split(",").map(k => k.trim().toUpperCase()) : [];

                // Find keyword index after normalization
                var index = keywords.indexOf(keyword);

                if (index === -1) {
                    // Add keyword if it doesn't exist
                    keywords.push(keyword);
                    $(this).find("span").removeClass("bg-grey").addClass("bg-dark");
                } else {
                    // Remove keyword if it already exists
                    keywords.splice(index, 1);
                    $(this).find("span").removeClass("bg-dark").addClass("bg-grey");
                }

                // Update input field, ensuring no extra commas or spaces
                inputField.val(keywords.join(", "));
            });

        });
    </script>
</body>

</html>
