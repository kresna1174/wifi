<?php

namespace App\Http\Controllers;

use App\User;
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

    public function setting($id) {
        $model = userServices::findOrFail($id);
        return view('userServices.setting', ['model' => $model]);
    }

    public function changeUsername(Request $request) {
        $rules = self::username_validate($request->all());
        $messages = self::username_validation_message($request->all());
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi Kesalah Input',
                'errors' => $validator->messages()
            ], 400);
        }
        if($request->foto != null) {
            $file = $request->file('foto');
            $file_name = sha1($file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
            $file->move('../storage/app/public/file', $file_name);
        }
        if(isset($request->id_pelanggan)) {
            $user = userServices::findOrFail($request->id_pelanggan);
        } else {
            $user = userServices::findOrFail(Auth::user()->id);
        }
        if($user->update(['name' => $request->name, 'foto' => $file_name ?? null])) {
            return [
                'success' => true,
                'message' => 'Username Berhasil di Update'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Username Gagal di Update'
            ];
        }
    }
        
    public function changePassword(Request $request) {
        $rules = self::setting_validate($request->all());
        $messages = self::setting_validation_message($request->all());
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi Kesalah Input',
                'errors' => $validator->messages()
            ], 400);
        }
        if(isset($request->id_pelanggan)) {
            $user = userServices::findOrFail($request->id_pelanggan);
        } else {
            $user = userServices::findOrFail(Auth::user()->id);
        }
        if(isset($request->old_password)) {
            if (!Hash::check($request->old_password, $user->password)) {
                return [
                    'success' => false,
                    'message' => 'Old Password Salah'
                ];
            } else {
                $user->password = Hash::make($request->new_password);
                if($user->save()) {
                    return [
                        'success' => true,
                        'message' => 'Password Berhasil di Update'
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Password Gagal di Update'
                    ];
                }
            }
        } else {
            $user->password = Hash::make($request->new_password);
            if($user->save()) {
                return [
                    'success' => true,
                    'message' => 'Password Berhasil di Update'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Password Gagal di Update'
                ];
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
            'password' => $request->password,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->name,
        ];
        $data['password'] = bcrypt($data['password']);
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
            'password' => $request->password,
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->name,
        ];
      
        $userSurvices = userServices::findOrFail($id);
        if(!empty($data['password'])){ 
            $data['password'] = bcrypt($data['password']);     
        }
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
            'password' => 'required|same:konfirmasi_password',
        ];
    } 

    public function validation_message() {
        $messages = [];
        $messages['name.required'] = 'Username Harus Di Isi';
        $messages['password.required'] = 'Password Harus Di Isi';
        $messages['password.same:komfirmasi_password'] = 'Konfirmasi Password Harus Sama';
        return $messages;
    }

    public function setting_validation_message($data) {
        $messages = [];
        if(isset($data['old_password'])) {
            $messages['old_password.required'] = 'Old Password Harus Di Isi';
            $messages['new_password.required'] = 'New Password Harus Di Isi';
            $messages['konfirmasi_password.required'] = 'Konfirmasi Password Harus Di Isi';
        } else {
            $messages['new_password.required'] = 'New Password Harus Di Isi';
            $messages['konfirmasi_password.required'] = 'Konfirmasi Password Harus Di Isi';
        }
        return $messages;
    }

    public function setting_validate($data) {
        if(isset($data['old_password'])) {
            return [
                'old_password' => 'required',
                'new_password' => 'required',
                'konfirmasi_password' => 'required',
            ];
        } else {
            return [
                'new_password' => 'required',
                'konfirmasi_password' => 'required',
            ];
        }
    }

    public function username_validate() {
        return [
            'name' => 'required',
        ];
    }

    public function username_validation_message() {
        $messages = [];
        $messages['name.required'] = 'Username Harus Di Isi';
        return $messages;
    }
     
}