<?php include "txt/header.txt";?>

<!-- Avslutande av header-div -->
		<div id="description">
      <h3 id="descriptionText" class="headerText">Beskrivande text kommer...</h3>
    </div>

  </div>
  <div id="background"> 

		<img id="legobit-test" src="legobit-test.svg" alt="legobit-test">

	  <div id="searchBar">
			<form id="myForm" action ="searchresult.php" method="GET">
			  <!-- Hela "sökfönstret" -->
			  <div id="searchRow1">
				<input id="searchField" type="text" name="search" placeholder="Sök på en sats här..."/>
				<!-- Söktext in i denna -->
			  <!-- <div id="searchButton"> -->
				<input  id="searchButton" type="submit" />
			  </div>

			  <div id="catergorySearch">
				<!--kommer behöva hämta alla kategorier från databasen-->
				<!--<label for="category">Välj kategori</label>-->
				<select name="category">
				  <option value="" disabled selected>Välj kategori...</option>
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
				  <option value="" disabled selected>Välj år...</option>
				  <option value="1999">1999</option>
				  <option value="2000">2000</option>
				  <option value="2001">2001</option>
				  <option value="2002">2002</option>
				</select>
			  </div>

			  <div id="sortBy">
				<!--<label for="sortBy">Sortera efter...</label>-->
				<select name="sortBy">
				  <option value="" disabled selected>Sortera efter...</option>
				  <option value="sets">Sats</option>
				  <option value="parts">Bitar</option>
				</select>
				
						<?php
							include "radio.txt";
						?>
						
				</div>
					
			</form>
		</div>
		
		<?php include "txt/footer.txt";?>
		
		
	</body>
</html>
