@php
    $settings = DB::table('settings')->first();
    $fullUrl = $settings->business_website_address;

    // Check if the URL starts with http or https, if not add https://
    if (!preg_match('/^https?:\/\//', $fullUrl)) {
        $fullUrl = 'https://' . $fullUrl;
    }

    // Parse the URL to get just the host
    $parsedUrl = parse_url($fullUrl);
    $domain = isset($parsedUrl['host']) ? $parsedUrl['host'] : $fullUrl; // Fallback to full URL if parsing fails

@endphp
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <title>Create Account</title>

    <link rel="icon" href="{{ asset('logo.png') }}" type="image/x-icon">

    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous" />

    <!-- Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/plugins/monthSelect/style.min.css"
        integrity="sha512-V7B1IY1DE/QzU/pIChM690dnl44vAMXBidRNgpw0mD+hhgcgbxHAycRpOCoLQVayXGyrbC+HdAONVsF+4DgrZA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Odometer -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/odometer.js/0.4.7/themes/odometer-theme-default.css"
        integrity="sha512-kMPqFnKueEwgQFzXLEEl671aHhQqrZLS5IP3HzqdfozaST/EgU+/wkM07JCmXFAt9GO810I//8DBonsJUzGQsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Summernote  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css" rel="stylesheet">


    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />



    <script src="https://js.stripe.com/v3/"></script>

    @if ($settings && $settings->page_background_image)
    <style>
        .onboarding-page .hero-image {
            background: url("{{ url('images/'. $settings->page_background_image) }}");
            background-size: cover;
        }

        .onboarding-page .hero-text .hero-text-bg {
            background: url("{{  url('images/'. $settings->page_background_image) }}") !important;
            background-size: cover !important;
        }
    </style>
    @endif

    <style>
        .loader1 {
            position: fixed;
            top: 50%;
            z-index: 9999;
            width: 100%;
        }

        .loaderout {
            position: fixed;
            z-index: 999;
            height: 100vh;
            width: 100%;
            background-color: black;
            opacity: 48%;
        }

        h1 {
            margin-bottom: 50px;
            font-weight: 600;
            font-size: 40px;
        }

        .container {
            display: flex;
            column-gap: 11px;
            justify-content: center;
        }

        .container .dot {
            width: 25px;
            height: 25px;
            background-color: black;
            border-radius: 50%;
            animation: loading 1s infinite alternate;
        }

        .container .dot:nth-child(1) {
            background-color: #BB7E45;
            animation-delay: -0.25s;
        }

        .container .dot:nth-child(2) {
            background-color: #FFE4CD;
            animation-delay: -0.5s;
        }

        .container .dot:nth-child(3) {
            background-color: #BB7E45;
            animation-delay: -0.75s;
        }

        .container .dot:nth-child(4) {
            background-color: #FFE4CD;
            animation-delay: -1s;
        }

        @keyframes loading {
            0% {
                transform: translateY(-40px);
            }

            100% {
                transform: translateY(7px);
            }
        }

        label.error {
            color: red;
            font-size: 14px;
        }

        .hero-text-content {
            width: 80%;
        }

        #card-status {
            display: none;
            /* Hidden by default */
        }

        #card-status.visible {
            display: block;
            /* Show when visible */
        }


        @media only screen and (max-width: 400px) {


            .es-text-15xl {


                font-size: 22px !important;
            }


            .es-text-normal {


                font-size: 10px !important;
            }


            .es-gap-7 {
                gap: 1rem;
            }



            .logo1 {

                height: 1.2rem;
            }


        }


        @media only screen and (max-width:400px) {
            .custom-gap {
                gap: 0.6rem;
            }

            .custom-font-size {
                font-size: 24px !important;
            }

            .custom-font-size2 {
                font-size: 12px !important;
            }
        }

        @media only screen and (min-width: 401px) and (max-width: 600px) {
            .custom-gap {
                gap: 1rem;
            }

            .custom-font-size {
                font-size: 29px !important;
            }

            .custom-font-size2 {
                font-size: 17px !important;
            }
        }

        @media only screen and (max-width:1000px) {
            .col-reverse {
                flex-direction: column-reverse !important;
            }
        }

        @media only screen and (max-width:460px) {
            .block {
                display: block !important;
            }

            .mt {
                margin: 12px 0px!important;
            }

            .cust-size{
                font-size: 30px!important;
            }
        }
    </style>
