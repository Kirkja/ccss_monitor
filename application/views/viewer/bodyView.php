<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (isset($imageID)) {

    $sql = "SELECT * FROM bank_image where id = {$imageID}";
    $query = $this->db->query($sql);
    
    $src = "";
    
    if ($query->num_rows() > 0) {
        $row = $query->row();
        
        $src = base_url() ."scan_images/{$row->imagePath}{$row->imageName}";
    }
}



?>
<div ng-controller="viewerController">
    
    <div class="fixedPalletteNarrow">
        <a href="" class="button " ng-click="rotate(-90)">Rotate CCW</a>
        <a href="" class="button " ng-click="rotate(90)">Rotate CW</a>
        <!--
        <input type="range" min="10" max="100" ng-model="scaler"  ng-change="resize(scaler)"/> {{scaler}}
        -->
    </div>

    
    <div>

        <img 
            rotate degrees='angle' 
            scale='scaler'                     
            id="detachedImage"                      
            ng-src="<?php echo $src; ?>"  
            style="display:block; position:relative; top:0;bottom:0;left:0;right:0; margin:auto;"
        />

    </div>
    
</div>
