<?php


?>
<div class="padded">
   
<form id="login" method="post" action="<?php echo base_url(); ?>entry" name="loginform">
    <fieldset id="inputs">        
        <input id="username" type="text" placeholder="Username"  required name="user_name" />   
        <input id="password" type="password" placeholder="Password" required autocomplete="off" name="user_password" />        
    </fieldset>
    <fieldset id="actions">
        <input type="submit" id="submit" value="Log in" class="btn right">        
    </fieldset>
</form>
</div>