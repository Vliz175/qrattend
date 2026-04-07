<section id="edit-event">
    <a href="<?= BASEURL ?>" class="back"><i class="fa-solid fa-arrow-left"></i> kembali</a>
    <h1>Edit event</h1>

    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="general">
                <img id="preview-image" src="<?= BASEURL ?>/uploads/events/<?= $data['eventData']['event_image'] ?? 'no_img.png' ?>" alt="preview image">

                <div class="mb-4">
                    <label for="event_image" class="form-label">Photo Event</label>
                    <input class="form-control form-control-sm" id="profile-image" name="event_image" type="file" accept="image/*">
                </div>

                <div class="mb-4">
                    <div>
                        <label class="form-label">Banner Color</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input bg-info" type="radio" name="banner_color" id="inlineRadio1" value="blue.png" <?= (isset($data['eventData']['banner_color']) == 'blue.png') ? 'checked' : '' ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input bg-danger" type="radio" name="banner_color" id="inlineRadio2" value="pink.png" <?= (isset($data['eventData']['banner_color']) == 'pink.png') ? 'checked' : '' ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input bg-warning" type="radio" name="banner_color" id="inlineRadio3" value="orange.png" <?= (isset($data['eventData']['banner_color']) == 'orange.png') ? 'checked' : '' ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input bg-success" type="radio" name="banner_color" id="inlineRadio4" value="green.png" <?= (isset($data['eventData']['banner_color']) == 'green.png') ? 'checked' : '' ?>>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="event-name" class="form-label">Event Name</label>
                    <input type="text" class="form-control" id="event-name" name="event_name" value="<?= $data['eventData']['event_name'] ?? '' ?>">
                </div>
            </div>

            <div class="venue">
                <div class="mb-4">
                    <label for="start-date" class="form-label">Start Date</label>
                    <input type="datetime-local" class="form-control" id="start-date" name="start_date" value="<?= $data['eventData']['start_date'] ?? '' ?>">
                </div>
                <div class="mb-4">
                    <label for="end-date" class="form-label">End Date</label>
                    <input type="datetime-local" class="form-control" id="end-date" name="end_date" value="<?= $data['eventData']['end_date'] ?? '' ?>">
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" value="<?= $data['eventData']['description'] ?? '' ?>"></textarea>
                </div>

                <div class="mb-4">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?= $data['eventData']['location'] ?? '' ?>">
                    <div id="emailHelp" class="form-text"> <span class="text-danger">*</span> Fill this if event type offline or hybrid</div>
                </div>

                <button class="btn btn-primary" type="submit" name="save_event">Save</button>
            </div>
        </form>
    </div>
</section>

<?php if ($data['eventId'] != null) : ?>
    <section id="ticket-event">
        <div class="container">
            <h2>Ticket Type</h2>
            <?php foreach ($data['tickets'] as $ticket) : ?>
                <div class="ticket-cards">
                    <!-- harga tiket -->
                    <span class="price-ticket">
                        <span class="value">
                            <?php if ($ticket['price'] == 0) : ?>
                                Gratis
                            <?php else: ?>
                                Rp <?= number_format($ticket['price'], 0, ',', '.') ?>
                            <?php endif; ?>
                        </span>
                    </span>

                    <!-- nama dan tanggal penjualan  -->
                    <span class="detail-ticket">
                        <p class="name-ticket"><?= $ticket['ticket_name'] ?></p>
                        <p class="sale-date"><?= date('d M Y H:i', strtotime($ticket['start_sale'])) ?> WIB - <?= date('d M Y H:i', strtotime($ticket['end_sale'])) ?> WIB</p>
                    </span>

                    <span class="btn-ticket">
                        <!-- button bayar -->
                        <a type="button" href="<?= BASEURL ?>/Ticket/editTicket/<?= $data['eventData']['event_id'] ?>/<?= $ticket['ticket_id'] ?>" class="btn btn-primary btn-sm mt-2">Edit</a>
                        <form action="" method="post">
                            <button type="submit" name="delete_ticket" class="btn btn-sm btn-danger mt-2">Delete</button>
                        </form>
                    </span>
                </div>
            <?php endforeach; ?>

            <a type="button" href="<?= BASEURL ?>/Ticket/editTicket/<?= $data['eventData']['event_id'] ?>" class="btn btn-sm btn-success mt-2">Add Ticket</a>
        </div>
    </section>
<?php endif; ?>