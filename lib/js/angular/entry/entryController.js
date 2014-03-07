/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//var url_base = "";


app.controller("entryController", function($scope, $http, $rootScope) {
    
    // create a blank object to hold our form information
    // $scope will allow this to pass between controller and view
    $scope.formData = {};  
    
    // process the form
    $scope.processForm = function() {
        $http({
        method  : 'POST',
        url     : base_url + 'api/user/check',
        data    : $scope.formData,  // pass in data as strings
        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
        })
        .success(function(data) {
            console.log(data);
            if (!data.success) {
                //alert()
            	// if not successful, bind errors to error variables
                $scope.errorName = data.errors.name;
                $scope.errorSuperhero = data.errors.superheroAlias;
            } else {
            	// if successful, bind success message to message
                //$scope.message = data.message;
                //$rootScope.activeID = data.message;               
                window.location = base_url + "workspace";
            }
        });
    };    
    
});

