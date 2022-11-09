<?php

$page = 'index';

require __DIR__ . '/view/header.php';
require __DIR__ . '/inc/database/users.php';



$usersArray = json_decode($users, true);

$userNames = [];

// var_dump($usersArray);

foreach($usersArray as $user){
    // var_dump($user['name']);

    if(!in_array($user['name'], $userNames)){
        $userNames[] = $user['name'];
    }
}

?>

<main class="index">
    <ul class="index_user-list">
        <?php 

            if(!empty($userNames)){
                foreach($userNames as $userName){
                    $thisQuery = http_build_query(['name' => $userName]);
                    // var_dump($thisQuery);

                    echo "
                        <li>
                            <a href='./view/user.php?{$thisQuery}'>$userName</a>
                        </li>
                    ";
                }
            }
        ?>
    </ul>
</main>


<?php require __DIR__ . '/view/footer.php'; ?>