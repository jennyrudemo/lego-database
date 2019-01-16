<?php include "txt/header.txt";?>

	</div>
	
	<div id="constainer">
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
							//$searchID = $_GET['searchID'];//
							$searchID = "'".$_GET["searchID"]."'";
							
							//$searchID = "'3342-1'";
							
							$urlBase="http://www.itn.liu.se/~stegu76/img.bricklink.com/";
							$contents = mysqli_query($connection,
							"SELECT sets.Setname, sets.SetID, sets.Year, categories.Categoryname, images.has_gif,
							images.has_jpg, images.has_largegif, images.has_largejpg
							FROM sets, categories, images 
							WHERE sets.SetID = $searchID AND sets.CatID = categories.CatID AND images.ItemID = sets.SetID
							LIMIT 30");
							
				
							
							//Skriver ut satsnamn, sats ID, år och katergori
							while($row = mysqli_fetch_array($contents)){
								
								//För bild
								$SetID = $row['SetID'];
								$itemtype = 'S';
								$filetype;
								
								if($row['has_gif'] OR $row['has_largegif']){
									$filetype = ".gif";
									
									if($row['has_largegif']){
									$itemtype = 'SL';
									};
								}
							
								else{
									$filetype = ".jpg";
									
									if($row['has_largejpg']){
									$itemtype = 'SL';
									};
								};
								
								
								$fileName = $itemtype."/".$SetID.$filetype;
								$imgsrc = $urlBase.$fileName;
								print("<img src= $imgsrc alt=$fileName>");
		
								$Setname = $row['Setname'];
								$SetYear = $row['Year'];
								$SetCat = $row['Categoryname'];
								
								print("<h3>$Setname</h3>\n");
								print("<p> SetID: $SetID </p> \n");
								print("<p> År: $SetYear </p> \n");
								print("<p> Kategori: $SetCat </p> \n");
								
							}
							
				
							// Räknare för antal totala bitar i ett set
							$setQuantity = mysqli_query($connection,
							"SELECT inventory.Quantity
							FROM inventory
							WHERE inventory.SetID=$searchID AND (inventory.ItemtypeID = 'P' OR inventory.ItemtypeID = 'M')
							LIMIT 30");
							
							$totSetQuantity = 0;
							while($quantRow = mysqli_fetch_array($setQuantity)) {
								$totSetQuantity += $quantRow['Quantity'];
							}
							
							print("<p>Antal bitar: $totSetQuantity</p>\n");
							
							$collectionQuant = mysqli_query($connection,
							"SELECT collection.Quantity
							FROM collection
							WHERE collection.SetID=$searchID
							LIMIT 30");
							
							
							//Kollar om satsen finns i samling
							if($colQuantRow = mysqli_fetch_array($collectionQuant)){
								
								$colQuant= $colQuantRow['Quantity'];
								
								print("<p>Satsen finns!</p>");
								print("<p>Antal av denna sats: $colQuant </p>");
							}
							else {
								//Om ej i samling: kolla hur många av satsens bitar som finns i samling
								
								//Hur många av bitarna i söksatsen finns i andra satser i samlingen?
								
								//För varje bit i sökta satsen vill vi kolla om den biten finns i
								//någon annan sats i samlingen
								
								$partsInSet = mysqli_query($connection,
								"SELECT inventory.ItemID, inventory.Quantity, inventory.ColorID
								FROM inventory
								WHERE inventory.SetID=$searchID AND (inventory.ItemtypeID = 'P' OR inventory.ItemtypeID = 'M') 
								LIMIT 30"
								);
								
								//Display alla bitar och figurer i satsen som vi söker på - IN PROGRESS
								//print("<table>");
								
								//lägg till counter för samlarens bitar (som räknas i while loopen) (totPartsCounter)
								$totPartsCounter = 0;
								
								while($setRow = mysqli_fetch_array($partsInSet)){
									//while loop för varje bit i det sökta setet
									$itemID = $setRow['ItemID'];
									$colorID = $setRow['ColorID'];
									$quantGoal = $setRow['Quantity'];
									
									//sökning på collections mm, se bild på Trello
									//kommer ge antal av biten för varje setID, en rad för varje SetID
									$bitQuantity = mysqli_query($connection,
									"SELECT inventory.Quantity AS invQuantity, collection.Quantity AS colQuantity
									FROM inventory, collection
									WHERE inventory.ItemID = '$itemID' AND inventory.ColorID = $colorID
									AND collection.SetID = inventory.SetID
									LIMIT 30
									"
									);
									
									//varaibel counter
									$bitCounter = 0;
									
									//while loop för varje rad i sökningen
									while ($bitRow = mysqli_fetch_array($bitQuantity)){
										$quantity = $bitRow['invQuantity'] * $bitRow['colQuantity'];
										
										//lägga på quantity till en counter
										$bitCounter += $quantity;
									}
									
									//när raderna är slut och om counter < quant_goal: return counter
									//counter eller countGoal ska läggas till i den totala countern (fullCounter)
									//testa om counter >= quant_goal(el counterGoal)
										//om den når det: lägg countGoal i totPartsCounter
									if ($bitCounter < $quantGoal) {
										$totPartsCounter += $bitCounter;
									}
									else if ($bitCounter >= $quantGoal) {
										$totPartsCounter += $quantGoal;
									}
									
								}
								
								//Skriv ut fullCounter i "Varav i samling:"
								print("<p>Varav i samling (från andra satser): $totPartsCounter</p>");
								
								
								//print("</table>");
							}
							
							//Note: i tabellen: skriv ut totalt antal bitar i samlingen (även om inte alla behövs till att bygga ett set)
							
							
							
							
							
					print("</div>");
					
					print("<div id='availabilityInfo'>");
					print("
							<table>
							<tr>
							<td><img src='./green.svg' class='availInfo'  alt='gröm cirkel'></td>
							<td><p>Komplett</p></td>
							</tr>
							<tr>
							<td><img class='availInfo' src='./yellow.svg' alt='gul cirkel'></td>
							<td><p>Ersättningsbitar finns</p></td>
							</tr>
							<tr>
							<td><img class='availInfo' src='./red.svg' alt='röd cirkel'></td>
							<td><p>Ej komplett</p></td>
							</tr>
							</table> ");
						
					print("</div>");
				 print("</div>");
				 
				 
				 
				 print("<div id='setTable'>");
				 
					/***********************
					*       MINIFIGURES    *
					***********************/
					print("<div id='minifigs'>");
					
					print ("<h3>Minifigurer</h3>");
					
					$minifigsSearch= mysqli_query($connection,
					"SELECT minifigs.Minifigname, inventory.ItemID, inventory.Quantity,
					images.has_gif, images.has_jpg, images.has_largegif, images.has_largejpg
					FROM inventory, minifigs, images
					WHERE inventory.SetID = $searchID AND inventory.ItemID = minifigs.MinifigID AND
					inventory.ItemtypeID = 'M' AND inventory.ItemID=images.ItemID
					LIMIT 30");
					if(mysqli_num_rows($minifigsSearch)< 1){
						print("<p>Inga minifigurer i detta set!</p>");
					}
					
					else{
						print("<table>\n<tr>");
						print ("<th>  </th>");
						print ("<th> Bild </th>");
						print ("<th> Namn </th>");
						print ("<th> Figur ID </th>");
						print ("<th> Antal i sats </th>");
						print ("<th> Antal i samling </th>");
						print ("</tr>\n");
						
						$availableFigGreen = array();
						$availableFigRed = array();
						
						//Varje figur
						while($minifigRow = mysqli_fetch_array($minifigsSearch)) {
							$itemID = $minifigRow['ItemID'];
							$itemtype = 'M';
							$figName = $minifigRow['Minifigname'];
							$inventQuant = $minifigRow['Quantity'];
							
							$bitQuantity = mysqli_query($connection,
							"SELECT inventory.Quantity AS invQuantity, collection.Quantity AS colQuantity
							FROM inventory, collection
							WHERE inventory.ItemID = '$itemID'
							AND collection.SetID = inventory.SetID
							LIMIT 30
							"
							);
							
							
							//Hämta hela bild-url
							if($minifigRow['has_gif'] OR $minifigRow['has_largegif']){
								$filetype = ".gif";
								
								if(!$minifigRow['has_gif']){
									$itemtype = 'ML';
								};
							}
							else{
								$filetype = ".jpg";
								
								if(!$minifigRow['has_jpg']){
									$itemtype = 'ML';
								};
							};
							
							$imgsrc = $urlBase.'/'.$itemtype.'/'.$itemID.$filetype;
							
							//Räkna ut colQuant (bitCounter)
							$bitCounter = 0;
							
							//while loop för varje rad i sökningen
							while ($bitRow = mysqli_fetch_array($bitQuantity)){
								$quantity = $bitRow['invQuantity'] * $bitRow['colQuantity'];
								 
								//lägga på quantity till en counter
								$bitCounter += $quantity;
							}
							
							if ($bitCounter >= $inventQuant){
									$imgsrcAvail = '"green.svg"';
									$availabilityText = 'Tillgänglig';
									
									$availableFigGreen[] = 
									"<td><img class='availInfo' src=$imgsrcAvail alt=$availabilityText></td>
									<td><img src=$imgsrc alt=$figName></td>
									<td>$figName</td>
									<td>$itemID</td>
									<td>$inventQuant</td>
									<td>$bitCounter</td>
									</tr>\n";
									
								}
							else{
								$imgsrcAvail = '"red.svg"';
								$availabilityText = 'Ej tillgänglig';
								
								$availableFigRed[] = 
								"<td><img class='availInfo' src=$imgsrcAvail alt=$availabilityText></td>
								<td><img src=$imgsrc alt=$figName></td>
								<td>$figName</td>
								<td>$itemID</td>
								<td>$inventQuant</td>
								<td>$bitCounter</td>
								</tr>\n";
							};
					
						}
					}	
			
					for ( $i = 0; $i < count($availableFigRed); $i++) {
						print $availableFigRed[$i];
					}
					for ( $i = 0; $i < count($availableFigGreen); $i++) {
						print $availableFigGreen[$i];
					}
					
					print("</table>");
					
					print("</div>");
					
					/***********************
					*       PARTS          *
					***********************/
					print("<div id='parts'>");
					
					print ("<h3>Bitar</h3>");
					
						$partsSearch= mysqli_query($connection,
						"SELECT parts.Partname, inventory.ItemID, inventory.Quantity, inventory.ColorID,
						images.has_gif, images.has_jpg, images.has_largegif, images.has_largejpg, colors.Colorname
						FROM inventory, parts, images, colors
						WHERE inventory.SetID = $searchID AND inventory.ItemID = parts.PartID AND
						inventory.ItemtypeID = 'P' AND inventory.ItemID=images.ItemID AND 
						inventory.ColorID = images.ColorID AND inventory.ColorID = colors.ColorID
						LIMIT 30");
						
						if(mysqli_num_rows($partsSearch)< 1){
						print("<p>Inga bitar i detta set!</p>");
						}
						
						else{
							print("<table>\n<tr>");
							print ("<th>  </th>");
							print ("<th> Bild </th>");
							print ("<th> Namn </th>");
							print ("<th> FigurID </th>");
							print ("<th> Färg </th>");
							print ("<th> Antal i sats </th>");
							print ("<th> Antal i samling </th>");
							print ("</tr>\n");
							
						}	
							$availableGreen= array();
							$availableYellow = array();
							$availableRed = array();
							//$finnsinte[] = $row;
						
							//Varje bit
							while($partsRow = mysqli_fetch_array($partsSearch)) {
								
								$itemID = $partsRow['ItemID'];
								$itemtype = 'P';
								$partName = $partsRow['Partname'];
								$colorID = $partsRow['ColorID'];
								$inventQuant = $partsRow['Quantity'];
								$colorName = $partsRow['Colorname'];
								
								$bitQuantity = mysqli_query($connection,
								"SELECT inventory.Quantity AS invQuantity, collection.Quantity AS colQuantity, inventory.ColorID
								FROM inventory, collection
								WHERE inventory.ItemID = '$itemID'
								AND collection.SetID = inventory.SetID 
								LIMIT 1000"
								);
								
								
								//Hämta hela bild-url
								if($partsRow['has_gif'] OR $partsRow['has_largegif']){
									$filetype = ".gif";
								
									if($partsRow['has_gif']){
										$itemtype = $itemtype."/".$colorID;
									}
									else {
										$itemtype = 'PL';
									};
								}
								else{
									$filetype = ".jpg";
									
									if($partsRow['has_jpg']){
										$itemtype = $itemtype."/".$colorID;
									}
									else {
										$itemtype = 'PL';
									};
								};
								
								$imgsrc = $urlBase.'/'.$itemtype.'/'.$itemID.$filetype;
								
								//Räkna ut colQuant (bitCounter)
								$bitCounter = 0;
								$mixedColorCounter = 0;
								
								//while loop för varje rad i sökningen
								while ($bitRow = mysqli_fetch_array($bitQuantity)){
									$tempColorID = $bitRow['ColorID'];
									
									//$quantity = $bitRow['invQuantity'] ;
									$quantity = $bitRow['invQuantity'] * $bitRow['colQuantity'];
									 
									//lägga på quantity till en counter
									if ($tempColorID == $colorID) {
										$bitCounter += $quantity;
									};
									
									//mixedColorCounter är antalet av en bit, oavsett färg
									$mixedColorCounter += $quantity;
									
								}
								
								//AVAILABILITY-FÄRG
								//OBS: Lägg till counter för fel färg i while-loopen
								
								//Testa om antal bitar i samlingen uppnår antal i satsen 
								/*if($bitCounter < $inventQuant){
									$imgsrcAvail = 'red';
								}*/
								if ($bitCounter >= $inventQuant){
									$imgsrcAvail = '"green.svg"';
									$availabilityText = 'Tillgänglig';
									
									$availableGreen[] = 
									"<td><img class='availInfo' src=$imgsrcAvail alt=$availabilityText></td>
									<td><img src=$imgsrc alt=$partName></td>
									<td>$partName</td>
									<td>$itemID</td>
									<td>$colorName</td>
									<td>$inventQuant</td>
									<td>$bitCounter ($mixedColorCounter)</td>
									</tr>\n";
									
								}
								else if($mixedColorCounter >= $inventQuant){
									$imgsrcAvail = '"yellow.svg"';
									$availabilityText = 'Tillgänglig i annan färg';
									
									$availableYellow[] = 
									"<td><img class='availInfo' src=$imgsrcAvail alt=$availabilityText></td>
									<td><img src=$imgsrc alt=$partName></td>
									<td>$partName</td>
									<td>$itemID</td>
									<td>$colorName</td>
									<td>$inventQuant</td>
									<td>$bitCounter ($mixedColorCounter)</td>
									</tr>\n";
								}
								else{
									$imgsrcAvail = '"red.svg"';
									$availabilityText = 'Ej tillgänglig';
									
									$availableRed[] = 
									"<td><img class='availInfo' src=$imgsrcAvail alt=$availabilityText></td>
									<td><img src=$imgsrc alt=$partName></td>
									<td>$partName</td>
									<td>$itemID</td>
									<td>$colorName</td>
									<td>$inventQuant</td>
									<td>$bitCounter ($mixedColorCounter)</td>
									</tr>\n";
								};
							
							}
						
						//ev array merge
						for ( $i = 0; $i < count($availableRed); $i++) {
							print $availableRed[$i];
						}
						
						for ( $i = 0; $i < count($availableYellow); $i++) {
							print $availableYellow[$i];
						}
						
						for ( $i = 0; $i < count($availableGreen); $i++) {
							print $availableGreen[$i];
						}
						
						print("</table>");
							
					
					print("</div>");
				 print("</div>");
				?>
			</div>
		</div>
		</div>
		<?php include "txt/footer.txt";?>
	</body>
</html>
