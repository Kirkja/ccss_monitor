<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h3>Topic 02: Working with sample navigation</h3> 
<div class="lessonArea">
    <img class="imageAreaRight" src="<?php echo base_url(); ?>css/images/topic_02_a.png"/>
    
    <p>Sample navigation works the same for both the "<b>Open Work</b>" and "<b>Completed Work</b>" panels.  Both 
        panels the following features:</p>
    
    <ol>
        <li>Scrollbars as needed to access the full listing of assigned folders</li>
        <li>A refresh button (circle with two curved arrows) to update the listing of assigned folders</li>
        <li>An information footer</li>
    </ol>    
    
    <p>Folder names are usually composed of two parts, the subject area and grade level separated by a hyphen.  
        The grade level is expressed as a number. Kindergarten is "0", First Grade is "1", and so forth.  Grade levels 
        greater than "100" are used to denote discrete classes; like, "English Lit", or "Junior High English."        
    </p>
        
    <p class='noted'>
        To select a folder, click on the folder's name.
    </p>    
    
    <p>
        When a folder is selected (highlights as light blue), the panel footer will contain some information about this folder:
    </p>
    
    <ol>
        <li><b>Due on:</b> This the date in which the assigned folder must be completed.  After midnight of that day, the work is considered over due.</li>
        <li><b>Pays:</b> This is the pay out value for the all the samples in the folder.  This rate varies between folders depending on the number and complexity of the samples.</li>
        <li><b>Alpha Code:</b> This is the folder's unique access code used for trouble shooting or support purposes.</li>
    </ol>    
    
    <p class='noted'>
        To open a specific folder, click on the little folder icon at the left of the folder's name.
    </p>
    
      <p>
        Once a folder has been opened, you will see a list of individual samples.  The sample name is a unique code composed 
        of letters and numbers followed by a hyphen and another number.  The appended number represents a page number 
        for the assignment. While most samples have a single page (hence the - 1), some may have more than one page. In that 
        case, you would see something like "HY7K9OAX-1" followed by a "HY7K9OAX-2".  In general, this is simply a means to identify 
        samples and their respective pages when the need arises.
    </p>  
    
    <p>
        When a sample is selected (highlights as light blue), the footer information changes:
    </p>
    
    <ol>
        <li><b>Due on:</b> Does not show since this is the same as the folder.</li>
        <li><b>Pays:</b> This is the pay out value of this selected sample only.</li>
        <li><b>Alpha Code:</b> This is the sample's unique access code used for trouble shooting or support purposes.</li>
    </ol>
    
    
    <hr/>
    
    <p>Selecting a sample will activate other panels and those areas will automatically reset themselves to match the currently selected sample.</p>
    
</div>


