@extends('layouts.app')
@section('content')
    <style type="text/css">
        .no-text-decoration {
            text-decoration: none;
        }
    </style>
    <div class="col-lg-9 es-py-8">
        <div class="es-mb-10">
            <div class="es-header-4 es-font-mulish-bold">Products</div>
        </div>

        <div class="card border-0 rounded-3">
            <div class="card-body px-0 pt-0 es-pb-6">
                <div class="es-table">
                    <div
                        class="es-table-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 gap-md-0">
                        <div class="es-font-mulish-bold es-text-xl">Products</div>
                        <div class="d-flex align-items-center gap-4">
                            <div class="d-flex gap-3">
                                <button id="sortAsc" class="bg-transparent p-0 es-btn-icon">
                                    <img src="{{ url('/images/filter-up-dark.png') }}" alt="" />
                                </button>

                                <button id="sortDesc" class="bg-transparent p-0 es-btn-icon">
                                    <img src="{{ url('/images/filter-down-dark.png') }}" alt="" />
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label for="filter" class="es-font-600 es-text-gray-500 text-nowrap">
                                    Filter by:
                                </label>
                                <select id="filter" class="form-select border-0 h-100 es-font-600 es-text-gray-900"
                                    aria-label="Default select example">
                                    <option value="1">Active</option>
                                    <option value="3">Archived</option>
                                </select>
                            </div>
                            @if(auth()->guard('admin')->user()->hasPermission('add_products'))
                            <div>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#addProductModal"
                                    class="es-link-primary border-0 bg-transparent es-text-lg">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 4.16699V15.8337" stroke="#BB7E45" stroke-width="1.8"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4.16602 10H15.8327" stroke="#BB7E45" stroke-width="1.8"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Add Product
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="es-table-container">
                        <table id="productsTable">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Date Archived</th>
                                    <th>View/Edit</th>
                                </tr>
                            </thead>
                            <tbody id="product-rows">
                                @if(count($products) > 0)
                                @foreach ($products as $list)
                                    <tr>
                                        <td>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#photoModal"
                                                data-image="{{ $list->image ?? url('/images/services-image.svg') }}"
                                                class="border-0 es-outline-none bg-transparent p-0 hover-darken-95">
                                                <img src="{{ $list->image ?? url('/images/services-image.svg') }}"
                                                    width="40" height="40" alt="" />
                                            </button>
                                        </td>
                                        <td>{{ $list->title }}</td>
                                        <td>{{ $list->description }}</td>
                                        <td>{{ $list->quantity }}</td>
                                        <td>{{ $list->status == 3 ? $list->archived_at->format('M d, Y') : '' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(auth()->guard('admin')->user()->hasPermission('edit_products'))
                                                <a href="#updateProductModal" class="es-link-primary" data-bs-toggle="modal"
                                                    role="button" data-id="{{ $list->id }}"
                                                    data-title="{{ $list->title }}"
                                                    data-description="{{ $list->description }}"
                                                    data-quantity="{{ $list->quantity }}"
                                                    data-image="{{ $list->image }}"
                                                    data-archived_at="{{ $list->archived_at }}"
                                                    >
                                                    View/Edit
                                                </a>
                                                @else
                                                <a href="#viewProductModal" class="es-link-primary" data-bs-toggle="modal"
                                                    role="button" data-id="{{ $list->id }}"
                                                    data-title="{{ $list->title }}"
                                                    data-description="{{ $list->description }}"
                                                    data-quantity="{{ $list->quantity }}"
                                                    data-image="{{ $list->image }}">
                                                    View
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr class="text-center"><td colspan="6">No Record Found</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination" class="d-flex justify-content-end pt-3 me-3"></div>
                </div>
            </div>
        </div>

        <form action="">
            <div class="card border-0 es-mb-6">
                <div class="card-body d-flex flex-column es-font-mulish es-px-6 es-pb-8"></div>
            </div>
        </form>
    </div>
    </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="addProductModal" tabindex="-1" role="dialog"
        aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 540px">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent closeModal position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ url('/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body p-4">
                        <form action="{{ url('admin/product/store') }}" method="post" class=""
                            enctype="multipart/form-data">
                            @csrf
                            <div class="es-text-lg es-font-600 es-mb-6">Add Product</div>
                            <div>
                                <div>Photo</div>
                                <div class="mt-2">
                                    <input type="file" name="image" accept=".jpg,.jpeg,.png" hidden id="photo_input" />
                                    <label for="photo_input" class="es-file-input" id="photo-label">
                                        Upload
                                        <svg class="es-svg-download" width="16" height="16" viewBox="0 0 16 16"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                                                stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M4.6665 6.6665L7.99984 9.99984L11.3332 6.6665" stroke="#984A02"
                                                stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M8 10V2" stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </label>
                                    <div class="d-none" id="file-preview-container">
                                        <img src="#" alt="Preview Uploaded Image" id="photo-preview"
                                            class="es-h-80 es-mb-3 file-preview w-100" />
                                        <div class="d-flex es-gap-8">
                                            <label for="photo_input" class="btn border-0 es-text-sm es-font-600 p-0">
                                                Change
                                                <img src="{{ url('/images/refresh.png') }}" width="14"
                                                    height="14" alt="" />
                                            </label>
                                            <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                                id="clear_photo_input">
                                                Delete
                                                <img src="{{ url('/images/trash.png') }}" width="14"
                                                    height="14" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="title">Title</label>
                                <input id="title" name="title" type="text" class="form-control es-input mt-2"
                                    placeholder="Title" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="description">Description</label>
                                <input id="description" name="description" type="text"
                                    class="form-control es-input mt-2" placeholder="Description" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="quantity">Quantity</label>
                                <input id="quantity" name="quantity" type="number" class="form-control es-input mt-2"
                                    placeholder="Quantity" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="es-mt-6 col-xl-3">
                                <button type="submit" class="es-btn es-w-full es-h-auto">
                                    Add
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Product Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="updateProductModal" tabindex="-1" role="dialog"
        aria-labelledby="updateProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 540px">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ url('/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body p-4">
                        <form action="{{ url('admin/product/store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="es-text-lg es-font-600 es-mb-6">Update Product</div>
                            <div>
                                <div>Photo</div>
                                <div class="mt-2">
                                    <input type="hidden" name="id" hidden id="id" />
                                    <input type="hidden" name="image_url" hidden id="update_image_url" />
                                    <input type="file" accept=".jpg,.jpeg,.png" name="image" hidden
                                        id="update_photo_input" />
                                    <label for="update_photo_input" class="es-file-input" id="update-photo-label">
                                        Upload
                                        <svg class="es-svg-download" width="16" height="16" viewBox="0 0 16 16"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                                                stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M4.6665 6.6665L7.99984 9.99984L11.3332 6.6665" stroke="#984A02"
                                                stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M8 10V2" stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </label>
                                    <div class="d-none" id="update-file-preview-container">
                                        <img src="#" alt="Preview Uploaded Image" id="update-photo-preview"
                                            class="es-h-100 es-mb-3 update-file-preview w-100" />
                                        <div class="d-flex es-gap-8">
                                            <label for="update_photo_input"
                                                class="btn border-0 es-text-sm es-font-600 p-0">
                                                Change
                                                <img src="{{ url('/images/refresh.png') }}" width="14"
                                                    height="14" alt="" />
                                            </label>
                                            <button type="button" class="btn border-0 es-text-sm es-font-600 p-0"
                                                id="update_clear_photo_input">
                                                Delete
                                                <img src="{{ url('/images/trash.png') }}" width="14"
                                                    height="14" alt="" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="title">Title</label>
                                <input id="title" name="title" type="text" class="form-control es-input mt-2"
                                    placeholder="Title" value="Nail Treatment" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="description">Description</label>
                                <input id="description" name="description" type="text"
                                    class="form-control es-input mt-2" placeholder="Description"
                                    value="Full Set Pink & White (French)" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="quantity">Quantity</label>
                                <input id="quantity" name="quantity" type="text" class="form-control es-input mt-2"
                                    placeholder="Quantity" value="12" />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="d-flex es-mt-6 es-gap-x-5">
                                <button type="submit" data-bs-dismiss="modal" class="es-btn">
                                    Save
                                </button>
                                @if(auth()->guard('admin')->user()->hasPermission('archive_products'))
                                <!-- data-bs-dismiss="modal" -->
                                <a href="#" type="button" id="update-archive-button"
                                    class="es-btn-outline no-text-decoration">
                                    Archive
                                </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Product Modal -->
    <div class="modal fade" data-bs-backdrop="static" id="viewProductModal" tabindex="-1" role="dialog"
        aria-labelledby="viewProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 540px">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ url('/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body p-4">

                            <div class="es-text-lg es-font-600 es-mb-6">View Product</div>
                            <div>
                                <div>Photo</div>
                                <div class="mt-2">
                                    <input type="hidden" name="id" hidden id="id" />
                                    <input type="hidden" name="image_url" hidden id="view_image_url" />
                                    <input type="file" accept=".jpg,.jpeg,.png" name="image" hidden
                                        id="view_photo_input" />
                                    <label for="view_photo_input" class="es-file-input" id="view-photo-label">
                                        Upload
                                        <svg class="es-svg-download" width="16" height="16" viewBox="0 0 16 16"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10"
                                                stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M4.6665 6.6665L7.99984 9.99984L11.3332 6.6665" stroke="#984A02"
                                                stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M8 10V2" stroke="#984A02" stroke-width="1.4" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </label>
                                    <div class="d-none" id="view-file-preview-container">
                                        <img src="#" alt="Preview Uploaded Image" id="view-photo-preview"
                                            class="es-h-80 es-mb-3 view-file-preview w-100" />
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="title">Title</label>
                                <input id="title" name="title" type="text" class="form-control es-input mt-2"
                                    placeholder="Title" value="Nail Treatment" disabled />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="description">Description</label>
                                <input id="description" name="description" type="text"
                                    class="form-control es-input mt-2" placeholder="Description"
                                    value="Full Set Pink & White (French)" disabled />
                                <div class="es-input-error-message"></div>
                            </div>
                            <div class="d-flex flex-column es-mt-6">
                                <label for="quantity">Quantity</label>
                                <input id="quantity" name="quantity" type="text" class="form-control es-input mt-2"
                                    placeholder="Quantity" value="12" disabled />
                                <div class="es-input-error-message"></div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 540px">
            <div class="modal-content position-relative">
                <button type="button" class="border-0 bg-transparent position-absolute es-top-6 es-right-6 es-z-50"
                    data-bs-dismiss="modal">
                    <img src="{{ url('/images/close.png') }}" alt="" />
                </button>
                <div class="card">
                    <div class="card-body p-5">
                        <img id="modalImage" src="{{ url('/images/services-image.svg') }}" alt=""
                            class="w-100" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <script>
        flatpickr(".flatpickr", {
            // Human-friendly format
            altInput: true,
            altFormat: "M j, Y",
            dateFormat: "Y-m-d",

            minDate: "today",
        });

        const photo_input = document.getElementById("photo_input");

        photo_input.addEventListener("change", () => {
            const file = photo_input.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById("photo-preview");

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document.getElementById("photo-label").classList.add("d-none");
                document
                    .getElementById("file-preview-container")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("clear_photo_input")
            .addEventListener("click", () => {
                document.getElementById("photo_input").value = null;
                document.getElementById("photo-label").classList.remove("d-none");
                document
                    .getElementById("file-preview-container")
                    .classList.add("d-none");
            });

        const update_photo_input = document.getElementById("update_photo_input");

        update_photo_input.addEventListener("change", () => {
            const file = update_photo_input.files[0];
            if (file && file.type.match("image.*")) {
                const fileReader = new FileReader();
                const preview = document.getElementById("update-photo-preview");

                fileReader.onload = function(event) {
                    preview.setAttribute("src", event.target.result);
                };
                fileReader.readAsDataURL(file);

                document.getElementById("update-photo-label").classList.add("d-none");
                document
                    .getElementById("update-file-preview-container")
                    .classList.remove("d-none");
            }
        });

        document
            .getElementById("update_clear_photo_input")
            .addEventListener("click", () => {
                document.getElementById("update_photo_input").value = null;
                document
                    .getElementById("update-photo-label")
                    .classList.remove("d-none");
                document
                    .getElementById("update-file-preview-container")
                    .classList.add("d-none");
            });


        $(document).ready(function() {
            $('#photoModal').on('show.bs.modal', function(event) {
                // Get the button that triggered the modal
                var button = $(event.relatedTarget);

                // Extract the image URL from the data attribute
                var imageUrl = button.data('image');

                // Find the modal image element and update its src attribute
                $('#modalImage').attr('src', imageUrl);
            });


            var isAscending = true;
            var $table = $('#productsTable');
            var $tbody = $table.find('tbody');
            var $originalRows = $tbody.find('tr').clone(); // Clone rows to restore original order

            $('#sortAsc').click(function() {
                if (!isAscending) {
                    sortTable(true);
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

                $rows.sort(function(a, b) {
                    // Change the index here to the column you want to sort by (0 is Photo, 1 is Title, etc.)
                    var keyA = $(a).find('td').eq(1).text().toLowerCase(); // Assuming sorting by Title
                    var keyB = $(b).find('td').eq(1).text().toLowerCase(); // Change index as needed

                    if (ascending) {
                        return keyA.localeCompare(keyB);
                    } else {
                        return keyB.localeCompare(keyA);
                    }
                });

                $tbody.html('');
                $.each($rows, function(index, row) {
                    $tbody.append(row);
                });
            }
        });

        $(document).ready(function() {
            $('#filter').change(function() {
                var filterValue = $(this).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ url('admin/product/filter') }}", // Route to your filtering method
                    type: 'GET',
                    data: {
                        status: filterValue
                    },
                    success: function(response) {
                        // Update the table body with the new rows
                        $('#product-rows').html(response);
                    }
                });
            });
        });


        $(document).ready(function() {
            // Handle the 'show.bs.modal' event for the update modal
            $('#updateProductModal').on('show.bs.modal', function(event) {
                // Get the link that triggered the modal
                var button = $(event.relatedTarget);

                // Extract the data from the link's data attributes
                var productId = button.data('id');
                var productTitle = button.data('title');
                var productDescription = button.data('description');
                var productQuantity = button.data('quantity');
                var productImage = button.data('image');
                var archived_at = button.data('archived_at');

                // Set the data in the modal form fields
                var modal = $(this);
                modal.find('#id').val(productId);
                modal.find('#update-archive-button').attr('href', "{{ url('admin/product/archive') }}/" +
                    productId);
                modal.find('#title').val(productTitle);
                modal.find('#description').val(productDescription);
                modal.find('#quantity').val(productQuantity);

                if (productImage) {
                    modal.find('#update-photo-preview').attr('src', productImage);
                    modal.find('#update-file-preview-container').removeClass('d-none');
                    $('#update-photo-label').addClass('d-none');
                    $('#update_image_url').val(productImage);

                }

                if (archived_at) {
                    modal.find('#update-archive-button').text('Restore');
                } else {
                    modal.find('#update-archive-button').text('Archive');
                }

            });

            // Handle the 'show.bs.modal' event for the update modal
            $('#viewProductModal').on('show.bs.modal', function(event) {
                // Get the link that triggered the modal
                var button = $(event.relatedTarget);

                // Extract the data from the link's data attributes
                var productId = button.data('id');
                var productTitle = button.data('title');
                var productDescription = button.data('description');
                var productQuantity = button.data('quantity');
                var productImage = button.data('image');

                // Set the data in the modal form fields
                var modal = $(this);
                modal.find('#id').val(productId);
                modal.find('#view-archive-button').attr('href', "{{ url('admin/product/archive') }}/" + productId);
                modal.find('#title').val(productTitle);
                modal.find('#description').val(productDescription);
                modal.find('#quantity').val(productQuantity);

                if (productImage) {
                    modal.find('#view-photo-preview').attr('src', productImage);
                    modal.find('#view-file-preview-container').removeClass('d-none');
                    $('#view-photo-label').addClass('d-none');
                    $('#view_image_url').val(productImage);
                }else{
                    modal.find('#view-photo-preview').attr('src', '#');
                    modal.find('#view-file-preview-container').addClass('d-none');
                    $('#view-photo-label').removeClass('d-none');
                    $('#view_image_url').val('');
                }
            });


            // Handle the clear photo button click
            $('#update_clear_photo_input').click(function() {
                $('#update-photo-preview').attr('src', ''); // Clear the image preview
                $('#update_photo_input').val(''); // Clear the file input
                $('#update_image_url').val('');
            });
        });

        $(document).ready(function() {
            // Initialize form validation
            $("#addProductModal form").validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 2
                    },
                    description: {
                        required: true,
                        minlength: 10
                    },
                    quantity: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    image: {
                        required: true,
                        extension: "jpg,jpeg,png"
                    }
                },
                messages: {
                    title: {
                        required: "Please enter a title",
                        minlength: "Title must be at least 2 characters long"
                    },
                    description: {
                        required: "Please enter a description",
                        minlength: "Description must be at least 10 characters long"
                    },
                    quantity: {
                        required: "Please enter quantity",
                        digits: "Please enter a valid number",
                        min: "Quantity must be at least 1"
                    },
                    image: {
                        required: "Please upload an image",
                        extension: "Only jpg, jpeg, and png formats are allowed"
                    }
                },
                errorElement: "div",
                errorClass: "es-input-error-message",
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    form.submit(); // Submit the form when valid
                }
            });

            // Handle the submit button click
            $('#addProductModal form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                if ($(this).valid()) { // Only proceed if the form is valid
                    this.submit(); // Submit the form
                }
            });

            // // Close modal and reset form when the close button is clicked
            // $('#addProductModal').on('click', '.border-0.bg-transparent', function() {
            //     $("#addProductModal form")[0].reset(); // Reset the form
            //     $('#addProductModal').modal('hide'); // Close the modal
            // });
        });

        $(document).ready(function() {
            let rowsPerPage = 10; // Rows to display per page
            let currentPage = 1;
            let totalRows = $("#productsTable tbody tr").length; // Count total rows
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            function renderTable(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                // Hide all rows initially
                $("#productsTable tbody tr").hide();

                // Show rows for the current page
                $("#productsTable tbody tr").slice(start, end).show();
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
