<section id="detail">
    <a href="<?= BASEURL ?>" class="back"><i class="fa-solid fa-arrow-left"></i> kembali</a>
    <div class="container">
        <div class="img-wrapper">
            <div class="img" style="background-image: url('<?= BASEURL ?>/assets/img/<?= $data['events']['banner_color'] ?>'); background-size: contain; background-position: center; background-repeat: no-repeat;">
                <img src="<?= BASEURL ?>/uploads/events/<?= $data['events']['event_image'] ?>" alt="">
            </div>
        </div>
        <div class="detail">
            <div class="judul-tiket">
                <h2><?= $data['events']['event_name'] ?></h2>
            </div>
            <div class="deskripsi-tiket">
                <?= $data['events']['description'] ?>
            </div>
            <div class="info-tiket">
                <div class="info-row">
                    <span class="label"><i class="fa-regular fa-calendar"></i> </span>
                    <?php if ($data['events']['end_date'] == null) : ?>
                        <span class="date"> <?= date('d M Y', strtotime($data['events']['start_date'])) ?></span>
                    <?php else : ?>
                        <span class="date"> <?= date('d M Y', strtotime($data['events']['start_date'])) ?> - <?= date('d M Y', strtotime($data['events']['end_date'])) ?></span>
                    <?php endif; ?>
                </div>
                <div class="info-row">
                    <span class="label"><i class="fa-regular fa-clock"></i> </span>
                    <span class="value"><?= date('H:i', strtotime($data['events']['start_date'])) ?> - <?= date('H:i', strtotime($data['events']['end_date'])) ?> WIB</span>
                </div>
                <div class="info-row">
                    <span class="label"><i class="fa-solid fa-location-dot"></i> </span>
                    <span class="value">Stadion Utama, Jakarta</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="ticket">
    <div class="container">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        online sales
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">

                    <?php foreach ($data['tickets'] as $ticket) : ?>
                        <div class="accordion-body">
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

                            <!-- status ketersediaan tiket -->
                            <span class="status-ticket">
                                <?php if ($ticket['sold'] >= $ticket['quota']) : ?>
                                    <span class="badge bg-secondary">Sold Out</span>
                                    <form action="" method="post">
                                        <button type="submit" name="buyTicket" class="btn mt-2 btn-sm" disabled>Buy Ticket</button>
                                    </form>
                                <?php elseif (date('Y-m-d H:i:s') < $ticket['start_sale']) : ?>
                                    <span class="badge bg-warning">Upcoming</span>
                                    <form action="" method="post">
                                        <button type="submit" name="buyTicket" class="btn mt-2 btn-sm" disabled>Buy Ticket</button>
                                    </form>
                                <?php elseif (date('Y-m-d H:i:s') >= $ticket['start_sale'] && date('Y-m-d H:i:s') <= $ticket['end_sale']) : ?>
                                    <span class="badge bg-success">On Sale</span>
                                    <form action="" method="post">
                                        <input type="hidden" value="<?= $ticket['price'] ?>" name="price">
                                        <input type="hidden" value="<?= $ticket['ticket_id'] ?>" name="ticket_id">
                                        <button type="submit" name="buyTicket" class="btn mt-2 btn-sm btn-outline-primary">Buy Ticket</button>
                                    </form>
                                <?php elseif (date('Y-m-d H:i:s') > $ticket['end_sale']): ?>
                                    <span class="badge bg-secondary">Sale Ended</span>
                                    <form action="" method="post">
                                        <button type="submit" name="buyTicket" class="btn mt-2 btn-sm" disabled>Buy Ticket</button>
                                    </form>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</section>