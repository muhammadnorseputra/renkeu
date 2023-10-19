<div class="container">
	<div class="row">
		<div class="col-xl-12">
			<div class="card">
				<div class="card-body">
					<div class="alert bg-transparent fade show text-dark border-warning text-center my-5" role="alert">
						<span class="alert-icon"><i class="ni ni-bulb-61 text-warning"></i></span>
						<span class="alert-text">
							<?php
							$default = 'Anda tidak di ijinkan untuk mengakses fitur ini.';  
							$custom_msg = isset($pesan) ? $pesan : $default;
							?> 
							<strong>Rejected !</strong> <?= $custom_msg ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
