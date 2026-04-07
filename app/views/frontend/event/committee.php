<section id="committee">
    <a href="<?= BASEURL . '/Event/statistic/' . $data['eventId'] ?>" class="back"><i class="fa-solid fa-arrow-left"></i> kembali</a>

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['list'] as $list) : ?>
                    <tr>
                        <td><?= $list['name'] ?></td>
                        <td><?= $list['email'] ?></td>
                        <td><?= $list['committee_role'] ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="committee_id" value="<?= $list['committee_id'] ?>">
                                <button type="submit" class="btn btn-danger kick-btn" name="kick" <?= ($list['committee_role'] === 'creator') ? 'disabled' : '' ?>>
                                    Kick
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <button type="button" class="btn btn-primary add-committee" data-bs-toggle="modal" data-bs-target="#addCommittee">
        Add Committee
    </button>
</section>

<!-- modal add committee -->
<div class="modal fade" id="addCommittee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">add Committee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="add">Add</button>
                </div>
            </form>

        </div>
    </div>
</div>