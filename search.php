
<?php
	//om sökrutan är tom sök inget
	
		
		$sequered = mysqli_real_escape_string($link, $_GET["search"]);
		
		$search = ' WHERE (sets.SetID LIKE "'.$sequered.'%" OR sets.Setname LIKE "%'.$sequered.'%") ';
		
		
	if(isset($_GET["search"]))
	{
		
		
		//$test = "sets.SetID LIKE '%".$_GET["search"]."%'";//Vi vill söka på setID, men i start vill vi inte ha alla.
		//$test_name="sets.Setname LIKE '%".$_GET["search"]."%'";
		$limit = $_GET["limit"];
		$order = mysqli_real_escape_string($link, $_GET["searchorder"]);
		
		$searchorder = mysqli_real_escape_string($link, $_GET["searchorder"]);
		
		
		
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
				
				
			
			
			if(!$limit = $_GET["limit"])
			{
				$limit = 10000;
			}
			
			
			
			//Ställer in hur många resultat som visas per sida
			//flytta upp denna högre upp?
			$itemsPerPage= 10;
			
			if(isset($_GET['page']) && !empty($_GET['page'])){
				$page= $_GET['page'];
				$offset = ($page - 1)*$itemsPerPage;
				
				$result = mysqli_query($link, //I FROM behöver vi sets?
				"SELECT sets.SetID, sets.Setname, images.ItemID, images.has_gif, images.has_jpg, images.has_largejpg,
				images.has_largegif, sets.Year
				FROM sets, images 
				$search AND sets.SetID = images.ItemID AND images.ItemtypeID = 'S'
				ORDER BY $order 
				LIMIT $limit OFFSET $offset" //Vi vill ha en offset här < så att man kan visa de andra satserna! Helst med hjälp av att klicka på en knapp elr något.
				);
			}
			else{
				$page = 1;
				
				$result = mysqli_query($link, //I FROM behöver vi sets?
				"SELECT sets.SetID, sets.Setname, images.ItemID, images.has_gif, images.has_jpg, images.has_largejpg,
				images.has_largegif, sets.Year
				FROM sets, images 
				$search AND sets.SetID = images.ItemID AND images.ItemtypeID = 'S'
				ORDER BY $order 
				LIMIT $limit" //Vi vill ha en offset här < så att man kan visa de andra satserna! Helst med hjälp av att klicka på en knapp elr något.
				);
			}
			//ev ändra variabel $page till $offset
			
	
	}
	else{
		echo "din sökning:".$_GET['search'];
		
	}
	
	
	?>
