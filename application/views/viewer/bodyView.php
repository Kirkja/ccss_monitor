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
<h3>Image Viewer</h3>
<p><?php echo $imageID; ?></p>

<img src="<?php echo $src; ?>"/>
