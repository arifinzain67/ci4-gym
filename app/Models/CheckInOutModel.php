<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckInOutModel extends Model
{
    protected $table = 'tb_check_in_out';
    protected $primaryKey = 'id_check';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['id_member', 'check_in', 'check_out', 'status'];

    public function getActiveMembers()
    {
        return $this->select('tb_check_in_out.*, tb_members.name as member_name, tb_members.member_code')
            ->join('tb_members', 'tb_members.id_member = tb_check_in_out.id_member')
            ->where('tb_check_in_out.status', 'active')
            ->findAll();
    }

    public function getCheckInHistory()
    {
        return $this->select('tb_check_in_out.*, tb_members.name as member_name, tb_members.member_code')
            ->join('tb_members', 'tb_members.id_member = tb_check_in_out.id_member')
            ->orderBy('tb_check_in_out.check_in', 'DESC')
            ->findAll();
    }

    public function getCheckInHistoryByMember($memberId)
    {
        return $this->where('id_member', $memberId)
            ->orderBy('check_in', 'DESC')
            ->findAll();
    }
}
