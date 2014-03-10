<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div ng-controller="entryController">
    <div id="messages" ng-show="message">{{ message }}</div>
<form ng-submit="processForm()">
    <!-- NAME -->
    <div id="name-group" class="form-group">
        <label>Name</label>
        <input type="text" name="user_name" class="form-control" placeholder="Bruce Wayne" ng-model="formData.userName">
        <span class="help-block"></span>
    </div>

    <!-- SUPERHERO NAME -->
    <div id="superhero-group" class="form-group">
        <label>Superhero Alias</label>
        <input type="text" name="user_password" class="form-control" placeholder="Caped Crusader" ng-model="formData.userPassword">
        <span class="help-block"></span>
    </div>

    <!-- SUBMIT BUTTON -->
    <button type="submit" class="btn btn-success btn-lg btn-block">
        <span class="glyphicon glyphicon-flash"></span> Submit!
    </button>
</form>

<!-- SHOW DATA FROM INPUTS AS THEY ARE BEING TYPED -->
<pre>
{{ formData}}
</pre>
</div>