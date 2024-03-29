    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg">
                                <div class="p-5">
                                    <div class="text-center">                                        
                                        <img src="<?= base_url('assets/images/Group 2.1.png') ?>" class="rounded" alt="haifa.jpg" style="height: 150px;">
                                    </div>
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Lupa Kata Sandi?</h1>
                                        <p class="mb-4">Kami mengerti, hal-hal terjadi. Cukup masukkan alamat email Anda di bawah ini dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda!</p>
                                    </div>
                                    <?= $this->session->flashdata('message'); ?>
                                    <form class="user" method="post" action="<?= base_url('auth/forgotPassword') ?>">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." name="email" value="<?= set_value('email') ?>">
                                                <?= form_error('email','<small class="text-danger pl-3">','</small>') ?>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Reset Kata Sandi
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="<?= base_url('Auth/forgotPassword2') ?>">Gunakan metode lain</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="<?= base_url('Auth/registration') ?>">Buat Akun!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="<?= base_url('Auth/login') ?>">Ingat Kata Sandi? kembali ke halaman login!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="<?= base_url('Home'); ?>">Kembali ke Beranda</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>