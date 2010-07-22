<script type="text/javascript">
$(document).ready(function(){
	var i = false;
	$(".stripes").each(function(){
		
		if (!i) {
			$(this).addClass('stripe1');
		} else {
			$(this).addClass('stripe2');
		}

		i = !i;
	});
});
</script>
<p>You have uploaded the following files to the repository:</p>
<div id="upload2_box">