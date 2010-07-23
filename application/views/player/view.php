<div class="replay_row stripes">
    <div class="player_viewl"><?php echo HTML::anchor($replay_uri, 
                                                      Text::limit_chars($replay_text, 50),
                                                      array('title' => 'with ' . $opponents,
                                                            'class' => 'tooltip')); ?></div>
    <div class="player_viewr"><?php echo $race; ?></div>
</div>