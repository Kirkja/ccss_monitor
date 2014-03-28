<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="tab3" class="tab-panel hidden">
    <div class="ui-layout-north ui-widget">
        <div class="toolbar ui-widget-content ui-state-active">
            Toolbar - tab3
        </div>
    </div>
    <div class="ui-layout-south ui-widget">

    </div>
    <div id="innerTabs" class="ui-layout-center container tabs">
        <div class="ui-widget-header ui-corner-top"> Center - Center </div>
        <ul>
            <li class="tab1"><a href="#simpleTab1">Settings</a></li>
            <li class="tab2"><a href="#simpleTab2">Messages</a></li>
            <li class="tab3"><a href="#simpleTab3">Tab #3</a></li>
        </ul>
        <div class="ui-widget-content" style="border-top: 0;">

            <div id="simpleTab1" class="container" style="height: 100%;" ng-controller="SettingsController">
                <div class="padding_container bordered-red">
                    <fieldset id="inputs"> 
                        <legend>Login Details</legend>
                        <dl class="form_element">
                            <dt><label for="screenname">Screen name</label></dt>
                            <dd><input id="screenname" type="text" ng-model="user.screenName" /></dd>
                        </dl>                                 

                        <dl class="form_element">
                            <dt><label for="username">User name</label></dt>
                            <dd><input type="text" ng-model="user.userName"/></dd>
                        </dl> 

                        <dl class="form_element">
                            <dt><label for="usercode">Password</label></dt>
                            <dd><input type="password" placeholder="Use Password to Save" ng-model="user.userCode"/></dd>
                        </dl> 
                        <dl class="form_element">
                            <dt><input type="button" ng-click="userUpdate()" value="Save"/></dt>
                            <dd></dd>
                    </fieldset>

                </div>
            </div>

            <div id="simpleTab2">
                <div class="padding_container bordered-red">

                </div>                        
            </div>

            <div id="simpleTab3"> 
                Tab #3 Content 
            </div>
        </div>                
    </div>

</div>