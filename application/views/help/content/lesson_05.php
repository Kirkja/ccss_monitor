<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h3>Topic 05: Working with Search</h3> 
<div class="lessonArea">
    
    <img src="<?php echo base_url(); ?>css/images/topic_05_a.jpg" class="imageAreaRight" />
    
    <p>When a sample is currently selected, a catalog of standards available for searching 
        can be found in the "<b>Search Tab</b>" located in the center region of the workspace.</p>
    
    <p>In most cases, this is a single set of standards; however, multiple standards may be listed depending 
        on the project. Standards typically have names such as: "<i>CA CCSS English Language Arts</i>" which 
        would be California's variant on the Common Core State Standards for English Language Arts.       
    </p>  
    
    <p class='noted'>
        To choose standards for searching from the catalog, click on its name or the checkbox in front of its name. 
        You may select as many of the available standards as needed.
    </p>
    
    
    
    <p>
        Once a set of standards has been chosen, you can enter search terms in the text box below the catalog.
    </p>
    
    <p class="noted">To conduct the search, press the enter key while typing inside the search term box, or click the round 
        round magnifying glass button on the right side of the search terms box.</p>
    

    <p>The search results will appear below in a scrollable area.  Clicking on standard buttons will inject 
        that standard as a review item.  These injected standards will appear in the "<i>Review Items</i>" panel. 
        There is no limit to the number of standards that may be injected this way.</p>

    
    <hr/>
    
    <p>The search features uses a "full text boolean" style system.  This mean you can refine 
        search terms and change how they are used during the search. For example:</p>
    
    <ol>
        <li>Adding a "<b>+</b>" in front of a term requires that the term <u>MUST BE PRESENT</u> in the results</li>
    <li>Adding a "<b>-</b>" in front of the term requires that the term <u>NOT BE PRESENT</u> in the results.</li>
        <li>Results are filtered to NOT show extremely common words</li>
    </ol>
    <br/>

    <p>For example: a "+common +factor" search entry would return results where both "common" and "factor" terms MUST BE PRESENT.</p>
    
    <p>For example: a "-common +factor" search entry would return results where "factor" MUST BE PRESENT and excludes any that includes the "common" term.</p>
</div>