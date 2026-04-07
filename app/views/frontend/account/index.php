<section id="account-page">
    <!-- <a href="javascript:history.back()" class="back ml-0"><i class="fa-solid fa-arrow-left"></i> kembali</a> -->
    <h1>PROFILE</h1>
    <div class="container">

        <!-- profile card -->
        <div class="profile-card">
            <div class="wrapper-profile">

                <div class="profile-image">
                    <img src="<?= BASEURL; ?>/uploads/account/<?= $_SESSION['user_image'] ?>" alt="Profile Image">
                </div>

                <div class="profile-info">
                    <h2><?= $_SESSION['user_name']; ?></h2>
                    <?php if ($_SESSION['is_verified']) : ?>
                        <span>verified</span>
                    <?php else : ?>
                        <span>not verified</span>
                    <?php endif; ?>
                    <span><?= $_SESSION['user_email']; ?></span>
                    <span><?= $_SESSION['user_noHp']; ?></span>
                </div>

            </div>

            <div class="danger-btn">

                <form action="" method="post">

                    <?php if (!$_SESSION['is_verified']) : ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#beOrganizerModal">
                            Be Verified
                        </button>
                    <?php endif; ?>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        Log Out
                    </button>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        Delete Account
                    </button>
                </form>

            </div>
        </div>


        <!-- update profile card -->
        <div class="update-profile-card">
            <h2>Update Section</h2>

            <form action="" method="POST" enctype="multipart/form-data">

                <img id="preview-image" src="<?= BASEURL ?>/uploads/account/<?= $_SESSION['user_image'] ?>" alt="preview image">

                <div class="mb-3">
                    <label for="profile-image" class="form-label">Photo Profile</label>
                    <input class="form-control form-control-sm" id="profile-image" name="profile-image" type="file" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">New Name</label>
                    <input type="name" class="form-control" id="name" name="name">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    <div id="emailHelp" class="form-text"> <span class="text-danger">*</span> Fill content you want to change</div>
                </div>

                <button type="submit" class="btn btn-primary" name="confirm">Update</button>

            </form>
        </div>
    </div>
</section>

<!-- modal area of logout button -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Log Out</h1>
            </div>
            <div class="modal-body">
                Are you sure you want to log out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">No</button>
                <form action="" method="POST">
                    <button type="submit" class="btn btn-danger" name="logout-yes">Yes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal area of delete account button -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Account</h1>
            </div>
            <div class="modal-body">
                Are you sure want to delete your account?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">No</button>
                <form action="" method="POST">
                    <button type="submit" class="btn btn-danger" name="delete-yes">Yes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal area of be verified button -->
<div class="modal fade" id="beOrganizerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Be Verified</h1>
            </div>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="full_name">
                    </div>

                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik">
                    </div>

                    <div class="mb-3">
                        <label for="foto-wajah" class="form-label">Foto Wajah</label>
                        <input class="form-control form-control-sm" id="foto-wajah" name="foto_wajah" type="file">
                    </div>

                    <div class="mb-3">
                        <label for="foto-ktp" class="form-label">Kartu Tanda Penduduk (KTP)</label>
                        <input class="form-control form-control-sm" id="foto-ktp" name="foto_ktp" type="file">
                    </div>

                    <p>Example Photo :</p>
                    <img class="img-fluid mb-4"
                        src="<?= BASEURL ?>/assets/img/example_ktp.jpg"
                        alt="Example KTP">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="be-verified">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>