/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var url_base = "http://ccssmonitor.local/";



app.controller("SettingsController", function($scope, Api, $http, $window, $q) {

    
    // gets the active user details
    //$scope.user = Api.user.get();
     
     $scope.user = Api.getUser().then(function(result) {       
       $scope.getOpenWork(result.activeID);
     });
    

     $scope.getOpenWork = function(user) {
        $http({
            url: "/api/work/getwork2",
            method: "JSON",
            data: {"id":user.activeID}
        })
        .success(function(data, status, headers, config) {            
            $scope.status = data;            
            $scope.blocks = data.data;
            $scope.treedata2 = data.data;            
        })
        .error(function(data, status, headers, config) {
            $scope.status = "errors";
            //alert("error:" + headers);
        });
     };
     
    
    //-- Tree View -----------------------------------------------------------
    //
    var num = 0;
    function getNum() {
        return num;
    }
    function addNum() {
        return ++num;
    }    

    $scope.treedata = createSubTree(2);
    
    $scope.treedata2 = [];
    
    function createSubTree(level) {
        if (level > 0)
            return [
                {"label": "Node " + addNum(), "id": getNum(), "cashValue": 10.75, "children": createSubTree(level - 1)},
                {"label": "Node " + addNum(), "id": getNum(), "children": createSubTree(level - 1)},
                {"label": "Node " + addNum(), "id": getNum(), "children": createSubTree(level - 1)},
                {"label": "Node " + addNum(), "id": getNum(), "children": createSubTree(level - 1)},
                {"label": "Node " + addNum(), "id": getNum(), "children": createSubTree(level - 1)},
                {"label": "Node " + addNum(), "id": getNum(), "children": createSubTree(level - 1)},                
                {"label": "Node " + addNum(), "id": getNum(), "children": createSubTree(level - 1)}
            ];
        else
            return [];
    }

    $scope.showSelected = function(sel) {
        $scope.selected = sel;
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

