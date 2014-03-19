



app.controller("TreeController", function($scope) {

    var num = 1;
    function getNum() {
        return num++;
    }

    $scope.showSelected = function(sel) {
        $scope.selected = sel.label;
    };

    $scope.addRoot = function() {
        $scope.treedata.push({"label": "New Node " + getNum(), "id": "id", "children": []});
    };
    
    $scope.addChild = function() {
        $scope.treedata[0].children.push({"label": "New Node " + getNum(), "id": "id", "children": []});
    };

});