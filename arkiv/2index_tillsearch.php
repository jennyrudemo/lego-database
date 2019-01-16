<!doctype html>


<html lang="ca">
	<head>
		<meta charset="utf-8">
		<title>Grupp1 Lego</title>
		<link href="style.css" media="screen" rel="stylesheet" />
		
	</head>
	<body>
		<form action ="index_tillsearch.php" method="GET">
					<p>Search <input type="text" name="search"/></p>
					<p><input type="submit"/></p>
					
		</form>
		<table>
	
		<tr>
			<th>Quantity</th>
			<th>File name</th>
			<th>Color</th>
			<th>Image</th>
			<th>Part name</th>
		</tr>

	
		
		
	
<?php

$link = mysqli_connect("mysql.itn.liu.se" ,"lego","", "lego");
	
	


if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

include "search.php";


//Koppla upp <li> blog.php?order=asc;<li> blog.php?order=desc&limit=10 colors.Colorname LIKE "%'.$_GET["search"].'%" OR Setname LIKE "%'.$_GET["search"].'%"  ;

//Search keyword thingy:

//Skriv ut alla poster
while($row = mysqli_fetch_array($result)) {
	

	$ItemID = $row["ItemID"];
	$ItemtypeID = $row["ItemtypeID"];
			
	$ColorID = $row["ColorID"];

	$Quantity = $row["Quantity"];
	$image= "http://www.itn.liu.se/~stegu76/img.bricklink.com";
	$Partname = $row["Partname"];
	
	$Colorname = $row["Colorname"];
	
	
	print("<tr><td>$Quantity</td>");
	
	
	if($row["has_jpg"])
	{
		$typ = ".jpg";
	}
	else if($row["has_gif"])
	{
		$typ = ".gif";
	}
	else if($row["has_largejpg"])
	{
		$typ = ".jpg";
	}
	else if($row["has_largegif"])
	{
		$typ = ".gif";
	}
	else{$typ = ".nope";}
	
	
	$filename = "/".$ItemtypeID."/".$ColorID."/".$ItemID.$typ;
	print("<td>$filename</td> ");
	
	

	print("<td>$Colorname</td>");
	
	
	print("<td> <img src=\"$image$filename\"></td>");
												
	
	print("<td>$Partname</td> </tr>");
	
}

mysqli_close($link);
						
?>

	</tr>
	
	</table>
		
	</body>
</html>