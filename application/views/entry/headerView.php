<?php
// views/login/headerView


//
// Test for how to handle the case of a valid user
//

if (Lib_auth::ActiveUser())
{
    /*
    $logoutButton = "<input class=\"btn right fix001\" type=\"button\" value=\"Log Out\" onclick=\"location.href = '". base_url() ."login/out'\" />";

    $currentUser = Lib_login::currentUser();
    
    echo "<div>{$logoutButton}<h1>Logged in as {$currentUser}</h1></div>";
    */

    redirect(base_url()."workspace2");

}
else
{
    echo "<div><h1>Log in</h1></div>";
    
    //echo "<pre>". print_r($_POST, true) ."</pre>";    
}


?>
