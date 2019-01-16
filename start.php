<?php include "txt/header.txt";?>

<!-- Avslutande av header-div -->
	<div id="description">
      <h3 id="descriptionText" class="headerText">Beskrivande text kommer...</h3>
    </div>

  </div>
  
			<?php include "txt/help.txt";?>
			</div>

		<div id="container">

				
			
			<div class="background"> 
			<div class="wrapper">

				<div class="searchBar">
					<form id="myForm" action ="searchresult.php" method="GET">
					<!-- Hela "sökfönstret" -->
						
						<input id="searchField" type="text" name="search" placeholder="Sök på en sats här..." /required>
						<!-- Söktext in i denna -->
						<!-- <div id="searchButton"> -->
						
						<div class="floati">

						<div id="catergorySearch">
							<!--kommer behöva hämta alla kategorier från databasen-->
							<!--<label for="category">Välj kategori</label>-->
							<select name="category">
							  <option value="" disabled selected><p>Välj kategori...</p></option>
							  <option value="starwars">Star Wars</option>
							  <option value="castle">Castle</option>
							  <option value="basic">Basic</option>
							  <option value="duplo">Duplo</option>
							</select>
						</div>

						<div id="yearSearch">
							<!--<label for="year">Välj år</label>-->
							<!--php för att hämta alla år-->
							<select name="year">
							  <option value="" disabled selected><p>Välj år...</p></option>
							  <option value="1999">1999</option>
							  <option value="2000">2000</option>
							  <option value="2001">2001</option>
							  <option value="2002">2002</option>
							</select>
						</div>

						<div id="sortBy">
							<!--<label for="sortBy">Sortera efter...</label>-->
							<select name="sortBy">
							  <option value="" disabled selected><p>Sortera efter...</p></option>
							  <option value="sets">Sats</option>
							  <option value="parts">Bitar</option>
							</select>
						</div>
								<div id="radio_buttons">
									<?php
										include "txt/radio.txt";
									?>
								</div>
								<input  id="searchButton" type="submit" />
						</div>
						
					</form>
				</div>
			
			</div>
		</div>
		
		</div>		
		
		<?php include "txt/footer.txt";?>
		
	</body>
</html>