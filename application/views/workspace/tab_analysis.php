<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="tab2" class="tab-panel hidden" >
    <div class="ui-layout-north ui-widget">
        <div class="toolbar ui-widget-content ui-state-active">
            Toolbar - tab2
        </div>
    </div>
 
    <div id="innerTabs2" 
         class="ui-layout-center container tabs" 
         ng-controller="SettingsController" 
         style="height:100%;">
     
        <ul>
            <li class="tab1"><a href="#simpleTab1b">Sample</a></li>
            <li class="tab2"><a href="#simpleTab2b">Standards</a></li>
            <li class="tab3"><a href="#simpleTab3b">Search</a></li>
        </ul>
    
        <div class="ui-widget-content" style="border-top:0;padding:0px;">

            <div id="simpleTab1b" style="padding:0px 0px 5px 0px;" ng-show="selected.image">                                   
                <img src="{{selected.image}}" width="100%"/>                                                                        
            </div>
            
            <div id="simpleTab2b" style="padding:0px;"> 
                <!-- List of catalogs that are assigned to this block        -->
                <div id="catalogPalette">
                    <label ng-repeat="item in catalogs">                    
                        <input type="radio" ng-model="$parent.catalogID" value="{{item.catalogID}}" ng-change="setCID(catalogID)"/>
                        <span>{{item.label}}</spn><br/>
                    </label>
                </div>
     
                
                <div class="loading">
                    {{loadingMessage}}
                </div>
                
                <!-- The standards for the selected catalog                  -->
                <div class="catalogEntry" 
                     ng-repeat="item in catalogEntries" 
                     ng-class-odd="'rowOdd'" 
                     ng-class-even="'rowEven'">                    
                    <button 
                        class="stdKey" 
                        ng-click="addStd(item.key)"
                        alt="Click to add standard" 
                        title="Click to add standard">{{item.key}}</button> <span class="stdDesc">{{item.desc}}</span>                    
                </div>
            </div>

            <div id="simpleTab3b" style="padding:0px 0px 5px 0px;"> 
                <div id="searchPalette">
                    <label ng-repeat="item in catalogs">                    
                        <input class="rdSearch" type="checkbox" name="{{item.catalogID}}" value="{{item.catalogID}}"/>
                        <span>{{item.label}}</spn><br/>
                    </label> 
                    <table width="99%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><input id="searchInput" type="text" ng-model="searchTerms"</td>
                        <td width="40" align="right"><image id="searchButton" class="btn" ng-click="searchNow(searchTerms)" src="/css/images/search.png"/></td>
                    </tr>
                    </table>                  
                    
                </div>
                
                    
                <div class="searchEntry" 
                     ng-repeat="item in searchEntries" 
                     ng-class-odd="'rowOdd'" 
                     ng-class-even="'rowEven'">                    
                    <button 
                        class="stdKey" 
                        ng-click="addStd(item.key)"
                        alt="Click to add standard" 
                        title="Click to add standard">{{item.key}}</button> <span class="stdDesc">{{item.desc}}</span>                    
                </div>
            </div>
        </div>
    </div>



    
    <div class="ui-layout-west">
        <div class="ui-layout-center">
            <div class="ui-widget-header ui-corner-top">Open Work                        
                <span class="btn right" ng-click="getOpenWork(user)">
                    <img src="<?php echo base_url(); ?>css/images/refresh_btn_b.png" alt="Refresh Assignments" title="Refresh Assignments"/>
                </span>                                          
            </div>
            <div class="ui-widget-content">
                <treecontrol class="tree-classic" 
                             tree-model="openworkTree" 
                             node-children="children" 
                             on-selection="showSelected(node)" 
                             selected-node="node1">
                    {{node.label}}
                </treecontrol>                
            </div>
            <div class="ui-state-default blockSummary">
                <div ng-show="node1.dueON">
                    <span class="right">Due On {{node1.dueON}}</span>
                    ${{node1.cashValue}}
                </div>
            </div>
        </div>
        <div class="ui-layout-south">
            <div class="ui-widget-header ui-corner-top">Completed Work
                <span class="btn right" ng-click="getClosedWork(user)">
                    <img src="<?php echo base_url(); ?>css/images/refresh_btn_b.png" 
                         alt="Refresh Assignments" 
                         title="Refresh Assignments"/>
                </span>             
            </div>
            <div class="ui-widget-content">
                <treecontrol class="tree-classic" 
                             tree-model="closedworkTree" 
                             node-children="children" 
                             on-selection="showSelected(node)" 
                             selected-node="node2">
                    {{node.label}}
                </treecontrol>
            </div>
            <div class="ui-state-default blockSummary">
                <div ng-show="node2.dueON">
                    <span class="right">Due On {{node2.dueON}}</span>
                    ${{node2.cashValue}}
                </div>
            </div>            
        </div>
    </div>

    <div class="ui-layout-east">
        <div class="ui-layout-center">
            <div class="ui-widget-header ui-corner-top">
                Sample Review
            </div>
            <div class="ui-widget-content" style="padding:0px;">

                <div class="reviewPalette">
                    <a href="" ng-click="addSCR()" class="button add">Add SCR</a>
                    <a href="" ng-click="delSCR()" class="button delete">Del SCR</a>
                </div>
                <div id="sampleInfo">
                    {{currentBlockName}} <span ng-show="currentSampleName"> - </span> {{currentSampleName}}
                </div>
                
                <div id="reviewDataZone">
                <table class="reviewFormTable"  
                       ng-repeat="(key, value) in rdf" 
                       ng-class-odd="'trOdd'" 
                       ng-class-even="'trEven'">
                    <tr >
                        <td width="20">
                            <input class="rdSelector" type="checkbox" name="{{key}}" />
                        </td>
                        
                        <td ng-repeat="cell in value">
                            <div ng-switch="cell.dataName">
                                
                                <input class="rdBox" ng-switch-when="id" name="{{cell.dataName}}-{{cell.recordID}}" type="text" size="4" maxlength="4" value="{{cell.dataValue}}" />                                                             

                                <input class="rdBox" ng-switch-when="counter" name="{{cell.dataName}}-{{cell.recordID}}" type="text" size="4" maxlength="4" value="{{cell.dataValue}}"/>

                                <select class="rdCRdd" ng-switch-when="dok" name="{{cell.dataName}}-{{cell.recordID}}">
                                    <option ng-repeat="i in dokArray" ng-selected="{{cell.dataValue == i}}">
                                        {{i}}
                                    </option>
                                </select>

                                <select class="rdCRdd"  ng-switch-when="blm" name="{{cell.dataName}}-{{cell.recordID}}">
                                    <option ng-repeat="i in blmArray" ng-selected="{{cell.dataValue == i}}">
                                        {{i}}
                                    </option>
                                </select>
                                   
                                <input type="text"
                                       class="rdStd"                                         
                                       id="{{cell.dataName}}-{{cell.recordID}}" 
                                       name="{{cell.dataName}}-{{cell.recordID}}" 
                                       value="{{cell.dataValue}}"
                                       ng-switch-when="standard"/>
                                                                                                    
                                <div class="reviewInput"  ng-switch-default="">
                                    {{cell.dataName}}, {{cell.dataValue}}
                                </div> 
                                
                            </div>

                        </td>
                        <td>
                            {{value.dataName}}
                            <!--
                            <image ng-click="getNote(key)" src="/css/images/no_notes.png" id="{{key}}"/>
                            -->
                        </td>
                    </tr>
                       
                </table>
                
                </div>                                
            </div>
        </div>
        
        <div class="ui-layout-south">
            <div class="ui-widget-header ui-corner-top">East-South</div>
            <div class="ui-widget-content">
                <p>AID: {{user.activeID}}</p>
                <p>BID: {{currentBlockID}}</p>
                <p>SID: {{currentSampleID}}</p>
                <p>IID: {{currentImageID}}</p>
                <p>CID: {{currentCatalogID}}</p>   
            </div>
        </div>
    </div>
</div>
