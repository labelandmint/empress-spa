@extends('layouts.app')
@section('content')

@if(session('modal'))
    <script>
        $(document).ready(function() {
            $('#successModal').modal('show');
        });
    </script>
@endif

<style type="text/css">
    .es-badge-orange {
        background-color: #ffe3b1;
        color: #aa6e00;
        text-align: center;
        padding: 4px;
        border-radius: 5px;
    }
</style>

    <div class="col-lg-9 es-py-8">
        <div class="es-mb-10">
            <div class="es-header-4 es-font-mulish-bold">Members</div>
        </div>

        <div class="d-flex flex-column flex-md-row gap-3 es-mb-8">
            <div class="card border-0 w-100 rounded-3">
                <div class="card-body es-p-4 es-text-gray-900">
                    <div class="d-flex align-items-center es-mb-4">
                        <div
                            class="es-rounded-full es-w-8 es-h-8 es-bg-blue-100 d-flex align-items-center justify-content-center es-mr-4">
                            <img src="{{ url('public/images/users-blue.png') }}" alt="" />
                        </div>
                        <div class="es-text-sm es-font-500">Total Members</div>
                    </div>
                    <div class="es-text-5xl es-font-600">{{ $totalMember }}</div>
                </div>
            </div>
            <div class="card border-0 w-100 rounded-3">
                <div class="card-body es-p-4 es-text-gray-900">
                    <div class="d-flex align-items-center es-mb-4">
                        <div
                            class="es-rounded-full es-w-8 es-h-8 es-bg-green-100 d-flex align-items-center justify-content-center es-mr-4">
                            <img src="{{ url('public/images/check-circle-green.png') }}" alt="" />
                        </div>
                        <div class="es-text-sm es-font-500">Total Active</div>
                    </div>
                    <div class="es-text-5xl es-font-600">{{ $activeMember }}</div>
                </div>
            </div>
            <div class="card border-0 w-100 rounded-3">
                <div class="card-body es-p-4 es-text-gray-900">
                    <div class="d-flex align-items-center es-mb-4">
                        <div
                            class="es-rounded-full es-w-8 es-h-8 es-bg-gray-100 d-flex align-items-center justify-content-center es-mr-4">
                            <img src="{{ url('public/images/pause-gray.png') }}" alt="" />
                        </div>
                        <div class="es-text-sm es-font-500">Total Paused</div>
                    </div>
                    <div class="es-text-5xl es-font-600">{{ $pauseMember }}</div>
                </div>
            </div>
            <div class="card border-0 w-100 rounded-3">
                <div class="card-body es-p-4 es-text-gray-900">
                    <div class="d-flex align-items-center es-mb-4">
                        <div
                            class="es-rounded-full es-w-8 es-h-8 es-bg-red-100 d-flex align-items-center justify-content-center es-mr-4">
                            <img src="{{ url('public/images/times-red.png') }}" alt="" />
                        </div>
                        <div class="es-text-sm es-font-500">Total Cancelled</div>
                    </div>
                    <div class="es-text-5xl es-font-600">{{ $cancelledMember }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 rounded-3">
            <div class="card-body p-0">
                <div class="es-table">
                    <div class="es-table-header d-flex flex-column flex-md-row gap-4 gap-md-5">
                        <div class="position-relative w-100">
                            <div class="position-absolute es-left-4 es-translate-y-1/2 es-top-1/2">
                                <div class="es-w-6 es-h-6 es-bg-gray-500 rounded-circle position-relative">
                                    <img src="{{ url('public/images/magnifying-glass.png') }}" alt=""
                                        class="position-absolute es-bottom-0.5 es-right-0.5" />
                                </div>
                            </div>
                            <input type="text" id="searchInput" class="form-control es-input es-pl-14 w-100"
                                placeholder="Search..." />
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label for="filter" class="es-font-600 es-text-gray-500 text-nowrap">
                                Filter by:
                            </label>
                            <select id="filter"
                                class="form-select es-w-md-48 es-w-auto border-0 h-100 es-font-600 es-text-gray-900"
                                aria-label="Default select example">
                                <option value="1">First Name</option>
                                <option value="2">Last Name</option>
                                <option value="3">Subscription Tier</option>
                                <option value="4">Subscription Status</option>
                                <option value="5">Last Service</option>
                                <option value="6">Last Booking Date & Time</option>
                                <option value="7">Last Payment Date</option>
                            </select>
                        </div>
                    </div>
                    <div class="es-table-container">
                        <table id="membersTable">
                            <thead>
                                <tr>
                                    <th>First name</th>
                                    <th>Last name</th>
                                    <th>Subscription</th>
                                    <th>Subscription Status</th>
                                    <th>Last Service</th>
                                    <th>Last Booking Date & Time</th>
                                    <th>Last Payment Date</th>
                                    <th>View/Edit</th>
                                    <th>Upcoming Bookings</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($members as $member)
                                    <tr>
                                        <td>{{ $member->f_name }}</td>
                                        <td>{{ $member->l_name }}</td>
                                        <td>{{ $member->title }}</td>
                                        <td>
                                            <div class="
                                                {{
                                                    $member->status === 1 ? 'es-badge-green' :
                                                    ($member->status === 2 ? 'es-badge-red' :
                                                    ($member->status === 3 ? 'es-badge-orange' : 'es-badge-orange'))
                                                }}
                                            ">
                                                {{
                                                    $member->status === 1 ? 'Active' :
                                                    ($member->status === 2 ? 'Cancelled' :
                                                    ($member->status === 3 ? 'Paused' : 'N/A'))
                                                }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($member->service)
                                                <div class="text-nowrap me-2">
                                                    {{ $member->service }}
                                                </div>
                                                <button
                                                    type="button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rebookModal"
                                                    data-id="{{$member->booking_id}}"
                                                    data-title="{{$member->service}}"
                                                    data-description="{{$member->service_desc}}"
                                                    data-service_id="{{$member->service_id}}"
                                                    data-booking_date="{{$member->booking_date}}"
                                                    data-slot_id="{{$member->slot_id}}"
                                                    class="border-0 bg-transparent es-btn-icon rebook-button">
                                                    <img src="{{ url('public/images/rebook-icon.png') }}" alt="" />
                                                </button>
                                                @else
                                                N/A
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            {{ $member->booking_date ? \Carbon\Carbon::parse($member->booking_date)->format('M d, Y') : 'N/A' }} <br>

                                            @if($member->booking_start_time)
                                            {{ \Carbon\Carbon::parse($member->booking_start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($member->booking_end_time)->format('g:i A') }}
                                            @endif
                                        </td>
                                        <td>{{ $member->payment_date ? \Carbon\Carbon::parse($member->payment_date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{url('admin/members/edit/'.$member->id)}}" class="es-link-primary"> View/Edit </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{url('admin/bookings/view/'.$member->id)}}" class="es-link-primary"> View Bookings </a>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($member->rating)
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex gap-1">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $member->rating)
                                                                <img src="{{ url('public/images/star-solid.svg') }}"
                                                                    alt="" width="14" height="14" />
                                                            @else
                                                                <img src="{{ url('public/images/star-regular.svg') }}"
                                                                    alt="" width="14" height="14" />
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <div class="es-text-sm es-text-gray-900 es-font-500">
                                                        {{ $member->rating }}
                                                    </div>
                                                </div>
                                            @else
                                                <a href="#ratingModal" data-id="{{ $member->id }}"
                                                    class="es-link-primary add-rating" data-bs-toggle="modal"
                                                    role="button">
                                                    Add Rating
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center"><td colspan="9">No Record Found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p id="noRecords" class="text-center mt-3 ">No records found</p>
                    </div>
                    <div id="pagination" class="d-flex justify-content-end pt-3 me-3"></div>
                    <div class="es-table-footer d-flex flex-column flex-md-row gap-3 gap-md-5">
                        <div class="d-flex">
                            <a href="{{ url('admin/members/export/excel') }}" class="es-link-primary es-link-sm d-flex">
                                Export Report in Excel
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-download" viewBox="0 0 16 16">
                                    <path
                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                    <path
                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                </svg>
                            </a>
                        </div>
                        <div class="d-flex">
                            <a href="{{ url('admin/members/export/pdf') }}" class="es-link-primary es-link-sm">
                                Export Report in PDF
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                    <path
                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                    <path
                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- start closing div of the header part --}}
    </div>
    </div>
    {{-- end closing div of the header part --}}

    <!-- Rebook Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="rebookModal" tabindex="-1" role="dialog"
        aria-labelledby="rebookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ url('public/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body p-4">
                        <form action="{{url('admin/booking/update')}}" method="post" class="es-multi-step-form">
                            @csrf
                            <div
                                class="es-progressbar gap-0 gap-md-5 d-flex align-items-center justify-content-around justify-content-md-start">
                                <div class="es-step d-flex flex-column flex-md-row align-items-center">
                                    <span class="es-step-number"> 1 </span>
                                    <span class="es-step-title"> Date & Time </span>
                                </div>
                                <hr class="d-none d-md-block" />
                                <div class="es-step d-flex flex-column flex-md-row align-items-center">
                                    <span class="es-step-number"> 2 </span>
                                    <span class="es-step-title"> Information </span>
                                </div>
                            </div>

                            <div>
                                <div class="es-tab es-pt-8">
                                    <div class="es-text-gray-900 es-font-500">
                                        Select Date & Time:
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-center es-pb-8">
                                            <input type="text" name="booking_date" placeholder="Select Date.." class="flatpickr es-flatpickr-inline" hidden />
                                        </div>
                                        <div class="es-pb-16">
                                            <div id="current-date" class="d-flex justify-content-center es-font-700">
                                            </div>

                                            <div id="time-buttons" class="es-pt-6 d-flex flex-wrap gap-3">
                                                <div>
                                                    <input type="radio" class="btn-check" name="time"
                                                        id="option1" autocomplete="off" value="1:00 PM" />
                                                    <label class="es-time-btn" for="option1">
                                                        1:00 PM
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="es-tab es-pt-8">
                                    <div class="es-text-gray-900 es-font-500">Information:</div>
                                    <div class="es-pt-8 es-pb-16 d-grid gap-4">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center es-mr-14 es-w-full es-w-md-96">
                                                <div>
                                                    <div
                                                        class="es-w-14 es-h-14 es-bg-gray-100 es-rounded-full d-flex justify-content-center align-items-center es-mr-5">
                                                        <img src="{{ url('public/images/calendar.png') }}" width="18"
                                                            height="18" alt="" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="es-text-gray-900 es-font-600 es-mb-1" id="booking-date">
                                                        Thursday, 8th June
                                                    </div>
                                                    <div class="es-text-gray-500 es-text-sm" id="slot-time">
                                                        3:15 PM - 3:55 PM
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="javascript:void(0)" onclick="nextPrev(-1)">
                                                    <img src="{{ url('public/images/Edit Icon.png') }}" alt="" />
                                                </a>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center es-mr-14 es-w-full es-w-md-96">
                                                <div>
                                                    <div
                                                        class="es-w-14 es-h-14 es-bg-gray-100 es-rounded-full d-flex justify-content-center align-items-center es-mr-5">
                                                        <img src="{{ url('public/images/info.png') }}" width="18"
                                                            height="18" alt="" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="es-text-gray-900 es-font-600 es-mb-1" id="title">
                                                        Manicure
                                                    </div>
                                                    <div class="es-text-gray-500 es-text-sm" id="description">
                                                        Full Set Overlay (Naturall nails, No Tips) Full
                                                        Set Overlay (Naturall nails, No Tips)
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <!-- <a href="#">
                                                    <img src="{{ url('public/images/Edit Icon.png') }}" alt="" />
                                                </a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="hiddenInput"></div>

                            <div class="es-navigation-buttons">
                                <button type="button" class="es-btn-outline" id="prevBtn" onclick="nextPrev(-1)">
                                    Back
                                </button>
                                <button type="button" class="es-btn" id="nextBtn" onclick="nextPrev(1)">
                                    Next
                                </button>
                                <button
                                    type="submit"
                                    class="es-btn"
                                    id="submitBtn"
                                >
                                    Book
                                </button>
                                <!-- <a href="#successModal" class="es-btn" id="submitBtn" role="button"
                                    data-bs-toggle="modal">
                                    Book
                                </a> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="successModal" tabindex="-1" role="dialog"
        aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ url('public/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div
                        class="card-body d-flex align-items-center justify-content-center es-py-24 mt-3 flex-column es-gap-6">
                        <img src="{{ url('public/images/Success Icon.png') }}" alt="" class="" />
                        <div class="es-text-3xl es-font-mulish-bold">Success</div>
                        <div class="es-text-gray-500 text-center es-w-auto es-w-md-72">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </div>
                        <div>
                            <button type="button" class="es-btn es-w-full es-w-md-auto" data-bs-dismiss="modal">OK</button>
                            <!-- <a href="../client/services.html" class="es-btn es-w-full es-w-md-auto">
                                OK
                            </a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="ratingModal" tabindex="-1" role="dialog"
        aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div style="max-width: 400px" class="modal-dialog modal-dialog-centered">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-5 es-right-5 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ url('public/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('admin/rating/add') }}" method="post">
                            @csrf
                            <div class="es-text-gray-900 es-header-4 es-font-700 text-center es-mb-14">
                                Add Rating
                            </div>
                            <div class="es-mb-14">
                                <fieldset class="es-star-rating">
                                    <input type="radio" id="rating5" name="rating" value="5" />
                                    <label for="rating5" title="5 stars"></label>

                                    <input type="radio" id="rating4" name="rating" value="4" />
                                    <label for="rating4" title="4 stars"></label>

                                    <input type="radio" id="rating3" name="rating" value="3" />
                                    <label for="rating3" title="3 stars"></label>

                                    <input type="radio" id="rating2" name="rating" value="2" />
                                    <label for="rating2" title="2 stars"></label>

                                    <input type="radio" id="rating1" name="rating" value="1" />
                                    <label for="rating1" title="1 star"></label>

                                    <input type="hidden" name="user_id" id="user_id" />
                                </fieldset>
                            </div>
                            <div class="es-mt-6 d-flex justify-content-center">
                                <button type="submit" id="pauseModal" class="es-btn es-px-24" data-bs-dismiss="modal">
                                    Rate
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
    </script>

    <!-- Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.js"
        integrity="sha512-1cMYNLuYP3nNQUA42Gj7XvcJN5lAukNNw3fE1HtK3Fs1DA5JPrNQHv5g/FM+1yL5cT6x3sf2o1mKmTpVO0iGcA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="{{ url('public/js/multi-step-form.js') }}"></script>

    <script>


        $(document).ready(function() {
            $("#noRecords").hide();
            // Search function
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                var hasVisibleRows = false;

                // Filter table rows
                $("#membersTable tbody tr").filter(function() {
                    var matchFound = $(this).text().toLowerCase().indexOf(value) > -1;
                    $(this).toggle(matchFound);
                    if (matchFound) {
                        hasVisibleRows = true;
                    }
                });

                // Show or hide the "No records found" message
                if (hasVisibleRows) {
                    $("#noRecords").hide();
                } else {
                    $("#noRecords").show();
                }
            });
        });

        $(document).on('click', '.add-rating', function() {
            var user_id = $(this).data('id');
            $('#user_id').val(user_id);
        });

        // $(document).change('#filter', function() {
        //     var filter = $(this).val();
        //     alert(filter);
        // });
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('membersTable').getElementsByTagName('tbody')[0];
            const filterSelect = document.getElementById('filter');

            // Event listener for the filter dropdown
            filterSelect.addEventListener('change', function() {
                sortTable();
            });

            function sortTable() {
                let rows = Array.from(table.rows);
                let filterValue = filterSelect.value;
                let columnIndex;

                // Determine which column to sort by
                switch (filterValue) {
                    case '1':
                        columnIndex = 0;
                        break; // First Name
                    case '2':
                        columnIndex = 1;
                        break; // Last Name
                    case '3':
                        columnIndex = 2;
                        break; // Subscription Tier
                    case '4':
                        columnIndex = 3;
                        break; // Subscription Status
                    case '5':
                        columnIndex = 4;
                        break; // Last Service
                    case '6':
                        columnIndex = 5;
                        break; // Last Booking Date & Time
                    case '7':
                        columnIndex = 6;
                        break; // Last Payment Date
                    default:
                        return; // No sorting if the value is not valid
                }

                // Sort rows based on the selected column
                rows.sort((a, b) => {
                    let textA = a.cells[columnIndex].textContent.trim().toLowerCase();
                    let textB = b.cells[columnIndex].textContent.trim().toLowerCase();

                    // If sorting by date, parse the dates first
                    if (filterValue === '6' || filterValue === '7') {
                        textA = new Date(textA);
                        textB = new Date(textB);
                    }

                    return textA > textB ? 1 : textA < textB ? -1 : 0;
                });

                // Append sorted rows back to the table
                rows.forEach(row => table.appendChild(row));
            }
        });


        // Event listener for rebook buttons
        $(document).on('click', '.rebook-button', function() {
            const booking_id = $(this).data('id');

            const service_id = $(this).data('service_id');
            const title = $(this).data('title');
            const description = $(this).data('description');
            const slot_id = $(this).data('slot_id');

            $("#hiddenInput").html(`
                <input type="hidden" name="id" value="${booking_id}" />
                <input type="hidden" name="service_id" value="${service_id}" />
            `);

            if (!booking_id) return;

            $.ajax({
                url: `{{ url("admin/booking/getById") }}/${booking_id}`,
                method: 'GET',
                success: function(response) {
                    if (!response.success) {
                        alert('Failed to retrieve booking details.');
                        return;
                    }

                    const { booking, slots } = response;
                    const bookingDate = moment(booking.booking_date);
                    const formattedDate = bookingDate.format("YYYY-MM-DD");
                    const isPastDate = bookingDate.isBefore(moment().startOf('day'));

                    $("#current-date").text(bookingDate.format("MMMM Do, YYYY"));
                    $("#booking-date").text(bookingDate.format("dddd, Do MMMM"));
                    $("#title").text(title);
                    $("#description").text(description);

                    // Initialize calendar
                    let calendar = flatpickr(".flatpickr", {
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                        inline: true,
                        onChange: selectedDates => fetchAvailableSlots(selectedDates[0], bookingDate,slot_id) // Pass the bookingDate
                    });

                    calendar.clear();
                    calendar.set('minDate', isPastDate ? formattedDate : 'today');
                    calendar.set('maxDate', isPastDate ? formattedDate : null);
                    calendar.setDate(formattedDate, true);
                    $('#rebookModal').modal('show');
                },
                error: () => alert('There was an error processing your request.')
            });
        });

        // Function to fetch available slots based on selected date
        function fetchAvailableSlots(selectedDate, bookingDate,slot_id) {
            const date = moment(selectedDate).format('MMMM Do, YYYY');
            const service_id = $('input[name="service_id"]').val();

            $.ajax({
                url: "{{ url('admin/services/slots') }}",
                method: "GET",
                data: { date:date, service_id :service_id },
                success: function(response) {
                    const slotsContainer = $("#time-buttons").empty();
                    slotsContainer.toggleClass("d-none", response.slots?.length === 0);

                    document.getElementById("current-date").innerHTML = date;

                    if (response.slots) {
                        response.slots.forEach((slot, index) => {
                            const formattedStartTime = new Date(`1970-01-01T${slot.start_time}`).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });

                            // Check if the slot date matches the booking date
                            const isChecked = moment(bookingDate).isSame(moment(selectedDate), 'day') && (slot.id === slot_id) ? 'checked' : '';

                            if (isChecked) {
                                $("#slot-time").html(`${moment(slot.start_time, "HH:mm:ss").format("h:mm A")} - ${moment(slot.end_time, "HH:mm:ss").format("h:mm A")}`);
                            }

                            const isPastDate = bookingDate.isBefore(moment().startOf('day'));

                            slotsContainer.append(`
                                <div>
                                    <input type="radio" class="btn-check" name="slot_id" id="slot${index}" value="${slot.id}" ${isChecked} ${isPastDate ? 'disabled' : ''} data-start_time="${slot.start_time}" data-end_time="${slot.end_time}" />
                                    <label class="es-time-btn" for="slot${index}">${formattedStartTime}</label>
                                </div>
                            `);
                        });
                    }
                },
                error: (xhr, status, error) => console.error("AJAX Error: ", status, error)
            });
        }

        $(document).on('change', '.btn-check', function() {
            var booking_date = $('input[name="booking_date"]').val();
            var start_time = $(this).data('start_time');
            var end_time = $(this).data('end_time');


            // Format both times
            var formattedSlot = `${moment(start_time, "HH:mm:ss").format("h:mm A")} - ${moment(end_time, "HH:mm:ss").format("h:mm A")}`;

            let formattedDate = moment(booking_date).format("MMMM Do, YYYY");
            // Display the formatted slot
            $('#slot-time').html(formattedSlot);
            $('#booking-date').html(formattedDate);
        });




        $(document).ready(function() {
            let rowsPerPage = 10; // Rows to display per page
            let currentPage = 1;
            let totalRows = $("#membersTable tbody tr").length; // Count total rows
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            function renderTable(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                // Hide all rows initially
                $("#membersTable tbody tr").hide();

                // Show rows for the current page
                $("#membersTable tbody tr").slice(start, end).show();
            }

            function renderPagination() {
                $('#pagination').empty();

                // Add Previous button (disabled if on the first page)
                if (currentPage === 1) {
                    $('#pagination').append(`<span class="page prev disabled"><i class="fa-solid fa-chevron-left"></i></span> `);
                } else {
                    $('#pagination').append(`<span class="page prev" data-page="${currentPage - 1}"><i class="fa-solid fa-chevron-left"></i></span> `);
                }

                // Add page numbers
                for (let i = 1; i <= totalPages; i++) {
                    let activeClass = (i === currentPage) ? 'active' : '';
                    $('#pagination').append(`<span class="page ${activeClass}" data-page="${i}">${i}</span> `);
                }

                // Add Next button (disabled if on the last page)
                if (currentPage === totalPages) {
                    $('#pagination').append(`<span class="page next disabled"><i class="fa-solid fa-chevron-right"></i></span> `);
                } else {
                    $('#pagination').append(`<span class="page next" data-page="${currentPage + 1}"><i class="fa-solid fa-chevron-right"></i></span> `);
                }
            }

            // Initial table and pagination render
            renderTable(currentPage);
            renderPagination();

            // Handle page click
            $(document).on('click', '.page:not(.disabled)', function() {
                currentPage = $(this).data('page');
                renderTable(currentPage);
                renderPagination();
            });
        });

    </script>
@endsection
