  <div class="bg-fixed" style="margin-left:25%; width: 75%; position: fixed;
  inset: 0;
  background: url(' <?= BASEURL ?>/assets/img/bg2.jpeg') center / cover no-repeat;
  z-index: -1;"></div>

  <div id="userverif">
      <header>
          <h1>User Verification</h1>
      </header>

      <table class="table">
          <thead>
              <tr>
                  <th scope="col">id</th>
                  <th scope="col">Full Name</th>
                  <th scope="col">Created at</th>
                  <th scope="col"></th>
                  <th scope="col"></th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($data['list'] as $list) : ?>
                  <form action="" method="post">
                      <tr>
                          <input type="hidden" value="<?= $list['verification_id'] ?>" name="verificationId">
                          <th scope="row"><?= $list['verification_id'] ?></th>
                          <td><?= $list['full_name'] ?></td>
                          <td><?= $list['created_at'] ?></td>
                          <td>
                              <button type="submit" class="btn btn-primary btn-sm" name="approve">Approve</button>
                              <button type="submit" class="btn btn-danger btn-sm" name="reject">Reject</button>
                          </td>

                      </tr>
                  </form>
              <?php endforeach; ?>
          </tbody>
      </table>
  </div>