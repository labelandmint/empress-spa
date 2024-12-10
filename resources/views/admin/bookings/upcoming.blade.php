@extends('layouts.app')
@section('content')
    <div class="col-lg-9 es-py-8">
        <div class="mb-5 d-flex align-items-center gap-2 justify-content-between">
            <div>
                <div class="es-header-4 es-font-mulish-bold">Upcoming Bookings</div>
                <div class="es-text-gray-500 mt-2">Stay Informed About Your Next Appointments.</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="filter" class="es-font-600 es-text-gray-500 text-nowrap">
                    Filter by:
                </label>
                <select id="filter" class="form-select es-w-md-48 es-w-auto border-0 h-100 es-font-600 es-text-gray-900"
                    aria-label="Default select example">
                    <option value="" selected disabled>Select Service</option>
                    <option value="0">All</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card border-0 es-font-mulish">
            <div class="card-body px-4 pt-4 es-pb-12 ">
                <div class="d-flex justify-content-center es-pb-8">
                    <input type="text" placeholder="Select Date.." class="flatpickr es-flatpickr-inline" hidden>
                </div>
                <div id="bookings-container" class="bookings-container d-none">
                    <div id="current-month" class="es-text-lg es-font-700 es-mb-8"></div>
                    <div class="d-grid gap-3" id="bookingData">
                        <!-- Booking data will be injected here dynamically -->
                    </div>
                </div>

                <div id="no-bookings-message" class="es-pt-20">
                    <div class="es-text-lg es-font-600 es-text-gray-900 es-mb-3 text-center">
                        The selected member does not have bookings in the calendar.
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        let calendar;
        let calendarDateArray = [];
        document.getElementById("current-month").innerHTML = moment().format('MMMM YYYY');

        $(document).ready(function() {

            fetchBookings()
            // Make the AJAX call to fetch data from the server

        });

        const fetchBookings = (month, year) => {
            $('#filter').val('');
            $('#bookingData').empty();
            let userId = @json($userId); // Get user ID from Laravel Blade
            let baseUrl = `{{ url('/admin/bookings/user') }}/${userId}`;

            // Initialize query parameters
            let queryParams = [];

            // Append parameters only if they are defined
            if (month !== null && month !== undefined) {
                queryParams.push(`month=${month}`);
            }
            if (year !== null && year !== undefined) {
                queryParams.push(`year=${year}`);
            }

            // Construct the full URL with query parameters
            let url = queryParams.length ? `${baseUrl}?${queryParams.join('&')}` : baseUrl;
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    // Get the bookings and extract booking dates from the response
                    let bookings = response.bookings;
                    let current_month_dates = bookings.map(booking => moment(booking.booking_date)
                        .format('YYYY-MM-DD'));
                    // Convert calendarDateArray to a Set to automatically remove duplicates and then back to an array
                    calendarDateArray = [...new Set([...calendarDateArray, ...current_month_dates])];
                    // Initialize Flatpickr with dynamic booking dates
                    initializeFlatPicker()

                    // Check if there are any bookings
                    if (bookings.length > 0) {
                        // Show the bookings container and hide the "no bookings" message
                        $('#bookings-container').removeClass('d-none');
                        $('#no-bookings-message').addClass('d-none');

                        // Clear existing content
                        $('#bookingData').empty();

                        // Iterate through the bookings and append to the container
                        bookings.forEach(function(booking) {
                            let bookingDate = moment(booking.booking_date).format(
                                'dddd, D MMMM');
                            let startTime = moment(booking.booking_start_time, 'HH:mm:ss')
                                .format('h:mm A');
                            let endTime = moment(booking.booking_end_time, 'HH:mm:ss').format(
                                'h:mm A');

                            // Create booking item HTML
                            let bookingHtml = `
                        <div class="d-flex bookings justify-content-between es-p-5 es-bg-gray-100 es-rounded" data-date=${booking.booking_date} data-service=${booking.service_id}>
                            <div>
                                <div class="es-font-600 es-text-gray-900 es-mb-2">${bookingDate}</div>
                                <div class="es-text-sm es-text-gray-500 es-font-500">
                                    <b>${booking.name}</b><br />
                                    ${booking.description}
                                </div>
                            </div>

                            <div class="es-text-gray-900 es-font-500 d-flex flex-column flex-md-row align-items-end align-items-md-start">
                                <div class="d-flex">
                                    <div class="es-mr-2">
                                        <img src="{{ asset('images/clock.png') }}" alt="">
                                    </div>
                                    <div class="es-leading-6">${startTime} - ${endTime}</div>
                                </div>
                            </div>
                        </div>
                    `;

                            // Append the booking to the container
                            $('#bookingData').append(bookingHtml);
                        });
                    } else {
                        // No bookings found: Show the "no bookings" message and hide the container
                        $('#bookings-container').addClass('d-none');
                        $('#no-bookings-message').removeClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching bookings:", error);
                }
            });
        }

        const initializeFlatPicker = () => {

            // Check if the calendar is already initialized
            if (calendar) {
                // calendar.clear()
                updateBookedDates(calendarDateArray);
                return;
            }

            // Initialize the calendar
            calendar = flatpickr(".flatpickr", {
                inline: true,
                onDayCreate: function(dObj, dStr, fp, dayElem) {

                    let date = moment(dayElem.dateObj).format('YYYY-MM-DD');
                    if (calendarDateArray.includes(date)) {
                        dayElem.classList.add('booked');
                    }


                },
                onChange: function(selectedDates, dateStr, instance) {

                    hideByDate(dateStr)
                },
                onMonthChange: function(selectedDates, dateStr, instance) {

                    const month = instance.currentMonth + 1; // 0-indexed (0 = January, 11 = December)
                    const year = instance.currentYear;
                    instance.selectedDateElem?.classList.remove('selected');
                    document.getElementById("current-month").innerHTML = moment(`${year}-${month}`, 'YYYY-MM').format('MMMM YYYY');
                    fetchBookings(month, year)
                },
                onYearChange: function(selectedDates, dateStr, instance) {
                    const month = instance.currentMonth + 1; // 0-indexed (0 = January, 11 = December)
                    const year = instance.currentYear;
                    instance.selectedDateElem?.classList.remove('selected');
                    document.getElementById("current-month").innerHTML = moment(`${year}-${month}`, 'YYYY-MM').format('MMMM YYYY');
                    fetchBookings(month, year)
                },
            });
        }

        const updateBookedDates = (current_month_dates) => {
            // Find all day elements (days in the calendar)
            const dayElems = document.querySelectorAll(".flatpickr-day");

            // Iterate through each day element and update its 'booked' status
            dayElems.forEach(dayElem => {
                let date = moment(dayElem.dateObj).format('YYYY-MM-DD');
                if (current_month_dates.includes(date)) {
                    dayElem.classList.add('booked');
                } else {
                    dayElem.classList.remove(
                        'booked'); // Optionally remove the 'booked' class for unselected dates
                }
            });
        };

        $('#filter').on('change', function() {
            try {
                let selectedServiceId = $(this).val();
                if (selectedServiceId) {


                    if (selectedServiceId == 0) {
                        $('.bookings').removeClass('d-none'); // Show all bookings
                        $('#bookings-container').removeClass('d-none'); // Show bookings container
                        $('#no-bookings-message').addClass('d-none'); // Hide the 'no bookings' message
                        return; // Exit the function early since we're showing all bookings
                    }

                    let foundMatch = false;
                    $('.bookings').each(function() {
                        // Get the booking date from the data attribute
                        let serviceId = $(this).data('service');

                        // Show or hide the booking based on the selected date
                        if (serviceId == selectedServiceId) {
                            foundMatch = true;
                            $(this).removeClass('d-none');
                        } else {
                            $(this).addClass('d-none');

                        }
                        if (!foundMatch) {
                            $('#bookings-container').addClass('d-none');
                            $('#no-bookings-message').removeClass('d-none');
                        } else {
                            $('#bookings-container').removeClass('d-none');
                            $('#no-bookings-message').addClass('d-none');
                        }
                    });
                }
            } catch (error) {
                console.log(error)
            }

        });


        const hideByDate = (dateStr) => {
            if (dateStr && dateStr !== "") {
                $('#filter').val(''); // Clear the filter input field

                let foundMatch = false; // Flag to track if any match is found

                $('.bookings').each(function() {
                    // Get the booking date from the data attribute
                    let bookingDate = $(this).data('date');

                    // Show or hide the booking based on the selected date
                    if (bookingDate === dateStr) {
                        $(this).removeClass('d-none'); // Show the booking
                        foundMatch = true; // Mark that a match was found
                    } else {
                        $(this).addClass('d-none'); // Hide the booking
                    }
                });

                // If no match is found, show a "No bookings found" message
                if (!foundMatch) {
                    $('#bookings-container').addClass('d-none');
                    $('#no-bookings-message').removeClass('d-none');
                } else {
                    $('#bookings-container').removeClass('d-none');
                    $('#no-bookings-message').addClass('d-none');
                }
            }
        };
    </script>
@endsection
