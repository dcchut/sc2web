<div class="upload2 <?php echo ($id) ? 'stripe1' : 'stripe2' ?>">
    <div class="upload2l">&#187; <?php echo ($id) ? HTML::anchor('replay/view/' . $id, $replay) : $replay; ?></div>
    <div class="upload2r"><?php echo HTML::image(($success) ? 'media/img/tick.png' : 'media/img/stop.png'); ?></div>
</div>
