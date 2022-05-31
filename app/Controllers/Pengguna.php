<?php

namespace App\Controllers;

use App\Models\PenggunaModel;
use CodeIgniter\Database\Config;
use CodeIgniter\RESTful\ResourceController;
use ParagonIE\Sodium\Core\BLAKE2b;

class Pengguna extends ResourceController
{

    public function __construct()
    {
        $this->pengguna = new PenggunaModel();
    }
    
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {

        return $this->respondNoContent($message = "No Content");
    }

    public function authenticate()
    {
        $validation = $this->validate([
            'username' => [
                'rules' => 'required|is_not_unique[pengguna.username]',
                'errors' => [
                    'required' => 'Silahkan Masukan Username Pengguna !',
                    'is_not_unique' => 'Pengguna Belum Terdaftar, Silahkan Minta Admin Untuk Proses Registrasi !'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]|max_length[12]',
                'errors' => [
                    'required' => 'Silahkan Masukan Password !',
                    'min_length' => 'Password Setidaknya Terdiri Dari 5 Karakter',
                    'max_length' => 'Password Tidak Lebih Dari 12 Karakter'
                ]
            ]
        ]);

        if (!$validation) {
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors());
            
        } else {
            $username = $this->request->getVar('username');
            $password = $this->request->getvar('password');
            $pengguna_info = $this->pengguna->where('username', $username)->first();

            return $this->respond( $this->pengguna->validateUser($password, $pengguna_info['password']));
        }
    }

    
}
