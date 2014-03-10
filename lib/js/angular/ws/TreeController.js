



app.controller("TreeController", function($scope) {

    var num = 1;
    function getNum() {
        return num++;
    }

    $scope.treedata = createSubTree(2);
    
    function createSubTree(level) {
        if (level > 0)
            return [
                {"label": "Node " + getNum(), "id": "id", "children": createSubTree(level - 1)},
                {"label": "Node " + getNum(), "id": "id", "children": createSubTree(level - 1)},
                {"label": "Node " + getNum(), "id": "id", "children": createSubTree(level - 1)},
                {"label": "Node " + getNum(), "id": "id", "children": createSubTree(level - 1)},
                {"label": "Node " + getNum(), "id": "id", "children": createSubTree(level - 1)},
                {"label": "Node " + getNum(), "id": "id", "children": createSubTree(level - 1)},                
                {"label": "Node " + getNum(), "id": "id", "children": createSubTree(level - 1)}
            ];
        else
            return [];
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