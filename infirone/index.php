<?php
if(isset($_SERVER['PATH_INFO'])){
    if(str_contains($_SERVER['PATH_INFO'], '/')){
        $Slug = str_replace('/','',$_SERVER['PATH_INFO']);
    }else{
        $Slug = $_SERVER['PATH_INFO'];
    }

    if(strcasecmp($Slug,'home')==0){
        include 'home.php';
    }elseif(strcasecmp($Slug,'about-us')==0){
        include 'about.php';
    }
    
}else{
    include 'home.php';
}
?>
