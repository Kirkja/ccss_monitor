/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var url_base = "http://ccssmonitor.local/";

    var userInit = true;
    var owInit = true;
    var cwInit = true;
    
    var flgSelectedChanged = false;
    



app.controller("SettingsController", function($scope, Api, $http, $window, $q) {
    
    if (userInit) {
        Api.getUser().then(function(result) {       
           $scope.getOpenWork(result.activeID);           
           $scope.getClosedWork(result.activeID);
           $scope.user = result;
        });
        userInit = false;
    }
    
    $scope.$watch('selected.id', function(newVal, oldVal) { 
        if (flgSelectedChanged) 
        {
            if (oldVal != newVal) {
                if ($scope.selected.children.length == 0) {                   
                    //$scope.getReview($scope.user.activeID, newVal);
                }                
            }  
            flgSelectedChanged = false;
        }    
    });


    
     $scope.getOpenWork = function(user) {
        $http({
            url: "/api/work/getwork2",
            method: "JSON",
            data: {"id":user.activeID}
        })
        .success(function(response, status, headers, config) {            
            $scope.openworkTree = response.data;            
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });
     };
     
     
     $scope.getClosedWork = function(user) {
        $http({
            url: "/api/work/getwork3",
            method: "JSON",
            data: {"id":user.activeID}
        })
        .success(function(response, status, headers, config) {            
            $scope.closedworkTree = response.data;            
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });
     };  
     
     
     
     $scope.getReview = function(userID, blockID, sampleID) {
        $http({
            url: "/api/work/getreview",
            method: "JSON",
            data: { "userID":userID, "blockID":blockID, "sampleID": sampleID }
        })
        .success(function(response, status, headers, config) {
            //alert(response.data.template.toString());
            $scope.formTemplate = response.data.template;
            
            $scope.fields = response.data.form;
            
            //$scope.apply();
            
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });         
     };
     
     
     
     $scope.processForm = function(event) {
        alert("ptocess form") ;
     };
     
     
    
    //-- Tree View -----------------------------------------------------------
    //

    $scope.closedworkTree = [];
    $scope.openworkTree = [];
    


    $scope.showSelected = function(sel) {
        flgSelectedChanged = true;
        $scope.selected = sel;
        
    };

   
    $scope.formTemplate = {
        "first": {
            "type": "text",
            "label": "First Name"
        },
        "last": {
            "type": "text",
            "label": "Last Name"
        },
        "submit": {
            "type": "submit",
            "label": "Submit",
            "val": "submit"
        },
    };
    
    
   // -------------------------------------------------------------------------
   // Other
    
    
    
    
    
    // saves user changes to server
    /*
    $scope.userUpdate = function()
    {
        if ($scope.user) {
            if ($scope.user.userCode.length >1) {
            $http({
                method: 'POST',
                url: url_base + "/api/settings/userupdate",
                data: $scope.user,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
            .success(function(data) {
                if (!data.success) {
                    alert("Error: " + data.errors.name)
                } else {
                                                            
                }
            });
        }
        }
    };
    */
   
   
   
});

