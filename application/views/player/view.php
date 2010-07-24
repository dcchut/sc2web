<div class="player_view_row stripes">
    <div class="player_view_left"><?php echo HTML::anchor($replay_uri, 
                                                      Text::limit_chars($replay_text, 50),
                                                      array('title' => 'with ' . $opponents,
                                                            'class' => 'tooltip')); ?></div>
    <div class="player_view_right"><?php echo $race; ?></div>
</div>
