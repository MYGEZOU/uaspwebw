<?php

namespace App\Controllers;

use App\Models\TurnamenModel;
use App\Models\DaftarModel;
use App\Models\TimModel;

class Turnamen extends BaseController
{
    public function index()
    {
        $peran = session()->get('peran');
        if (!in_array($peran, ['Admin', 'AdminGame'])) {
            return redirect()->to('dashboard')->with('error', 'Akses ditolak.');
        }
        
        $model = new TurnamenModel();
        // Join game table to get nama_game
        $data['turnamen'] = $model->select('turnamen.*, game.nama_game as game')
                                  ->join('game', 'game.id_game = turnamen.id_game', 'left')
                                  ->findAll();
        $data['title'] = 'Kelola Turnamen';
        return view('turnamen/index', $data);
    }
    
    public function peserta()
    {
        $this->checkRole('Peserta');
        
        $model = new TurnamenModel();
        $daftarModel = new DaftarModel();
        $id_tim = session()->get('id_tim');
        
        $data['turnamen'] = $model->select('turnamen.*, game.nama_game as game')
                                  ->join('game', 'game.id_game = turnamen.id_game', 'left')
                                  ->where('status !=', 'Selesai')
                                  ->findAll();
        $data['title'] = 'Turnamen Tersedia';
        $data['pendaftaran_saya'] = [];
        if ($id_tim) {
            $pendaftaran = $daftarModel->where('id_tim', $id_tim)->findAll();
            foreach ($pendaftaran as $p) {
                $data['pendaftaran_saya'][] = $p['id_turnamen'];
            }
        }
        return view('turnamen/peserta', $data);
    }

    public function tambah()
    {
        $this->checkRole('Admin');
        
        $gameModel = new \App\Models\GameModel();
        $data['games'] = $gameModel->findAll();
        $data['title'] = 'Tambah Turnamen';
        return view('turnamen/form', $data);
    }

    public function simpan()
    {
        $this->checkRole('Admin');
        
        $model = new TurnamenModel();
        $data = [
            'nama_turnamen'     => $this->request->getVar('nama_turnamen'),
            'id_game'           => $this->request->getVar('id_game'),
            'tanggal_mulai'     => $this->request->getVar('tanggal_mulai'),
            'biaya_pendaftaran' => $this->request->getVar('biaya_pendaftaran'),
            'status'            => $this->request->getVar('status'),
        ];
        
        $model->insert($data);
        return redirect()->to('turnamen')->with('success', 'Turnamen berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->checkRole('Admin');
        
        $model = new TurnamenModel();
        $gameModel = new \App\Models\GameModel();
        $data['turnamen'] = $model->find($id);
        $data['games'] = $gameModel->findAll();
        $data['title'] = 'Edit Turnamen';
        return view('turnamen/form', $data);
    }

    public function update($id)
    {
        $this->checkRole('Admin');
        
        $model = new TurnamenModel();
        $data = [
            'nama_turnamen'     => $this->request->getVar('nama_turnamen'),
            'id_game'           => $this->request->getVar('id_game'),
            'tanggal_mulai'     => $this->request->getVar('tanggal_mulai'),
            'biaya_pendaftaran' => $this->request->getVar('biaya_pendaftaran'),
            'status'            => $this->request->getVar('status'),
        ];
        
        $model->update($id, $data);
        return redirect()->to('turnamen')->with('success', 'Turnamen berhasil diupdate.');
    }

    public function hapus($id)
    {
        $this->checkRole('Admin');
        
        $model = new TurnamenModel();
        $model->delete($id);
        return redirect()->to('turnamen')->with('success', 'Turnamen berhasil dihapus.');
    }
}