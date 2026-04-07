<div class="container main">
    <section id="form-pencarian">
        <form class="d-flex" role="search" method="get">
            <input class="form-control me-2" type="search" placeholder="Cari" id="search" name="search_value">
            <button class="btn" type="submit" name="search">Cari</button>
        </form>
    </section>

    <section id="display-event">
        <?php foreach ($data['list'] as $ticket) : ?>
            <!-- wrap card -->
            <div class="wrap-card">
                <!-- card image -->
                <div class="img" style="background-image: url('<?= BASEURL; ?>/assets/img/<?= $ticket['banner_color'] ?>');">
                    <img src="<?= BASEURL ?>/uploads/events/<?= $ticket['event_image'] ?>" alt="">
                </div>

                <!-- card detail -->
                <div class="text">
                    <div class="top">
                        <div class="text-event">
                            <p>Event : <?= $ticket['event_name'] ?></p>
                            <p>Type : <?= $ticket['ticket_name'] ?></p>
                            <p>ID : <?= $ticket['attendee_id'] ?></p>
                            <!-- <p><?= $ticket['organizer_name'] ?></p> -->
                        </div>
                    </div>

                    <div class="bottom">
                        <div class="days-left"><?= $ticket['days_left'] ?> Hari Lagi</div>

                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ticket<?= $ticket['attendee_id'] ?>">
                            show QR
                        </button>
                    </div>
                </div>
            </div>

            <!-- Elemen QR yang di tengah layar -->
            <div class="modal fade" id="ticket<?= $ticket['attendee_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Ticket</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="top">
                                <div class="name">
                                    <p><?= $ticket['event_name'] ?></p>
                                </div>
                                <div class="date-time-location">
                                    <p><?= $ticket['start_date'] ?> <?= $ticket['location'] ?></p>
                                </div>
                            </div>

                            <img src="<?= BASEURL ?>/uploads/qrcodes/ticket_<?= $ticket['attendee_id'] ?>.png" alt="">

                            <div class="detail">
                                <p>Ticket Type : <?= $ticket['ticket_name'] ?></p>
                                <p>Ticket ID : <?= $ticket['attendee_id'] ?></p>
                                <p>status : <?= $ticket['status'] ?></p>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </section>
</div>