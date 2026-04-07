    <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> kembali</a>


    <div class="qr-page">
        <div class="qr-card">
            <h2 class="qr-title">Scan QR Ticket</h2>
            <p class="qr-subtitle">Arahkan QR Code ke dalam kotak</p>

            <div class="qr-scanner">
                <div id="reader"></div>

                <div class="qr-overlay">
                    <span class="corner tl"></span>
                    <span class="corner tr"></span>
                    <span class="corner bl"></span>
                    <span class="corner br"></span>
                    <div class="scan-line"></div>
                </div>
            </div>
        </div>

        <pre id="scan-result"></pre>
    </div>

    <script>
        const html5QrCode = new Html5Qrcode("reader");
        let scanned = false;

        function onScanSuccess(decodedText) {
            if (scanned) return;

            let data;
            try {
                data = JSON.parse(decodedText);
            } catch {
                alert("QR tidak valid");
                return;
            }

            if (!data.ticket_id || !data.token) {
                alert("Format QR salah");
                return;
            }

            scanned = true;

            fetch("<?= BASEURL ?>/Ticket/scanTicket", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    alert(res.message);
                })
                .catch(() => alert("Server error"))
                .finally(() => {
                    html5QrCode.stop().catch(() => {});
                });
        }

        Html5Qrcode.getCameras().then(cameras => {
            if (!cameras.length) {
                alert("Kamera tidak ditemukan");
                return;
            }

            let selectedCamera = cameras[0];
            cameras.forEach(cam => {
                const name = cam.label.toLowerCase();
                if (name.includes("back") || name.includes("rear")) {
                    selectedCamera = cam;
                }
            });

            html5QrCode.start(
                selectedCamera.id, {
                    fps: 10,
                    qrbox: 250
                },
                onScanSuccess
            );
        });
    </script>