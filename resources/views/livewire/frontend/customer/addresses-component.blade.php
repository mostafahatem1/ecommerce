<div x-data="{ formShow: @entangle('showForm') }" class="col-lg-12 pt-2">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Addresses</h5>
        @if ($editMode)
            <div wire:ignore>
                <button  type="button" wire:click.prevent="cancelEdit" class="btn btn-outline-secondary">
                    Cancel
                </button>
            </div>
        @else
            <button @click="formShow = !formShow" class="btn btn-primary rounded shadow">Add Address</button>
        @endif
    </div>

    <div x-show="formShow" class="card-body my-4" >
        <livewire:frontend.customer.addresses-form-component/>
    </div>

    <div class="table-responsive card-body">
        @if ($addresses->count() > 0)
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="col-1">Title</th>
                    <th class="col-4">Location</th>
                    <th class="col-2">Default</th>
                    <th class="col-1">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($addresses as $address)
                    <tr>
                        <td>{{ $address->address_title }}</td>
                        <td>{{ $address->country->name . ' - ' . $address->state->name .' - ' . $address->city->name }}</td>
                        <td>
                            {!! $address->defaultAddress() ? '<span class="text-success">✅ Default</span>' : '' !!}
                        </td>
                        <td class="text-right">
                            <div class="btn-group btn-group-sm">
                                <button type="button" wire:click.prevent="editAddress('{{ $address->id }}')"
                                        class="btn btn-success">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" wire:click.prevent="DeleteAddress('{{ $address->id }}')"
                                        class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No addresses found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        @else
            <p>No addresses found.</p>
        @endif
    </div>

</div>
