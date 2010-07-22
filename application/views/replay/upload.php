<?php defined('SYSPATH') or die('No direct script access.'); ?>

<b>Upload replay(s):</b><br />
<?php echo Form::open('replay/upload2', array('enctype' => 'multipart/form-data')); ?>
<?php echo Form::file('file'); ?><br /><br />
<?php echo Form::submit('submit', 'Upload'); ?>
<?php echo Form::close(); ?>