<div class="replay_row <?php echo ($id) ? 'green' : 'red' ?>">
    <div class="replay_upload2_left">&#187; <?php echo ($id) ? HTML::anchor('replay/view/' . $id, $replay) : $replay; ?></div>
    <div class="replay_upload2_right"><?php echo HTML::image(($success) ? 'media/img/tick.png' : 'media/img/stop.png'); ?></div>
</div>
