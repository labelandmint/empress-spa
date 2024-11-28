@extends('layouts.app')
@section('content')
<style>
.form-check-input:focus {
    border-color: black;
    outline: 0;
    box-shadow: 0 0 0 .25rem #f8f1eb;
}

.form-check-input:checked {
    background-color: #bb7e45;
    border-color: #bb7e45;ack;
}
</style>

@if(session('modal'))
    <script>
        $(document).ready(function() {
            $('#successModal').modal('show');
        });
    </script>
@endif

@if ($errors->has('error'))
    <script>
        $(document).ready(function() {
            $('#errorModal').modal('show');
        });
    </script>
@endif

<div class="col-lg-9 es-py-8">
    <div class="mb-5">
        <div class="es-header-4 es-font-mulish-bold">Services</div>
        <div class="es-text-gray-500 mt-2"><b>Note:</b> You can select only one service at a time.</div>
        <div class="es-text-gray-500 mt-2 d-none text-danger" id="serviceError">Please select a service to proceed.</div>

    </div>
    <div class="card border-0">
        <div class="card-body px-4 pt-4 es-pb-12">
            <form action="{{url('booking/store')}}" method="post" class="es-multi-step-form">
                @csrf
                <div class="es-progressbar gap-0 gap-md-5 d-flex align-items-center justify-content-between justify-content-md-start">
                    <div class="es-step d-flex flex-column flex-md-row align-items-center">
                        <span class="es-step-number">
                            1
                        </span>
                        <span class="es-step-title">
                            Service
                        </span>
                    </div>
                    <hr class="d-none d-md-block">
                    <div class="es-step d-flex flex-column flex-md-row align-items-center">
                        <span class="es-step-number">
                            2
                        </span>
                        <span class="es-step-title">
                            Date & Time
                        </span>
                    </div>
                    <hr class="d-none d-md-block">
                    <div class="es-step d-flex flex-column flex-md-row align-items-center">
                        <span class="es-step-number">
                            3
                        </span>
                        <span class="es-step-title">
                            Information
                        </span>
                    </div>
                </div>

                <div>
                    <div class="es-tab es-pt-8">
                        <div class="es-text-gray-900 es-font-500">
                            Select Service:
                        </div>
                        <div class="es-py-8">
                            <div class="accordion es-accordion" id="accordionExample">
                                @foreach($categories as $value)
                                @if(count($value->services) > 0)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading1">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$value->id}}" aria-expanded="true" aria-controls="collapse1">
                                            {{$value->name}}
                                        </button>
                                    </h2>
                                    @foreach($value->services as $service)
                                    <div id="collapse{{$value->id}}" class="accordion-collapse collapse" aria-labelledby="heading1" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="accordion-body-item d-flex justify-content-between">
                                                <div class="form-check">
                                                    <div class="es-w-4">
                                                        <input class="form-check-input" name="service_id" type="radio" value="{{$service->id}}" id="flexCheckChecked{{$service->id}}" data-category="{{$value->name}}" data-description="{{$service->description}}">
                                                    </div>
                                                    <label class="form-check-label ps-lg-3 ps-0" for="flexCheckChecked{{$service->id}}">
                                                        {{$service->description}}
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <img
                                                        src="{{url('/images/clock.png')}}"
                                                        alt=""
                                                        width="16"
                                                        height="16"
                                                    >
                                                    {{$service->session_timeframe}} min
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="es-navigation-buttons">
                            <button type="button" class="es-btn" id="nextBtn"
                            onclick="nextPrev(1)"> Next </button>
                        </div>

                    </div>
                    <div class="es-tab es-pt-8">
                        <div class="es-text-gray-500 mt-2 d-none text-danger" id="dateTimeError">Please select a Date & Time .</div>
                        <div class="es-text-gray-900 es-font-500">
                            Select Date & Time:
                        </div>
                        <div>
                            <div class="d-flex justify-content-center es-pb-8">
                                <input
                                    type="text"
                                    placeholder="Select Date.."
                                    class="flatpickr es-flatpickr-inline"
                                    name="booking_date"
                                    id="booking_date"
                                    hidden
                                >
                            </div>
                            <div class="es-pb-16">
                                <div>
                                    <div id="no-slot-message">
                                        <div class="es-font-700 es-text-gray-900 text-center es-mb-2">
                                            There are no available slots on this date
                                        </div>
                                        <div class="es-text-sm es-text-gray-500 text-center">
                                            <div class="es-mb-1">
                                                Next available slot:
                                            </div>
                                            <div id="next-available-slot">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="selected-date" class="d-flex justify-content-center es-font-700"></div>
                                </div>
                                <div id="time-buttons" class="es-pt-6 d-flex flex-wrap gap-3 d-none">
                                    
                                </div>
                            </div>
                        </div>

                        <div class="es-navigation-buttons">
                            <button type="button" class="es-btn-outline" id="prevBtn"
                        onclick="nextPrev(-1)" > Back </button>

                            <button type="button" class="es-btn" id="nextBtn2" onclick="nextPrev(1)" >
                            Next  </button>
                        </div>

                    </div>
                    <div class="es-tab es-pt-8">
                        <div class="es-text-gray-900 es-font-500">
                            Information:
                        </div>
                        <div class="es-pt-8 es-pb-16 d-grid gap-4">
                             <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center es-mr-14 es-w-full es-w-md-96">
                                    <div>
                                        <div class="es-w-14 es-h-14 es-bg-gray-100 es-rounded-full d-flex justify-content-center align-items-center es-mr-5">
                                            <img src="{{url('/images/calendar.png')}}" width="18" height="18" alt="">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="es-text-gray-900 es-font-600 es-mb-1" id="selected-booking-date">
                                            Thursday, 8th June
                                        </div>
                                        <div class="es-text-gray-500 es-text-sm" id="selected-slot">
                                            3:15 PM - 3:55 PM
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a href="#" onclick="nextPrev(-1)">
                                        <img src="{{url('/images/Edit Icon.png')}}" alt="">
                                    </a>
                                </div>
                             </div>
                             <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center es-mr-14 es-w-full es-w-md-96">
                                    <div>
                                        <div class="es-w-14 es-h-14 es-bg-gray-100 es-rounded-full d-flex justify-content-center align-items-center es-mr-5">
                                            <img src="{{url('/images/info.png')}}" width="18" height="18" alt="">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="es-text-gray-900 es-font-600 es-mb-1" id="selected-category">
                                            Manicure
                                        </div>
                                        <div class="es-text-gray-500 es-text-sm" id="selected-service">
                                            Full Set Overlay (Naturall nails, No Tips) Full Set Overlay (Naturall nails, No Tips)
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a href="#" onclick="nextPrev(-2)">
                                        <img src="{{url('/images/Edit Icon.png')}}" alt="">
                                    </a>
                                </div>
                             </div>
                        </div>
                    <!-- <div class="es-navigation-buttons">
                        <button type="button" class="es-btn-outline" id="prevBtn" onclick="nextPrev(-1)"> Back  </button>
                    </div> -->
                    <div class="es-navigation-buttons">
                     <button type="submit" class="es-btn" id="submitBtn" role="button"
                        data-bs-toggle="modal"> Book </button>
                    </div>

                    </div>
                     
                </div>

               
            </form>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div
    class="modal fade"
    data-bs-backdrop="static"
    id="successModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="successModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content position-relative">
            <button
                type="button"
                class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                data-bs-dismiss="modal"
            >
                <img src="{{url('/images/close.png')}}" alt="" />
            </button>
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-center es-py-24 mt-3 flex-column es-gap-6">
                    <img src="{{url('/images/Success Icon.png')}}" alt="" class="" />
                    <div class="es-text-3xl es-font-mulish-bold">Success</div>
                    <div class="es-text-gray-500 text-center es-w-auto " >
                        Your appointment has been successfully booked! <br/> We look forward to seeing you.
                    </div>
                    <div>
                        <!-- <button type="button" class="es-btn es-w-full es-w-md-auto">
                            Book a new service
                        </button> -->
                        <a href="{{url('services')}}" class="es-btn es-w-full es-w-md-auto">
                            Book a new service
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div
    class="modal fade"
    data-bs-backdrop="static"
    id="errorModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="successModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content position-relative">
            <button
                type="button"
                class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                data-bs-dismiss="modal"
            >
                <img src="{{url('/images/close.png')}}" alt="" />
            </button>
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-center es-py-24 mt-3 flex-column es-gap-6">
                    <img src="{{url('/images/failed-icon.png')}}" alt="" class="" />
                    <div class="es-text-3xl es-font-mulish-bold">Failed</div>
                    <div class="es-text-gray-500 text-center es-w-auto es-w-md-72">
                         @if ($errors->has('error'))
                            {{ $errors->first('error') }}
                        @endif
                    </div>
                    <div>
                        <!-- <button type="button" class="es-btn es-w-full es-w-md-auto">
                            Book another slot
                        </button> -->
                        <a href="{{url('services')}}" class="es-btn es-w-full es-w-md-auto">
                            Book a new service
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.js" integrity="sha512-1cMYNLuYP3nNQUA42Gj7XvcJN5lAukNNw3fE1HtK3Fs1DA5JPrNQHv5g/FM+1yL5cT6x3sf2o1mKmTpVO0iGcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{url('/js/multi-step-form.js')}}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 


