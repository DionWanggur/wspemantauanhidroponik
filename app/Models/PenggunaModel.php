<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pengguna';
    protected $primaryKey       = 'idPengguna';
    

    public function validateUser($enteredPass, $db_pass)
    {
        $data = [
            'password'=>  'Login Gagal, Coba Periksa Kembali Password Anda !',
            'login' => false
        ];

        if (hash_equals(hash('sha256', $enteredPass), $db_pass)) {
            $data['password'] = 'Login Sukses';
            $data['login'] = true;
        } 

        return $data;
    }

}
