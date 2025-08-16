function jumlah_tagihan($pemakaian, $tarif_per_kwh) {
    return $pemakaian * $tarif_per_kwh;
}
echo jumlah_tagihan(-100, 900);// Output: -90000