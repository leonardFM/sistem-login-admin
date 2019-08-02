

<body class="bg-gradient-dark">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-lg-6">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              
              <div class="col-lg">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Forgot Your Password ?</h1>
                  </div>

                  <?= $this->session->flashdata('message'); ?>

                  <form class="user" method="post" action="<?= base_url('auth/forgotpassword'); ?>">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="email" placeholder="Enter Your Email Address" name="email" value="<?= set_value('email'); ?>">
                       <?= form_error('email', '<small class="text-danger pl-3">', '</small>') ?>
                    </div>

                    <button type="submit" class="btn btn-dark btn-user btn-block">
                      Reset Password
                    </button>
                    <hr>
                  </form>

                  <div class="text-center">
                    <a class="small" href="<?= base_url('auth'); ?>">Back To Login</a>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  