<script>

    document.getElementById("next-available-slot").innerHTML = moment().format('dddd, Do MMMM')
    document.getElementById("no-slot-message").classList.add("d-none");

    // flatpickr(".flatpickr", {
    //     altFormat: "F j, Y",
    //     dateFormat: "Y-m-d",
    //     inline: true,
    //     minDate: "today",

    //     onChange: function(selectedDates, dateStr, instance) {
    //         let date = moment(dateStr).format('MMMM Do, YYYY');
    //         var service_id = $('input[name="service_id"]:checked').val();

    //         // AJAX request to get available slots
    //         $.ajax({
    //             url: "{{ url('services/slots') }}",
    //             method: "GET",
    //             data: {
    //                 date: date,
    //                 service_id: service_id // Assuming you're passing a service ID
    //             },
    //             success: function(response) {
    //                 console.log(response); // Log the response to inspect its structure
    //                 let slotsContainer = document.getElementById("time-buttons");
    //                 slotsContainer.innerHTML = ''; // Clear existing slots

    //                 if (response.slots && response.slots.length > 0) { // Check if slots exist
    //                     document.getElementById("no-slot-message").classList.add("d-none");
    //                     document.getElementById("time-buttons").classList.remove("d-none");
    //                     document.getElementById("selected-date").innerHTML = date;

    //                     response.slots.forEach(function(slot, index) {
    //                         let startTime = new Date(`1970-01-01T${slot.start_time}`); // Create a date object
    //                         let formattedStartTime = startTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });

    //                         let timeSlot = `
    //                             <div>
    //                                 <input type="radio" class="btn-check" name="slot_id" data-start_time="${slot.start_time}" data-end_time="${slot.end_time}" id="slot${index}" autocomplete="off" value="${slot.id}">
    //                                 <label class="es-time-btn" for="slot${index}">
    //                                     ${formattedStartTime}
    //                                 </label>
    //                             </div>
    //                         `;
    //                         slotsContainer.innerHTML += timeSlot; // Append to the slots container
    //                     });
    //                 } else {
    //                     // No slots available
    //                     document.getElementById("time-buttons").classList.add("d-none");
    //                     document.getElementById("no-slot-message").classList.remove("d-none");
    //                 }
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error("AJAX Error: ", status, error);
    //             }
    //         });

    //     },
    // });




