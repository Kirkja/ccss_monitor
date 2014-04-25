/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

helpApp.controller("helpController", function($scope, $http, $window, $q, $compile) {

   $scope.menus = [];
   $scope.currentLesson = "";
   
   
   $scope.getMenu = function(menuName) {
        $http({
            url: "/api/help/menus",
            method: "JSON",
            data: { 
                "menu": menuName
            }
        })
        .success(function(response, status, headers, config) { 
            $scope.currentLesson = "";
            $scope.menus = response.data;
            
        })
        .error(function(response, status, headers, config) {
             $scope.status = "errors";
        });        
   };
   
   $scope.getPage = function(pageName) {
        $http({
            url: "/api/help/getPage",
            method: "JSON",
            data: { 
                "page": pageName
            }
        })
        .success(function(response, status, headers, config) { 
            $scope.currentLesson = response;
            $scope.menus = [];
            
        })
        .error(function(response, status, headers, config) {
             $scope.status = "errors";
        });         
   }
   
   
   
   $scope.getLesson = function(lesson) {
     
        $http({
            url: "/api/help/lesson",
            method: "JSON",
            data: { 
                "lesson": lesson
            }
        })
        .success(function(response, status, headers, config) { 
            $scope.currentLesson = response;
        })
        .error(function(response, status, headers, config) {
             $scope.status = "errors";
        }); 
       
   };

});

