<tr x-data="{ show: true }" x-show="show">
    <th class="pl-0 border-0" scope="row">
        <div class="media align-items-center">
            <a class="reset-anchor d-block animsition-link" href="{{ route('frontend.product', $item->model->slug) }}">
                <img src="{{ asset('backend/uploads/products/' . $item->model->firstMedia->file_name) }}" alt="{{ $item->name }}" width="70"/>
            </a>
            <div class="media-body ml-3">
                <strong class="h6">
                    <a class="reset-anchor animsition-link" href="{{ route('frontend.product', $item->model->slug) }}">
                        {{ $item->name }}
                    </a>
                </strong>
            </div>
        </div>
    </th>
    <td class="align-middle border-0">
        <p class="mb-0 small">${{ $item->price }}</p>
    </td>
    <td class="align-middle border-0">
        <a wire:click.prevent="moveToCart('{{ $item->rowId }}')" x-on:click="show = false" class="reset-anchor">
            Move to cart
        </a>
    </td>
    <td class="align-middle border-0">
        <a wire:click.prevent="removeFromWishList('{{ $item->rowId }}')" x-on:click="show = false" class="reset-anchor">
            <i class="fas fa-trash-alt small text-muted"></i>
        </a>
    </td>
</tr>
