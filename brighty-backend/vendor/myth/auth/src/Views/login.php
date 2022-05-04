<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 ">

			<div class="login card shadow-sm mb-3" style="    background: #f5feff;">
				<h5 class="card-header"><?=lang('Auth.loginTitle')?></h5>
				<div class="card-body">

					<?= view('Myth\Auth\Views\_message_block') ?>

					<form action="<?= route_to('login') ?>" method="post">
						<?= csrf_field() ?>

				<?php if ($config->validFields === ['email']): ?>
						<div class="form-group">
							<label for="login"><?=lang('Auth.email')?></label>
							<input type="email" class="form-control <?php if(session('errors.login')) : ?>is-invalid<?php endif ?>"
								   name="login" placeholder="<?=lang('Auth.email')?>">
							<div class="invalid-feedback">
								<?= session('errors.login') ?>
							</div>
						</div>
				<?php else: ?>
						<div class="form-group">
							<label for="login"><?=lang('Auth.emailOrUsername')?></label>
							<input type="text" class="form-control <?php if(session('errors.login')) : ?>is-invalid<?php endif ?>"
								   name="login" placeholder="<?=lang('Auth.emailOrUsername')?>">
							<div class="invalid-feedback">
								<?= session('errors.login') ?>
							</div>
						</div>
				<?php endif; ?>

						<div class="form-group">
							<label for="password"><?=lang('Auth.password')?></label>
							
								<input type="password" name="password" class="form-control  <?php if(session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?=lang('Auth.password')?>">
								<div class="password-toggle">
        								<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
								</div>
								
							
							<div class="invalid-feedback">
								<?= session('errors.password') ?>
							</div>
						</div>

			<?php if ($config->allowRemembering): ?>
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" name="remember" class="form-check-input" <?php if(old('remember')) : ?> checked <?php endif ?>>
								<?=lang('Auth.rememberMe')?>
							</label>
						</div>
			<?php endif; ?>

						<br>

						<button type="submit" class="btn btn-primary btn-block"><?=lang('Auth.loginAction')?></button>
					</form>


			<?php if ($config->activeResetter): ?>
					<p class="mt-3"> <a href="<?= route_to('forgot') ?>"><?=lang('Auth.forgotYourPassword')?></a></p>
			<?php endif; ?>
				</div>
			</div>

		</div>

		<div class="col-sm-6 ">
			<div class="card">

				<h5 class="card-header">Create an account</h5>

				<div class="card-body">
					<h3>I am new here</h3>
					<ul>
						<p>Create an account to:</p>
							<li>Manage your services</li>
							<li>Manage your orders</li>
							<li>Manage your bills and payment methods</li>
					</ul>

				<?php if ($config->allowRegistration) : ?>
						<p><a class="btn btn-block btn-outline btn-outline-success" href="<?= route_to('register') ?>"><?=lang('Auth.needAnAccount')?></a></p>
				<?php endif; ?>
				</div>
			</div>
				
		</div>
	</div>
</div>

<?= $this->endSection() ?>
