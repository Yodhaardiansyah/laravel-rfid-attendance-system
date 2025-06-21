document.addEventListener("DOMContentLoaded", function () {
    // ✅ Pastikan Form Menggunakan Action yang Benar
    let form = document.getElementById("addSantriForm");
    let formAction = form.getAttribute("action"); // Ambil action langsung dari form

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch(formAction, { // Menggunakan action dari form
            method: "POST",
            headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => console.error("Error:", error));
    });

    // ✅ Fungsi Hapus Santri
    window.deleteSantri = function (id) {
        if (confirm("Yakin ingin menghapus santri ini?")) {
            fetch(`/admin/santri/${id}`, { // Menggunakan string literal untuk URL
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                document.getElementById("row-" + id).remove();
            })
            .catch(error => console.error("Error:", error));
        }
    };

    // ✅ Fungsi Ambil RFID
    document.getElementById("fetchRfid").addEventListener("click", function () {
        fetch("/api/push-rfid") // Pastikan API ini memang memberikan RFID terbaru
            .then(response => response.json())
            .then(data => {
                if (data.rfid_number) {
                    document.getElementById("rfid_number").value = data.rfid_number;
                    showNotification(`RFID berhasil didapatkan: ${data.rfid_number}`, "success");
                } else {
                    showNotification("RFID tidak ditemukan. Silakan coba lagi.", "danger");
                }
            })
            .catch(error => {
                console.error("Error fetching RFID:", error);
                showNotification("Terjadi kesalahan saat mengambil RFID.", "danger");
            });
    });

    // ✅ Fungsi Notifikasi
    function showNotification(message, type) {
        let notif = document.getElementById("notification");
        notif.innerText = message;
        notif.className = `alert alert-${type} mt-3`;
        notif.classList.remove("d-none");
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Fungsi untuk mengisi form edit
    window.editSantri = function (santri) {
        document.getElementById("edit_id").value = santri.id;
        document.getElementById("edit_name").value = santri.name;
        document.getElementById("edit_rfid_number").value = santri.rfid_number;
        document.getElementById("edit_year").value = santri.year;
    };
    document.getElementById("fetchRfidEdit").addEventListener("click", function () {
        fetch("/api/push-rfid") // API untuk mengambil RFID terbaru
            .then(response => response.json())
            .then(data => {
                if (data.rfid_number) {
                    document.getElementById("edit_rfid_number").value = data.rfid_number;
                    showNotification(`RFID berhasil diperbarui: ${data.rfid_number}`, "success");
                } else {
                    showNotification("RFID tidak ditemukan. Silakan coba lagi.", "danger");
                }
            })
            .catch(error => {
                console.error("Error fetching RFID:", error);
                showNotification("Terjadi kesalahan saat mengambil RFID.", "danger");
            });
    });
    
    // Submit form edit
    document.getElementById("editSantriForm").addEventListener("submit", function (e) {
        e.preventDefault();
    
        let id = document.getElementById("edit_id").value;
        let formData = new FormData(this);
    
        fetch(`/admin/santri/${id}`, {
            method: "POST",  
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => console.error("Error:", error));
    });
    
    document.addEventListener("click", function (event) {
        if (event.target && event.target.id === "fetchRfidEdit") {
            fetch("/api/push-rfid") // API untuk mengambil RFID terbaru
                .then(response => response.json())
                .then(data => {
                    if (data.rfid_number) {
                        document.getElementById("edit_rfid_number").value = data.rfid_number;
                        showNotification(`RFID berhasil diperbarui: ${data.rfid_number}`, "success");
                    } else {
                        showNotification("RFID tidak ditemukan. Silakan coba lagi.", "danger");
                    }
                })
                .catch(error => {
                    console.error("Error fetching RFID:", error);
                    showNotification("Terjadi kesalahan saat mengambil RFID.", "danger");
                });
        }
    });
    
});

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("fetchRfid").addEventListener("click", function () {
        console.log("Tombol 'Ambil RFID' ditekan");
    });

    document.getElementById("fetchRfidEdit").addEventListener("click", function () {
        console.log("Tombol 'Ambil RFID Edit' ditekan");
    });
});
