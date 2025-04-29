<?php

namespace App\Http\Livewire\Frontend\Customer;


use Livewire\Component;


class AddressesComponent extends Component
{
    protected $listeners = ['addressAdded' => 'refreshAddresses'];

    public $editMode = false;
    public $showForm = false;
    public $addresses = [];

    public function mount()
    {
        $this->addresses = auth()->user()->addresses;
    }

    public function editAddress($id)
    {
        $this->showForm = true;
        $this->editMode = true;
        $this->emitTo('frontend.customer.addresses-form-component', 'edit_address', $id);
    }
    public function DeleteAddress($id)
    {
        $this->emitTo('frontend.customer.addresses-form-component', 'delete_address',$id);
    }




    public function cancelEdit(){
        $this->emitTo('frontend.customer.addresses-form-component', 'edit_cancel');
        $this->showForm = false;
        $this->editMode = false;
    }


    public function refreshAddresses()
    {
        $this->addresses = auth()->user()->addresses;
        $this->showForm = false;
        $this->editMode = false;
    }


    public function render()
    {
        return view('livewire.frontend.customer.addresses-component', [
            'addresses' => $this->addresses,
        ]);
    }
}
