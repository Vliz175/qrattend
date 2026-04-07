<section id="register">
    <div class="container-fluid">
        <div class="form">
            <h1>Register</h1>
            <form action="" method="post">
                <!-- form nama -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" autocomplete="off" required>
                </div>

                <!-- form email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="off" required>
                </div>

                <!-- form tanggal lahir -->
                <div class="mb-3">
                    <label for="birth-date" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="birth-date" name="birth_date" required>
                </div>

                <!-- form jenis kelamin -->
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <div class="wrapper-gender">
                        <div class="radio-wrapper">
                            <input class="form-check-input" type="radio" name="gender" id="pria" value="pria" checked>
                            <label class="form-check-label" for="pria">
                                Pria
                            </label>
                        </div>
                        <div class="radio-wrapper">
                            <input class="form-check-input" type="radio" name="gender" id="wanita" value="wanita">
                            <label class="form-check-label" for="wanita">
                                Wanita
                            </label>
                        </div>
                    </div>
                </div>

                <!-- form nomor hp -->
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Nomor Handphone</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" autocomplete="off" placeholder="08xxx" pattern="[0-9]+" inputmode="numeric" required>
                </div>

                <!-- form password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
                </div>

                <!-- form konfirmasi password -->
                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" autocomplete="off" required>
                </div>

                <!-- link menuju login -->
                <div id="register-link" class="form-text mb-3"><a href="<?= BASEURL ?>/Auth/login" class="text-secondary">apakah sudah punya akun?</a></div>

                <!-- button submit -->
                <button type="submit" class="btn btn-outline-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>
</section>