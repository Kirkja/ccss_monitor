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
        <input type="range" min="10" max="100" ng-model="scaler"  ng-change="resize(scaler)"/> {{scaler}}
    </div>
    <div>
        scale: {{scaler}}
    </div>
    
    <div style="margin:0 auto 0 auto; background-color:yellow;">
        
        <div style="border:1px solid green;display:block; width:200px; height:300px; background-color:red;"
             magicview degrees='angle' scale="scaler" >
            
        </div>
 <!--   
        <img  degrees='angle' rotate resizer scale='scaler'                     
        id="detachedImage"                      
        ng-src="<?php echo $src; ?>"  
        style="border:1px solid green;display:block;"
    />
-->
    </div>
    
</div>
