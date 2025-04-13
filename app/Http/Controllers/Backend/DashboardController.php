<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AccountInfoRequest;
use App\Traits\ImageRemoveTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    use ImageUploadTrait, ImageRemoveTrait;
    public function index()
    {
        return view('backend.index');
    }
    public function account_settings()
    {
        return view('backend.accounts.account-settings');
    }

    public function update_account_settings(AccountInfoRequest $request)
    {
        if ($request->validated()) {
            $data['first_name'] = $request->first_name;
            $data['last_name'] = $request->last_name;
            $data['username'] = $request->username;
            $data['email'] = $request->email;
            $data['mobile'] = $request->mobile;
            if ($request->password != '') {
                $data['password'] = bcrypt($request->password);
            }
            if ($request->hasFile('user_image') && auth()->user()->hasRole('admin')) {
                // Retrieve the uploaded file
                $image = $request->file('user_image');

                // Delete old image
                $this->deleteImages(auth()->user(), 'admin', 'user_image');

                // Upload new image
                $file_name = $this->uploadSingleImage($image, $request->username, 'admin');
                $data['user_image'] = $file_name;
            } elseif ($request->hasFile('user_image') && auth()->user()->hasRole('supervisor')) {
                // Retrieve the uploaded file
                $image = $request->file('user_image');

                // Delete old image
                $this->deleteImages(auth()->user(), 'supervisors', 'user_image');

                // Upload new image
                $file_name = $this->uploadSingleImage($image, $request->username, 'supervisors');
                $data['user_image'] = $file_name;
            }

            auth()->user()->update($data);
            Alert::toast('تم تعديل البيانات بنجاح', 'success')->position('top-end');

            return redirect()->route('admin.account_settings');

        }
    }


    public function remove_image(Request $request)
    {
        if (!auth()->user()->ability('admin,supervisor', 'delete_supervisors')) {
            return redirect('admin/');
        }
        if(auth()->user()->hasRole('admin')){
            $this->removeImage(auth()->user(), 'admin','user_image');
        }elseif (auth()->user()->hasRole('supervisor')){
            $this->removeImage(auth()->user(), 'supervisors','user_image');
        }
        return true;
    }

}
