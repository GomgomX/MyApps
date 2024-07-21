<?php require APPROOT.'/views/inc/header.php'; ?>
<div class="jumbotron jumbotron-flud text-center">
	<div class="container">
		<h1 class="display-3"><?php echo $data['title'] ?></h1> 
		<p class="lead"><?php echo $data['description']; ?></p>
	</div>
</div>

<!-- <div class="jumbotron jumbotron-flud text-center card mb-3 border-0"><a href="<?php echo URLROOT.(isLoggedIn() ? '/pages/home' : '/users/login'); ?>" class="btn btn-dark"><?php echo (isLoggedIn() ? 'Home' : 'Login'); ?></a></div> -->
<div class="jumbotron jumbotron-flud text-center card mb-3 border-0"><a href="<?php echo URLROOT; ?>/pages/home" class="btn btn-dark">Home</a></div>
<?php require APPROOT.'/views/inc/footer.php'; ?>

