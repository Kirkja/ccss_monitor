/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {

    $("#btn_logout").bind( "click", function() {
      //alert( "User clicked on 'log out.'" );
      
        window.location.href = 'login/out';
    });



});