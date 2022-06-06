<?php
namespace App\Controllers;
header('Access-Control-Allow-Origin: *');

use App\Models\PemantauanModel;
use CodeIgniter\RESTful\ResourceController;

class Pemantauan extends ResourceController
{

    public function __construct()
    {
        $this->Pemantauan = new PemantauanModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {

        $dt = $this->Pemantauan->getWaktuPemantauanTerakhir(); //urutkan data dari besar ke kecil (DES)
        return $this->respond($dt);
    }

    public function node()
    {
        $dt = $this->Pemantauan->getAllNode();
        return $this->respond($dt);
    }

    public function history()
    {
        $validation = $this->validate([
            'awal' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Silahkan Masukan Tanggal Awal Dari Hasil Pemantauan Yang Ingin Dicari! ',
                    'valid_date' => 'Format Tanggal Salah, Coba Dengan Format Y-M-D !'
                ]
            ],
            'akhir' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Silahkan Masukan Tanggal Akhir Dari Hasil Pemantauan Yang Ingin Dicari! ',
                    'valid_date' => 'Format Tanggal Salah, Coba Dengan Format Y-M-D !'
                ]
            ],
            'namaNode' => [
                'rules' => 'required|is_not_unique[nodeSensor.namaNode]',
                'errors' => [
                    'required' => 'Silahkan Masukan Nama Node !',
                    'is_not_unique' => 'Node Sensor Tidak Ditemukan, Silahkan Periksa Kembali Inputan Anda'
                ]
            ]

        ]);

        if (!$validation) {
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors());
        } else {
            $awal = $this->request->getVar('awal');
            $akhir = $this->request->getVar('akhir');
            $namaNode = $this->request->getVar('namaNode');

            return $this->respond($this->Pemantauan->getHistoryData($awal, $akhir, $namaNode));
        }
    }

    public function statistics()
    {
        $validation = $this->validate([
            'namaNode' => [
                'rules' => 'required|is_not_unique[nodeSensor.namaNode]',
                'errors' => [
                    'required' => 'Silahkan Masukan Nama Node !',
                    'is_not_unique' => 'Node Sensor Tidak Ditemukan, Silahkan Periksa Kembali Inputan Anda'
                ]
            ],
            'parameter' =>[
                'rules' => 'required',
                'errors' => [
                    'required' => 'Silahkan Pilih Parameter Pemantauan !!!'
                ],
            ]

        ]);

        if (!$validation) {
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors());
        } else {
            $namaNode = $this->request->getVar('namaNode');
            $parameter = $this->request->getVar('parameter');

            return $this->respond($this->Pemantauan->getStatisticsData($namaNode,$parameter));
        }
    }


    public function monitoring()
    {
        $validation = $this->validate([
            'namaNode' => [
                'rules' => 'required|is_not_unique[nodeSensor.namaNode]',
                'errors' => [
                    'required' => 'Silahkan Masukan Nama Node !',
                    'is_not_unique' => 'Node Sensor Tidak Ditemukan, Silahkan Periksa Kembali Inputan Anda'
                ]
            ]

        ]);

        if (!$validation) {
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors());
        } else {
            $namaNode = $this->request->getVar('namaNode');

            return $this->respond($this->Pemantauan->getSensingData($namaNode));
        }
    }
}
