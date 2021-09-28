<?php

function dictionary($word){
    $result = file_get_contents("https://api.dictionaryapi.dev/api/v2/entries/en/$word");
    $result = json_decode($result,true);
    if (!$result) {
        exit;
    }else{
        $meaning = $result[0]['meanings'][0]['definitions'][0]['definition'];
        return $meaning;
    }
}
?>
