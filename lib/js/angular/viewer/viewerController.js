/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

viewerApp.controller("viewerController", function($scope, $http, $window, $q, $compile) {

    $scope.angle = 0;
    $scope.scaler = 75;

    $scope.rotate = function(value) {        
        $scope.angle += value;
    };
    
    
    $scope.resize = function(value) {        
        $scope.scaler = value;
    };
    

});