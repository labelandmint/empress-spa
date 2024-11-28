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
            <div class="{{count($bookings) > 0 ? '' : 'd-none'}}">
                <div id="current-month" class="es-text-lg es-font-700 es-mb-8">
                </div>
                <div class="d-grid gap-3">
                    @foreach($bookings as $booking)
                        <div class="d-flex justify-content-between es-p-5 es-bg-gray-100 es-rounded">
                            <div>
                                <div class="es-font-600 es-text-gray-900 es-mb-2">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('l, jS F') }}
                                </div>
                                <div class="es-text-sm es-text-gray-500 es-font-500">
                                    {{$booking->description}}
                                </div>
                            </div>

                            <div class="es-text-gray-900 es-font-500 d-flex flex-column flex-md-row align-items-end align-items-md-start">
                                <div class="d-flex">
                                    <div class="es-mr-2">
                                        <img src="{{url('/images/clock.png')}}" alt="">
                                    </div>
                                    <div class="es-leading-6">
                                        {{ \Carbon\Carbon::parse($booking->booking_start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($booking->booking_end_time)->format('g:i A') }}
                                    </div>
                                </div>
                                <div class="es-ml-5">
                                    <button
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#cancelBookingModal"
                                        class="outline-none border-0 delete-booking"
                                        data-id="{{$booking->id}}"
                                    >
                                        <img src="{{url('/images/trash.png')}}" alt="">
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <div class="es-pt-20 {{count($bookings) > 0 ? 'd-none' : ''}}">
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
                                <img src="{{url('/images/trash-white-large.png')}}" alt="" class="es-h-7" />
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

    let upcoming_bookings_dates = @json($bookingDates);
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
   document.getElementById("current-month").innerHTML = moment().format('MMMM YYYY');

flatpickr(".flatpickr", {
    inline: true,
    minDate: "today",
    onDayCreate: function (dObj, dStr, fp, dayElem) {
        let date = moment(dayElem.dateObj).format('YYYY-MM-DD');
        let current_month_dates = @json($bookingDates); 
        if (current_month_dates.includes(date)) {
            dayElem.classList.add('booked');
        }
    }
});

$('.delete-booking').click(function () {
    var booking_id = $(this).data('id');
    $('#booking_id').val(booking_id);
});

</script>

@endsection
