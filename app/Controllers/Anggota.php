<?php

namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\TimModel;

class Anggota extends BaseController
{
    public function index()
    {
        $this->checkRole('Admin');
        
        $model = new AnggotaModel();
        $data['anggota'] = $model->getAnggotaWithTim();
        $data['title'] = 'Manajemen Anggota';
        return view('anggota/index', $data);
    }

    public function tambah()
    {
        $this->checkRole('Admin');
        
        $timModel = new TimModel();
        $data['tim_list'] = $timModel->findAll();
        $data['title'] = 'Tambah Anggota';
        return view('anggota/form', $data);
    }

    // -------------------------------------------------------
    // AKSES PESERTA: tambah anggota ke tim sendiri
    // -------------------------------------------------------
    public function tambahPeserta()
    {
        $id_tim = session()->get('id_tim');
        if (!$id_tim) {
            return redirect()->to('tim/tambah')->with('error', 'Buat tim terlebih dahulu sebelum menambah anggota.');
        }

        $data['title']  = 'Tambah Anggota Tim';
        $data['id_tim'] = $id_tim;
        return view('anggota/form_peserta', $data);
    }

    public function simpanPeserta()
    {
        $id_tim = session()->get('id_tim');
        if (!$id_tim) {
            return redirect()->to('tim/tambah')->with('error', 'Buat tim terlebih dahulu.');
        }

        $rules = [
            'nickname' => 'required|is_unique[anggota.nickname]',
            'peran'    => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $model = new AnggotaModel();
        $data = [
            'id_tim'         => $id_tim,
            'nickname'       => $this->request->getVar('nickname'),
            'peran'          => $this->request->getVar('peran'),
            'peringkat_game' => $this->request->getVar('peringkat_game'),
        ];

        $model->insert($data);
        return redirect()->to('tim/profil')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function editPeserta($id)
    {
        $id_tim = session()->get('id_tim');
        if (!$id_tim) {
            return redirect()->to('tim/profil')->with('error', 'Tim tidak ditemukan.');
        }

        $model = new AnggotaModel();
        $anggota = $model->find($id);

        if (!$anggota || $anggota['id_tim'] != $id_tim) {
            return redirect()->to('tim/profil')->with('error', 'Anggota tidak ditemukan atau tidak berhak mengedit.');
        }

        $data['title'] = 'Edit Anggota Tim';
        $data['anggota'] = $anggota;
        return view('anggota/form_peserta', $data);
    }

    public function updatePeserta($id)
    {
        $id_tim = session()->get('id_tim');
        if (!$id_tim) {
            return redirect()->to('tim/profil')->with('error', 'Tim tidak ditemukan.');
        }

        $model = new AnggotaModel();
        $anggota = $model->find($id);

        if (!$anggota || $anggota['id_tim'] != $id_tim) {
            return redirect()->to('tim/profil')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'nickname' => "required|is_unique[anggota.nickname,id_anggota,{$id}]",
            'peran'    => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $data = [
            'nickname'       => $this->request->getVar('nickname'),
            'peran'          => $this->request->getVar('peran'),
            'peringkat_game' => $this->request->getVar('peringkat_game'),
        ];

        $model->update($id, $data);
        return redirect()->to('tim/profil')->with('success', 'Anggota berhasil diupdate.');
    }

    public function hapusPeserta($id)
    {
        $id_tim = session()->get('id_tim');
        if (!$id_tim) {
            return redirect()->to('tim/profil')->with('error', 'Tim tidak ditemukan.');
        }

        $model = new AnggotaModel();
        $anggota = $model->find($id);

        if (!$anggota || $anggota['id_tim'] != $id_tim) {
            return redirect()->to('tim/profil')->with('error', 'Akses ditolak.');
        }

        $model->delete($id);
        return redirect()->to('tim/profil')->with('success', 'Anggota berhasil dihapus.');
    }

    public function simpan()
    {
        $this->checkRole('Admin');
        
        $rules = [
            'id_tim'   => 'required',
            'nickname' => 'required|is_unique[anggota.nickname]',
            'peran'    => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $model = new AnggotaModel();
        $data = [
            'id_tim'         => $this->request->getVar('id_tim'),
            'nickname'       => $this->request->getVar('nickname'),
            'peran'          => $this->request->getVar('peran'),
            'peringkat_game' => $this->request->getVar('peringkat_game'),
        ];
        $model->insert($data);
        
        $redirectUrl = $this->request->getVar('redirect') ?? 'anggota';
        return redirect()->to($redirectUrl)->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->checkRole('Admin');
        
        $model = new AnggotaModel();
        $timModel = new TimModel();
        
        $data['anggota'] = $model->find($id);
        $data['tim_list'] = $timModel->findAll();
        $data['title'] = 'Edit Anggota';
        return view('anggota/form', $data);
    }

    public function update($id)
    {
        $this->checkRole('Admin');
        
        $rules = [
            'id_tim'   => 'required',
            'nickname' => "required|is_unique[anggota.nickname,id_anggota,{$id}]",
            'peran'    => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $model = new AnggotaModel();
        $data = [
            'id_tim'         => $this->request->getVar('id_tim'),
            'nickname'       => $this->request->getVar('nickname'),
            'peran'          => $this->request->getVar('peran'),
            'peringkat_game' => $this->request->getVar('peringkat_game'),
        ];
        
        $model->update($id, $data);
        
        $redirectUrl = $this->request->getVar('redirect') ?? 'anggota';
        return redirect()->to($redirectUrl)->with('success', 'Anggota berhasil diupdate.');
    }

    public function hapus($id)
    {
        $this->checkRole('Admin');
        
        $model = new AnggotaModel();
        $model->delete($id);
        
        $redirectUrl = $this->request->getVar('redirect') ?? 'anggota';
        return redirect()->to($redirectUrl)->with('success', 'Anggota berhasil dihapus.');
    }
}