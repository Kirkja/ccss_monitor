<?php
// views/help/bodyView


?>
<div ng-controller="helpController">
    
    <div class="fixedPalletteNarrow">        
        <a href="" class="button " ng-click="getMenu('reviewTool')">Review Tool</a>
        <a href="" class="button " ng-click="getPage('dok_guide')">DOK Guide</a>
        <a href="" class="button " ng-click="getPage('blm_guide')">Bloom's Guide Tool</a>
    </div>
    
    
    
    <table id="lessons">
        <tr>
            <td>
                <div ng-repeat="menu in menus">
                    <a href="" class="button w150" ng-click="getLesson(menu.file)">{{menu.label}}</a>
                </div>
            </td>
            <td>
                <div ng-bind-html="currentLesson"></div>
            </td>
        </tr>
    </table>
    
    
   
</div>