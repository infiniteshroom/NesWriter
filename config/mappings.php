<?php
	/* rom font table mappings - based on iNes Title found at the end of the ROM 
		if a mapping can't be found the user is prompted to supply one - since the iNES title is
		optional it is sometimes missing from the ROM =/ - leave blank to stop check */
	$iNes_tables = array(
		'ZELDA' => 'generic.php',
	);

	/* these tables attempt a failback to map the rom, if they contain a certain string within
	   the filename - can be pretty inaccurate - leave blank to stop check*/
	$filename_tables = array(
		'Super Mario Bros. (JU)' => 'generic.php',
		'Super Mario Bros (JU)' => 'generic.php',
		'Super Mario Bros (E)' => 'generic.php',
		'Legend of Zelda, The (U)' => 'generic.php',	
	);


?>