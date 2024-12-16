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
		<title>Forgot Password</title>

		<!-- Required meta tags -->
		<meta charset="utf-8" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, shrink-to-fit=no"
		/>

		<!-- Bootstrap CSS v5.2.0 -->
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
			rel="stylesheet"
			integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx"
			crossorigin="anonymous"
		/>

		<!-- Datepicker -->
		<link
			rel="stylesheet"
			href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
		/>
		<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/plugins/monthSelect/style.min.css"
			integrity="sha512-V7B1IY1DE/QzU/pIChM690dnl44vAMXBidRNgpw0mD+hhgcgbxHAycRpOCoLQVayXGyrbC+HdAONVsF+4DgrZA=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"
		/>

		<!-- Odometer -->
		<link
			rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/odometer.js/0.4.7/themes/odometer-theme-default.css"
			integrity="sha512-kMPqFnKueEwgQFzXLEEl671aHhQqrZLS5IP3HzqdfozaST/EgU+/wkM07JCmXFAt9GO810I//8DBonsJUzGQsQ=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"
		/>

		<!-- Stylesheet -->
		<link rel="stylesheet" href="{{asset('css/style.css')}}" />

		@if($settings && $settings->page_background_image)
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
	    	.hero-text-content{
	    		width: 80%;
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
        }
	    </style>
	</head>
	<body
		class="es-bg-gray-50 es-text-gray-900 es-text-normal es-font-mulish onboarding-page position-relative"
	>
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
			<form action="{{url('password/send-link')}}" method="post">
                @csrf
				<div class="row col-reverse position-relative">
					<div class="col-lg-6 es-pt-20 es-pb-24">
						<div class="es-mb-3">
							<div class="es-header-3 es-font-600 es-mb-3">
								Forgot Password?
							</div>
						</div>
						<div class="es-mb-8">
							<div class="es-text-gray-500 es-mb-8">
								No problem. Just let us know your email address and we will
								email you a password reset link that will allow you to choose a
								new one.
							</div>
							<div class="es-mb-5">
								<input
									id="email_address"
                                    name="email"
									type="text"
									class="form-control es-input mt-2"
									placeholder="Email Address"
								/>
                                @error('email')
                                <div class="es-input-error-message">{{$message}}</div>
                                @enderror

							</div>
						</div>
						<div class="d-flex justify-content-between es-gap-6">
							<a href="{{url('/')}}" class="text-decoration-none es-btn-outline w-100 es-mb-8">
								Back
                            </a>
							<button type="submit" class="es-btn w-100 es-mb-8">
								Send a reset link
							</button>
						</div>
						@if (session('success'))
						<div class="d-flex align-items-center justify-content-center">
							<span class="es-text-green-500">
								{{session('success')}}
							</span>
						</div>
						@endif
					</div>
					<div class="col-lg-6 hero-text">
						<div class="hero-text-bg"></div>
						<div class="w-100 h-100">
							<div class="hero-text-content">
								<div class="es-mb-8">
									<img src="{{ url('images/'.$settings->logo) ?? asset('images/logo-white.svg')}}"  style="object-fit: contain"
                                    alt="" class="img600x100 img-fluid" />
								</div>
								<div class="es-mb-8">
									<div
										class="es-header-2 es-font-600 text-white es-font-inter es-mb-5"
									>
									@if($settings && $settings->title)
									{{$settings->title}}
									@else
									Limited Time Offer: Exclusive<br />Empress Spa Package
									@endif
									
									</div>
									<div class="es-text-xl text-white">
									@if($settings && $settings->subtitle)
									{{$settings->subtitle}}
									@else
									Only a Few Seats Left! Reserve Your Spot Today and<br />Indulge
											in Pure Relaxation!
									@endif
											
										
									</div>
								</div>
								<div class="d-flex es-mb-8">
									<div
										class="es-px-5 es-py-2 bg-white d-flex align-items-center gap-2 rounded-2"
									>
										<!--<span class="es-text-xl es-font-600">@if($settings && $settings->number){{$settings->number}} @else 2000 @endif</span>-->
									<span class="es-text-xl es-font-600">{{$remainingSeats}}</span>
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

		<!-- Bootstrap JavaScript Libraries -->
		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
			crossorigin="anonymous"
		></script>

		<!-- Datepicker -->
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
		<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

		<!-- Odometer -->
		<script
			src="https://cdnjs.cloudflare.com/ajax/libs/odometer.js/0.4.7/odometer.min.js"
			integrity="sha512-v3fZyWIk7kh9yGNQZf1SnSjIxjAKsYbg6UQ+B+QxAZqJQLrN3jMjrdNwcxV6tis6S0s1xyVDZrDz9UoRLfRpWw=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"
		></script>

		<script>
			flatpickr(".flatpickr", {
				plugins: [
					new monthSelectPlugin({
						shorthand: true,
						dateFormat: "m/y",
						altFormat: "F Y",
					}),
				],
			});

			// Input format: MM : DD : HH : MM : SS
        const countdownInput = "{{$settings->countdown_timer ?? '00 : 00 : 00 : 00 : 00'}}"; // Example input

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

			setTimeout(() => {
				const subscribersOdometer = document.querySelector(".odometer");
				createOdometer(subscribersOdometer, 45);
			}, 1000);
		</script>
	</body>
</html>