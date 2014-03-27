/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {

    $("#btn_logout").bind( "click", function() {
      //alert( "User clicked on 'log out.'" );
      
        window.location.href = 'entry/out';
    });


    $("#addSCR").bind("click", function() {
        alert("Add SCR");
    });
    
    
    $("#delSCR").bind("click", function() {
        //alert("delete selected SCR");
        
        var inputs = document.querySelectorAll("input[type='checkbox']");
        var checked = [];
        var listed = "";
        
        for(var i = 0; i < inputs.length; i++) {
            if (inputs[i].checked) {
                 checked.push(inputs[i]);
                 listed += inputs[i].name;
                 listed += ",";
            }   
        }
        
        alert("reslts: " 
                + "\nTotal: "  + inputs.length 
                + "\nChecked: " + checked.length
                + "\nList: " + listed
                
                
        );
    });    

});