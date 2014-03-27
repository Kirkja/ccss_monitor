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

    $(document).on('change','input[type="text"]', function() {
        var data = $(this).attr('name').split("-");
        alert(
            "id:" + data[1] + "\nvalue:" + $(this).val().trim()
        );
    });



    $("#btn_logout").bind( "click", function() {    
        window.location.href = 'entry/out';
    });



    $("#delSCR").bind("click", function() {

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