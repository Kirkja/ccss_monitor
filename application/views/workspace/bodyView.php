<?php
/*
 * To change this license header, choose License Headers in project properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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

    <ul id="tabbuttons" class="hidden">
        <li class="tab1"><a href="#tab1">Admin</a></li>
        <li class="tab2"><a href="#tab2">Analysis</a></li>
        <li class="tab3"><a href="#tab3">Account</a></li>
    </ul>

    <div id="tabpanels" ng-controller="SettingsController">

        <div id="tab1" class="tab-panel hidden">
            <div class="ui-layout-north ui-widget">
                <div class="toolbar ui-widget-content ui-state-active">
                    Toolbar - tab1
                </div>
            </div>

            <div class="ui-layout-south ui-widget">

            </div>

            <div class="ui-layout-center">
                <div class="ui-widget-header ui-corner-top">Center-Center</div>
                <div class="ui-widget-content">
                    <div>
                        path = {{selected.imagePath}} <br/>
                        name = {{selected.imageName}}
                    </div>
                    
                    <div style="text-align:center" ng-show="selected.imagePath">
                        <img src="{{selected.imagePath}}{{selected.imageName}}" width="99%"/>
                    </div>
                </div>
            </div>
            
            <div class="ui-layout-west">
                <div class="ui-layout-north">
                    <div class="ui-widget-header ui-corner-top">West-North</div>
                    <div class="ui-widget-content">
                        stuff WN
                    </div>

                </div>
                <div class="ui-layout-center" >
                    <div class="ui-widget-header ui-corner-top">West-Center</div>
                    <div class="ui-widget-content">
                        <treecontrol class="tree-classic" 
                            tree-model="treedata" 
                            node-children="children" 
                            on-selection="showSelected(node)" 
                            selected-node="node1">
                                {{node.label}}
                        </treecontrol>
                      
                    </div>

                </div>
                <div class="ui-layout-south">
                    <div class="ui-widget-header ui-corner-top">West-South</div>
                    <div class="ui-widget-content">

                    </div>

                </div>
            </div>
            <div class="ui-layout-east">
                <div class="ui-layout-center">
                    <div class="ui-widget-header ui-corner-top">East-Center</div>
                    <div class="ui-widget-content">
                        stuff
                    </div>

                </div>
                <div class="ui-layout-south">
                    <div class="ui-widget-header ui-corner-top">East-South</div>
                    <div class="ui-widget-content">
                        stuff
                    </div>                    
                </div>
            </div>
        </div>
        <!-- /#tab1 (Admin) ................................................ -->

        <div id="tab2" class="tab-panel hidden">
            <div class="ui-layout-north ui-widget">
                <div class="toolbar ui-widget-content ui-state-active">
                    Toolbar - tab2
                </div>
            </div>

            <div class="ui-layout-south ui-widget">

            </div>

            <div class="ui-layout-center">
                <div class="ui-widget-header ui-corner-top">Center-Center</div>
                <div class="ui-widget-content container">
                    {{user.activeID}}
                    <div style="text-align:center" ng-show="selected.imagePath">
                        <img src="{{selected.imagePath}}{{selected.imageName}}" width="99%"/>
                    </div>                    
                </div>                
            </div>

            <div class="ui-layout-west">
                <div class="ui-layout-center">
                    <div class="ui-widget-header ui-corner-top">Open Work                        
                        <span class="btn right" ng-click="getOpenWork(user)">
                            <img src="<?php echo base_url();?>css/images/refresh_btn_b.png" alt="Refresh Assignments" title="Refresh Assignments"/>
                        </span>                                          
                    </div>
                    <div class="ui-widget-content">
                        <treecontrol class="tree-classic" 
                            tree-model="treedata2" 
                            node-children="children" 
                            on-selection="showSelected(node)" 
                            selected-node="node1">
                                {{node.label}}
                        </treecontrol>
                    </div>
                </div>
                <div class="ui-layout-south">
                    <div class="ui-widget-header ui-corner-top">Completed Work</div>
                    <div class="ui-widget-content">
                        West center stuff here
                    </div>
                </div>
            </div>

            <div class="ui-layout-east">
                <div class="ui-layout-center">
                    <div class="ui-widget-header ui-corner-top">East-Center</div>
                    <div class="ui-widget-content">
                        stuff here
                    </div>

                </div>
                <div class="ui-layout-south">
                    <div class="ui-widget-header ui-corner-top">East-South</div>
                    <div class="ui-widget-content">
                        stuff here
                    </div>
                </div>
            </div>
        </div>
        <!-- /#tab2 (Analysis) ............................................. -->

        <div id="tab3" class="tab-panel hidden">
            <div class="ui-layout-north ui-widget">
                <div class="toolbar ui-widget-content ui-state-active">
                    Toolbar - tab3
                </div>
            </div>
            <div class="ui-layout-south ui-widget">

            </div>
            <div id="innerTabs" class="ui-layout-center container tabs">
                <div class="ui-widget-header ui-corner-top"> Center -Center </div>
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
        <!-- /#tab3 (Account) .............................................. -->

    </div>
    <!-- /#tabpanels ....................................................... -->

</div>
<!-- /#outer-center ........................................................ -->

