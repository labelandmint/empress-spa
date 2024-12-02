@extends('layouts.app')
@section('content')
<div class="col-lg-9 es-py-8">
    <div class="mb-5">
        <div class="es-header-4 es-font-mulish-bold">Transactions</div>
        <div class="es-text-gray-500 mt-2">Access and manage transaction records seamlessly.</div>
    </div>
    <div class="bg-white es-rounded-lg es-pb-6">
        <div class="es-table">
            <div class="es-table-header">
                <div class="es-font-mulish-bold es-header-6">History</div>
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
                    <tbody>
                        @if(count($transactions) > 0)
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{$transaction->f_name}}</td>
                            <td>{{$transaction->l_name}}</td>
                            <td>{{$transaction->title}}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M j, Y') }}</td>
                            <td>
                                <a
                                    href="{{url('transactions/download/invoice/'.$transaction->id)}}"
                                    class="es-link-primary"
                                >
                                    Download Invoice
                                    <svg
                                        class="es-svg-download"
                                        width="16"
                                        height="16"
                                        viewBox="0 0 16 16"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                                            stroke="#984A02"
                                            stroke-width="1.4"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path
                                            d="M4.6665 6.6665L7.99984 9.99984L11.3332 6.6665"
                                            stroke="#984A02"
                                            stroke-width="1.4"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                        <path
                                            d="M8 10V2"
                                            stroke="#984A02"
                                            stroke-width="1.4"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr class="text-center"><td colspan="5">No Record Found</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div id="pagination" class="d-flex justify-content-end pt-3 me-3"></div>

        </div>
    </div>
</div>

<script type="text/javascript">
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