</head>

<body class="es-bg-gray-50 es-text-gray-900 es-text-normal es-font-mulish onboarding-page position-relative">

    <div class="loaderout d-none"></div>
    <div class="loader1 d-none">
        <div class="container">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>

    <div class="es-navbar es-z-50">
        <nav class="navbar navbar-expand-lg es-h-20 bg-white es-drop-shadow">
            <div class="container-xxl">
                <div class="d-flex block align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <a href="#" class="">
                            <img src="{{ url('images/'.$settings->logo) }}" alt="" class="es-h-8" />
                        </a>
                    </div>
                    <div class="mt">
                        <a href="{{ $fullUrl }}" class="text-decoration-none es-text-gray-900 es-font-500">
                            <div class="d-flex align-items-center gap-2 gap-md-3">
                                <img src="{{ asset('images/left-arrow.png') }}" width="16" height="16"
                                    alt="" />
                                <div>
                                    <span class="d-none d-md-inline-block">Back to&nbsp;</span><span
                                        class="text-decoration-underline">{{ $domain }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div class="hero-image es-z-0"></div>
    <div class="container-xxl es-z-10 position-relative">
        <form id="payment-form" action="{{ url('/store') }}" method="POST">
            @csrf
            <div class="row col-reverse position-relative">
                <div class="col-lg-6 es-pt-20 es-pb-24">
                    <div class="es-mb-10 d-flex" style="justify-content: space-between;align-items:center;">
                        <div>
                            <div class="es-header-3 es-font-600 es-mb-3">
                                {{ $plan ? $plan->title : '' }}
                            </div>
                            <div class="es-text-gray-500">
                                {{ $plan ? $plan->description : '' }}
                            </div>
                        </div>
                        <div style="text-align:end;">
                            <p><span class="es-header-1 es-font-600 es-mb-3 cust-size"
                                    style="font-size:61px;">${{ $plan ? $plan->promo_price : '' }}</span>
                                <span
                                    class="es-header-5 es-font-400 es-mb-3">{{ $plan ? $plan->promo_period : '' }}</span>
                            </p>

                            <p class="es-text-gray-500">{{ $plan ? $plan->promo_sub_title : '' }}
                                {{ $plan ? $plan->promo_sub_title_price : '' }}</p>
                        </div>
                    </div>
                    <div class="es-mb-4 es-mb-6">
                        {{-- <input type="file" accept=”.jpg,.jpeg,.png” hidden id="photo_input" /> --}}
                        <div for="photo_input" class="es-file-input mt-2 es-h-48 p-0" id="photo-label">
                            <img src="{{ $plan ? url('images/'.$plan->photo) : '#' }}" style="width: 100%;height:100%">
                        </div>
                        {{-- <div class="d-none" id="file-preview-container">
                            <img
                                src="#"
                                alt="Preview Uploaded Image"
                                id="photo-preview"
                                class="es-h-80 es-mb-3 file-preview"
                            >
                            <div class="d-flex es-gap-8">
                                <label
                                    for="photo_input"
                                    class="btn border-0 es-text-sm es-font-600 p-0"
                                >
                                    Change
                                    <img src="{{asset('images/refresh.png')}}" width="14" height="14" alt="">
                                </label>
                                <button
                                    type="button"
                                    class="btn border-0 es-text-sm es-font-600 p-0"
                                    id="clear_photo_input"
                                >
                                    Delete
                                    <img src="{{asset('images/trash.png')}}" width="14" height="14" alt="">
                                </button>
                            </div>
                        </div> --}}
                    </div>
                    <div
                        class="es-bg-gray-100 es-border es-border-gray-500 es-border-solid es-font-500 es-px-6 es-py-5 rounded-2 es-mb-14">
                        <div class="d-flex justify-content-between">
                            <div>
                                Subscription Package
                            </div>
                            <div>
                                <ul class="list-unstyled m-0 d-grid gap-1">
                                    @foreach ($products as $product)
                                        <li>{{ $product->title }}</li>
                                    @endforeach
                                    @foreach ($services as $service)
                                        <li>{{ $service->title }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div>
                                Payment Frequency
                            </div>
                            <div>
                                @switch($plan->payment_frequency)
                                    @case(1)
                                        Weekly
                                    @break

                                    @case(2)
                                        Monthly
                                    @break

                                    @case(3)
                                        Quarterly
                                    @break

                                    @case(4)
                                        Half-Yearly
                                    @break

                                    @case(5)
                                        Yearly
                                    @break

                                    @default
                                        Unknown
                                @endswitch
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div>
                                Price inc. GST
                            </div>
                            <div style="text-align: end;">
                                ${{ number_format($plan->price_of_subscription, 2) }}
                                <p>Total Amount Paid Upfront </p>
                            </div>
                            <input type="hidden" name="amount" value="{{ $plan->price_of_subscription }}">
                        </div>
                    </div>
                    <div class="es-mb-3">
                        <div class="es-header-3 es-font-600 es-mb-3">
                            Create Account
                        </div>
                        <!--  @if ($errors->any())
                            <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
                                                            </ul>
                                                        </div>
                            @endif -->
                        <input type="hidden" name="stripeToken" id="stripeToken" />
                    </div>
                    @error('payment_error')
                        <div class="es-input-error-message">{{ $message }}</div>
                    @enderror
                    <div class="es-mb-8">
                        <div class="es-text-gray-500 es-mb-8">
                            Fill in the information below.
                        </div>
                        <div class="es-mb-5">
                            <input type="hidden" name="subscription_id" value="{{ $plan->id }}" />
                            <input id="first_name" name="f_name" type="text" class="form-control es-input mt-2"
                                placeholder="First Name" value="{{ old('f_name') }}" />
                            @error('f_name')
                                <div class="es-input-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="es-mb-5">
                            <input id="last_name" name="l_name" type="text" class="form-control es-input mt-2"
                                placeholder="Last Name" value="{{ old('l_name') }}" />
                            @error('l_name')
                                <div class="es-input-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="es-mb-5">
                            <input id="email_address" name="email" type="text"
                                class="form-control es-input mt-2" placeholder="Email Address / Username"
                                value="{{ old('email') }}" />
                            @error('email')
                                <div class="es-input-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="es-mb-5">
                            <input id="phone_number" name="phone_no" type="text"
                                class="form-control es-input mt-2" placeholder="Phone Number"
                                value="{{ old('phone_no') }}" />
                            @error('phone_no')
                                <div class="es-input-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="es-mb-5">
                            <div class=" d-flex">
                                <input id="password" name="password" type="password" class="form-control es-input"
                                    placeholder="Password" />
                                <button id="toggle-password" type="button"
                                    class="d-flex align-items-center bg-transparent border-0 es--ml-12 es-outline-none">
                                    <img src="{{ asset('images/eye-dark.png') }}"
                                        alt="Toggle Password Visibility" />
                                </button>
                            </div>
                            @error('password')
                                <div class="es-input-error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="es-mb-5">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="form-control es-input" placeholder="Confirm Password" />
                        </div>
                    </div>
                    <div class="es-mb-10">
                        <div class="es-text-gray-500 es-mb-4">
                            Payment Information.
                        </div>
                        <input type="hidden" name="afterpay_token" id="afterpay_token" />

                        <div id="card-status" role="alert"></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="es-mb-5">
                                    <select id="payment_method" name="payment_method"
                                        class="form-control es-input mt-2">
                                        <option value="">Select Payment Method</option>
                                        {{-- <option value="1">Stripe</option> --}}
                                        <option value="2">Credit/Debit Cards (Square)</option>
                                        <option value="3">Afterpay (Square)</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="es-input-error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div id="card-element" class="form-control d-none es-input mt-2"></div>
                        <div class="row hidee">
                            <div class="col-lg-6">
                                <div class="es-mb-5">
                                    <input id="payment_name" name="name_on_card" type="text"
                                        class="form-control es-input mt-2" placeholder="Name"
                                        value="{{ old('name_on_card') }}" />
                                    @error('name_on_card')
                                        <div class="es-input-error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="es-mb-5">
                                    <input id="card_number" name="card_number" type="text"
                                        class="form-control es-input mt-2" placeholder="Card Number"
                                        value="{{ old('card_number') }}" />
                                    @error('card_number')
                                        <div class="es-input-error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row hidee">
                            <div class="col-lg-6">
                                <div class="es-mb-5">
                                    <input id="expiry" name="expiry" type="text"
                                        class="form-control es-input flatpickr mt-2" placeholder="Expiry (MM/YY)"
                                        value="{{ old('expiry') }}" />
                                    @error('expiry')
                                        <div class="es-input-error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="es-mb-5">
                                    <input id="ccv" name="ccv" type="text"
                                        class="form-control es-input mt-2" placeholder="CCV"
                                        value="{{ old('ccv') }}" />
                                    @error('ccv')
                                        <div class="es-input-error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="acceptTerms"
                                        value="1" id="acceptTerms">
                                    <a href="#" id="termsModalTrigger" style="color:black;">
                                        <label class="form-check-label es-pl-1 text-decoration-underline es-font-500"
                                            for="acceptTerms">
                                            Acknowledge Payment Terms and Conditions
                                        </label>
                                    </a>
                                    @error('acceptTerms')
                                        <div class="es-input-error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div id="afterpay-button" class="mb-2"></div>
                        <button type="submit" id="create-account-button" class="es-btn w-100 es-mb-8">
                            Create Account
                        </button>
                        <div class="d-flex align-items-center justify-content-center">
                            <div>
                                Already a member?&nbsp;
                            </div>
                            <div>
                                <a href="{{ url('/') }}" class="es-link-primary">
                                    Log in
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-text">
                    <div class="hero-text-bg"></div>
                    <div class="w-100 h-100">
                        <div class="hero-text-content">
                            <div class="es-mb-8">
                                <img src="{{ url('images/'.$settings->logo) ?? asset('images/logo-white.svg') }}"  style="object-fit: contain"
                                    alt="" class="img600x100 img-fluid" />
                            </div>
                            <div class="es-mb-8">
                                <div class="es-header-2 es-font-600 text-white es-font-inter es-mb-5">
                                    @if ($settings && $settings->title)
                                        {{ $settings->title }}
                                    @else
                                        Limited Time Offer: Exclusive<br />Empress Spa Package
                                    @endif

                                </div>
                                <div class="es-text-xl text-white">
                                    @if ($settings && $settings->subtitle)
                                        {{ $settings->subtitle }}
                                    @else
                                        Only a Few Seats Left! Reserve Your Spot Today and<br />Indulge
                                        in Pure Relaxation!
                                    @endif


                                </div>
                            </div>
                            <div class="d-flex es-mb-8">
                                <div class="es-px-5 es-py-2 bg-white d-flex align-items-center gap-2 rounded-2">
                                    <!-- <span class="es-text-xl es-font-600">
                                        @if ($settings && $settings->number)
{{ $settings->number }}
@else
2000
@endif
                                    </span> -->
                                    <span class="es-text-xl es-font-600">
                                        {{ $remainingSeats }}
                                    </span>
                                    <span>Seats Remaining</span>
                                </div>
                            </div>

                            <div class="text-white d-flex es-gap-7 custom-gap text-center">
                                <div>
                                    <div class="es-text-15xl custom-font-size es-font-500 es-font-inter odometer"
                                        id="months">00</div>
                                    <div class="es-font-500 custom-font-size2">MONTHS</div>
                                </div>
                                <div>
                                    <div class="es-text-15xl custom-font-size es-font-500 es-font-inter odometer"
                                        id="days">00</div>
                                    <div class="es-font-500 custom-font-size2">DAYS</div>
                                </div>
                                <div>
                                    <div class="es-text-15xl custom-font-size es-font-500 es-font-inter odometer"
                                        id="hours">00</div>
                                    <div class="es-font-500 custom-font-size2">HOURS</div>
                                </div>
                                <div>
                                    <div class="es-text-15xl custom-font-size es-font-500 es-font-inter odometer"
                                        id="minutes">00</div>
                                    <div class="es-font-500 custom-font-size2">MINUTES</div>
                                </div>
                                <div>
                                    <div class="es-text-15xl custom-font-size es-font-500 es-font-inter odometer"
                                        id="seconds">00</div>
                                    <div class="es-font-500 custom-font-size2">SECONDS</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="successModal" tabindex="-1" role="dialog"
        aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div
                        class="card-body d-flex align-items-center justify-content-center es-py-24 mt-3 flex-column es-gap-6">
                        <img src="{{ asset('images/logo.svg') }}" alt="" class="" />
                        <div class="es-text-3xl es-font-mulish-bold">Congratulations!</div>
                        <div class="es-text-gray-500 text-center es-w-auto es-w-md-96 es-mb-4">
                            We're pleased to have you as an Empress Spa Member, and look forward to meeting you.
                            <br><br>
                            To get started with Booking, we've emailed you a Verification Email to confirm your
                            Membership, together with your Login Information.
                        </div>
                        <div>
                            <button type="button" class="es-btn es-w-full es-w-md-auto" data-bs-dismiss="modal">
                                Close Message
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Payment terms and conditions modal -->

    <div class="modal fade" data-bs-backdrop="static" id="termsModal" tabindex="-1" role="dialog"
        aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ asset('images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div
                        class="card-body d-flex align-items-center justify-content-center es-py-12 mt-3 flex-column es-gap-6">
                        <img src="{{ asset('images/logo.svg') }}" alt="" class="" />
                        <div class="es-text-3xl es-font-mulish-bold">Payment Terms and Conditions</div>
                        <div class="es-text-gray-500 text-center es-w-auto es-w-md-96 es-mb-4"
                            style="width:35rem; height: 400px; overflow-y: scroll;">
                            {!! $settings->terms_condition !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
    </script>

    <!-- Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

    <!-- Odometer -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/odometer.js/0.4.7/odometer.min.js"
        integrity="sha512-v3fZyWIk7kh9yGNQZf1SnSjIxjAKsYbg6UQ+B+QxAZqJQLrN3jMjrdNwcxV6tis6S0s1xyVDZrDz9UoRLfRpWw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>

    <script>
        // Hide fields when selecting afterpay (Square)

        $(document).ready(function() {
            $('#payment_method').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue === '3') {
                    $('.hidee').fadeOut(300); // Hides the div with a fade-out effect over 300 milliseconds
                } else if (selectedValue === '2') {
                    $('.hidee').fadeIn(300); // Shows the div with a fade-in effect over 300 milliseconds
                }
            });
        });




        // Modal box displaying information 

        $(document).ready(function() {
            $('#termsModalTrigger').click(function(event) {
                event.preventDefault(); // Prevent the default anchor click behavior
                $('#termsModal').modal('show'); // Show the terms modal
            });
        });




        document.addEventListener('DOMContentLoaded', async () => {
            const paymentForm = document.getElementById('payment-form');
            const cardContainer = document.getElementById('card-element');
            const paymentStatus = document.getElementById('card-status');
            const createAccountButton = document.getElementById('create-account-button');
            const paymentMethodSelect = document.getElementById('payment_method');



            // Event listener for payment method selection
            paymentMethodSelect.addEventListener('change', function() {
                const selectedMethod = this.value;

                // Show or hide elements based on the selected payment method
                if (selectedMethod === '1' || selectedMethod === '2') {
                    createAccountButton.disabled = false; // Enable for Stripe or Square
                    cardContainer.classList.remove('d-none'); // Show card input
                    document.getElementById('afterpay-button').classList.add(
                    'd-none'); // Hide Afterpay button
                    initializePaymentMethod(selectedMethod); // Initialize selected payment method

                } else if (selectedMethod === '3') { // Afterpay
                    createAccountButton.disabled = true; // Disable Create Account button initially
                    document.getElementById('afterpay-button').classList.remove('d-none'); // 
                    cardContainer.classList.add('d-none'); // Hide card input
                    initializeAfterpay(); // Initialize Afterpay
                } else {
                    createAccountButton.disabled =
                    true; // Disable Create Account button for no selection
                    cardContainer.classList.add('d-none'); // Hide card input
                    document.getElementById('afterpay-button').classList.add(
                    'd-none'); // Hide Afterpay button
                }
            });

            // Function to initialize Stripe or Square
            async function initializePaymentMethod(method) {
                cardContainer.innerHTML = '';
                if (method === '1') { // Stripe
                    const stripe = Stripe("{{ get_setting('stripe_publishable_key') }}");
                    const elements = stripe.elements();
                    const cardElement = elements.create('card');
                    cardElement.mount(cardContainer);

                    paymentForm.addEventListener('submit', async (event) => {
                        event.preventDefault();
                        if (!$("#payment-form").valid()) return; // Validate the form

                        const {
                            paymentMethod: pm,
                            error
                        } = await stripe.createPaymentMethod({
                            type: 'card',
                            card: cardElement,
                        });

                        if (error) {
                            paymentStatus.textContent = error.message;
                            paymentStatus.classList.add('visible', 'text-danger');
                        } else {
                            handleSuccessfulPayment(pm.id);
                        }
                    });
                } else if (method === '2') { // Square
                    const payments = Square.payments("{{ get_setting('square_application_id') }}");
                    const card = await payments.card();
                    await card.attach(cardContainer);

                    paymentForm.addEventListener('submit', async (event) => {
                        event.preventDefault();
                        if (!$("#payment-form").valid()) return; // Validate the form

                        const {
                            token,
                            error
                        } = await card.tokenize();

                        if (error) {
                            paymentStatus.textContent = error.message;
                            paymentStatus.classList.add('visible', 'text-danger');
                        } else {
                            handleSuccessfulPayment(token);
                        }
                    });
                }
            }

            // Function to initialize Afterpay
            async function initializeAfterpay() {

                const afterpayButtonEle = document.getElementById('afterpay-button');
                afterpayButtonEle.innerHTML = ''; // Clear existing content


                const application_id = "{{ get_setting('square_application_id') }}";
                const location_id = "{{ get_setting('square_location_id') }}";
                const amount = "{{ $plan->price_of_subscription }}";

                const payments = Square.payments(application_id, location_id);
                const paymentRequest = payments.paymentRequest({
                    countryCode: 'US',
                    currencyCode: 'USD',
                    total: {
                        amount: amount,
                        label: 'Total',
                    },
                    requestShippingContact: true,
                });

                paymentRequest.addEventListener('afterpay_shippingaddresschanged', function() {
                    return {
                        shippingOptions: [{
                            amount: '0.00',
                            id: 'shipping-option-1',
                            label: 'Free',
                            total: {
                                amount: amount,
                                label: 'Total',
                            },
                        }],
                    };
                });

                let afterpay;
                try {
                    afterpay = await payments.afterpayClearpay(paymentRequest);
                    await afterpay.attach('#afterpay-button');
                } catch (error) {
                    handleError(error);
                }


                const afterpayButton = document.getElementById('afterpay-button');
                afterpayButton.addEventListener('click', async function(event) {
                    event.preventDefault();
                    if (!$("#payment-form").valid()) return; // Validate the form

                    try {
                        const tokenResult = await afterpay.tokenize();
                        if (tokenResult.status === 'OK') {
                            $('#afterpay_token').val(tokenResult.token);
                            paymentStatus.textContent =
                                'Payment has been completed successfully. Now you can create your account!';
                            paymentStatus.classList.add('visible', 'text-success');
                            // Enable the Create Account button
                            createAccountButton.disabled = false;
                            createAccountButton.addEventListener('click', function() {
                                if ($("#payment-form")
                                .valid()) { // Validate the form before submission
                                    paymentForm
                                .submit(); // Submit the form to create the account
                                }
                            });
                            afterpayButton.classList.add('d-none');
                        } else {
                            handleError(tokenResult);
                        }
                    } catch (e) {
                        handleError(e);
                    }
                });
            }

            // Helper function to handle successful payments
            function handleSuccessfulPayment(token) {
                paymentStatus.textContent = '';
                paymentStatus.classList.remove('visible', 'text-danger');
                document.getElementsByClassName('loaderout')[0].classList.remove('d-none');
                document.getElementsByClassName('loader1')[0].classList.remove('d-none');

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = (paymentMethodSelect.value === '1') ? 'stripeToken' : 'nonce';
                tokenInput.value = token;

                paymentForm.appendChild(tokenInput);
                paymentForm.submit();
            }

            // Helper function to handle errors
            function handleError(error) {
                let errorMessage = typeof error === 'string' ? error : error.message;
                paymentStatus.textContent = `Payment Failed: ${errorMessage}`;
                paymentStatus.classList.add('visible', 'text-danger');
            }
        });

        // jQuery Validation for the form
        $(document).ready(function() {
            $("#payment-form").validate({
                rules: {
                    f_name: {
                        required: true,
                        minlength: 2
                    },
                    l_name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone_no: {
                        required: true,
                        digits: true,
                        minlength: 8,
                        maxlength: 15
                    },
                    payment_method: {
                        required: true
                    },
                    name_on_card: {
                        required: true
                    },
                    expiry: {
                        required: true,
                        minlength: 5
                    },
                    ccv: {
                        required: true,
                        digits: true,
                        minlength: 3,
                        maxlength: 4
                    },
                    // password: {
                    //     required: true,
                    //     minlength: 6
                    // }
                },
                messages: {
                    f_name: {
                        required: "Please enter your first name.",
                        minlength: "Your first name must be at least 2 characters long."
                    },
                    l_name: {
                        required: "Please enter your last name.",
                        minlength: "Your last name must be at least 2 characters long."
                    },
                    email: {
                        required: "Please enter your email address.",
                        email: "Please enter a valid email address."
                    },
                    phone_no: {
                        required: "Please enter your phone number.",
                        digits: "Please enter only digits.",
                        minlength: "Your phone number must be at least 8 digits long.",
                        maxlength: "Your phone number cannot exceed 15 digits."
                    },
                    payment_method: {
                        required: "Please select a payment method.",
                    },
                    name_on_card: {
                        required: "Please enter the name on the card."
                    },
                    expiry: {
                        required: "Please enter the expiry date.",
                        minlength: "Please enter a valid expiry date (MM/YY)."
                    },
                    ccv: {
                        required: "Please enter the CCV.",
                        digits: "Please enter only digits.",
                        minlength: "CCV must be at least 3 digits long.",
                        maxlength: "CCV cannot exceed 4 digits."
                    },
                    // password: {
                    //     required: "Please enter a password.",
                    //     minlength: "Your password must be at least 6 characters long."
                    // }
                },
                submitHandler: function(form) {
                    // You can add any pre-submit actions here if needed
                }
            });
        });
    </script>



    <script>
        flatpickr(".flatpickr", {
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "m/y",
                    altFormat: "F Y",
                })
            ]
        });

        // Input format: MM : DD : HH : MM : SS
        const countdownInput = "{{ $settings->countdown_timer ?? '00 : 00 : 00 : 00 : 00' }}"; // Example input

        // Parse the countdown timer into its components
        const [mm, dd, hh, min, ss] = countdownInput.split(' : ').map(Number);

        // Get the current date and time
        const now = new Date();

        // Create the target date
        const targetDate = new Date(now.getFullYear(), mm - 1, dd, hh, min, ss);

        // If the target date is in the past, set it for next year
        if (targetDate < now) {
            targetDate.setFullYear(now.getFullYear() + 1);
        }

        const createOdometer = (el, value) => {
            const odometer = new Odometer({
                el: el,
                value: value,
            });

            let hasRun = false;

            const options = {
                threshold: [0, 0.9],
            };

            const callback = (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        if (!hasRun) {
                            odometer.update(value);
                            hasRun = true;
                        }
                    }
                });
            };

            const observer = new IntersectionObserver(callback, options);
            observer.observe(el);
        };

        function updateTimer() {
            const currentTime = new Date();
            const elapsedTime = targetDate - currentTime; // Calculate how much time is left

            if (elapsedTime < 0) {
                // If the target time has passed, set all values to zero
                createOdometer(document.getElementById('months'), 0);
                createOdometer(document.getElementById('days'), 0);
                createOdometer(document.getElementById('hours'), 0);
                createOdometer(document.getElementById('minutes'), 0);
                createOdometer(document.getElementById('seconds'), 0);
                return; // Exit the function if the target has been reached
            }

            // Calculate remaining time components
            const totalSeconds = Math.floor(elapsedTime / 1000);

            const seconds = totalSeconds % 60; // Remaining seconds
            const totalMinutes = Math.floor(totalSeconds / 60);
            const minutes = totalMinutes % 60; // Remaining minutes
            const totalHours = Math.floor(totalMinutes / 60);
            const hours = totalHours % 24; // Remaining hours
            const totalDays = Math.floor(totalHours / 24);

            // Calculate remaining months and days (simple approximation)
            const months = Math.floor(totalDays / 30); // Rough approximation for months
            const days = totalDays % 30; // Remaining days

            // Update the HTML with the calculated values
            createOdometer(document.getElementById('months'), months);
            createOdometer(document.getElementById('days'), days);
            createOdometer(document.getElementById('hours'), hours);
            createOdometer(document.getElementById('minutes'), minutes);
            createOdometer(document.getElementById('seconds'), seconds);
        }

        // Update the timer every second
        updateTimer(); // Initial call to display the timer immediately

        setInterval(updateTimer, 1000);



        document.addEventListener("DOMContentLoaded", function() {
            function togglePasswordVisibility(
                inputId,
                buttonId,
                eyeDarkSrc,
                eyeLightSrc,
            ) {
                const passwordInput = document.getElementById(inputId);
                const toggleButton = document.getElementById(buttonId);
                const eyeIcon = toggleButton.querySelector("img");

                toggleButton.addEventListener("click", function() {
                    if (passwordInput.type === "password") {
                        passwordInput.type = "text";
                        eyeIcon.src = eyeLightSrc;
                    } else {
                        passwordInput.type = "password";
                        eyeIcon.src = eyeDarkSrc;
                    }
                });
            }

            togglePasswordVisibility(
                "password",
                "toggle-password",
                "{{ asset('images/eye-dark.png') }}",
                "{{ asset('images/eye-off-dark.png') }}",
            );
        });
    </script>
</body>

</html>
