<section id="login">
    <div class="container-fluid">
        <div class="form">
            <h1>Login</h1>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="off" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
                </div>
                <div id="register-link" class="form-text mb-3"><a href="<?= BASEURL ?>/Auth/register" class="text-secondary">apakah belum punya akun?</a></div>
                <button type="submit" class="btn btn-outline-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>
</section>