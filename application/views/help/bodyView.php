<?php
// views/help/bodyView


?>
<div ng-controller="helpController">
    
    <div class="fixedPalletteNarrow">        
        <a href="" class="button" ng-click="getMenu('reviewTool')">Review Tool</a>
    </div>
    
    
    
    <table id="lessons">
        <tr>
            <td width="120">
                <div ng-repeat="menu in menus">
                    <a href="" class="button" ng-click="getLesson(menu.file)">{{menu.label}}</a>
                </div>
            </td>
            <td>
                <div ng-bind-html="currentLesson"></div>
            </td>
        </tr>
    </table>
    
    
   
</div>