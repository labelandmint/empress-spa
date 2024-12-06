@extends('layouts.app')
@section('content')
<div class="col-lg-9 es-py-8">
    <div class="mb-5">
        <div class="es-header-4 es-font-mulish-bold">Upcoming Bookings</div>
        <div class="es-text-gray-500 mt-2">Stay Informed About Your Next Appointments.</div>
    </div>
    <div class="card border-0 es-font-mulish">
        <div class="card-body px-4 pt-4 es-pb-12 ">
            <div class="d-flex justify-content-center es-pb-8">
                <input
                    type="text"
                    placeholder="Select Date.."
                    class="flatpickr es-flatpickr-inline"
                    hidden
                >
            </div>
            <div id="bookings-container" class="bookings-container d-none">
                <div id="current-month" class="es-text-lg es-font-700 es-mb-8"></div>
                <div class="d-grid gap-3" id="bookingData">
                    <!-- Booking data will be injected here dynamically -->
                </div>
            </div>
            <div id="no-bookings-message" class="es-pt-20">
                <div class="es-text-lg es-font-600 es-text-gray-900 es-mb-3 text-center">
                    It looks like you have no bookings in your calendar.
                </div>
                <div class="es-text-sm es-text-gray-500 es-mb-8 text-center">
                    Choose a service, and it’ll show up here.
                </div>
                <div class="text-center">
                    <a
                        href="{{url('services')}}"
                        class="es-btn"
                    >
                        Book a service
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Cancel Booking Modal -->
    <div
        class="modal fade"
        data-bs-backdrop="static"
        id="cancelBookingModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="cancelBookingModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="card border-0">
                    <div class="card-body">
                        <div
                            class="d-flex es-py-8 flex-column align-items-center justify-content-center es-gap-6"
                        >
                            <div class="es-bg-brown-500 rounded-circle es-h-16 es-w-16 d-flex justify-content-center align-items-center">
                                <img src="{{url('public/images/trash-white-large.png')}}" alt="" class="es-h-7" />
                            </div>
                            <div class="es-text-3xl es-font-mulish-bold">Cancel Booking</div>
                            <div class="es-text-gray-500 text-center col-8">
                                Are you sure you would like to cancel this booking?
                            </div>
                            <form action="{{url('booking/delete')}}" method="post">
                                @csrf
                            <div class="d-flex gap-3">
                                <input type="hidden" name="booking_id" id="booking_id">
                                <button
                                    type="button"
                                    data-bs-dismiss="modal"
                                    class="es-btn-outline es-w-md-auto"
                                >
                                    No
                                </button>
                                <button type="submit" class="es-btn es-w-md-auto">
                                    Confirm
                                </button>
                            </div>
                            </form>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- <script>

    document.getElementById("current-month").innerHTML = moment().format('MMMM YYYY')


    console.log(upcoming_bookings_dates);

    flatpickr(".flatpickr", {
        inline: true,
        minDate: "today",

        onDayCreate: function(dObj, dStr, fp, dayElem){

            let date = moment(dayElem.dateObj).format('YYYY-MM-DD')

            if (upcoming_bookings_dates.includes(date)) {
                dayElem.classList.add('booked')
            }
        }
    });

    $('.delete-booking').click(function(){
        var booking_id = $(this).data('id');
        $('#booking_id').val(booking_id);
    });
</script> -->


<script>
        let calendar;
        let calendarDateArray = [];
        document.getElementById("current-month").innerHTML = moment().format('MMMM YYYY');

        $(document).ready(function() {

            fetchBookings()
            // Make the AJAX call to fetch data from the server

        });

        const fetchBookings = (month, year) => {
            $('#bookingData').empty();
            let userId = @json($userId); // Get user ID from Laravel Blade
            let baseUrl = `{{ url('/booking/member') }}/${userId}`;

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
                                        <img src="{{ url('public/images/clock.png') }}" alt="">
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


$('.delete-booking').click(function () {
    var booking_id = $(this).data('id');
    $('#booking_id').val(booking_id);
});

</script>

@endsection
