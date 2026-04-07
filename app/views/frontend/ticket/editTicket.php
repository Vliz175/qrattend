<section id="edit">
    <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> kembali</a>
    <h1>Edit event</h1>

    <div class="container">
        <form action="" method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="ticket-name" class="form-label">Nama Tiket</label>
                    <input type="text" class="form-control" id="ticket-name" name="ticket_name" value="<?= $data['ticketData']['ticket_name'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga Tiket</label>
                    <input class="form-control" type="text" inputmode="numeric" pattern="[0-9.,]*" name="price" id="price" value="<?= $data['ticketData']['price'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="quota" class="form-label">Kuota Tiket</label>
                    <input type="text" class="form-control" id="quota" name="quota" value="<?= $data['ticketData']['quota'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="start-sale" class="form-label">Mulai Penjualan</label>
                    <input type="datetime-local" class="form-control" id="start-sale" name="start_sale" value="<?= $data['ticketData']['start_sale'] ?? '' ?>">
                </div>

                <div class="mb-3">
                    <label for="end-sale" class="form-label">Akhir Penjualan</label>
                    <input type="datetime-local" class="form-control" id="end-sale" name="end_sale" value="<?= $data['ticketData']['end_sale'] ?? '' ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="save">Simpan</button>
        </form>
    </div>
</section>