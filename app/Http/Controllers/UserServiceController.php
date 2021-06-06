<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\userServices;
use Illuminate\Support\Facades\Validator;

class UserServiceController extends Controller
{
    public function index() {
        return view('userServices.index', ['title' => 'User Service']);
    }

    public function get() {
        return Datatables::of(userServices::orderBy('id', 'ASC')->get())
        ->make(true);
    }

    public function create() {
        return view('userServices.create');
    }

    public function edit($id) {
        $model = userServices::findOrFail($id);
        return view('userServices.edit', ['model' => $model]);
    }

    public function edit_user($id) {
        $model = userServices::findOrFail($id);
        return view('userServices.setting', ['model' => $model]);
    }
        
    public function changePassword(Request $request, $id) {
        $rules = self::setting_validate($request->all());
        $messages = self::setting_validation_message($request->all());
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            return redirect()->back()->with(['error', 'Terjadi Kesalahan Input']);
        }
        $user = userServices::findOrFail($id);
        if($request->old_password != $user->password) {
            return redirect()->back()->with(['error', 'Old Password Salah']);
        }

        if(isset($request->name) && !isset($request->new_password) && !isset($request->old_password) && !isset($request->konfirmasi_password)) {
            if($user->update(['name' => $request->name])) {
                return redirect()->back()->with(['success', 'Data Berhasil di Update']);
            } else {
                return redirect()->back()->with(['error', 'Data Gagal di Update']);
            }
        }

        if(!isset($request->name) && isset($request->new_password) && isset($request->old_password) && isset($request->konfirmasi_password)) {
            dd($request->all());
            if($user->update(['password' => $request->new_password])) {
                return redirect()->back()->with(['success', 'Data Berhasil di Update']);
            } else {
                return redirect()->back()->with(['error', 'Data Gagal di Update']);
            }
        }

        if(isset($request->name) && isset($request->new_password) && isset($request->old_password) && isset($request->konfirmasi_password)) {
            if($user->update(['name' => $request->name, 'password' => $request->new_password])) {
                return redirect()->back()->with(['success', 'Data Berhasil di Update']);
            } else {
                return redirect()->back()->with(['error', 'Data Gagal di Update']);
            }
        }
    }

    public function store(Request $request) {
        $rules = self::validation($request->all());
        $messages = self::validation_message($request->all());
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi Kesalahan Input',
                'errors' => $validator->messages()
            ], 400);
        }
        $data = [
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->name,
        ];
        if(userServices::create($data)) {
            return [
                'success' => true,
                'message' => 'Data Berhasil di Tambahkan'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Data Gagal di Tambahkan'
            ];
        }
    }

    public function update(Request $request, $id) {
        $rules = self::validation($request->all());
        $messages = self::validation_message($request->all());
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi Kesalahan Input',
                'errors' => $validator->messages()
            ], 400);
        }
        $data = [
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->name,
        ];
        $userSurvices = userServices::findOrFail($id);
        if($userSurvices->update($data)) {
            return [
                 'success' => true,
                 'message' => 'Data Berhasil di Update'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Data Gagal di Update'
            ];
        }
    } 

    public function delete($id) {
        $model = userServices::findOrFail($id);
        if($model) {
            if($model->delete()) {
                return [
                    'success' => true,
                    'message' => 'Data Berhasil Di Hapus'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Gagal Di Hapus'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Data Tidak Di Temukan'
            ];
        }
    } 

    public function validation() {
        return [
            'name' => 'required',
            'password' => 'required',
        ];
    } 

    public function validation_message() {
        $messages = [];
        $messages['name.required'] = 'Username Harus Di Isi';
        $messages['password.required'] = 'Password Harus Di Isi';
        return $messages;
    }

    public function setting_validation_message() {
        $messages = [];
        $messages['old_password.required'] = 'Old Password Harus Di Isi';
        $messages['old_password.min:8'] = 'Old Password Minimal 8 Karakter';
        $messages['new_password.required'] = 'New Password Harus Di Isi';
        $messages['new_password.min:8'] = 'New Password Minimal 8 Karakter';
        $messages['konfirmasi_password.required'] = 'Konfirmasi Password Harus Di Isi';
        $messages['konfirmasi_password.min:8'] = 'Konfirmasi Password Minimal 8 Karakter';
        return $messages;
    }

    public function setting_validate() {
        return [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'konfirmasi_password' => 'required|min:8',
        ];
    }
     
}
