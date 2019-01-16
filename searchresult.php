<?php include "txt/header.txt";?>

		</div>
		<?php include "txt/help.txt";?>
		<div class="background">
			<div class="wrapper">
				<div class="searchBar">
				<form id="myForm" action ="searchresult.php" method="GET">
				<!-- Hela "sökfönstret" -->
					<div id="searchField">
					<p>Search <input type="text" name="search"/ required></p>
					<!-- Söktext in i denna -->
					</div>
					<div id="serachButton">
					<p><input type="submit" /> </p>
					</div>
					
					<div id="catergorySearch">
					</div>
					<div id="yearSearch">
					</div>
					<div id="sortBy">
						<?php
							include "radio.txt";	
						?>
					</div>
					</form>
				</div>
				<!-- php start-->
				<?php
				print("<p>Visar ANTAL resultat för sökordet ".$_GET['search']."...</p>");
				?>
				<div id="searchResult">
					<!--
					<table>
					
						<tr>
							<th>SetID</th>
							<th>Item-ID</th>
							<th>Setname</th>
							<th>Image</th>
						</tr>
						-->
		<?php

				$setCounter = 0;
				
				print("<p>Visarresultat för sökordet ".$_GET['search']."...</p>");
				
				print('<div id="searchResult">');
				$link = mysqli_connect("mysql.itn.liu.se" ,"lego","", "lego");
					
					
					if (!$link) {
						echo "Error: Unable to connect to MySQL." . PHP_EOL;
						echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
						echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
						exit;
					}
					include "search.php";
					//Sidan ska söka på ett setId elr setnamn, ge förslag på matchande sökresultat. Skriver man något som matchar 100% så kmr detta bara. Annars visas de som matchar fast i ordning.
					//Koppla upp <li> blog.php?order=asc;<li> blog.php?order=desc&limit=10 colors.Colorname LIKE "%'.$_GET["search"].'%" OR Setname LIKE "%'.$_GET["search"].'%"  ;
					//Search keyword thingy:
					//Skriv ut alla poster
					$urlBase="http://www.itn.liu.se/~stegu76/img.bricklink.com/";
					
					
					
					print("<ul class='resultsList'>");
			
					while($row = mysqli_fetch_array($result)) {
						
						//För bild
						$SetID = $row['SetID'];
						$itemtype = 'S';
						$filetype;
						$setYear = $row['Year'];
						
						
						
						if($row['has_largegif']OR $row['has_largejpg']){
							$itemtype = 'SL';
							
								if ($row['has_largegif'])
								{
									$filetype = ".gif";
								}
								else
								{
									$filetype = ".jpg";
								}
							}
							else{
							$itemtype= 'S';	
								
								if($row['has_jpg'])
								{
									$filetype=".jpg";
								}
								else
								{
									$filetype=".gif";
								}
							}
							
						$fileName = $itemtype."/".$SetID.$filetype;
						$imgsrc = $urlBase.$fileName;
						
						
						$setname=$row['Setname'];
						/*
						print ("<div class = searchResult>");
						print ("<tr><td><p>$fileName</p></td>");
						print ("<td><p>$SetID</p></td>");
						print ("<td><p>$setname</p></td>");
						print("<td><img src= $imgsrc alt=$fileName></td></tr>");
						print("</div>");
						*/
						
						print("<li>");
						
						print("
						<a href='set.php?searchID=$SetID'>
							<div class='box'>
								<div class='imgDiv'>
								<img class = 'setImg' src= $imgsrc alt=$fileName>
								</div> \n
								<div class='searchResultInfo'>
								<p><a href='set.php?searchID=$SetID'>$setname</a></p>
								<p class= 'year'>$setYear</p>
								</div> \n
							</div>
						</a>
						");
						
						print("</li>");
						
						$setCounter += 1;
						if( $setCounter == $itemsPerPage){
							print("<p>HEJ!</p>");
							$pageCounter += 1;
							break;
						}
						
					}
					
					print("</ul>");
					mysqli_close($link);
										
				?>
					<!--</table>-->
					<div id="arrows">
						<form name="pagination" id="pagination" action ="searchresult.php" method="GET">
							<button  id="prev" name="prev" value="2" onclick="changePage();" /><</button>
							<button  id="next" name="next" value="2" onclick="paginationNext"/>></button>
						</form>
					</div>
					
					<form id="pagination" name="pagination" action="searchresult.php" method="GET">
						<input id="prev2" name="prev" type="button" value="4" onclick="changePage();">
						<input type="submit" value="submit"/>
					</form>
					
					<script type="text/javascript">
						function changePage()
						{
							//Change value of button
							document.getElementById("prev").value = "3";
						}
					</script>
					
					
					</script>
				
				</div>
				
				
				
				<!-- php slut? -->
				<div id="searchFilter">
				</div>
				
			</div>
		</div>
		
	</body>
</html>