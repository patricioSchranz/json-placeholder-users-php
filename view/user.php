<?php

$page = 'user';

require __DIR__ . '/header.php';
require __DIR__ . '/../inc/database/users.php';
require __DIR__ . '/../inc/database/albums.php';
require __DIR__ . '/../inc/functions/main-functions.php';
require __DIR__ . '/../inc/database/photos.php';
require __DIR__ . '/../inc/database/todos.php';



// => => 
// JSON DATENSÄTZE WERDEN IN ASSOZIATIVE ARRAYS UMGEWANDELT

$usersArray = json_decode($users, true);
$albumsArray = json_decode($albums, true);
$photoCollections = json_decode($photos, true);
$todoList = json_decode($todos, true);


$searchedUser = $_GET['name'];  // gesucheter User aus Query String
$findUser = 0;                  // State ob der User gefunden wurde oder nicht
$searchedUserDatas = 0;         // Initialwert 0 wird durch Userdaten ersetzt (Array)
$userId = 0;                    // Initialwert 0 wird durch tatsächliche User Id ersetzt

$searchedUserAlbums = [];       // die Alben des gesuchten Users
$searchedPhotoCollections = []; // die Photos des gesuchten Users

$searchedUserTodos = [];        // die Todos des gesuchten Users



// => => 
// USERDATENSÄTZE WERDEN GESAMMELT

// => USERDATEN UND STATE
// es wird überprüft ob der gesuchte User existiert und gegebenenfalls werden seine Daten
// in ein Array gespeichert
// es wird die alternative PHP Syntax verwendet

if($searchedUser && $usersArray):
    foreach($usersArray as $user):
        // var_dump($user['name']);
        if($user['name'] === $searchedUser) $GLOBALS['findUser'] = 1;
        if($user['name'] === $searchedUser) $searchedUserDatas = $user;
    endforeach;
endif;


// => USER ALBEN
// wenn ein User gefunden wurde wird
// 1.) seine ID in eine Variable gespeichert
// 2.) aus dem Datensatz aller Alben, die des gesuchten Users in ein Array gespeichert

if($findUser){

    $userId = $searchedUserDatas['id'];

    foreach($albumsArray as $album){
        // var_dump($album);
        // echo "<br><br>";
        if($album['userId'] === $userId){
            $searchedUserAlbums[] = $album;
        }
    }
}


// => USER BILDER
// wenn ein Array mit dem Alben des Users existiert
// werden aus dem Foto Datensatz alle Fotos des
// gesuchten Users in ein Array gespeichert

if($searchedUserAlbums){
   
    foreach($photoCollections as $photoCollection){
      foreach($searchedUserAlbums as $searchedUserAlbum){
        if($searchedUserAlbum['id'] === $photoCollection['albumId'])
            $searchedPhotoCollections[] = $photoCollection;
      }
    }
}


// => USER TODOS

if($findUser && $todoList){
    foreach($todoList as $todoItem){
        // var_dump($todoItem);
        if($todoItem['userId'] === $userId) $searchedUserTodos[] = $todoItem;
    }
}

?>

<main class="user">

<!-- Überschrift und Userdatenliste -->

    <!-- 
        wenn der Datensatz aller User vorhanden ist und
        wenn der gesuchte User gefunde wurde wird
        der Query String in die Überschrift eingefügt
        und eine Liste mit den Userdaten generiert
        => Alternative Syntax 
    -->

    <?php if(!empty($usersArray)) :?>
        <h2 class="user_h2">

            <!-- 
             die Ausgabe wird escaped => 
             gefährliche Zeichen werden in Entitäten umgewandelt 
             die Funktion kommt aus main-functions.php
            -->

            <?php if($findUser) echo escapeUserInput($searchedUser) ?>
        </h2>

        <ul class="user_data-list">
            
            <!-- 
                Userdatenliste wird von PHP generiert 
                Funktion aus mainfunctions.php 
            -->

            <?php createDataList($searchedUserDatas) ?>
        </ul>     
    <?php endif; ?>



