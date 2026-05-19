<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table            = 'jadwal';
    protected $primaryKey       = 'id_jadwal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_turnamen', 'id_tim_1', 'id_tim_2', 'jadwal_tanding', 'babak'];

    public function getJadwalWithDetail()
    {
        return $this->select('jadwal.*, turnamen.nama_turnamen, game.nama_game as game, tim1.nama_tim as nama_tim_1, tim2.nama_tim as nama_tim_2, skor.skor_tim_1, skor.skor_tim_2')
                    ->join('turnamen', 'turnamen.id_turnamen = jadwal.id_turnamen')
                    ->join('game', 'game.id_game = turnamen.id_game', 'left')
                    ->join('tim as tim1', 'tim1.id_tim = jadwal.id_tim_1')
                    ->join('tim as tim2', 'tim2.id_tim = jadwal.id_tim_2')
                    ->join('skor', 'skor.id_jadwal = jadwal.id_jadwal', 'left')
                    ->findAll();
    }

    public function getJadwalByTurnamen($id_turnamen)
    {
        return $this->select('jadwal.*, tim1.nama_tim as nama_tim_1, tim2.nama_tim as nama_tim_2, skor.skor_tim_1, skor.skor_tim_2')
                    ->join('tim as tim1', 'tim1.id_tim = jadwal.id_tim_1')
                    ->join('tim as tim2', 'tim2.id_tim = jadwal.id_tim_2')
                    ->join('skor', 'skor.id_jadwal = jadwal.id_jadwal', 'left')
                    ->where('jadwal.id_turnamen', $id_turnamen)
                    ->findAll();
    }

    public function getJadwalHariIni()
    {
        $today = date('Y-m-d');
        return $this->select('jadwal.*, turnamen.nama_turnamen, game.nama_game as game, tim1.nama_tim as nama_tim_1, tim2.nama_tim as nama_tim_2')
                    ->join('turnamen', 'turnamen.id_turnamen = jadwal.id_turnamen')
                    ->join('game', 'game.id_game = turnamen.id_game', 'left')
                    ->join('tim as tim1', 'tim1.id_tim = jadwal.id_tim_1')
                    ->join('tim as tim2', 'tim2.id_tim = jadwal.id_tim_2')
                    ->like('jadwal_tanding', $today)
                    ->findAll();
    }

    public function getJadwalByTim($id_tim)
    {
        return $this->select('jadwal.*, turnamen.nama_turnamen, game.nama_game as game, tim1.nama_tim as nama_tim_1, tim2.nama_tim as nama_tim_2, skor.skor_tim_1, skor.skor_tim_2, skor.id_tim_pemenang')
                    ->join('turnamen', 'turnamen.id_turnamen = jadwal.id_turnamen')
                    ->join('game', 'game.id_game = turnamen.id_game', 'left')
                    ->join('tim as tim1', 'tim1.id_tim = jadwal.id_tim_1')
                    ->join('tim as tim2', 'tim2.id_tim = jadwal.id_tim_2')
                    ->join('skor', 'skor.id_jadwal = jadwal.id_jadwal', 'left')
                    ->groupStart()
                        ->where('jadwal.id_tim_1', $id_tim)
                        ->orWhere('jadwal.id_tim_2', $id_tim)
                    ->groupEnd()
                    ->findAll();
    }
}
