        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h5 mb-4 text-gray-800"><?= $title; ?></h1>

          

          <!-- table menu -->
          <div class="row">
          	<div class="col-lg-10">
          		<?php if(validation_errors()) :  ?>
          			<div class="alert alert-danger" role="alert">
          				<?= validation_errors();  ?>
          			</div>
          		<?php endif; ?>

          		<?= $this->session->flashdata('message'); ?>

          		<a href="" class="btn btn-outline-primary mb-3" data-toggle="modal" data-target="#newSubMenuModal">Add New SubMenu</a>
          		<table class="table table-hover table-dark">

				  <thead>
				    <tr>
				      <th scope="col">#</th>
				      <th scope="col">Menu</th>
				      <th scope="col">title</th>
				      <th scope="col">url</th>
				      <th scope="col">icon</th>
				      <th scope="col">is_active</th>
				      <th scope="col">action</th>
				    </tr>
				  </thead>
				  <tbody>
				  <?php $no = 1; ?>
				  <?php foreach($subMenu as $sm ) : ?>
				    <tr>
				      <th scope="row"><?= $no; ?></th>
				      <td><?= $sm['title']; ?></td>
				      <td><?= $sm['menu']; ?></td>
				      <td><?= $sm['url']; ?></td>
				      <td><?= $sm['icon']; ?></td>
				      <td><?= $sm['is_active']; ?></td>
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


        <!-- modal untuk menambahkan submenu baru -->

			<!-- Modal -->
			<div class="modal fade" id="newSubMenuModal" tabindex="-1" role="dialog" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="newSubMenuModalLabel">Add New Menu Management</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>

			      <form action="<?= base_url('menu/submenu'); ?>" method="post">
				      <div class="modal-body">

				        <div class="form-group">
						    <input type="text" class="form-control" id="title" placeholder="Menu Title" name="title">
						</div>

						<!-- dropbox submenu -->
						<div class="form-group">
							<select name="menu_id" id="menu_id" class="form-control">
								<option value="">Select Menu</option>
								<?php foreach ($menu as $m) : ?>]
									<option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
								<?php endforeach; ?>	
							</select>
						</div>

						<!-- url -->
						<div class="form-group">
						    <input type="text" class="form-control" id="url" placeholder="Menu url" name="url">
						</div>

						<!-- icon -->
						<div class="form-group">
						    <input type="text" class="form-control" id="icon" placeholder="Menu icon" name="icon">
						</div>

						<!-- is_active -->
						<div class="form-group">
							<div class="form-check">
							  <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active">
							  <label class="form-check-label" for="is_active">
							    Actived
							  </label>
							</div>
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

      