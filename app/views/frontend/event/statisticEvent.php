<section id="statistic">
    <a href="javascript:history.back()" class="back"><i class="fa-solid fa-arrow-left"></i> kembali</a>

    <div class="container">
        <header>
            <h1>Event Statistic</h1>
        </header>

        <div class="square-wrapper">
            <div class="square">
                <h4 class="number">100</h4>
                <h5 class="title">Participant</h5>
            </div>
            <div class="square">
                <h4 class="number">25</h4>
                <h5 class="title">Absence</h5>
            </div>
            <div class="square">
                <h4 class="number">95</h4>
                <h5 class="title">Attendance</h5>
            </div>
            <div class="square">
                <h4 class="number">75%</h4>
                <h5 class="title">Percentage Attendance</h5>
            </div>
        </div>

        <div class="button-wrapper">
            <a class="btn btn-primary" href="<?= BASEURL ?>/Ticket/scanTicket" role="button">scan QR</a>
            <a class="btn btn-primary" href="#" role="button">View Report</a>
            <a class="btn btn-primary" href="<?= BASEURL ?>/Event/committee/<?= $data['eventId'] ?>" role="button">Add Committee</a>
            <a class="btn btn-primary" href="<?= BASEURL ?>/Event/editEvent/<?= $data['eventId'] ?>" role="button">Edit Event</a>
        </div>

    </div>
</section>