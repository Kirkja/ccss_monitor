<?php
// views/login/headerView


//
// Test for how to handle the case of a valid user
//

if (GSAuth::IsActive())
{
    redirect(base_url()."workspace");
}


?>
