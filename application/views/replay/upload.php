<?php defined('SYSPATH') or die('No direct script access.'); ?>

<p>Upload a replay (or a .zip file containing multiple replays):</p>
<?php echo Form::open('replay/upload2', array('enctype' => 'multipart/form-data')); ?>
<?php echo Form::file('file'); ?><br /><br />
<?php echo Form::submit('submit', 'Upload'); ?>
<?php echo Form::close(); ?>