/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {

    $(document).on('change','select', function() {
        var data = $(this).attr('name').split("-");        
        $.ajax({
            type: "POST",
            dataType:"json",
            contentType: "application/json; charset=utf-8",
            url: "/api/review/updateSCR",
            data: JSON.stringify({ id: data[1], value: $(this).val().trim() })
        })        
    });


    $(document).on('change','input[type="text"].rdBox', function() {
        var data = $(this).attr('name').split("-");
        $.ajax({
            type: "POST",
            dataType:"json",
            contentType: "application/json; charset=utf-8",
            url: "/api/review/updateSCR",
            data: JSON.stringify({ id: data[1], value: $(this).val().trim() })
        })        
    });


    $(document).on('change','input[type="text"].rdStd', function() {

        var id = $(this).attr('id');
        var data = $(this).attr('name').split("-");
        
        if ($(this).val().trim().length === 0) {
            return;
        }
        
        $.ajax({
            type: "POST",
            dataType:"json",
            contentType: "application/json; charset=utf-8",
            url: "/api/review/updateSTD",
            data: JSON.stringify({ id: data[1], value: $(this).val().trim() })
        })
        .done(function(resp){
            if (resp.data === false) {
                
                alert("The standard was not valid.");  
            }            
        })
    });


/*
    $(document).on('change','input[type="button"]', function() {
        var data = $(this).attr('name').split("-");
        
        alert("Std btn changed to = " + $(this).val().trim());
    });
*/



    $("#btn_logout").bind( "click", function() {    
        window.location.href = 'entry/out';
    });


/*
    $("#delSCR").bind("click", function() {

        var inputs = document.querySelectorAll("input[type='checkbox'].rdSelector");
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
    */
    
    
    
    

});