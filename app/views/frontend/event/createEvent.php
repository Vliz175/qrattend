<div class="container main">
    <!-- display event -->
    <section id="display-event">

        <?php if (!$_SESSION['is_verified']) : ?>
            <p class="no-event">You must verified first</p>
        <?php elseif (!$data['events']): ?>
            <p class="no-event">Event Not Available</p>
        <?php endif; ?>

        <?php foreach ($data['events'] as $event) : ?>
            <a href="<?= BASEURL ?>/Event/statistic/<?= $event['event_id'] ?>" class="wrap-card">
                <div class="img" style="background-image: url('<?= BASEURL; ?>/assets/img/<?= $event['banner_color'] ?>');">
                    <img src="<?= BASEURL ?>/uploads/events/<?= $event['event_image'] ?>" alt="">
                </div>

                <div class="text">
                    <div class="top">
                        <p class="title"><?= $event['event_name'] ?></p>
                        <p><?= $event['name'] ?></p>
                        <?php if ($event['end_date'] == null) : ?>
                            <p class="date"><?= date('d M Y', strtotime($event['start_date'])) ?></p>
                        <?php else : ?>
                            <p class="date"><?= date('d M Y', strtotime($event['start_date'])) ?> - <?= date('d M Y', strtotime($event['end_date'])) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="bottom">
                        <?php if ($event['status_event'] == 'approved') : ?>
                            <div class="status-publish">Publish</div>
                        <?php elseif ($event['status_event'] == 'pending') : ?>
                            <div class="status-pending">Pending</div>
                        <?php else : ?>
                            <div class="status-reject">Rejectd</div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>

        <?php endforeach; ?>

        <?php if ($_SESSION['is_verified']): ?>
            <a class="btn btn-primary add-event-btn" href="<?= BASEURL ?>/Event/editEvent" role="button">
                <i class="fa-solid fa-plus"></i> Add Event
            </a>
        <?php endif; ?>
    </section>
    <!-- end of display event -->
</div>