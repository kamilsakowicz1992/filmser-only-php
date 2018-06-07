<!DOCTYPE html>
<html>
<?php include 'functions.php';?>
<head>
<link rel="stylesheet" href="style.css" type="text/css" />
<script  type="text/javascript" src="timer.js"></script>
</head>
<body onload="czas()">
<div id="container" >

	<div id="logo" >
	<img src="img/logo.png" />
	</div>
	<div id="user_panel" >
		<h3>Witaj: <?php session_start(); if(isset($_SESSION['zalogowany']))
		{
		echo $_SESSION['zalogowany'];
		echo "</br>";
		logout_form();
		}else echo "gosc";
		    ?></h3>
		    <?php if(isset($_POST['logout']))
		    {
		        $user = new User();
		        $user->logout();

		    }?>
	<?php user_panel();?>
		
	</div>
	<div id="menu" >
	
		<div class="menubutton" ><a href="index.php">Strona główna</a></div>
		<div class="menubutton" ><a href="movies.php">Filmy</a></div>
		<div class="menubutton" ><a href="waiting.php">Poczekalnia</a></div>
		<?php if(isset($_SESSION['zalogowany'])) echo '<div class="menubutton" ><a href="add_movie.php">Dodaj film</a></div><div class="menubutton" ><a href="my_movies.php">Moje filmy</a></div>';
		?>
		
		
	
	</div>
	<div id="topbar" >
	<span id="Data" style="float:left; margin-right:10px;" > </span>
	<?php  search();?>
	</div>
	<div id="sidebar">
	<?php  wypisz_kategorie();?>

	</div>
	<div id="content" >
	<?php //add_movie_form();
	   view_my_movies();
	?>
	<?php view_search();?>
	<?php
// 	for($i=0;$i<10;$i++)
// 	{
// 	    echo '<div class="movie_result">
//         <div class="movie_result_img" > a </div>
//         <div class="movie_result_title">Tytul filmu nanana!!!!!! </div>
//         <div class="movie_result_description" >opis filmu </div>

//             </div>';
// 	}
	?>
	</div>
	<div id="footer" >
	    Copyright © 2017 Kamil Sakowicz

Żaden z prezentowanych materiałów nie jest hostowany na serwerach fili.tv. Serwis udostępnia jedynie informacje o filmach oraz odnośniki do serwisów udostępniających zamieszczone materiały wideo (mi. vidoza.net, youtube.com, streamango.com, openload.co itp.), których użytkownicy potwierdzili, że posiadają prawa autorskie do udostępnianych przez siebie zasobów. Wszelkie roszczenia prawne należy kierować pod adresem serwisów publikujących zamieszczone materiały. Administracja serwisu nie ponosi odpowiedzialności za treści i komentarze publikowane przez użytkowników.
	</div>



</div>





</body>
</html>