<?php 
//include 'db.connect.php';
include 'classes.php';
include 'db.connect.php';
function wypisz_kategorie()
{
    include 'db.connect.php';
    if($result = $mysqli->query("SELECT id,name FROM category ORDER BY id") )
        
    {   
        
        while($row = $result->fetch_object())
        {   
            echo '<a href="index.php?search_category='.$row->id.'&search_category_submit=true" > <li class="category_li_button">'.$row->name .'</li> </a>';
          //  echo '<a href="" > <li class="category_li_button">'.$row->name .'</li> </a>';
        }
    }
    $mysqli->close();
}

function search()
{
    echo '<div class="search" >
        <form action="" method="get">
        <input type="text" name="search_phrase" size="30" />
        <input type="submit" name="search_submit" value="szukaj" />            
        </form> </div>';
//     if(isset($_GET['search_submit']))
//     {
//         $search = new Search($_GET['search_phrase']);
//         $search->start_search();
//     }
}
function login()
{
    if(!isset($_POST['submit']))
    {
    echo '<form action="" method="post">
		Login:<input type="text" name="login" /></br></br>
		Hasło:<input type="password" name="password" /></br>
		<input type="submit" name="submit" value="zaloguj" />
        <input type="submit" name="register" value="zarejestruj" />
		</form>';
    } else {
        $user = new User($_POST['login'], $_POST['password']);
        $user->login();
    }
    
}
function register()
{      
    if(!isset($_POST['reg_submit']))

{
   echo '<form action="" method="post">
		Login:<input type="text" name="login" /></br></br>
		Hasło: <input type="password" name="password" /></br>
        Hasło: <input type="password" name="p_password" /> </br>
        Email:<input type="text" name="email" /> </br>
		<input type="submit" name="reg_submit" value="zarejestruj" />
		</form>';
}else 
{

    $user = new User($_POST['login'], $_POST['password'], $_POST['p_password'], $_POST['email']);
    $user->add();
}
}
function user_panel()
{
     if(isset($_SESSION['zalogowany']))
    {
        $user = new User();
        $user->user_stats();
    } else
    {
        if(isset($_POST['register']) | (isset($_POST['reg_submit'])))
        {
            register();
        } else login();
    }
}
function logout_form()
{       
    echo '<form action="" method="post" >
           <input type="submit" name="logout" value="wyloguj" />
             </form>';
}
function accept_form()
{
    
}
function play_movie_form()
{
    echo '<div id="player"> </div>';
}

function add_movie_form()
{
    echo '<form action="" method="post" ENCTYPE="multipart/form-data">
		Tytuł:<input type="text" name="title" /></br></br>
        Orginalny Tytuł:<input type="text" name="orginal_title" /></br></br>
        Opis: <input type="text" name="description" /></br>
        <input type="hidden" name="user_id" value="'.$_SESSION['zalogowany_id'].'" />
		Rok produkcji: <input type="text" name="year" /></br>
        Screen:<input type="file" name="img_link" id="img_link"> </br>
        Kategoria: <select name="category_id">';
    
    include 'db.connect.php';
    if($result = $mysqli->query("SELECT id,name FROM category ORDER BY id") )
    {    
        while($row = $result->fetch_object())
        {
            echo '<li class="category_li_button">'.$row->name .'</li>';
            echo '<option value=' . $row->id. '>'.$row->name. '</option>';
        }
    }

       echo '</select> </br>
        Link:<input type="text" name="link" /> </br>
		<input type="submit" name="add_movie_submit" value="uploaduj" />
		</form>';
    if(isset($_POST['add_movie_submit']))
    {
        $movie = new Movie($_POST['title'],$_POST['orginal_title'],$_POST['description'],$_POST['user_id'], $_POST['year'],$_FILES['img_link'],$_POST['category_id'],$_POST['link']);
        $movie->upload_screen();
        $movie->add();
    }
    $mysqli->close();
}
function view_search()
{
    if(isset($_GET['search_submit']))
    {
        $search = new Search($_GET['search_phrase'],'yes');
        $search->start_search();
    }

}
function view_my_movies()
{   if(!isset($_GET['search_submit']))
{
    $search = new Search('','',$_SESSION['zalogowany_id']);
    $search->search_my_movies();
}
}
function view_all()
{   if(!isset($_GET['search_submit']))
{
    $search = new Search();
    $search->view_all();
}
}
function view_all_sort()
{
    if(!isset($_GET['search_submit']))
    {
    $search = new Search();
    $search->view_all('sort');
    }
    
}
function search_by_category($category)
{
    $search = new Search('','yes','',$category);
    $search->search_by_category();

}

function view_search_accepted()
{
    if(isset($_GET['search_submit']))
    {   $accept = "yes";
        $search = new Search($_GET['search_phrase'],$accept);
        $search->start_search();
    }
}
function view_search_not_accepted()
{   if(!isset($_GET['search_submit']))
{
    $search = new Search('','no','');
    $search->start_search();
} 
    
}
function play_movie()
{
    if(isset($_POST['play']))
        {
            $movie = new Movie('','','','','','','','','',$_POST['id_to_play']);
            $movie->play_movie();
        }
}
// function mysql_escape_globals() {
//     global $_POST, $_GET, $_COOKIE;
//     foreach($_POST as $a => $B) { $_POST["$a"] = mysql_escape_string($B); }
//     foreach($_GET as $a => $B) { $_GET["$a"] = mysql_escape_string($B); }
//     foreach($_COOKIE as $a => $B) { $_COOKIE["$a"] = mysql_escape_string($B); }
// }