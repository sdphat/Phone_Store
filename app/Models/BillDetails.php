<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetails extends Model
{
    use HasFactory;
    protected $table="chitiethoadon";
    // Hàm xóa theo id hóa đơn và id sản phẩm
    function delete_by_2id($id, $id2)
    {
        return $this->remove($this->_table_name, $this->_key . "='" . $id . "' AND " . $this->_key2 . "='" . $id2 . "'");
    }

    // Hàm cập nhật theo id hóa đơn + id sản phẩm
    function update_by_2id($data, $id, $id2)
    {
        return $this->update($this->_table_name, $data, $this->_key . "='" . $id . "' AND " . $this->_key2 . "='" . $id2 . "'");
    }

    // hàm select theo id hóa đơn + id sản phẩm
    function select_by_2id($select, $id, $id2)
    {
        $sql = "select $select from " . $this->_table_name . " where " . $this->_key . " = '" . $id . "' AND " . $this->_key2 . "='" . $id2 . "'";
        return $this->get_row($sql);
    }

    // hàm get all chi tiết có mã hóa đơn truyền vào
    function select_all_in_hoadon($id)
    {
        $sql = "select * from " . $this->_table_name . " where " . $this->_key . " ='" . $id . "'";
        return $this->get_list($sql);
    }
}
