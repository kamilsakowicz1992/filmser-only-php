<?php
//session_start();
class User
{   
    
    
    var $login;
    var $password;
    var $p_password;
    var $email;
    
    function __construct($login='',$password='',$p_password='',$email='')
    {
        $this->login=$login;
        $this->password=$password;
        $this->p_password=$p_password;
        $this->email=$email;
    }
    
    function add()
    {
        include 'db.connect.php';
        
        $search = $mysqli->query("SELECT login FROM users WHERE login like '$this->login'");
        if($search->num_rows>0)
        {
            echo "istnieje juz taki uzytkownik";
           echo "</br><a href='' >wroc</a>";
        }
        else {
        if($this->login !='')
        {
            $this->login=htmlentities($this->login,ENT_QUOTES);
        } else echo "bledny login";
        if (preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/',$this->password))
        {
            $this->password=htmlentities($this->password,ENT_QUOTES);
        } else echo "zle haslo";
        if (preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/',$this->p_password))
        {
            $this->p_password=htmlentities($this->p_password,ENT_QUOTES);
        } else echo "zle potworzone haslo";
        if(preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/',$this->email))
        {
            $this->email=htmlentities($this->email,ENT_QUOTES);
        } else echo "zly email";
        if ($stmt = $mysqli->prepare("INSERT users (login,password,email) VALUES (?,?,?)"))
        {
            $stmt->bind_param("sss",$this->login,$this->password,$this->email);
            $stmt->execute();
            $stmt->close();
            header("Location: index.php");
        }
        }
        $mysqli->close();
    }
    function login()
    {
        include 'db.connect.php';
        $log = $mysqli->query("SELECT * FROM users WHERE login='$this->login' and password='$this->password'");
        if ($log->num_rows>0)
        {
            echo "zalogowany";
            $row = $log->fetch_object();
            //session_start();
            $_SESSION['zalogowany'] = $this->login;
          //  $_SESSION['zalogowany_id'] = $this->id;
            $_SESSION['zalogowany_id'] = $row->id;
            $_SESSION['zalogowany_rola'] = $row->role;
            header("Location: index.php");
        } else 
        {
            echo "bledny login badz haslo";
            echo "<a href='' > wroc </a>";
        }
        $mysqli->close();
    }
    function logout()
    {
        session_destroy();
        header("Location: index.php");
    }
    function user_stats()
    {
        include 'db.connect.php';
      //  $log2 = $mysqli->query("SELECT * FROM users WHERE login='$this->login' and password='$this->password'");
      $login = $_SESSION['zalogowany'];
        $log2 = $mysqli->query("SELECT * FROM users WHERE login='$login'");
       // $log2->fetch_object();
       // echo $log2->id;
        while($row = $log2->fetch_object())
        {
            echo 'konto: ' . $row->role . '</br>';
            echo 'ilosc uploadow: '.$row->n_uploaded_films . '</br>';
            echo 'punkty: '.$row->points . '</br>';
            echo 'dni premium: '.$row->d_premium;
        }
       // while($row = $search->fetch_object())
        
    }
    }

class Movie
{
    function __construct($title='',$orginal_title='',$description='',$user_id='',$year='',$img_link='',$category_id='',$link='',$accept='no',$id='')
    {   
        $this->id=$id;
        $this->title=$title;
        $this->orginal_title=$orginal_title;
        $this->description=$description;
        $this->user_id=$user_id;
        $this->year=$year;
        $this->img_link=$img_link;
        if(!$img_link=='')
        {
        $this->img_name='screens/'.$this->id.$img_link["name"];
        }
        $this->category_id=$category_id;
        $this->link=$link;
        $this->accept=$accept;
    }
    function play_movie()
    {
        include 'db.connect.php';
        $search = $mysqli->query("SELECT * FROM movies WHERE id='$this->id'");
        $row = $search->fetch_object();

        echo '<div id="top_left"><img src="'.$row->img_link.'" height="120" width="120" ></div>
              <div id="top_right"><h2>'.$row->title.'</h2></br>'.$row->orginal_title.'</div>
              <div id="left"><video width="700" controls>
  <source src="'.$row->link.'" type="video/mp4">
  <source src="mov_bbb.ogg" type="video/ogg">
  Your browser does not support HTML5 video.
</video></div>';
        $mysqli->close();
    }
    function upload_screen()
    {
        // $this->img_name='screens/'.$this->title.$img_link["name"];
        $target_dir = "screens/";
      
       $target_file = $target_dir .$this->id."" .basename($this->img_link["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        if(isset($_POST["add_movie_submit"])) {
            $check = getimagesize($this->img_link["tmp_name"]);
            if($check !== false) {
                echo "plik jest obrazem - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "plik nie jest obrazem.";
                $uploadOk = 0;
            }
        }
        
        if (file_exists($target_file)) {
            echo "</br>wybacz plik juz istnieje.</br>";
            $uploadOk = 0;
        }
       
        if ($this->img_link["size"] > 500000) {
            echo "plik jest za duzy";
            $uploadOk = 0;
        }
       
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                echo "wybacz, tylko pliki JPG, JPEG, PNG & GIF sa dozwolone .";
                $uploadOk = 0;
            }
           
            if ($uploadOk == 0) {
                echo "wysylanie sie nie powiodlo.</br>";
                
            } else {
                if (move_uploaded_file($this->img_link["tmp_name"], $target_file)) {
                    echo "The file ". basename( $this->img_link["name"]). " has been uploaded.";
                } else {
                    echo "wybacz wystaplil blad poczas uploadu pliku";
                }
            }
    }
    function add()
    {    include 'db.connect.php';

        //zapisywanie pliku
        
        if($stmt = $mysqli->prepare("INSERT movies (title,orginal_title,description,user_id,year,img_link,category_id,link) VALUES (?,?,?,?,?,?,?,?)"))
        {
            $stmt->bind_param("sssiisis",$this->title,$this->orginal_title,$this->description,$this->user_id,$this->year,$this->img_name,$this->category_id,$this->link);
            $stmt->execute();
            $stmt->close();

        }
        $mysqli->close();
    }
}

class Search
{
    function __construct($search_phrase='',$accept='no',$user_id='',$category='')
    {   
        $this->user_id=$user_id;
        $this->search_phrase=$search_phrase;
        $this->accept=$accept;
        $this->category=$category;
    }
    function start_search()
    {   
        include 'db.connect.php';
       // %$this->search_phrase
        $search = $mysqli->query("SELECT * FROM movies WHERE title LIKE '%$this->search_phrase%' AND accept='$this->accept'");
        if($search->num_rows > 0)
        {   
            echo "sa wyniki "; 
            if($this->accept=='no')echo " poczekalni";
            echo "</br>";
            while($row = $search->fetch_object())
            {
                	    echo '<div class="movie_result">
                        <div class="movie_result_img" ><img src="'.$row->img_link.'" height="120" width="120"> </div>
                        <div class="movie_result_title">'. $row->title .'</div>
                        <div class="movie_result_description" >'.$row->description.' </div>';
                        if(($row->accept=='no') &&(isset($_SESSION['zalogowany_rola']))&&($_SESSION['zalogowany_rola']=='admin') )echo '<div class="accept_button" >
                            <form action "waiting.php" method="POST">
                            <input type="hidden" name="id_to_accept" value="'.$row->id.'" />
                            <input type="submit" name="accept_movie" value="zaakceptuj" />
                             </form> </div>';
                         echo '<div class="accept_button" >
                            <form action="play.php" method="POST">
                            <input type="hidden" name="id_to_play" value="'.$row->id.'" />
                            <input type="submit" name="play" value="odtwarzaj" />
                             </form> </div>';
                            echo '</div>';
            }

                        
        } 
        else echo "brak wynikow";
        if(isset($_POST['accept_movie']))
        {
            
            $id_movie = $_POST['id_to_accept'];
            $accept = $mysqli->query("UPDATE movies SET accept='yes' WHERE id=$id_movie");
            echo 'zaakceptowano film ';
            
            
        }
        $mysqli->close();
    }
    
    function search_my_movies()
    {
        include 'db.connect.php';
        // %$this->search_phrase
        $search = $mysqli->query("SELECT * FROM movies WHERE user_id='$this->user_id'");
        if($search->num_rows > 0)
        {
            echo "sa wyniki </br>";
            while($row = $search->fetch_object())
            {
                echo '<div class="movie_result">
                        <div class="movie_result_img" ><img src="'.$row->img_link.'" height="120" width="120"> </div>
                        <div class="movie_result_title">'. $row->title .'</div>
                        <div class="movie_result_description" >'.$row->description.' </div>';
                echo '<div class="accept_button" >
                            <form action="play.php" method="POST">
                            <input type="hidden" name="id_to_play" value="'.$row->id.'" />
                            <input type="submit" name="play" value="odtwarzaj" />
                             </form> </div>';
                echo '</div>';
            }
            
        }
        else echo "brak wynikow";
        $mysqli->close();
    }
    function search_by_category()
    {
        include 'db.connect.php';
        // %$this->search_phrase
        $search = $mysqli->query("SELECT * FROM movies WHERE category_id='$this->category'");
        if($search->num_rows > 0)
        {
            echo "sa wyniki </br>";
            while($row = $search->fetch_object())
            {
                echo '<div class="movie_result">
                        <div class="movie_result_img" ><img src="'.$row->img_link.'" height="120" width="120"> </div>
                        <div class="movie_result_title">'. $row->title .'</div>
                        <div class="movie_result_description" >'.$row->description.' </div>';
                echo '<div class="accept_button" >
                            <form action="play.php" method="POST">
                            <input type="hidden" name="id_to_play" value="'.$row->id.'" />
                            <input type="submit" name="play" value="odtwarzaj" />
                             </form> </div>';
                echo '</div>';
            }
            
        }
        else echo "brak wynikow";
        $mysqli->close();
    }
    function view_all($sort_new ='no sort')
    {
        include 'db.connect.php';
        
        $this->sort_new=$sort_new;
        // %$this->search_phrase
        if($this->sort_new =='sort')
        {
            $search = $mysqli->query("SELECT * FROM movies WHERE accept='yes' ORDER BY created_at ASC");
            $wyniki='Najnowsze :';
        } else
        {
        $search = $mysqli->query("SELECT * FROM movies WHERE accept='yes' ORDER BY id DESC");
        $wyniki='';
        }
        if($search->num_rows > 0)
        {
            echo "$wyniki wyniki </br>";
            while($row = $search->fetch_object())
            {
                echo '<div class="movie_result">
                        <div class="movie_result_img" ><img src="'.$row->img_link.'" height="120" width="120"> </div>
                        <div class="movie_result_title">'. $row->title .'</div>
                        <div class="movie_result_description" >'.$row->description.' </div>';
                echo '<div class="accept_button" >
                            <form action="play.php" method="POST">
                            <input type="hidden" name="id_to_play" value="'.$row->id.'" />
                            <input type="submit" name="play" value="odtwarzaj" />
                             </form> </div>';
                           echo '</div>';
            }
            
        }
        else echo "brak wynikow";
        $mysqli->close();
    }
    
   
            
 
    }
    


?>