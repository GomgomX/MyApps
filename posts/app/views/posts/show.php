<?php require APPROOT.'/views/inc/header.php'; ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
<br>
<h1><?php echo $data['post']->title; ?></h1>
<div class="bg-secondary text-white p-2 mb-3">
	Written by <?php echo $data['user']->name ?> on <?php echo $data['post']->created_at ?>
</div>
<p><?php echo $data['post']->body ?></p>
<?php if($data['post']->user_id == $_SESSION['user_id'])
	echo '<hr>
	<a href="'.URLROOT.'/posts/edit/'.$data['post']->id.'" class="btn btn-dark"> Edit</a>
	<form class="pull-right" action="'.URLROOT.'/posts/delete/'.$data['post']->id.'" method="POST">
		<input type="submit" value="delete" class="btn btn-danger">
	</form>';
?>
<?php require APPROOT.'/views/inc/footer.php'; ?>