flatpickr(".flatpickr", {
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            inline: true,
            minDate: "today", 

            onChange: function(selectedDates, dateStr, instance) {
                let date = moment(dateStr).format('MMMM Do, YYYY');
                let selectedDate = moment(dateStr).format('YYYY-MM-DD'); 
                var service_id = $('input[name="service_id"]:checked').val();

                let currentTime = moment().format('YYYY-MM-DD HH:mm'); 
                let currentDate = moment().format('YYYY-MM-DD'); 
                let currentTimeOnly = moment().format('HH:mm'); 
                $.ajax({
                    url: "{{ url('services/slots') }}",
                    method: "GET",
                    data: {
                        date: date,
                        service_id: service_id 
                    },
                    success: function(response) {
                        console.log(response); 
                        let slotsContainer = document.getElementById("time-buttons");
                        slotsContainer.innerHTML = ''; 

                        if (response.slots && response.slots.length > 0) { 
                            document.getElementById("no-slot-message").classList.add("d-none");
                            document.getElementById("time-buttons").classList.remove("d-none");
                            document.getElementById("selected-date").innerHTML = date;

                            response.slots.forEach(function(slot, index) {
                            let slotStartTime = moment(`${selectedDate}T${slot.start_time}`).format('YYYY-MM-DD HH:mm');
                            let formattedSlotStartTime = moment(slotStartTime).format('h:mm A'); 
                            if (selectedDate === currentDate) {
                                if (moment(slotStartTime).isSameOrAfter(currentTime)) {
                                    let timeSlot = `
                                        <div>
                                            <input type="radio" class="btn-check" name="slot_id" data-start_time="${slot.start_time}" data-end_time="${slot.end_time}" id="slot${index}" autocomplete="off" value="${slot.id}">
                                            <label class="es-time-btn" for="slot${index}">
                                                ${formattedSlotStartTime}
                                            </label>
                                        </div>
                                    `;
                                    slotsContainer.innerHTML += timeSlot; 
                                }
                            } else {
                                let timeSlot = `
                                    <div>
                                        <input type="radio" class="btn-check" name="slot_id" data-start_time="${slot.start_time}" data-end_time="${slot.end_time}" id="slot${index}" autocomplete="off" value="${slot.id}">
                                        <label class="es-time-btn" for="slot${index}">
                                            ${formattedSlotStartTime}
                                        </label>
                                    </div>
                                `;
                                slotsContainer.innerHTML += timeSlot; 
                            }
                        });

                        } else {
                            document.getElementById("time-buttons").classList.add("d-none");
                            document.getElementById("no-slot-message").classList.remove("d-none");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            },
});



    $(document).on('change', '.btn-check', function() {
        var booking_date = $('#booking_date').val();
        var start_time = $(this).data('start_time');
        var end_time = $(this).data('end_time');
        var formattedSlot = `${formatTime(start_time)} - ${formatTime(end_time)}`;
        let formattedDate = formatDateToCustomFormat(booking_date);        
        // Display the formatted slot
        $('#selected-slot').html(formattedSlot);
        $('#selected-booking-date').html(formattedDate);
    });

    $(document).on('change', '.form-check-input', function() {
        var category = $(this).data('category');
        var description = $(this).data('description');

        $('#selected-category').html(category);
        $('#selected-service').html(description);
    });

    function formatDateToCustomFormat(dateString) {
        const date = moment(dateString, 'YYYY-MM-DD');
        return `${date.format('dddd')}, ${date.format('Do')} ${date.format('MMMM')}`;
    }

    // Format the start and end times
    function formatTime(timeString) {
        return new Date(`1970-01-01T${timeString}`).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
    }




   $(document).ready(function() {
    $(document).on('click', '#nextBtn', function(e) {
        var service_id = $('input[name="service_id"]:checked').val(); 

        if (!service_id) {
            e.preventDefault(); 
            $('#serviceError').removeClass('d-none'); 
            $('#dateTimeError').addClass('d-none'); 
            nextPrev(-1);
            return; 
        } else {
            $('#serviceError').addClass('d-none'); 
        }
    });

 $(document).on('click', '#nextBtn2', function(e) {
    var booking_date = $('#booking_date').val(); 
    var selected_time = $('#time-buttons .btn-check:checked').length;  

    if (!booking_date || selected_time === 0) {
        e.preventDefault();  
        $('#dateTimeError').removeClass('d-none');  
        nextPrev(-1);  
    } else {
        $('#dateTimeError').addClass('d-none');  
    }
});


});

</script>
@endsection
