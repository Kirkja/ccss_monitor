<?php
/* 
 * 
 */
?>
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

            center panel
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
