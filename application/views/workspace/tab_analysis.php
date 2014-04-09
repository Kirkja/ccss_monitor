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

            <div id="simpleTab1b" 
                 style="padding:0px 0px 5px 0px;" 
                 ng-show="selected.image"> 
                <div class="fixedPalletteNarrow">
                    <a href="" class="button " ng-click="rotate(-90)">Rotate CCW</a>
                    <a href="" class="button  right" ng-click="rotate(90)">Rotate CW</a>
                    <a href="" class="button " ng-click="blank()">Blank</a>
                </div>
                <img degrees='angle' rotate id="sampleImage" src="{{selected.image}}" width="100%"/>                                                                        
            </div>
            
            <div id="simpleTab2b" style="padding:0px;"> 
                
                <!-- List of catalogs that are assigned to this block        -->
                <div id="catalogPalette">
                    <label ng-repeat="item in catalogs">                    
                        <input type="radio" 
                               ng-model="$parent.catalogID" 
                               value="{{item.catalogID}}" 
                               ng-change="setCID(catalogID)"/>
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
                        title="Click to add standard">{{item.key}}</button> 
                    <span class="stdDesc" ng-bind-html="item.desc"></span>                    
                </div>
                
            </div>
        </div>
    </div>



    
    <div class="ui-layout-west">
        <div class="ui-layout-center">
            <div class="ui-widget-header ui-corner-top" style="padding:2px;">                                       
                <span class="btn fit" ng-click="getWork('open')">
                    <img src="<?php echo base_url(); ?>css/images/refresh24.png" 
                         alt="Refresh Assignments" 
                         title="Refresh Assignments"/>
                </span> 
                <span class="lift">Open Work</span>
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
            <div class="ui-state-default blockSummary infoZone">
                <div ng-show="node1.dueON">Due on: {{node1.dueON}}</div>
                <div ng-show="node1.cashValue">Pays: ${{node1.cashValue}}</div>
                <div ng-show="node1.alphaCode">Folder: {{node1.alphaCode}}</div>
            </div>
        </div>
        <div class="ui-layout-south">
            <div class="ui-widget-header ui-corner-top" style="padding:2px;">
                <span class="btn fit" ng-click="getWork('closed')">
                    <img src="<?php echo base_url(); ?>css/images/refresh24.png" 
                         alt="Refresh Assignments" 
                         title="Refresh Assignments"/>
                </span> 
                <span class="lift">Completed Work</span>
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
                <div ng-show="node2.dueON">Due on: {{node2.dueON}}</div>
                <div ng-show="node2.cashValue">Pays: ${{node2.cashValue}}</div>
                <div ng-show="node2.alphaCode">Folder: {{node2.alphaCode}}</div>
            </div>            
        </div>
    </div>

    <div class="ui-layout-east">
        <div class="ui-layout-center">
            <div class="ui-widget-header ui-corner-top" style="padding:2px;">
                <span class="btn fit" ng-click="refreshRD()">
                    <img src="<?php echo base_url(); ?>css/images/refresh24.png" 
                         alt="Refresh Rview Items" 
                         title="Refresh Review Items"/>
                </span>    
                <span class="lift">Review Items</span>
            </div>
            
            <div class="ui-widget-content" style="padding:0px;">

                <div class="fixedPallette">
                    <a href="" ng-click="addSCR()" class="button add right">Add SCR</a>
                    <a href="" ng-click="delSCR()" class="button delete">Del SCR</a>
                    <div class="palletteInfo">
                        {{currentBlockName}} 
                        <span ng-show="currentSampleName"> / </span> {{currentSampleName}}  
                        <span alt="This is the sample code." 
                              title="This is the sample code." 
                              class="right">{{currentSampleAlphacode}}
                        </span>
                    </div>
                </div>                
                
                <div id="reviewDataZone">
  
                <table class="reviewFormTable"  
                       ng-repeat="(key, value) in rdf2" 
                       ng-class-odd="'trOdd'" 
                       ng-class-even="'trEven'"
                       ng-class="{lite: currentGroupingID === (key|num)}">
                    <tr >
                        <td width="20">
                            <input class="rdSelector" type="checkbox" name="{{key}}" />
                        </td>
                        
                        <td ng-repeat="cell in value['cell']">
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
            <div class="ui-widget-header ui-corner-top">Item Note</div>
            <div class="ui-widget-content" style="padding:0px;">
                
                <div class="fixedPallette">                    
                    <a href="" class="button delete" ng-click="delNote()">Delete</a>
                    <a href="" class="button save right" ng-click="saveNote()">Save</a> 
                    <div class="palletteInfo">
                        <span>{{noteStamp}}</span>                            
                    </div>                                        
                </div>
                
                <textarea id="reviewNote" ng-model="currentNote"></textarea>
                
                
                <p>AID: {{user.activeID}}</p>
                <p>BID: {{currentBlockID}}</p>
                <p>SID: {{currentSampleID}}</p>
                <p>IID: {{currentImageID}}</p>
                <p>CID: {{currentCatalogID}}</p>  
                <p>GID: {{currentGroupingID}}</p> 
                <p>NID: {{currentNoteID}}</p> 
                
            </div>
        </div>
    </div>
</div>
