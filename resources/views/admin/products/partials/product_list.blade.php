@forelse($products as $list)
    <tr>
        <td>
            <button
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#photoModal"
                data-image="{{ $list->image ?? url('/images/subscriptions-img.png') }}"
                class="border-0 es-outline-none bg-transparent p-0 hover-darken-95"
            >
                <img
                    src="{{ $list->image ?? url('/images/subscriptions-img.png') }}"
                    width="40"
                    height="40"
                    alt=""
                />
            </button>
        </td>
        <td>{{ $list->title }}</td>
        <td>{{ $list->description }}</td>
        <td>{{ $list->quantity }}</td>
        <td>{{ \Carbon\Carbon::parse($list->archived_at)->format('M d, Y') }}</td>
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
@empty
    <tr class="text-center "><td colspan="6">No records found</td></tr>
@endforelse
