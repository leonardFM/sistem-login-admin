        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h5 mb-4 text-gray-800"><?= $title; ?></h1>

          

          <!-- table menu -->
          <div class="row">
          	<div class="col-lg-6">
          		<?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>') ?>

          		<?= $this->session->flashdata('message'); ?>

          		<a href="" class="btn btn-outline-primary mb-3" data-toggle="modal" data-target="#addMenuModal">Add New Menu</a>
          		<table class="table table-hover table-dark">

				  <thead>
				    <tr>
				      <th scope="col">#</th>
				      <th scope="col">Menu</th>
				      <th scope="col">Action</th>
				    </tr>
				  </thead>
				  <tbody>
				  <?php $no = 1; ?>
				  <?php foreach($menu as $m ) : ?>
				    <tr>
				      <th scope="row"><?= $no; ?></th>
				      <td><?= $m['menu']; ?></td>
				      <td>
				      	<a href="" class="badge badge-success">Edit</a>
				      	<a href="" class="badge badge-danger">Delete</a>
				      </td>
				    </tr>
				    <?php $no++; ?>
				  <?php endforeach; ?>

				  </tbody>
				</table>
          	</div>
          </div>


        <!-- modal untuk menambahkan menu baru -->

			<!-- Modal -->
			<div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalCenterTitle">Add New Menu Management</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>

			      <form action="<?= base_url('menu'); ?>" method="post">
				      <div class="modal-body">
				        <div class="form-group">
						    <input type="text" class="form-control" id="menu" placeholder="Menu Name" name="menu">
						    <small id="text" class="form-text text-muted ml-2">Enter a new menu</small>
						  </div>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				        <button type="submit" class="btn btn-primary">Add Menu</button>
				      </div>
			      </form>
			    </div>
			  </div>
			</div>














          </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      