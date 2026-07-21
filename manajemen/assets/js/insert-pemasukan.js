document.getElementById("formPemasukan").addEventListener("submit", function(e) {
    e.preventDefault();

    const tanggal = document.getElementById("tanggal").value;
    const rekening = document.getElementById("rekening").value;
    const nominal = document.getElementById("nominal").value;
    const keterangan = document.getElementById("keterangan").value;

    const createdDate = new Date().toISOString().slice(0,10);

    fetch("https://wnj.id/manajemen/api/insert-pemasukan.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            tanggal: tanggal,
            rekening: rekening,
            nominal: nominal,
            keterangan: keterangan
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.href = "https://wnj.id/manajemen/detail_pemasukan.php?tanggal=" + tanggal + "&created_date=" + createdDate;
    })
    .catch(error => {
        alert("Terjadi kesalahan: " + error);
        console.error(error);
    });
});