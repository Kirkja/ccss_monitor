<?php
// views/login/bodyView

// Use a login form is not active
if (!GSAuth::IsActive())
{
    $entry_form = $this->load->view('entry/login_form', null, true); 
    
    echo "<div>{$entry_form}</div>";
}



?>
