<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table            = 'anggota';
    protected $primaryKey       = 'id_anggota';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_tim', 'nickname', 'peran', 'peringkat_game'];

    public function getAnggotaByTim($id_tim)
    {
        return $this->where('id_tim', $id_tim)->findAll();
    }
    
    public function getAnggotaWithTim()
    {
        return $this->select('anggota.*, tim.nama_tim')
                    ->join('tim', 'tim.id_tim = anggota.id_tim')
                    ->findAll();
    }
}
