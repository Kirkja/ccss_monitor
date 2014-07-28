<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="tab2" class="tab-panel hidden" ng-controller="SettingsController">
    <div class="ui-layout-north ui-widget">
        <div class="toolbar ui-widget-content ui-state-active">
            <a href="" class="button" ng-click="markFolderCompleted()">Mark folder as completed</a>            
            <a href="" class="button" ng-click="markNIKS()">Mark as NIKS</a>            
            <a href="" class="button" ng-click="markSpecial('CNA')">Mark as special</a>
            
            <a href="" class="button right" ng-click="help()">help</a> 
            <a href="" class="button right" ng-click="debug()">debug</a> 
        </div>
    </div>
 
    <div id="innerTabs2" 
         class="ui-layout-center container tabs"          
         style="height:100%;">
     
        <ul>
            <li class="tab1"><a href="#simpleTab1b">Sample</a></li>
            <li class="tab2"><a href="#simpleTab2b">Standards</a></li>
            <li class="tab3"><a href="#simpleTab3b">Search</a></li>                       
        </ul>
    
        <div class="ui-widget-content" style="border-top:0;padding:0px;">

            <div id="simpleTab1b" 
                 style="padding:0px 0px 5px 0px;" 
                 ng-show="selected.image"> 
                <div class="fixedPalletteNarrow">
                    <a href="" class="button " ng-click="rotate(-90)">Rotate CCW</a>
                    <a href="" class="button " ng-click="rotate(90)">Rotate CW</a>
                    <a href="" class="button " ng-click="blank()">Blank</a>
                    <a href="" class="button right" ng-click="detachImage(currentImageID)">Detach</a>                   
                </div>
                
                <img degrees='angle' rotate 
                     id="sampleImage" 
                     src="<?php echo base_url();?>scan_images/{{selected.image}}" 
                     width="99%"/>                                                                        
            </div>
            
            <div id="simpleTab2b" style="padding:0px;"> 
                
                <!-- List of catalogs that are assigned to this block        -->
                <div id="catalogPalette">
                    <label ng-repeat="item in catalogs">                    
                        <input type="radio" 
                               ng-model="$parent.currentCatalogID" 
                               value="{{item.catalogID}}" 
                               ng-change="setCID($parent.currentCatalogID)"/>
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
                        title="Click to add standard">{{item.key}}
                    </button> 
                    <span class="stdDesc" ng-bind-html="item.desc"></span> 
                    
                </div>
                
            </div>

            <div id="simpleTab3b" style="padding:0px 0px 5px 0px;"> 
                <div id="searchPalette">
                    <label ng-repeat="item in catalogs">                    
                        <input class="rdSearch" type="checkbox" 
                               name="{{item.catalogID}}" 
                               value="{{item.catalogID}}"/>
                        <span>{{item.label}}</spn><br/>
                    </label> 
                    <table width="99%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><input id="searchInput" type="text" 
                                   ng-model="searchTerms" 
                                   ng-enter="searchNow(searchTerms)"
                        </td>
                        <td width="40" align="right">
                            <image id="searchButton" 
                                   class="btn" 
                                   ng-click="searchNow(searchTerms)" 
                                   src="/css/images/search.png"/>
                        </td>
                    </tr>
                    </table>                                      
                </div>
                
                <div class="loading">
                    {{searchMessage}}
                </div>   
                    
                <div class="searchEntry" 
                     ng-repeat="item in searchEntries" 
                     ng-class-odd="'rowOdd'" 
                     ng-class-even="'rowEven'">                    
                    <button 
                        class="stdKey" 
                        ng-click="addStd(item.key)"
                        alt="Click to add standard" 
                        title="Click to add standard">
                        <span ng-bind-html="item.key">/span>
                    </button> 
                    <span class="stdDesc" ng-bind-html="item.desc"></span>                    
                </div>                
            </div>  
        </div>
    </div>


    
    <div class="ui-layout-west">
        <div class="ui-layout-center">
            <div class="ui-widget-header ui-corner-top" style="padding:2px;">                                       
                <span class="btn fit" ng-click="refreshWA('open')">
                    <img src="<?php echo base_url(); ?>css/images/refresh24.png" 
                         alt="Refresh Assignments" 
                         title="Refresh Assignments"/>
                </span> 
                <span class="lift">Open Work</span>
            </div>
            <div class="ui-widget-content">
                <div ng-show="loadingOpenworkMessage">
                    <span class="loading">{{loadingOpenworkMessage}}</span>
                    <img src="<?php echo base_url(); ?>css/images/loading-icon.gif" />
                </div>
                                
                <treecontrol class="tree-classic" 
                             tree-model="openworkTree" 
                             node-children="children" 
                             on-selection="showSelected(node)" 
                             selected-node="node1">
                    {{node.label}}
                </treecontrol>                
            </div>
            <div class="ui-state-default blockSummary infoZone">
                <div>Due on: {{node1.dueON}}</div>
                <div>Pays: ${{node1.cashValue}}</div>
                <div>AlphaCode: {{node1.alphaCode}}</div>
            </div>
        </div>
        <div class="ui-layout-south">
            <div class="ui-widget-header ui-corner-top" style="padding:2px;">
                <span class="btn fit" 
                      ng-click="refreshWA('closed')">
                    <img src="<?php echo base_url(); ?>css/images/refresh24.png" 
                         alt="Refresh Assignments" 
                         title="Refresh Assignments"/>
                </span> 
                <span class="lift">Completed Work</span>
            </div>
            <div class="ui-widget-content">
                <div ng-show="loadingClosedworkMessage">
                    <span class="loading">{{loadingClosedworkMessage}}</span>
                    <img src="<?php echo base_url(); ?>css/images/loading-icon.gif" />
                </div>
                <treecontrol class="tree-classic" 
                             tree-model="closedworkTree" 
                             node-children="children" 
                             on-selection="showSelected(node)" 
                             selected-node="node2">
                    {{node.label}}
                </treecontrol>
            </div>
            <div class="ui-state-default blockSummary infoZone">
                <div>Due on: {{node2.dueON}}</div>
                <div>Pays: ${{node2.cashValue}}</div>
                <div>AlphaCode: {{node2.alphaCode}}</div>
            </div>            
        </div>
    </div>

    <div class="ui-layout-east">
        <div class="ui-layout-center">
            <div class="ui-widget-header ui-corner-top" style="padding:2px;">
                <span class="btn fit" ng-click="refreshRD()">
                    <img src="<?php echo base_url(); ?>css/images/refresh24.png" 
                         alt="Refresh Review Items" 
                         title="Refresh Review Items"/>
                </span>    
                <span class="lift">Review Items</span>
            </div>
            
            <div class="ui-widget-content" style="padding:0px;">

                <div class="fixedPallette">
                    <a href="" 
                       alt="Adds a new SCR entry" 
                       title="Adds a new SCR entry"
                       ng-click="addSCR()" 
                       class="button add right">Add SCR</a>
                    <a href="" 
                       ng-click="delSCR()" 
                       alt="Deletes all selected SCR entries" 
                       title="Deletes all selected SCR entries"                       
                       class="button delete">Del SCR</a>
                    <div class="palletteInfo">
                        <span alt="This is the folder name" 
                              title="This is the floder name">
                            {{currentBlockName}} 
                        </span>
                        <span ng-show="currentSampleName" 
                              alt="This is the sample name" 
                              title="This is the sample name"> 
                            / 
                        </span>
                        <span>{{currentSampleName}} </span>
                        <span alt="This is the image code" 
                              title="This is the image code" 
                              class="right">{{currentSampleAlphacode}}
                        </span>
                    </div>
                </div>                
                
                <div id="reviewDataZone">
                  <div ng-show="loadingRDFMessage">
                    <span class="loading">{{loadingRDFMessage}}</span>
                    <img src="<?php echo base_url(); ?>css/images/loading-icon.gif" />
                </div>
                <table class="reviewFormTable" 
                       ng-repeat="(key, value) in rdf2" 
                       ng-class-odd="'trOdd'" 
                       ng-class-even="'trEven'"
                       ng-class="{lite: currentGroupingID === (key|num)}">
                    <tr >
                        <td width="20">
                            <input class="rdSelector" type="checkbox" name="{{key}}" />
                        </td>
                        
                        <td ng-repeat="cell in value['cell']" >
                            <div ng-switch="cell.dataName">
                                
                                <input class="rdBox" ng-switch-when="id" name="{{cell.dataName}}-{{cell.recordID}}" type="text" size="4" maxlength="4" value="{{cell.dataValue}}" />                                                             

                                <input class="rdBox" ng-switch-when="counter" name="{{cell.dataName}}-{{cell.recordID}}" type="text" size="4" maxlength="4" value="{{cell.dataValue}}"/>

                                <select class="rdCRdd" ng-switch-when="dok" name="{{cell.dataName}}-{{cell.recordID}}">
                                    <option ng-repeat="i in dokArray" ng-selected="{{cell.dataValue === i}}">
                                        {{i}}
                                    </option>
                                </select>                                                              

                                <select class="rdCRdd"  ng-switch-when="blm" name="{{cell.dataName}}-{{cell.recordID}}">
                                    <option ng-repeat="i in blmArray" ng-selected="{{cell.dataValue === i}}">
                                        {{i}}
                                    </option>
                                </select>
                                
                                <select class="rdSPCdd" ng-switch-when="special" name="{{cell.dataName}}-{{cell.recordID}}">
                                    <option ng-repeat="i in spcArray" ng-selected="{{cell.dataValue === i}}">
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
                        <td width="20">                            
                            <image class="btn" ng-show="value.note" ng-click="getNote(key)" src="/css/images/yes_notes.png" id="{{key}}"/>
                            <image class="btn" ng-hide="value.note" ng-click="getNote(key)" src="/css/images/no_notes.png" id="{{key}}"/>                           
                        </td>
                    </tr>
                       
                </table>                                  
                
                </div>                                
            </div>
        </div>
        
        <div class="ui-layout-south">
            <div class="ui-widget-header ui-corner-top">Review Item Note</div>
            <div class="ui-widget-content" style="padding:0px;">
                <div ng-hide="currentGroupingID" style="padding:1em 2em;">
                    The note feature activates when you click either the  
                    <img class="inline" src="<?php echo base_url(); ?>css/images/no_notes.png"/> 
                    or <img class="inline" src="<?php echo base_url(); ?>css/images/yes_notes.png"/> icon on a review item in the above panel.
                </div>
                <div ng-show="currentGroupingID">
                    <div class="fixedPallette">                    
                        <a href="" class="button delete" ng-click="delNote()">Delete</a>
                        <a href="" class="button save right" ng-click="saveNote()">Save</a> 
                        <div class="palletteInfo">
                            <span>{{noteStamp}}</span> 
                            <span class="right">{{noteStatus}}</span>
                        </div>                                        
                    </div>

                    <textarea id="reviewNote" ng-model="currentNote"></textarea>
                </div>
                
                
            </div>
        </div>
    </div>
</div>
