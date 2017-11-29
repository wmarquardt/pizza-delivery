</div>
	
	<br><br>
	<hr>
	<center>&copy;&nbsp;<?=date('Y')?> - Canvas Studio</center>
    </div> <!-- /container -->


	
	


    <?php 
    if(isset($js_files)):
    	foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
	<?php endforeach;
	else:
	?>
		
	<?php
	endif;
	?>
</body>
</html>
