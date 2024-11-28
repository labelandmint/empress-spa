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

    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="{{ url('/logo.png') }}" type="image/x-icon">
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

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ url('/css/style.css') }}" />

    <script src="https://js.stripe.com/v3/"></script>


    <style>
        .loader1{
          position: fixed;
          top:50%;
          z-index:9999;
          width: 100%;
        }
        .loaderout{
            position: fixed;
            z-index:999;
            height: 100vh;
            width: 100%;
            background-color: black;
            opacity:48%;
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
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <a href="#" class="">
                            <img src="{{ url('/images/logo.svg') }}" alt="" class="es-h-8" />
                        </a>
                    </div>
                    <div>
                        <a href="{{ $fullUrl }}" class="text-decoration-none es-text-gray-900 es-font-500">
                            <div class="d-flex align-items-center gap-2 gap-md-3">
                                <img src="{{ url('/images/left-arrow.png') }}" width="16" height="16"
                                    alt="">
                                <div>
                                    <span class="d-none d-md-inline-block">Back to&nbsp;</span><span
                                        class="text-decoration-underline">{{ $domain}}</span>
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
        <form action="">
                <div class="row position-relative">
                    <div class="col-lg-6 es-pt-20 es-pb-24">
                        <div class="es-mb-3">
                            <div class="es-header-3 es-font-600 es-mb-3">
                                Create Account
                            </div>
                        </div>
                        <div class="es-mb-8">
                            <div class="es-text-gray-500 es-mb-8">
                                Fill in the information below.
                            </div>
                            <div class="es-mb-5">
                                <input
                                    id="first_name"
                                    type="text"
                                    class="form-control es-input mt-2"
                                    placeholder="First Name"
                                />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="es-mb-5">
                                <input
                                    id="last_name"
                                    type="text"
                                    class="form-control es-input mt-2"
                                    placeholder="Last Name"
                                />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="es-mb-5">
                                <input
                                    id="email_address"
                                    type="text"
                                    class="form-control es-input mt-2"
                                    placeholder="Email Address / Username"
                                />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="es-mb-5">
                                <input
                                    id="phone_number"
                                    type="text"
                                    class="form-control es-input mt-2"
                                    placeholder="Phone Number"
                                />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="es-mb-5 d-flex">
                                <input
                                    id="password"
                                    type="password"
                                    class="form-control es-input"
                                    placeholder="Password"
                                />
                                <button
                                    id="toggle-password"
                                    type="button"
                                    class="d-flex align-items-center bg-transparent border-0 es--ml-12 es-outline-none"
                                >
                                    <img
                                        src="{{url('/images/eye-dark.png')}}"
                                        alt="Toggle Password Visibility"
                                    />
                                </button>
                                <div class="es-input-error-message"></div>
                            </div>
                        </div>
                        <div class="es-mb-10">
                            <div class="es-text-gray-500 es-mb-4">
                                Payment Information.
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="es-mb-5">
                                        <input
                                            id="payment_name"
                                            type="text"
                                            class="form-control es-input mt-2"
                                            placeholder="Name"
                                        />
                                        <div class="es-input-error-message"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="es-mb-5">
                                        <input
                                            id="card_number"
                                            type="text"
                                            class="form-control es-input mt-2"
                                            placeholder="Card Number"
                                        />
                                        <div class="es-input-error-message"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="es-mb-5">
                                        <input
                                            id="expiry"
                                            type="text"
                                            class="form-control es-input flatpickr mt-2"
                                            placeholder="Expiry (MM/YY)"
                                        />
                                        <div class="es-input-error-message"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="es-mb-5">
                                        <input
                                            id="ccv"
                                            type="text"
                                            class="form-control es-input mt-2"
                                            placeholder="CCV"
                                        />
                                        <div class="es-input-error-message"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button
                                type="button"
                                class="es-btn w-100 es-mb-8"
                                data-bs-toggle="modal"
                                data-bs-target="#successModal"
                            >
                                Create Account
                            </button>
                            <div class="d-flex align-items-center justify-content-center">
                                <div>
                                    Already a member?&nbsp;
                                </div>
                                <div>
                                    <a
                                        href="#"
                                        class="es-link-primary"
                                    >
                                        Log in
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="col-lg-6 hero-text"
                    >
                        <div class="hero-text-bg"></div>
                        <div class="w-100 h-100">
                            <div class="hero-text-content">
                                <div class="es-mb-8">
                                    <img src="../../assets/logo-white.svg" alt="">
                                </div>
                                <div class="es-mb-8">
                                    <div class="es-header-2 es-font-600 text-white es-font-inter es-mb-5">
                                        Limited Time Offer: Exclusive<br>Empress Spa Package
                                    </div>
                                    <div class="es-text-xl text-white">
                                        Only a Few Seats Left! Reserve Your Spot Today and<br>Indulge in Pure Relaxation!
                                    </div>
                                </div>
                                <div class="d-flex es-mb-8">
                                    <div class="es-px-5 es-py-2 bg-white d-flex align-items-center gap-2 rounded-2">
                                        <span class="es-text-xl es-font-600">2000</span>
                                        <span>Seats Remaining</span>
                                    </div>
                                </div>
                                <div class="text-white d-flex es-gap-7 text-center">
                                    <div>
                                        <div class="es-text-15xl es-font-500 es-font-inter">
                                            02
                                        </div>
                                        <div class="es-font-500">
                                            MONTHS
                                        </div>
                                    </div>
                                    <div>
                                        <div class="es-text-15xl es-font-500 es-font-inter">
                                            14
                                        </div>
                                        <div class="es-font-500">
                                            DAYS
                                        </div>
                                    </div>
                                    <div>
                                        <div class="es-text-15xl es-font-500 es-font-inter">
                                            20
                                        </div>
                                        <div class="es-font-500">
                                            HOURS
                                        </div>
                                    </div>
                                    <div>
                                        <div class="es-text-15xl es-font-500 es-font-inter odometer">
                                            44
                                        </div>
                                        <div class="es-font-500">
                                            MINUTES
                                        </div>
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
                    <img src="{{ url('/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div
                        class="card-body d-flex align-items-center justify-content-center es-py-24 mt-3 flex-column es-gap-6">
                        <img src="{{url('/images/logo.svg')}}" alt="" class="" />
                        <div class="es-text-3xl es-font-mulish-bold">Congratulations!</div>
                        <div class="es-text-gray-500 text-center es-w-auto es-w-md-96 es-mb-4">
                            We're pleased to have you as an Empress Spa Member, and look forward to meeting you.
                            <br><br>
                            To get started with Booking, we've emailed you a Verification Email to confirm your
                            Membership, together with your Login Information.
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

        // Reference: https://github.com/livebloggerofficial/Counter-Animation
        const createOdometer = (el, value) => {
            const odometer = new Odometer({
                el: el,
                value: 44,
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

        setTimeout(() => {
            const subscribersOdometer = document.querySelector(".odometer");
            createOdometer(subscribersOdometer, 45);
        }, 1000);

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
                "{{ url('/images/eye-dark.png') }}",
                "{{ url('/images/eye-off-dark.png') }}",
            );
        });

        document.addEventListener('DOMContentLoaded', function() {
            var stripe = Stripe("{{get_setting('stripe_publishable_key')}}"); // Replace with your own key
            var elements = stripe.elements();
            var cardElement = elements.create('card');
            cardElement.mount('#card-element');

            var form = document.getElementById('payment-form');

            // jQuery Validation
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
                            minlength: 10,
                            maxlength: 15
                        },
                        // password: {
                        //     required: true,
                        //     minlength: 6
                        // },
                        // password_confirmation: {
                        //     required: true,
                        //     equalTo: "#password"
                        // },
                        name_on_card: {
                            required: true
                        },
                        card_number: {
                            required: true,
                            creditcard: true
                        },
                        expiry: {
                            required: true,
                            minlength: 5 // MM/YY format
                        },
                        ccv: {
                            required: true,
                            digits: true,
                            minlength: 3,
                            maxlength: 4
                        }
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
                            minlength: "Your phone number must be at least 10 digits long.",
                            maxlength: "Your phone number cannot exceed 15 digits."
                        },
                        // password: {
                        //     required: "Please provide a password.",
                        //     minlength: "Your password must be at least 6 characters long."
                        // },
                        // password_confirmation: {
                        //     required: "Please confirm your password.",
                        //     equalTo: "Password and confirmation do not match."
                        // },
                        name_on_card: {
                            required: "Please enter the name on the card."
                        },
                        card_number: {
                            required: "Please enter your card number.",
                            creditcard: "Please enter a valid card number."
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
                        }
                    },
                    submitHandler: function() {
                        // If the form is valid, create a token
                        document.getElementsByClassName('loaderout')[0].classList.remove('d-none');
                        document.getElementsByClassName('loader1')[0].classList.remove('d-none');

                        stripe.createToken(cardElement).then(function(result) {
                            if (result.error) {
                                // Show error in the UI
                                var errorElement = document.getElementById('card-errors');
                                errorElement.textContent = result.error.message;
                            } else {
                                // Send the token to your server
                                var hiddenInput = document.getElementById('stripeToken');
                                hiddenInput.value = result.token.id;
                                form.submit(); // Submit the form with the token
                            }
                        });
                    }
                });
            });
        });


    </script>
</body>
</html>
