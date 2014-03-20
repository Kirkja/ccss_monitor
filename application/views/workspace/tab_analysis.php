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
            {{selected.id}}
            <div style="text-align:center" ng-show="selected.imagePath">
                <img src="{{selected.imagePath}}{{selected.imageName}}" width="99%"/>
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