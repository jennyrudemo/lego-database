<?php include "txt/header.txt";?>

	</div>
	</div>
	<?php include "txt/help3.txt";?>
	<div id="container">
		<div class="background">
			<div class="wrapper">
				 <div id="setLeftColumn">
					<div id="setInfo">
						<?php
							//Koppla upp mot databasen
							$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
							
							if (!$connection) {
								die ('MySQL connection error.');
							}
							
							//Hämta sökord från URL
							$searchID = "'".$_GET["searchID"]."'";
							
							$urlBase="http://www.itn.liu.se/~stegu76/img.bricklink.com/";
							
							
							/***********************
							*       SETINFO        *
							***********************/
							
							//Fråga till databasen
							$contents = mysqli_query($connection,
							"SELECT sets.Setname, sets.SetID, sets.Year, categories.Categoryname, images.has_gif,
							images.has_jpg, images.has_largegif, images.has_largejpg
							FROM sets, categories, images 
							WHERE sets.SetID = $searchID AND sets.CatID = categories.CatID AND images.ItemID = sets.SetID
							LIMIT 4");
							
							//Skriver ut satsnamn, sats ID, år och katergori
							while($row = mysqli_fetch_array($contents)){
								
								//Variabeldeklaration - bildkälla
								$SetID = $row['SetID'];
								$itemtype = 'SL';
								$filetype = ".gif";
								
								//Väljer i första hand stor bild och av dem gif-format
								if(!($row['has_largejpg'] OR $row['has_largegif'])){
									$itemtype = 'S';
									
									if(!($row['has_gif'])){
										$filetype = ".jpg";
									};
								}
								else{
									if(!($row['has_largegif'])){
										$filetype = ".jpg";
									};
								};
								
								//Skriver ihop bildkällan
								$fileName = $itemtype."/".$SetID.$filetype;
								$imgsrc = $urlBase.$fileName;
								
								//Skriver ut bilden
								print("<img src= $imgsrc alt=$fileName>");
								
								
								//Variabeldeklaration - satsinformation
								$Setname = $row['Setname'];
								$SetYear = $row['Year'];
								$SetCat = $row['Categoryname'];
								
								//Skriver ut satsinformationen
								print("<h3>$Setname</h3>\n");
								print("<p> SetID: $SetID </p> \n");
								print("<p> År: $SetYear </p> \n");
								print("<p> Kategori: $SetCat </p> \n");
								
							}
							
				
							//Totalt bitar i ett set
							
							$setQuantity = mysqli_query($connection,
							"SELECT inventory.Quantity
							FROM inventory
							WHERE inventory.SetID=$searchID AND (inventory.ItemtypeID = 'P' OR inventory.ItemtypeID = 'M')");
							
							//Räknar totalt antal bitar i set
							$totSetQuantity = 0;
							
							//För varje bit i satsen läggs antalet av biten till $totSetQuantity
							while($quantRow = mysqli_fetch_array($setQuantity)) {
								$totSetQuantity += $quantRow['Quantity'];
							}
							
							print("<p>Antal bitar: $totSetQuantity</p>\n");
							
							
							//Bitar i setet som finns i samling
							$collectionQuant = mysqli_query($connection,
							"SELECT collection.Quantity
							FROM collection
							WHERE collection.SetID=$searchID");
							
							
							//Kollar om satsen finns i samling
							//Annars, kolla hur många av satsen bitar som finns i övriga satser i samlingen
							if($colQuantRow = mysqli_fetch_array($collectionQuant)){
								
								$colQuant= $colQuantRow['Quantity'];
								
								print("<p>Satsen finns!</p>");
								print("<p>Antal av denna sats: $colQuant </p>");
							}
							//För varje bit i sökta satsen, undersök om biten finns i annan sats i samlingen
							else {
								
								//Fråga efter itemID, antal och färg för alla bitar i sökta satsen
								$partsInSet = mysqli_query($connection,
								"SELECT inventory.ItemID, inventory.Quantity, inventory.ColorID
								FROM inventory
								WHERE inventory.SetID=$searchID AND (inventory.ItemtypeID = 'P' OR inventory.ItemtypeID = 'M')"
								);
								
								//Räknare för alla bitar i samlingen som tillhör satsen
								$totPartsCounter = 0;
								
								//Loopar varje bit i det sökta setet
								while($setRow = mysqli_fetch_array($partsInSet)){
									
									$itemID = $setRow['ItemID'];
									$colorID = $setRow['ColorID'];
									$quantGoal = $setRow['Quantity']; //antal av en viss bit i satsen
									
									//Frågar efter specifik bit som finns i samlingen
									//Ger antal av biten i varje sats den finns
									//samt antal av satsen som finns i samlingen
									$bitQuantity = mysqli_query($connection,
									"SELECT inventory.Quantity AS invQuantity, collection.Quantity AS colQuantity
									FROM inventory, collection
									WHERE inventory.ItemID = '$itemID' AND inventory.ColorID = $colorID
									AND collection.SetID = inventory.SetID");	
									
									//Räknare för totalt antal av specifik bit
									$bitCounter = 0;
									
									//Loopar för varje sats i samlingen som biten finns i
									while ($bitRow = mysqli_fetch_array($bitQuantity)){
										$quantity = $bitRow['invQuantity'] * $bitRow['colQuantity'];
										
										$bitCounter += $quantity;
									}
									
									//Testar om antalen av biten är störst i samlingen eller i satsen
									//$totPartsCounter tilldelas det mindre av värdena
									if ($bitCounter < $quantGoal) {
										$totPartsCounter += $bitCounter;
									}
									else if ($bitCounter >= $quantGoal) {
										$totPartsCounter += $quantGoal;
									}
									
								}
								
								//Om samlingen innehåller fler av en bit än vad som behövs till satsen
								//skrivs antalet som behövs till satsen ut. 
								print("<p>Varav i samling (från andra satser): $totPartsCounter</p>");
								
							}
							
					//Avsluta divven för satsinformation
					print("</div>");
					
					
					/***********************
					*  AVAILABILITY-INFO   *
					***********************/
					
					print("<div id='availabilityInfo'>");
					print("
							<table>
							<tr>
							<td><img src='img/green.svg' class='availInfo'  alt='grön cirkel'></td>
							<td><p>Komplett</p></td>
							</tr>
							<tr>
							<td><img class='availInfo' src='img/yellow.svg' alt='gul cirkel'></td>
							<td><p>Ersättningsbitar finns</p></td>
							</tr>
							<tr>
							<td><img class='availInfo' src='img/red.svg' alt='röd cirkel'></td>
							<td><p>Ej komplett</p></td>
							</tr>
							</table> ");
						
					//Avsluta availability info	
					print("</div>");
					
				//Avsluta vänstra kolumnen
				 print("</div>");
				 
				 
				 print("<div id='setTable'>");
				 
					/***********************
					*       MINIFIGURES    *
					***********************/
					print("<div id='minifigs'>");
					
					print ("<h3>Minifigurer</h3>");
					
					//Frågar efter minifigurerna i det sökta setet
					$minifigsSearch= mysqli_query($connection,
					"SELECT minifigs.Minifigname, inventory.ItemID, inventory.Quantity,
					images.has_gif, images.has_jpg, images.has_largegif, images.has_largejpg
					FROM inventory, minifigs, images
					WHERE inventory.SetID = $searchID AND inventory.ItemID = minifigs.MinifigID AND
					inventory.ItemtypeID = 'M' AND inventory.ItemID=images.ItemID");
					
					//Testar om sökningen gett ett resultat
					if(mysqli_num_rows($minifigsSearch)< 1){
						print("<p>Inga minifigurer i detta set!</p>");
					}
					else{
						print("<table class='left_float'>\n<tr>");
						print ("<th>  </th>");
						print ("<th> Bild </th>");
						print ("<th class='vanish'> Namn </th>");
						print ("<th class='vanish'> Figur ID </th>");
						print ("<th> Antal i sats </th>");
						print ("<th> Antal i samling </th>");
						print ("</tr>\n");
						
						$availableFigGreen = array(); //figurer som finns i samlingen
						$availableFigRed = array(); //figurer som inte finns i samlingen
							
						//Loopar för varje figur i satsen
						while($minifigRow = mysqli_fetch_array($minifigsSearch)) {
							$itemID = $minifigRow['ItemID'];
							$itemtype = 'ML';
							$filetype = ".jpg";
							$figName = $minifigRow['Minifigname'];
							$inventQuant = $minifigRow['Quantity']; //antalet av figuren i sökta satsen
							
							//Ger antalet av figuren för varje sats den finns i och antalet av den satsen
							$bitQuantity = mysqli_query($connection,
							"SELECT inventory.Quantity AS invQuantity, collection.Quantity AS colQuantity
							FROM inventory, collection
							WHERE inventory.ItemID = '$itemID'
							AND collection.SetID = inventory.SetID");
							
							
							//Undersöka om liten bild finns, sedan om gif finns
							if($minifigRow['has_gif'] OR $minifigRow['has_jpg']){
								$itemtype = 'M';
								
								if($minifigRow['has_gif']){
									$filetype = ".gif";
								};
							}
							else if($minifigRow['has_largegif']){
								$filetype = ".gif";
							};
							
							//Bildkälla
							$imgsrc = $urlBase.'/'.$itemtype.'/'.$itemID.$filetype;
							
							//Räknare för antal av figuren i hela samlingen
							$bitCounter = 0;
							
							//Loopar för varje sats figuren finns med i
							while ($bitRow = mysqli_fetch_array($bitQuantity)){
								$quantity = $bitRow['invQuantity'] * $bitRow['colQuantity'];
								 
								$bitCounter += $quantity;
							}
							
							//Jämför antalet av figuren i samlingen mot vad som behövs i satsen
							if ($bitCounter >= $inventQuant){
									$imgsrcAvail = '"img/green.svg"';
									$availabilityText = 'Tillgänglig';
									
									$availableFigGreen[] = 
									"<tr>
									<td><img class='availInfo' src=$imgsrcAvail alt='$availabilityText'></td>
									<td><img src=$imgsrc alt='$figName'></td>
									<td class='vanish'>$figName</td>
									<td>$itemID</td>
									<td>$inventQuant</td>
									<td>$bitCounter</td>
									</tr>\n";
									
							}
							else{
								$imgsrcAvail = '"img/red.svg"';
								$availabilityText = 'Ej tillgänglig';
								
								$availableFigRed[] = 
								"<tr>
								<td><img class='availInfo' src=$imgsrcAvail alt='$availabilityText'></td>
								<td><img src=$imgsrc alt='$figName'></td>
								<td class='vanish'>$figName</td>
								<td class='vanish'>$itemID</td>
								<td>$inventQuant</td>
								<td>$bitCounter</td>
								</tr>\n";
							};
					
						}
							
						//Skriver ut figurer som inte finns tillräckligt av
						for ( $i = 0; $i < count($availableFigRed); $i++) {
							print $availableFigRed[$i];
						}
						//Skriver ut figurer som finns
						for ( $i = 0; $i < count($availableFigGreen); $i++) {
							print $availableFigGreen[$i];
						}
					
						print("</table>");
					}	
					
					//Avsluta div för minifigurer
					print("</div>");
					
					/***********************
					*       PARTS          *
					***********************/
					print("<div id='parts'>");
					
					print ("<h3>Bitar</h3>");
					
						//Frågar efter bitarna i det sökta setet
						$partsSearch= mysqli_query($connection,
						"SELECT parts.Partname, inventory.ItemID, inventory.Quantity, inventory.ColorID,
						images.has_gif, images.has_jpg, images.has_largegif, images.has_largejpg, colors.Colorname
						FROM inventory, parts, images, colors
						WHERE inventory.SetID = $searchID AND inventory.ItemID = parts.PartID AND
						inventory.ItemtypeID = 'P' AND inventory.ItemID=images.ItemID AND 
						inventory.ColorID = images.ColorID AND inventory.ColorID = colors.ColorID");
						
						//Testar om sökningen gett ett resultat
						if(mysqli_num_rows($partsSearch)< 1){
						print("<p>Inga bitar i detta set!</p>");
						}
						else{
							print("<table>\n<tr>");
							print ("<th>  </th>");
							print ("<th> Bild </th>");
							print ("<th class='vanish'> Namn </th>");
							print ("<th> FigurID </th>");
							print ("<th  class='vanish'> Färg </th>");
							print ("<th> Antal i sats </th>");
							print ("<th> Antal i samling (godtyckling färg) </th>");
							print ("</tr>\n");
							
							$availableGreen= array(); //bitar som finns i samlingen
							$availableYellow = array(); //finns i rätt antal, men inte rätt färg
							$availableRed = array(); //bitar som inte finns i samlingen
						
							//Loopar för varje bit i satsen
							while($partsRow = mysqli_fetch_array($partsSearch)) {
								
								$itemID = $partsRow['ItemID'];
								$itemtype = 'PL';
								$filetype = ".jpg";
								$partName = $partsRow['Partname'];
								$colorID = $partsRow['ColorID'];
								$inventQuant = $partsRow['Quantity']; //antalet av biten i sökta satsen
								$colorName = $partsRow['Colorname'];
								
								//Ger antalet av biten för varje sats den finns i och antalet av den satsen
								$bitQuantity = mysqli_query($connection,
								"SELECT inventory.Quantity AS invQuantity, collection.Quantity AS colQuantity, inventory.ColorID
								FROM inventory, collection
								WHERE inventory.ItemID = '$itemID'
								AND collection.SetID = inventory.SetID 
								LIMIT 5000"
								);
								
								
								//Undersöka om liten bild finns, sedan om gif finns
								if($partsRow['has_gif'] OR $partsRow['has_jpg']){
									$itemtype = 'P/'.$colorID;
									
									if($partsRow['has_gif']){
										$filetype = ".gif";
									};
								}
								else if ($partsRow['has_largegif']){
									$filetype = ".gif";
								};
								
								//Bildkälla
								$imgsrc = $urlBase.'/'.$itemtype.'/'.$itemID.$filetype;
								
								$bitCounter = 0; //Räknare för biten i rätt färg i samlingen
								$mixedColorCounter = 0; //Räknare för biten i alla färger i samlingen
								
								//Loopar för varje sats biten finns med i
								while ($bitRow = mysqli_fetch_array($bitQuantity)){
									$tempColorID = $bitRow['ColorID'];
									
									$quantity = $bitRow['invQuantity'] * $bitRow['colQuantity'];
									 
									//testar om biten har rätt färg
									if ($tempColorID == $colorID) {
										$bitCounter += $quantity;
									};
									
									$mixedColorCounter += $quantity;
									
								}
								
								//AVAILABILITY-FÄRG
								
								//Tänkt optimering som inte fungerar felfritt
								/*$imgsrcAvail;
								$availabilityText;
								$partInfo =
									"<tr>
									<td><img class='availInfo' src=$imgsrcAvail alt='$availabilityText'></td>
									<td><img src=$imgsrc alt='$partName'></td>
									<td class='vanish'>$partName</td>
									<td class='vanish'>$itemID</td>
									<td>$colorName</td>
									<td>$inventQuant</td>
									<td>$bitCounter ($mixedColorCounter)</td>
									</tr>\n";
								*/
								
								//Testar om antal bitar i samlingen uppnår antal i satsen 
								if ($bitCounter >= $inventQuant){
									$imgsrcAvail = '"img/green.svg"';
									$availabilityText = 'Tillgänglig';
									
									$availableGreen[] = 
									"<tr>
									<td><img class='availInfo' src=$imgsrcAvail alt='$availabilityText'></td>
									<td><img src=$imgsrc alt='$partName'></td>
									<td class='vanish'>$partName</td>
									<td class='vanish'>$itemID</td>
									<td>$colorName</td>
									<td>$inventQuant</td>
									<td>$bitCounter ($mixedColorCounter)</td>
									</tr>\n";
									
								}
								else if($mixedColorCounter >= $inventQuant){
									$imgsrcAvail = '"img/yellow.svg"';
									$availabilityText = 'Tillgänglig i annan färg';
									
									$availableYellow[] = 
									"<tr>
									<td><img class='availInfo' src=$imgsrcAvail alt='$availabilityText'></td>
									<td><img src=$imgsrc alt='$partName'></td>
									<td class='vanish'>$partName</td>
									<td>$itemID</td>
									<td class='vanish'>$colorName</td>
									<td>$inventQuant</td>
									<td>$bitCounter ($mixedColorCounter)</td>
									</tr>\n";
								}
								else{
									$imgsrcAvail = '"img/red.svg"';
									$availabilityText = 'Ej tillgänglig';
									
									$availableRed[] = 
									"<tr>
									<td><img class='availInfo' src=$imgsrcAvail alt='$availabilityText'></td>
									<td><img src=$imgsrc alt='$partName'></td>
									<td class='vanish'>$partName</td>
									<td>$itemID</td>
									<td class='vanish'>$colorName</td>
									<td>$inventQuant</td>
									<td>$bitCounter ($mixedColorCounter)</td>
									</tr>\n";
								};
							
							}
							
							//Skriver ut bitar som inte finns tillräckligt av
							for ( $i = 0; $i < count($availableRed); $i++) {
								print $availableRed[$i];
							}
							//Skriver ut bitar som finns, men inte alla i rätt färg
							for ( $i = 0; $i < count($availableYellow); $i++) {
								print $availableYellow[$i];
							}
							//Skriver ut bitar där alla finns i rätt färg
							for ( $i = 0; $i < count($availableGreen); $i++) {
								print $availableGreen[$i];
							}
						}	
						
						print("</table>");
							
					//Avslutar divven för bitar
					print("</div>");
				//Avslutar divven för bitar och figurer
				 print("</div>");
				?>
			</div>
		</div>
		</div>
		<?php include "txt/footer.txt";?>
	</body>
</html>
