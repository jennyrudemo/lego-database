
<?php
	//om sökrutan är tom sök inget
	
		
		$sequered = mysqli_real_escape_string($link, $_GET["search"]);
		
		$search = ' WHERE (sets.SetID LIKE "'.$sequered.'%" OR sets.Setname LIKE "%'.$sequered.'%") ';
		
	if(isset($_GET["search"]))
	{
		echo "din sökning:".$_GET['search'];
		
		//$test = "sets.SetID LIKE '%".$_GET["search"]."%'";//Vi vill söka på setID, men i start vill vi inte ha alla.
		//$test_name="sets.Setname LIKE '%".$_GET["search"]."%'";
		$limit = $_GET["limit"];
		$order = mysqli_real_escape_string($link, $_GET["searchorder"]);
		
		
		
			//Spara radiobuttons och söka antingen på setID eller setname
				if ($_GET["searchorder"] == "sbname")
				{
					$order = "Setname, SetID";
					setcookie("rbutton", "sbname" , time()+(60*60*24*30));
				}
				else
				{
					$order = "SetID, Setname";
					setcookie("rbutton", "sbid" , time()+(60*60*24*30));
				}
				
				
			
			echo "rbutton = ".$_COOKIE['rbutton'];
			if(!$limit = $_GET["limit"])
			{
				$limit = 15;
			}

			
			print "GET:". $_GET["searchorder"]. "end";
		
		
		$result = mysqli_query($link, //I FROM behöver vi sets?
		"SELECT sets.SetID, sets.Setname, images.ItemID, images.has_gif, images.has_jpg, images.has_largejpg, images.has_largegif
		FROM sets, images 
		$search AND sets.SetID = images.ItemID AND images.ItemtypeID = 'S'
		ORDER BY $order 
		LIMIT $limit" //Vi vill ha en offset här < så att man kan visa de andra satserna! Helst med hjälp av att klicka på en knapp elr något.
		);

		print "NEXT:". $_GET["next_page"];
		print "GET:". $sequered;	
		print "GET:". $_GET["search"];
		
	

	}
	else{
		echo "din sökning:".$_GET['search'];
		
	}
	
	
	?>
