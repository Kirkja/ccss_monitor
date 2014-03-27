<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
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
                <div style="text-align:center" ng-show="selected.image">
                    <img src="{{selected.image}}" width="99%"/>
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
                    <img src="<?php echo base_url(); ?>css/images/refresh_btn_b.png" alt="Refresh Assignments" title="Refresh Assignments"/>
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
            <div class="ui-widget-header ui-corner-top">East-Center</div>
            <div class="ui-widget-content">

                <div class="reviewPalette">
                    <button ng-click="addSCR()">Add SCR</button>
                    <button ng-click="delSCR()">Delete Selected SCR</button>
                </div>
                
                <div id="reviewDataZone">
                <table class="reviewFormTable" width="100%" ng-repeat="(key, value) in rdf">
                    <tr>
                        <td width="30">
                            <input type="checkbox" name="{{key}}" />
                        </td>
                        
                        <td ng-repeat="cell in value">
                            <div ng-switch="cell.dataName">
                                
                                <input class="reviewInput" ng-switch-when="id" name="{{cell.dataName}}-{{cell.recordID}}" type="text" size="4" maxlength="4" value="{{cell.dataValue}}"/>                                                             

                                <input class="reviewInput" ng-switch-when="counter" name="{{cell.dataName}}-{{cell.recordID}}" type="text" size="4" maxlength="4" value="{{cell.dataValue}}"/>

                                <select class="reviewInput" ng-switch-when="dok" name="{{cell.dataName}}-{{cell.recordID}}">
                                    <option ng-repeat="i in dokArray" ng-selected="{{cell.dataValue == i}}">
                                        {{i}}
                                    </option>
                                </select>

                                <select class="reviewInput"  ng-switch-when="blm" name="{{cell.dataName}}-{{cell.recordID}}">
                                    <option ng-repeat="i in blmArray" ng-selected="{{cell.dataValue == i}}">
                                        {{i}}
                                    </option>
                                </select>
                                   
                                <button class="reviewInput"  ng-switch-when="standard">
                                    {{cell.dataValue}}
                                </button>
                                
                                <div class="reviewInput"  ng-switch-default="">
                                    {{cell.dataName}}, {{cell.dataValue}}
                                </div> 
                                
                            </div>

                        </td>
                        <td>{{key}}</td>
                    </tr>
                       
                </table>
                
                </div>
                
                
                
                
                <!--
                <form name="reviewForm">
                    <hidden name="aid" value="{{user.activeID}}"/>
                    <hidden name="bid" value="{{currentBlockID}}"/>
                    <hidden name="sid" value="{{currentSampleID}}"/>

                    <div ng-repeat="field in fields">
                        
                        <div ng-switch="field.type">
                            <div ng-switch-when="text">
                                {{field.label}} <input name="{{field.label}}" type="{{field.type}}" value="{{field.value}}"/>
                            </div>
                            
                            <div ng-switch-when="checkbox">
                                {{field.label}} <input name="{{field.label}}"  type="{{field.type}}" checked="{{field.value}}"/>
                            </div>  
                            
                            <div ng-switch-when="radio"> 
                                <span ng-repeat="radio in field.value">
                                    {{radio.label}} <input 
                                        name="{{field.label}}" 
                                        type="{{field.type}}" 
                                        value="{{radio.value}}" 
                                        ng-checked="{{radio.selected}}"/>                                   
                                </span>
                                <br/>
                            </div>
                            
                            <div ng-switch-when="select"> 
                                <select name="{{field.name}}">
                                    <option ng-repeat="opt in field.value" ng-selected="{{opt.selected}}">{{opt.label}}</option>
                                </select>
                            </div>                              
                                                        
                        </div>
                    </div>
                    
                    <button ng-click="saveForm()">Save</button>
                </form>
                -->
            </div>
        </div>
        
        <div class="ui-layout-south">
            <div class="ui-widget-header ui-corner-top">East-South</div>
            <div class="ui-widget-content">
                <p>AID: {{user.activeID}}</p>
                <p>BID: {{currentBlockID}}</p>
                <p>SID: {{currentSampleID}}</p>
                <p>IID: {{currentImageID}}</p>
                
                <!--
                <table class="std-table" width="100%" cellspacing="0">
                    <tr>
                        <td><input type="text" maxlength="4" size="4" value="" placeholder="id"/></td>
                        <td>Standard Here</td>
                        <td>
                            <select>
                                <option value="DOK-?">DOK</option>
                                <option value="DOK-1">DOK-1</option>
                                <option value="DOK-2">DOK-2</option>
                                <option value="DOK-3">DOK-3</option>
                                <option value="DOK-4">DOK-4</option>
                            </select>
                        </td>
                        <td>                            
                            <select>
                                <option value="BLM-?">BLM</option>
                                <option value="BLM-1">BLM-1</option>
                                <option value="BLM-2">BLM-2</option>
                                <option value="BLM-3">BLM-3</option>
                                <option value="BLM-4">BLM-4</option>
                                <option value="BLM-5">BLM-5</option>
                                <option value="BLM-6">BLM-6</option>                                
                            </select>
                        </td>
                        <td><input type="text" maxlength="4" size="4" value="" placeholder="count"/></td>
                        <td>Note</td>
                    </tr>
                    <tr>
                        <td><input type="text" maxlength="4" size="4" value="" placeholder="id"/></td>
                        <td>Standard Here</td>
                        <td>
                            <select>
                                <option value="DOK-?">DOK</option>
                                <option value="DOK-1">DOK-1</option>
                                <option value="DOK-2">DOK-2</option>
                                <option value="DOK-3">DOK-3</option>
                                <option value="DOK-4">DOK-4</option>
                            </select>
                        </td>
                        <td>                            
                            <select>
                                <option value="BLM-?">BLM</option>
                                <option value="BLM-1">BLM-1</option>
                                <option value="BLM-2">BLM-2</option>
                                <option value="BLM-3">BLM-3</option>
                                <option value="BLM-4">BLM-4</option>
                                <option value="BLM-5">BLM-5</option>
                                <option value="BLM-6">BLM-6</option>                                
                            </select>
                        </td>
                        <td><input type="text" maxlength="4" size="4" value="" placeholder="count"/></td>
                        <td>Note</td>
                    </tr>
                    <tr>
                        <td><input type="text" maxlength="4" size="4" value="" placeholder="id"/></td>
                        <td>Standard Here</td>
                        <td>
                            <select>
                                <option value="DOK-?">DOK</option>
                                <option value="DOK-1">DOK-1</option>
                                <option value="DOK-2">DOK-2</option>
                                <option value="DOK-3">DOK-3</option>
                                <option value="DOK-4">DOK-4</option>
                            </select>
                        </td>
                        <td>                            
                            <select>
                                <option value="BLM-?">BLM</option>
                                <option value="BLM-1">BLM-1</option>
                                <option value="BLM-2">BLM-2</option>
                                <option value="BLM-3">BLM-3</option>
                                <option value="BLM-4">BLM-4</option>
                                <option value="BLM-5">BLM-5</option>
                                <option value="BLM-6">BLM-6</option>                                
                            </select>
                        </td>
                        <td><input type="text" maxlength="4" size="4" value="" placeholder="count"/></td>
                        <td>Note</td>
                    </tr>                    
                </table>
                -->
                       
                              
            </div>
        </div>
    </div>
</div>
