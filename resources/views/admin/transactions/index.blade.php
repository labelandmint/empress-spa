@extends('layouts.app')
@section('content')

<div class="col-lg-9 es-py-8">
    <div class="es-mb-10">
        <div class="es-header-4 es-font-mulish-bold">Transactions</div>
    </div>

    <div class="d-flex flex-column flex-md-row gap-3 es-mb-8">
        <div class="card border-0 w-100 rounded-3">
            <div class="card-body es-p-4 es-text-gray-900">
                <div class="d-flex align-items-center es-mb-4">
                    <div
                        class="es-rounded-full es-w-8 es-h-8 es-bg-brown-100 d-flex align-items-center justify-content-center es-mr-4"
                    >
                        <img src="{{url('public/images/users-black.png')}}" alt="" />
                    </div>
                    <div class="es-text-sm es-font-500">Total Subscriptions</div>
                </div>
                <div class="es-text-5xl es-font-600">{{number_format($subscriberCount)}}</div>
            </div>
        </div>
        <div class="card border-0 w-100 rounded-3">
            <div class="card-body es-p-4 es-text-gray-900">
                <div class="d-flex align-items-center es-mb-4">
                    <div
                        class="es-rounded-full es-w-8 es-h-8 es-bg-brown-100 d-flex align-items-center justify-content-center es-mr-4"
                    >
                        <img src="{{url('public/images/credit-card.png')}}" alt="" />
                    </div>
                    <div class="es-text-sm es-font-500">
                        Total Subscriptions Value
                    </div>
                </div>
                <div class="es-text-5xl es-font-600">${{number_format($subscriptionValue)}}</div>
            </div>
        </div>
        <div class="card border-0 w-100 rounded-3">
            <div class="card-body es-p-4 es-text-gray-900">
                <div class="d-flex align-items-center es-mb-4">
                    <div
                        class="es-rounded-full es-w-8 es-h-8 es-bg-brown-100 d-flex align-items-center justify-content-center es-mr-4"
                    >
                        <img src="{{url('public/images/shopping-bag.png')}}" alt="" />
                    </div>
                    <div class="es-text-sm es-font-500">Total This Month</div>
                </div>
                <div class="es-text-5xl es-font-600">${{number_format($totalThisMonth)}}</div>
            </div>
        </div>
        <div class="card border-0 w-100 rounded-3">
            <div class="card-body es-p-4 es-text-gray-900">
                <div class="d-flex align-items-center es-mb-4">
                    <div
                        class="es-rounded-full es-w-8 es-h-8 es-bg-brown-100 d-flex align-items-center justify-content-center es-mr-4"
                    >
                        <img src="{{url('public/images/refresh.png')}}" alt="" />
                    </div>
                    <div class="es-text-sm es-font-500">Total Last Month</div>
                </div>
                <div class="es-text-5xl es-font-600">${{$totalLastMonth}}</div>
            </div>
        </div>
    </div>

    <div class="card border-0 rounded-3">
        <div class="card-body p-0">
            <div class="es-table">
                <div
                    class="es-table-header d-flex flex-column flex-md-row gap-4 gap-md-3"
                >
                    <div class="position-relative w-100">
                        <div
                            class="position-absolute es-left-4 es-translate-y-1/2 es-top-1/2"
                        >
                            <div
                                class="es-w-6 es-h-6 es-bg-gray-500 rounded-circle position-relative"
                            >
                                <img
                                    src="{{url('public/images/magnifying-glass.png')}}"
                                    alt=""
                                    class="position-absolute es-bottom-0.5 es-right-0.5"
                                />
                            </div>
                        </div>
                        <input
                            type="text"
                            id="searchInput"
                            class="form-control es-input es-pl-14 w-100"
                            placeholder="Search..."
                        />
                    </div>
                    <div
                        class="d-flex align-items-center justify-content-between gap-3"
                    >
                        <div class="d-flex gap-2">
                            <button id="sortAsc" class="bg-transparent p-0 es-btn-icon">
                                <img src="{{url('public/images/filter-up-dark')}}.png" alt="" />
                            </button>

                            <button id="sortDesc" class="bg-transparent p-0 es-btn-icon">
                                <img src="{{url('public/images/filter-down-dark')}}.png" alt="" />
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label
                                for="filter"
                                class="es-font-600 es-text-gray-500 text-nowrap"
                            >
                                Filter by:
                            </label>
                            <select
                                id="filter"
                                class="form-select es-w-24 border-0 h-100 es-font-600 es-text-gray-900"
                                aria-label="Default select example"
                            >
                                <option selected>Month</option>
                                <option value="1">First Name</option>
                                <option value="2">Last Name</option>
                                <option value="3">Subscription</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="es-table-container">
                    <table id="transactionsTable">
                        <thead>
                            <tr>
                                <th>First name</th>
                                <th>Last name</th>
                                <th>Subscription</th>
                                <th>Payment Date</th>
                                <th>Invoice</th>
                            </tr>
                        </thead>
                        <tbody id="transactionData">
                            @if(count($transactions) > 0)
                            @foreach($transactions as $list)
                            <tr>
                                <td>{{$list->f_name}}</td>
                                <td>{{$list->l_name}}</td>
                                <td>{{$list->title}}</td>
                                <td>{{ \Carbon\Carbon::parse($list->created_at)->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{url('admin/transactions/download/invoice/'.$list->id)}}" class="es-link-primary es-link-sm d-flex">
                                            Download Invoice
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center"><td colspan="5">No record found</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <p id="noRecords" class="text-center mt-3">No records found</p>
                </div>

                <div id="pagination" class="d-flex justify-content-end pt-3 me-3"></div>

                <div
                    class="es-table-footer d-flex flex-column flex-md-row gap-3 gap-md-5"
                >
                    <div class="d-flex">
                        <a href="{{url('admin/transactions/export/excel')}}" class="es-link-primary es-link-sm d-flex">
                            Export Report in Excel
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                class="bi bi-download"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"
                                />
                                <path
                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"
                                />
                            </svg>
                        </a>
                    </div>
                    <div class="d-flex">
                        <a href="{{url('admin/transactions/export/pdf')}}" class="es-link-primary es-link-sm">
                            Export Report in PDF
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                class="bi bi-download"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"
                                />
                                <path
                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"
                                />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>



<!-- Bootstrap JavaScript Libraries -->
<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
crossorigin="anonymous"
></script>

<script>
    $(document).ready(function() {
        // Search function
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            var hasVisibleRows = false;

            // Filter table rows
            $("#transactionsTable tbody tr").filter(function() {
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

        // $('#sortAsc').click(function() {
        //     if (!isAscending) {
        //         sortTable(true);
        //         isAscending = true;
        //     }
        // });
        var isAscending = true;
        var $table = $('#transactionsTable');
        var $tbody = $table.find('tbody');
        var $originalRows = $tbody.find('tr').clone(); // Clone rows to restore original order

        $('#sortAsc').click(function() {
            if (!isAscending) {
                $tbody.html($originalRows); // Restore original order
                isAscending = true;
            }
        });

        $('#sortDesc').click(function() {
            if (isAscending) {
                sortTable(false);
                isAscending = false;
            }
        });

        function sortTable(ascending) {
            var $rows = $tbody.find('tr').get();

            if (ascending) {
                $rows.sort(function(a, b) {
                    var keyA = $(a).find('td').eq(0).text();
                    var keyB = $(b).find('td').eq(0).text();
                    return keyA.localeCompare(keyB);
                });
            } else {
                $rows.reverse();
            }

            $tbody.html('');
            $.each($rows, function(index, row) {
                $tbody.append(row);
            });
        }

    });


 document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('transactionsTable').getElementsByTagName('tbody')[0];
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

$(document).ready(function() {
    let rowsPerPage = 10; // Rows to display per page
    let currentPage = 1;
    let totalRows = $("#transactionsTable tbody tr").length; // Count total rows
    let totalPages = Math.ceil(totalRows / rowsPerPage);

    function renderTable(page) {
        let start = (page - 1) * rowsPerPage;
        let end = start + rowsPerPage;

        // Hide all rows initially
        $("#transactionsTable tbody tr").hide();

        // Show rows for the current page
        $("#transactionsTable tbody tr").slice(start, end).show();
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
