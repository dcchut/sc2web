<div class="replay_row <?php echo ($id) ? 'green' : 'red' ?>">
    <div class="replay_upload2l">&#187; <?php echo ($id) ? HTML::anchor('replay/view/' . $id, $replay) : $replay; ?></div>
    <div class="replay_upload2r"><?php echo HTML::image(($success) ? 'media/img/tick.png' : 'media/img/stop.png'); ?></div>
</div>
