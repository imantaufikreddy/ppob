<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);/*
 * Script ini digunakan untuk mencetak tagihan listrik ke format PDF oleh admin atau pelanggan PLN.
 * Alur:
 * - Memastikan user sudah login (session_check.php) dan koneksi database aktif
 * - Mengambil id_tagihan dari parameter GET
 * - Mengambil data tagihan dari database
 * - Membuat file PDF tagihan menggunakan FPDF
 * - Menampilkan detail tagihan dalam format PDF
 */
include '../config/session_check.php';
require '../assets/fpdf/fpdf.php';
include '../config/koneksi.php';

$id = $_GET['id']; // id_tagihan

$query = "SELECT 
    t.*, 
    p.nama, p.alamat, p.daya,
    tanggal, pg.meter_awal, pg.meter_akhir,
    (pg.meter_akhir - pg.meter_awal) AS pemakaian,
    tf.tarif_per_kwh
  FROM tagihan t
  JOIN penggunaan pg ON t.id_penggunaan = pg.id_penggunaan
  JOIN pelanggan p ON pg.id_pelanggan = p.id_pelanggan
  JOIN tarif tf ON p.daya = tf.daya
  WHERE t.id_tagihan = '$id'";

$data = mysqli_fetch_assoc(mysqli_query($koneksi, $query));

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'TAGIHAN LISTRIK PASCA BAYAR',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','',12);
$pdf->Cell(50,10,'ID Tagihan',0,0); 
$pdf->Cell(70,10,': '.$data['id_tagihan'],0,1);

$pdf->Cell(50,10,'Nama Pelanggan',0,0); 
$pdf->Cell(70,10,': '.$data['nama'],0,1);

$pdf->Cell(50,10,'Alamat',0,0); 
$pdf->Cell(70,10,': '.$data['alamat'],0,1);

$pdf->Cell(50,10,'Daya (Watt)',0,0); 
$pdf->Cell(70,10,': '.$data['daya'],0,1);

$pdf->Cell(50,10,'Tanggal',0,0); 
$pdf->Cell(70,10,': '.$data['tanggal'],0,1);

$pdf->Cell(50,10,'Meter Awal - Akhir',0,0); 
$pdf->Cell(70,10,': '.$data['meter_awal'].' - '.$data['meter_akhir'],0,1);

$pdf->Cell(50,10,'Pemakaian (kWh)',0,0); 
$pdf->Cell(70,10,': '.$data['pemakaian'],0,1);

$pdf->Cell(50,10,'Tarif per kWh',0,0); 
$pdf->Cell(70,10,': Rp '.number_format($data['tarif_per_kwh']),0,1);

$pdf->Cell(50,10,'Total Tagihan',0,0); 
$pdf->Cell(70,10,': Rp '.number_format($data['jumlah_tagihan']),0,1);

$pdf->Cell(50,10,'Status',0,0); 
$pdf->Cell(70,10,': '.$data['status'],0,1);





$pdf->Ln(10);
$pdf->Cell(0,10,'Terima kasih telah membayar tagihan tepat waktu.',0,1,'C');

$pdf->Output();
?> 