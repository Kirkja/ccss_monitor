/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//var url_base = "";


app.controller("SettingsController", function($scope, Api, $http, $window, $rootScope) {

    // gets the active user details
    $scope.user = Api.user.get();
    
    
    //$scope.blocks = Api.blocks.query({id: $scope.user.activeID});
    
    $scope.getOpenWork = function(user) {
      $scope.treedata2 = [];
      var item = Api.blocks.query({id:user.activeID}
         , function(res) {
             $scope.treedata2 = res;
         }     
      );
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
    
    $scope.treedata2 = createSubTree2(2);
    
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

    function createSubTree2(level) {
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
        //$scope.selected = sel.label;
        $scope.selected = sel;
    };

/*
    $scope.addRoot = function() {
        $scope.treedata.push({"label": "New Node " + addNum(), "id": "id", "children": []});
    };
    
    $scope.addChild = function() {
        $scope.treedata[0].children.push({"label": "New Node " + addNum(), "id": "id", "children": []});
    };    
*/
    
    
    
    
    
    
    
    
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