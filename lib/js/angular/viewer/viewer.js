/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var viewerApp = angular.module('ccssmonitor', ['ngResource','ngSanitize']);


viewerApp.directive('magicview', function() {
    return {
        restrict:'A',
        link: function(scope, element, attrs) {
            var doStuff = function() {
                console.log(attrs.degrees);
                console.log(attrs.scale);
            };
            scope.$watch(attrs.scale, doStuff);
            scope.$watch(attrs.degrees, doStuff);
        }
    }
});




viewerApp.directive('rotate', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            scope.$watch(attrs.degrees, function (rotateDegrees) {
                //console.log(rotateDegrees);                
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


viewerApp.directive("resizer", function() {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            scope.$watch(attrs.scale, function (scale) {
                //console.log(scale);
                var s = 'scale(' + scale/100 + ',' + scale/100 +')';                
                element.css({
                    '-moz-transform': s,
                    '-webkit-transform': s,
                    '-o-transform': s,
                    '-ms-transform': s
                });
            });           
        }
    }
});