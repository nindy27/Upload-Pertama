<?php

include "app/Mhsw.php";

$mhsw = new Mahasiswa();
$rows = $mhsw->tampil('');
if(isset($_GET['simpan'])) $vsimpan =$_GET['simpan'];
else $vsimpan ='';
if(isset($_GET['update'])) $vupdate =$_GET['update'];
else $vupdate ='';
if(isset($_GET['reset'])) $vreset =$_GET['reset'];
else $vreset ='';
if(isset($_GET['cari'])) $vcari =$_GET['cari'];
else $vcari ='';
if(isset($_GET['aksi'])) $vaksi =$_GET['aksi'];
else $vaksi ='';
if(isset($_GET['id'])) $vid =$_GET['id'];
else $vid ='';
if(isset($_GET['nim'])) $vnim =$_GET['nim'];
else $vnim ='';
if(isset($_GET['nama'])) $vnama =$_GET['nama'];
else $vnama ='';
if(isset($_GET['alamat'])) $valamat =$_GET['alamat'];
else $valamat ='';
if(isset($_GET['jurusan'])) $vjrs =$_GET['jurusan'];
else $vjrs ='';
if(isset($_GET['fakultas'])) $vfak =$_GET['fakultas'];
else $vfak ='';

if($vsimpan=='simpan' && ($vnim <>''||$vnama <>''||$valamat <>'')){
    $mhsw->simpan();
    $rows = $mhsw->tampil('');
    $vid ='';
    $vnim ='';
    $vnama ='';
    $valamat ='';
	$vjrs ='';
	$vfak ='';
}

if($vaksi=="hapus")  {
    $mhsw->hapus();
    $rows = $mhsw->tampil('');
}

 if($vaksi=="lihat_update")  {
    $urows = $mhsw->tampil_update();
    foreach ($urows as $row) {
        $vid = $row['mhsw_id'];
        $vnim = $row['mhsw_nim'];
        $vnama = $row['mhsw_nama'];
        $valamat = $row['mhsw_alamat'];
		$vjrs = $row['id_jurusan'];
		$vfak = $row['id_fakultas'];
    }
 }

if ($vupdate=="update"){
    $mhsw->update($vid,$vnim,$vnama,$valamat,$vjrs,$vfak);
    $rows = $mhsw->tampil('');
    $vid ='';
    $vnim ='';
    $vnama ='';
    $valamat ='';
	$vjrs ='';
	$vfak ='';
}
if ($vreset=="reset"){
    $vid ='';
    $vnim ='';
    $vnama ='';
    $valamat ='';
	$vjrs ='';
	$vfak ='';	
}
 if($vcari=="cari")  {
    $rows = $mhsw->tampil_cari($vnim,$vnama,$valamat);
 }
	
	echo $mhsw::$universitas;
?>

<h1> Sistem Informasi </h1>
<form action="?" method="get">
<table>
    <tr><td>NIM</td><td>:</td><td>
        <input type="hidden" name="id" value="<?php echo $vid; ?>" /><input type="text" name="nim" value="<?php echo $vnim; ?>" /></td></tr>
    <tr><td>NAMA</td><td>:</td><td><input type="text" name="nama" value="<?php echo $vnama; ?>"/></td></tr>
    <tr><td>ALAMAT</td><td>:</td><td><input type="text" name="alamat" value="<?php echo $valamat; ?>"/></td></tr>
    <tr><td>JURUSAN</td><td>:</td><td>
		<select name="jurusan" id="jrs">
		<?php
		if($vjrs!=''){
			$jrs = new Jurusan();
			$jrows = $jrs->tampil($vjrs);
			foreach ($jrows as $jrow) {
			  echo "<option value=".$jrow['id_jurusan'].">$jrow[nama_jurusan]</option>";
			}
			$jrows = $jrs->tampil('');
			foreach ($jrows as $jrow) {
			  echo "<option value=".$jrow['id_jurusan'].">$jrow[nama_jurusan]</option>";
			}
		}else{ 
			echo "<option value=''>Pilih Jurusan</option>";
			$jrs = new Jurusan();
			$jrows = $jrs->tampil('');
			foreach ($jrows as $jrow) {
			  echo "<option value=".$jrow['id_jurusan'].">$jrow[nama_jurusan]</option>";
			}			
		}
		?>
		</select>
	</td></tr>
    <tr><td>FAKLUTAS</td><td>:</td><td>
		<select name="fakultas" id="fak">
		<?php
		if($vfak!=''){
			$fak = new Fakultas();
			$jrows = $fak->tampil($vfak);
			foreach ($jrows as $jrow) {
			  echo "<option value=".$jrow['id_fakultas'].">$jrow[nama_fakultas]</option>";
			}
			$jrows = $fak->tampil('');
			foreach ($jrows as $jrow) {
			  echo "<option value=".$jrow['id_fakultas'].">$jrow[nama_fakultas]</option>";
			}
		}else{
			$fak = new Fakultas();
			$jrows = $fak->tampil('');
			foreach ($jrows as $jrow) {
			  echo "<option value=".$jrow['id_fakultas'].">$jrow[nama_fakultas]</option>";
			}			
			echo "<option value=''>Pilih Fakultas</option>";
		}
		?>
		</select>	
	</td></tr>
	<tr><td></td><td></td><td>
    <input type="submit" name='simpan' value="simpan"/>
    <input type="submit" name='update' value="update"/>
    <input type="submit" name='reset' value="reset"/>
    <input type="submit" name='cari' value="cari"/>
    </td></tr>
</table>
</form>



    <table border="1px">
    <tr>
        <td>ID</td>
        <td>NIM</td>
        <td>NAMA</td>
        <td>ALAMAT</td>
		<td>JURUSAN</td>
		<td>FAKLUTAS</td>
        <td>AKSI</td>
    </tr>

    <?php foreach ($rows as $row) { ?>
        <tr>
            <td><?php echo $row['mhsw_id']; ?></td>
            <td><?php echo $row['mhsw_nim']; ?></td>
            <td><?php echo $row['mhsw_nama']; ?></td>
            <td><?php echo $row['mhsw_alamat']; ?></td>
			<td><?php echo $row['nama_jurusan']; ?></td>
			<td><?php echo $row['nama_fakultas']; ?></td>
            <td><a href="?mhsw_id=<?php echo $row['mhsw_id']; ?>&aksi=hapus">Hapus</a>&nbsp;&nbsp;&nbsp;
                <a href="?mhsw_id=<?php echo $row['mhsw_id']; ?>&aksi=lihat_update">Update</a></td>

        </tr>
    <?php } ?>
 </table>

