<div class="container main">
    <!-- form pencarian -->
    <section id="form-pencarian">
        <form class="d-flex" role="search" method="get">
            <input class="form-control me-2" type="search" placeholder="Cari" id="search" name="search" autocomplete="off" value="<?= htmlspecialchars($data['search'] ?? '') ?>">
            <button class="btn" type="submit" name="search-btn">Cari</button>
        </form>
    </section>
    <!-- end of form pencarian -->

    <!-- display event -->
    <section id="display-event">

        <?php foreach ($data['events'] as $event) : ?>
            <a href="<?= BASEURL ?>/Event/detail/<?= $event['event_id'] ?>" class="wrap-card">
                <div class="img" style="background-image: url('<?= BASEURL; ?>/assets/img/<?= $event['banner_color'] ?>');">
                    <img src="<?= BASEURL ?>/uploads/events/<?= $event['event_image'] ?>" alt="">
                </div>

                <div class="text">
                    <div class="top">
                        <p class="title"><?= $event['event_name'] ?></p>

                        <?php if ($event['end_date'] == null) : ?>
                            <p class="date"><?= date('d M Y', strtotime($event['start_date'])) ?></p>
                        <?php else : ?>
                            <p class="date"><?= date('d M Y', strtotime($event['start_date'])) ?> - <?= date('d M Y', strtotime($event['end_date'])) ?></p>
                        <?php endif; ?>

                        <?php if ($event['min_price'] == 0) : ?>
                            <p class="price">Gratis</p>
                        <?php else: ?>
                            <p class="price">Rp <?= $event['min_price'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="bottom">
                        <img src="<?= BASEURL ?>/uploads/account/<?= $event['creator_image'] ?>" alt="">
                        <span><?= $event['creator_name'] ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </section>
    <!-- end of display event -->
</div>