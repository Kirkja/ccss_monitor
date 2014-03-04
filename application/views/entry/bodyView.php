<?php
// views/login/bodyView

// Is the user already logged into the system
if (!GSAuth::IsActive())
{
    $entry_form = $this->load->view('entry/login_form', null, true); 
    
    echo "<div>{$entry_form}</div>";
}
else {
    echo "<div class=\"padded\"><h3>Log in body</h3><br /></div>";
}


?>