<!-- User Alben Links --->

    <div class="user_album-activators-container">

        <h3><?php echo $searchedUserDatas['name'] ?>`s Pictures</h3>
        <div>

        <!-- 
            Wenn ein Array mit den Useralben vorhanden ist
            wird mit einer for Schleife für jeden Album Eintrag
            ein Link erzeugt der das enstsprechende 
            Album einbledet
            => es wird eine Alternative zur Alternativen Syntax verwendet
        -->

        <?php if(!empty($searchedUserAlbums)){
            // echo count($searchedUserAlbums);
            for( $albumCounter = 1; $albumCounter < count($searchedUserAlbums)+1; $albumCounter++){
        ?>

            <a href="#album-<?php echo $albumCounter ?>">
                Album <?php echo $albumCounter ?>
            </a>

        <?php }} ?>
        </div>
    </div>



<!-- Alben und Bilder -->

    <!-- 
        Für jedes Useralbum wird eine Section erzeugt 
        die alle Bilder enthält die dem Album zugeordnet sind
        => Mischung aus Alternativer Syntax und deren Alternative
    -->
    
    <?php if(!empty($searchedUserAlbums)){
        $albumCounter = 0; // wird für die ID der Section gebraucht
        $imageCounter = 0; // wird gebraucht um dei Anzahl der Bilder auszugeben

        foreach($searchedUserAlbums as $searchedUserAlbum){
            $albumNumber = $searchedUserAlbum['id']; // wird gebraucht um die richtigen Bilder auszuwählen
            $GLOBALS['imageCounter'] = 0;
            $GLOBALS['albumCounter']++;
            // var_dump($searchedUserAlbum);
    ?>
        <!-- <span><?php echo $albumCounter ?></span> -->
        <section class="user_photo-section" id="album-<?php echo $albumCounter ?>">

            <?php if(!empty($searchedPhotoCollections)):?>
                <?php foreach($searchedPhotoCollections as $photoCollection):?>
                    <?php if($photoCollection['albumId'] === $albumNumber):?>
                        <img src="<?php echo $photoCollection['thumbnailUrl']?>" alt="ein Bild">
                        <?php $GLOBALS['imageCounter']++ ?>
                        <!-- <?php echo $albumNumber ?> -->
                        <!-- <?php echo $imageCounter?> -->
                    <?php endif;?>

                <?php endforeach;?>
            <?php endif; ?>

            <span>
            <?php
                echo
                    "In <span>Album "
                    . $GLOBALS['albumCounter']
                    . " </span>befinden sich "
                    . $GLOBALS['imageCounter']
                    . " Bilder";
            ?>
           </span>

        </section>
    <?php }} ?>
    


<!-- Todos des Users --->

    <section class="user_todo-section">
        <h3>Die Todos von <?php echo $searchedUserDatas['name']?></h3>

        <ul class="user_todo-section_list">
            <?php if($searchedUserTodos){
                foreach($searchedUserTodos as $todoItem){
                    if($todoItem['completed']){
                        echo
                            "<li class='completed'>"
                            . $todoItem['title']
                            . "</li>";
                    }
                    else{
                        echo
                            "<li class='not-completed'>"
                            . $todoItem['title']
                            . "</li>";
                    }
                }
            }
            ?>
        </ul>
    </section>
   
</main>



<!-- mehr schlecht als Rechtes Equivalent zur Consolen Arbeit mit JS -->

<pre>
    <?php
        // var_dump($searchedUser);
        // var_dump($findUser);
        // var_dump($albums);
        // var_dump($searchedUserDatas);
        // var_dump($searchedUserDatas['id']);
        // var_dump($userId);
        // var_dump($searchedUserAlbums);
        // var_dump($photoCollections);
        // var_dump($searchedPhotoCollections);
        // var_dump($todoList);
        // var_dump($searchedUserTodos);
    ?>
</pre>

<?php require __DIR__ . '/footer.php' ?>