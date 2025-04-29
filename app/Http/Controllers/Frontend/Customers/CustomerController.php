<?php

namespace App\Http\Controllers\Frontend\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ProfileRequest;
use App\Traits\ImageRemoveTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    use ImageRemoveTrait , ImageUploadTrait;
    public function dashboard()
    {


        return view('frontend.customers.index');

    }



    public function profile()
    {
        return view('frontend.customers.profile');
    }



    public function update_profile(ProfileRequest $request)
    {
        $user = auth()->user();
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($user_image = $request->file('user_image')) {

            // Remove the old image if it exists
            $this->deleteImages($user, 'customers', 'user_image');
            // Upload the new image
            $file_name = $this->uploadSingleImage($user_image, $user->username, 'customers');

            $data['user_image'] = $file_name;
        }

        $user->update($data);

        toast('Profile updated', 'success');
        return redirect()->back();
    }



    public function remove_profile_image()
    {
        $user = auth()->user();
        // Remove the old image
        $this->removeImage($user, 'customers', 'user_image');
        toast('Profile image deleted', 'success');
        return back();
    }


    public function addresses()
    {
        return view('frontend.customers.addresses');

    }



    public function orders()
    {
        return view('frontend.customers.orders');

    }




}
