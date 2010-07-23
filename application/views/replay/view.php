<?php echo HTML::anchor(Prettylink::uri('replay', $replay['id']), 'Permalink'); ?><br /><br />
<?php echo HTML::anchor('replay/download/' . $replay['id'], 'download replay'); ?><br /><br />

<b>Players:</b><br /><br />
<div id="replay_view_box">
    <?php foreach ($players as $c): ?>
    <div class="replay_row stripes">
        <div class="replay_viewl"><?php echo HTML::anchor(Prettylink::uri('player', $c->player->id), $c->player->name); ?></div>
        <div class="replay_viewr"><?php echo $c->race->name; ?></div>
    </div>
    <?php endforeach; ?>
</div>