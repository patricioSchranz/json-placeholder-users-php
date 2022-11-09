<?php

function escapeUserInput($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8', true);
}


// function createDataList($dataArray){
//     foreach($dataArray as $dataKey => $dataValue){
//         if(!is_array($dataValue)){
//             echo "<li>{$dataKey} => {$dataValue}</li>";
//         }
//         else{
//             // var_dump($dataValue);
//             // var_dump($dataKey);
//             echo "<li>{$dataKey} => <ul style='list-style:none'>";
//                 foreach($dataValue as $dataSubValue){
//                     if(!is_array($dataSubValue)){
//                         echo "<li>{$dataSubValue}</li>";
//                     }
                    
//                 }
//             echo "</ul></li>";
//         }
//     }
// }


function createDataList($dataArray){
    foreach($dataArray as $dataKey => $dataValue){
        if(!is_array($dataValue)){
            echo "<li>{$dataKey} => {$dataValue}</li>";
        }
        else{
            // var_dump($dataValue);
            // var_dump($dataKey);
            echo "<li>{$dataKey} => <ul style='list-style:none'>";
            createDataList($dataValue);
            echo "</ul></li>";
        }
    }
}