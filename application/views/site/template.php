<html>
<head>
<title><?php echo $title; ?></title>
<!--  created 22/07/2010 -->
<?php echo HTML::style('media/css/style.css'); ?>
<?php echo HTML::style('media/js/jquery.tooltip.css'); ?>
<?php echo HTML::script('media/js/jquery.js'); ?>
<?php echo HTML::script('media/js/jquery.bgiframe.js'); ?>
<?php echo HTML::script('media/js/jquery.delegate.js'); ?>
<?php echo HTML::script('media/js/jquery.dimensions.js'); ?>
<?php echo HTML::script('media/js/jquery.tooltip.js'); ?>
</head>
<body>
<script type="text/javascript">
$(document).ready(function(){
	var i = false;
	$("div.stripes").each(function(){
		
		if (!i) {
			$(this).addClass('stripe1');
		} else {
			$(this).addClass('stripe2');
		}

		i = !i;
	});

	$("a.tooltip").each(function(){
		var title = $(this).attr('title');
		
		$(this).tooltip({
			delay: 0,
			showURL: false,
			bodyHandler: function(){ return title; }
		});
	});	
});

// move this to a site.js file at some time in the future, to keep everything nice and clean
</script>
<div id="main">
<?php echo HTML::anchor('', '<h3>nitrated</h3>'); ?>
<?php echo $main; ?><br /><br />
created by <i>dcc</i> in <?php echo $exec; ?> seconds.
</div>
</body>
</html>