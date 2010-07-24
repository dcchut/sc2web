<div class="heading"><b>replay title</b> (uploaded <?php echo Date::fuzzy_span($replay['upload_date']);  ?>)</div>

downloaded <?php echo $replay['downloaded']; ?> <?php echo Inflector::plural('time', $replay['downloaded']); ?> - <?php 
echo HTML::anchor('replay/download/' . $replay['id'], 'download now'); ?><br /><br />

<div class="heading"><b>players</b></div>
<div id="replay_view_box">
    <?php foreach ($players as $c): ?>
    <div class="replay_row stripes">
        <div class="replay_view_left"><?php echo HTML::anchor(Prettylink::uri('player', $c->player->id), $c->player->name); ?></div>
        <div class="replay_view_right"><?php echo $c->race->name; ?></div>
    </div>
    <?php endforeach; ?>
</div><br />
<?php echo HTML::anchor(Prettylink::uri('replay', $replay['id']), 'permanent link to this page'); ?><br />
