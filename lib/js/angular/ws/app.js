
var base_url = "";


//'dynform', was removed


var app = angular.module('ccssmonitor', ['ngResource','ngSanitize']);




var api = function($resource, $q, $http) {

    this.getUser = function() {
        var deferred = $q.defer();
        $http({ 
            method: "GET",
            url:base_url + "/api/settings/user"}).success(function(result){
                deferred.resolve(result);
        });        
        return deferred.promise;          
    };   
       
};


app.service("Api", api);

app.filter('num', function() {
    return function(input) {
      return parseInt(input, 10);
    }
});


app.directive('treecontrol', ['$compile', function($compile) {
        return {
            restrict: 'E',
            require: "treecontrol",
            transclude: true,
            scope: {
                treeModel: "=",
                selectedNode: "=",
                onSelection: "&",
                nodeChildren: "@"
            },
            controller: function($scope) {

                $scope.nodeChildren = $scope.nodeChildren || 'children';
                $scope.expandedNodes = {};

                $scope.headClass = function(node) {
                    if (node[$scope.nodeChildren].length && !$scope.expandedNodes[this.$id])
                        return "tree-collapsed";
                    else if (node[$scope.nodeChildren].length && $scope.expandedNodes[this.$id])
                        return "tree-expanded";
                    else
                        return "tree-normal"
                };

                $scope.nodeExpanded = function() {
                    return $scope.expandedNodes[this.$id];
                };

                $scope.selectNodeHead = function() {
                    $scope.expandedNodes[this.$id] = !$scope.expandedNodes[this.$id];
                };

                $scope.selectNodeLabel = function(selectedNode) {
                    $scope.selectedScope = this.$id;
                    $scope.selectedNode = selectedNode;
                    if ($scope.onSelection)
                        $scope.onSelection({node: selectedNode});
                };

                $scope.selectedClass = function() {
                    return (this.$id === $scope.selectedScope) ? "tree-selected" : "";;
                };
        

                //tree template
                var template =
                        '<ul>' +
                        '<li ng-repeat="node in node.' + $scope.nodeChildren + '" ng-class="headClass(node)">' +
                        '<i class="tree-has-children" ng-click="selectNodeHead(node)"></i>' +
                        '<i class="tree-normal"></i>' +
                        '<div class="tree-label {{node.completed}} '+
                        '" ng-class="selectedClass()" ng-click="selectNodeLabel(node)" tree-transclude></div>' +
                        '<treeitem ng-if="nodeExpanded()"></treeitem>' +
                        '</li>' +
                        '</ul>';

                return {
                    templateRoot: $compile(template),
                    templateChild: $compile(template)
                }
            },
            compile: function(element, attrs, childTranscludeFn) {
                return function(scope, element, attrs, treemodelCntr) {

                    function updateNodeOnRootScope(newValue) {
                        if (angular.isArray(newValue)) {
                            scope.node = {};
                            scope.node[scope.nodeChildren] = newValue;
                        }
                        else {
                            scope.node = newValue;
                        }
                    }
                    scope.$watch("treeModel", updateNodeOnRootScope);
                    updateNodeOnRootScope(scope.treeModel);

                    //Rendering template for a root node
                    treemodelCntr.templateRoot(scope, function(clone) {
                        element.html('').append(clone);
                    });
                    // save the transclude function from compile (which is not bound to a scope as apposed to the one from link)
                    // we can fix this to work with the link transclude function with angular 1.2.6. as for angular 1.2.0 we need
                    // to keep using the compile function
                    scope.$treeTransclude = childTranscludeFn;
                }
            }
        };
    }]);

app.directive("treeitem", function() {
    return {
        restrict: 'E',
        require: "^treecontrol",
        link: function(scope, element, attrs, treemodelCntr) {

            // Rendering template for the current node
            treemodelCntr.templateChild(scope, function(clone) {
                element.html('').append(clone);
            });
        }
    }
});

app.directive("treeTransclude", function() {
    return {
        link: function(scope, element, attrs, controller) {
            scope.$treeTransclude(scope, function(clone) {
                element.empty();
                element.append(clone);
            });
        }
    }
});

app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
});


 
    app.directive('rotate', function () {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                scope.$watch(attrs.degrees, function (rotateDegrees) {
                    console.log(rotateDegrees);
                    var r = 'rotate(' + rotateDegrees + 'deg)';
                    element.css({
                        '-moz-transform': r,
                        '-webkit-transform': r,
                        '-o-transform': r,
                        '-ms-transform': r
                    });
                });
            }
        }
    });