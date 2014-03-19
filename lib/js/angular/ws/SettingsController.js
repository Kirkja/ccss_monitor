/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var url_base = "http://ccssmonitor.local/";

    var userInit = true;
    var owInit = true;
    var cwInit = true;
    
    var selectedChanged = false;
    


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
        if (selectedChanged) {
            if (oldVal != newVal) {
                alert("NV = " + newVal + ", (OV=" + oldVal +")");
            }  
            selectedChanged = false;
        }    
    });


    
     $scope.getOpenWork = function(user) {
        $http({
            url: "/api/work/getwork2",
            method: "JSON",
            data: {"id":user.activeID}
        })
        .success(function(data, status, headers, config) {            
            $scope.openworkTree = data.data;            
        })
        .error(function(data, status, headers, config) {
            $scope.status = "errors";
        });
     };
     
     
     $scope.getClosedWork = function(user) {
        $http({
            url: "/api/work/getwork3",
            method: "JSON",
            data: {"id":user.activeID}
        })
        .success(function(data, status, headers, config) {            
            $scope.closedworkTree = data.data;            
        })
        .error(function(data, status, headers, config) {
            $scope.status = "errors";
        });
     };     
    
    //-- Tree View -----------------------------------------------------------
    //

    $scope.closedworkTree = [];
    $scope.openworkTree = [];
    


    $scope.showSelected = function(sel) {
        $scope.selected = sel;
        selectedChanged = true;
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

