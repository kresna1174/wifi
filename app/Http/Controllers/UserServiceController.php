<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\userServices;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Validation;

class UserServiceController extends Controller
{
    public function index() {
        return view('userServices.index');
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
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'deleted' => 0,
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
        $validator = Validation::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi Kesalahan Input',
                'errors' => $validator->messages()
            ], 400);
        }
        $data = [
            'username' => $request->username,
            'password' => bcrypt($request('password')),
            'deleted' => 0,
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
            'username' => 'required',
            'password' => 'required',
        ];
    } 

    public function validation_message() {
        $messages = [];
        $messages['username.required'] = 'Username Harus Di Isi';
        $messages['password.required'] = 'Password Harus Di Isi';
        return $messages;
    } 
}
