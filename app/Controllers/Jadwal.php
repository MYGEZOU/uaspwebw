<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\TurnamenModel;
use App\Models\TimModel;

class Jadwal extends BaseController
{
    public function index()
    {
        $peran = session()->get('peran');
        if (!in_array($peran, ['Admin', 'AdminGame'])) {
            return redirect()->to('dashboard')->with('error', 'Akses ditolak.');
        }

        $model = new JadwalModel();
        $data['jadwal'] = $model->getJadwalWithDetail();
        $data['title'] = 'Jadwal Pertandingan';
        return view('jadwal/index', $data);
    }
    
    public function peserta()
    {
        $this->checkRole('Peserta');
        
        $model = new JadwalModel();
        $id_tim = session()->get('id_tim');
        
        if ($id_tim) {
            $data['jadwal'] = $model->getJadwalByTim($id_tim);
        } else {
            $data['jadwal'] = [];
        }
        
        $data['title'] = 'Jadwal Tim Saya';
        return view('jadwal/peserta', $data);
    }

    public function tambah()
    {
        $peran = session()->get('peran');
        if (!in_array($peran, ['Admin', 'AdminGame'])) {
            return redirect()->to('dashboard')->with('error', 'Akses ditolak.');
        }

        $turnamenModel = new TurnamenModel();
        $timModel = new TimModel();
        
        $data['turnamen'] = $turnamenModel->findAll();
        $data['tim'] = $timModel->findAll();
        $data['title'] = 'Tambah Jadwal';
        return view('jadwal/form', $data);
    }

    public function simpan()
    {
        $peran = session()->get('peran');
        if (!in_array($peran, ['Admin', 'AdminGame'])) {
            return redirect()->to('dashboard')->with('error', 'Akses ditolak.');
        }

        $model = new JadwalModel();
        $data = [
            'id_turnamen'    => $this->request->getVar('id_turnamen'),
            'id_tim_1'       => $this->request->getVar('id_tim_1'),
            'id_tim_2'       => $this->request->getVar('id_tim_2'),
            'jadwal_tanding' => $this->request->getVar('jadwal_tanding'),
            'babak'          => $this->request->getVar('babak'),
        ];
        
        $model->insert($data);
        return redirect()->to('jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $peran = session()->get('peran');
        if (!in_array($peran, ['Admin', 'AdminGame'])) {
            return redirect()->to('dashboard')->with('error', 'Akses ditolak.');
        }

        $model = new JadwalModel();
        $turnamenModel = new TurnamenModel();
        $timModel = new TimModel();
        
        $data['jadwal'] = $model->find($id);
        $data['turnamen'] = $turnamenModel->findAll();
        $data['tim'] = $timModel->findAll();
        $data['title'] = 'Edit Jadwal';
        return view('jadwal/form', $data);
    }

    public function update($id)
    {
        $peran = session()->get('peran');
        if (!in_array($peran, ['Admin', 'AdminGame'])) {
            return redirect()->to('dashboard')->with('error', 'Akses ditolak.');
        }

        $model = new JadwalModel();
        $data = [
            'id_turnamen'    => $this->request->getVar('id_turnamen'),
            'id_tim_1'       => $this->request->getVar('id_tim_1'),
            'id_tim_2'       => $this->request->getVar('id_tim_2'),
            'jadwal_tanding' => $this->request->getVar('jadwal_tanding'),
            'babak'          => $this->request->getVar('babak'),
        ];
        
        $model->update($id, $data);
        return redirect()->to('jadwal')->with('success', 'Jadwal berhasil diupdate.');
    }

    public function hapus($id)
    {
        $peran = session()->get('peran');
        if (!in_array($peran, ['Admin', 'AdminGame'])) {
            return redirect()->to('dashboard')->with('error', 'Akses ditolak.');
        }

        $model = new JadwalModel();
        $model->delete($id);
        return redirect()->to('jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }
}