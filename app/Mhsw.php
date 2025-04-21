<?php
//Polymorphism
abstract class Lihat{
	abstract protected function tampil($x);
}

class Fakultas extends Lihat {
	//kita protect agar hanya dapat digunakan oleh class anaknya (Encapsulasi)
	protected $db;
    public function __construct()
     {
        try {
			$this->db = new PDO("mysql:host=localhost;dbname=dbakademik", "root", ""); 
		} 
			catch (PDOException $e) { die ("Ditemukan Error : " . $e->getMessage());
        }
    }
	//Induk yang akan di wariskan
	public function tampilfakultas($id_fak){
		$sql = "SELECT nama_fakultas from fakultas where id_fakultas = '$id_fak'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();

		$rows = $stmt->fetch();
		$data = $rows['nama_fakultas'];
		return $data;
	}
	//bentuk method tampil pada klas jurusan
	public function tampil($vfak)
	 {
		$where = "";
		if($vfak!= '') $where = "WHERE id_fakultas = $vfak"; 
		$sql = "SELECT * from fakultas $where";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$data = [];
		while ($rows = $stmt->fetch()) {
		  $data[] = $rows;
		}
		return $data;
	}	
}

class Jurusan extends Fakultas {
	//Induk yang akan di wariskan
	public function tampiljurusan($id_jrs){
		$sql = "SELECT nama_jurusan from jurusan where id_jurusan = '$id_jrs'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();

		$rows = $stmt->fetch();
		$data = $rows['nama_jurusan'];
		return $data;
	}
	//bentuk method tampil pada klas jurusan
	public function tampil($vjrs)
	 {
		$where = "";
		if($vjrs!= '') $where = "WHERE id_jurusan = $vjrs"; 
		$sql = "SELECT * from jurusan $where";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$data = [];
		while ($rows = $stmt->fetch()) {
		  $data[] = $rows;
		}
		return $data;
	}	
}

class Mahasiswa extends Jurusan {
	public static string $universitas = "UNDHA - AUB";
	//bentuk method tampil pada klas Mahasiswa
	public function tampil($idmhsw)
	 {
		$where = "";
		if($idmhsw!= '') $where = "WHERE id_mahasiswa = $idmhsw"; 
		$sql = "SELECT * from tb_mhsw $where";

		$stmt = $this->db->prepare($sql);
		$stmt->execute();
	   
		$data = [];
		while ($rows = $stmt->fetch()) {
		  //Mewarisi sifat jurusan
		  $rows['nama_jurusan'] = $this->tampiljurusan($rows['id_jurusan']);
		  //Mewarisi sifat fakultas
		  $rows['nama_fakultas'] = $this->tampilfakultas($rows['id_fakultas']);
		  $data[] = $rows;
		}
		return $data;
	}
	 
    public function simpan()
    {
        $sql = "insert into tb_mhsw values ('','".$_GET['nim']."','".$_GET['nama']."','".$_GET['alamat']."','".$_GET['jurusan']."','".$_GET['fakultas']."')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        echo "DATA BERHASIL DISIMPAN !";
    } 

    public function hapus()
    {
		try{
			$sqls = "delete from tb_mhsw where mhsw_id='".$_GET['mhsw_id']."'";
			$stmts = $this->db->prepare($sqls);
			$status = $stmts->execute();
			echo "DATA BERHASIL DIHAPUS !";
			if (!$status) throw new Exception('terdapat error');
		}catch (Exception $e) {
			die ("Maaf Error, " . $e->getMessage());
		}
    }      
    public function tampil_update()
    {
        $sql = "SELECT * FROM tb_mhsw where mhsw_id='".$_GET['mhsw_id']."'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = [];
        while ($rows = $stmt->fetch()) {
            $data[] = $rows;
            }
        return $data;
    }
    public function update($id, $nim,$nama,$alamat,$id_jurusan,$id_fakultas)
    {
        $sql = "update tb_mhsw set mhsw_nim='".$nim."', mhsw_nama='".$nama."', mhsw_alamat='".$alamat."', id_jurusan=".$id_jurusan.", id_fakultas=".$id_fakultas." where mhsw_id='".$id."'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        echo "DATA BERHASIL DIUPDATE !";
    }   
    public function tampil_cari($nim,$nama,$alamat)
    {
        if($nim!='') $a = "or mhsw_nim like '%".$nim."%' ";
        else $a = '';
        if($nama!='') $b = "or mhsw_nama like '%".$nama."%' ";
        else $b = '';        
        if($alamat!='') $c = "or mhsw_alamat like '%".$alamat."%' ";
        else $c = ''; 
        $sql = "SELECT * FROM tb_mhsw where mhsw_id is null $a $b $c";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = [];
        while ($rows = $stmt->fetch()) {
		  //Mewarisi sifat jurusan
		  $rows['nama_jurusan'] = $this->tampiljurusan($rows['id_jurusan']);
		  //Mewarisi sifat fakultas
		  $rows['nama_fakultas'] = $this->tampilfakultas($rows['id_fakultas']);			
            $data[] = $rows;
            }
        return $data;
    }
 }
 
