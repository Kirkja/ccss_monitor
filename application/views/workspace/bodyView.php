<?php


?>
<div id="outer-north">
    <span id="branded_as">Granted Solutions</span>
    <span>(<?php echo GSAuth::GetUserObject()->screenName; ?>)</span>
    
    <button id="btn_logout">Log Out</button>
    <br/>
    <span>Analyst</span>    
</div>


<div id="page-loading">Loading...</div>

<div id="outer-south" class="hidden"></div>


<div id="outer-center" class="hidden">

    <!-- Tabs for the panels ............................................... -->
    <ul id="tabbuttons" class="hidden">
        <li class="tab1"><a href="#tab1">Admin</a></li>
        <li class="tab2"><a href="#tab2">Analysis</a></li>
        <li class="tab3"><a href="#tab3">Account</a></li>
    </ul>

    <!-- Tab panel container ............................................... -->
    <div id="tabpanels" ng-controller="SettingsController">
        <!-- Admin Tab ..................................................... -->
        <?php echo $tab_admin; ?>
        
        <!-- Analysis Tab .................................................. -->
        <?php echo $tab_analysis; ?>
        
        <!-- Account Tab ................................................... -->      
        <?php echo $tab_account; ?>
    </div>
    
</div>
<!-- /#outer-center ........................................................ -->

