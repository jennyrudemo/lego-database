<?php 
	$next == $limit;
	
	$test = "inventory.SetID = '".$_GET["search"]."'"  ; //Vi vill söka på setID, men i start vill vi inte ha alla.
	$limit = $_GET["limit"];
	$order = $_GET["order"];
	
	
	
		if(!(isset($_GET["search"])))
		{
			$test = "inventory.SetID='375-1'";
		}
		if(!$order = $_GET["order"])
		{
			$order = "inventory.Quantity";
		}
		if(!$limit = $_GET["limit"])
		{
			$limit = 15;
		}

	/*Att offset ger 'bla' men search ger "bla"
	Långsam, vårt fel?*/

	$result = mysqli_query($link, //I FROM behöver vi sets?
	"SELECT inventory.SetID, inventory.Quantity, inventory.ColorID, colors.Colorname, parts.Partname, images.ItemID, 
	images.has_gif, inventory.ItemtypeID, images.has_jpg, images.has_largejpg, images.has_largegif
	FROM inventory, colors, parts, images 
	WHERE images.ColorID=inventory.ColorID AND colors.ColorID=inventory.ColorID 
	AND parts.PartID=inventory.ItemID AND images.ItemID=inventory.ItemID AND $test ORDER BY $order LIMIT $limit"
	);

	print $test;			?>
	
	
	