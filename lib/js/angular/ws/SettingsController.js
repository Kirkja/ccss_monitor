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
    
    $scope.currentBlockID = "";
    $scope.currentSampleID = "";
    //$scope.reviewFormData = {};
     
    
    if (userInit) {
        Api.getUser().then(function(result) {       
           $scope.getOpenWork(result.activeID);           
           $scope.getClosedWork(result.activeID);
           $scope.user = result;
        });
        userInit = false;
    }
    
    
    $scope.$watch('currentSampleID', function(newVal, oldVal) { 
        if (flgSelectedChanged) 
        {
            $scope.getReview($scope.currentBlockID, $scope.currentSampleID, $scope.user.activeID);             
            flgSelectedChanged = false;
        }    
    });


    
     $scope.getOpenWork = function(user) {
        $http({
            url: "/api/work/getassignments",
            method: "JSON",
            data: { 
                "mode": "open"
            }
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
            url: "/api/work/getassignments",
            method: "JSON",
            data: {
                "mode": "closed"
            }
        })
        .success(function(response, status, headers, config) {            
            $scope.closedworkTree = response.data;            
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
        if ($scope.selected.children.length == 0) {
            flgSelectedChanged = true;
            $scope.currentBlockID  = $scope.selected.blockID;
            $scope.currentSampleID = $scope.selected.id;
        }
        else {
            $scope.currentBlockID = $scope.selected.id;
            $scope.currentSampleID = "";
        }
    };

   
   
   
   $scope.getReview = function(blockID, sampleID, activeID) {
       //alert("blockID: " + blockID +"\nsampleID: " + sampleID + "\nactiveID: " + activeID);
       
        $http({
            url: "/api/work/getReview",
            method: "JSON",
            data: { 
                "blockID": blockID, 
                "sampleID": sampleID, 
                "activeID": activeID 
            }
        })
        .success(function(response, status, headers, config) {            
            //$scope.closedworkTree = data.data; 
            $scope.fields = response.data;
            
            $scope.rfd = response.data;
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });       
        
     
        $scope.saveForm = function() {
            for (i=0; i < document.reviewForm.elements.length; i++) {
                switch(document.reviewForm.elements[i].type) {
                    case 'radio':
                        alert("Radio button");
                        break;
                    case 'checkbox':
                        alert("The field name is: " + document.reviewForm.elements[i].name +
                        " and it’s value is: " + document.reviewForm.elements[i].checked );                        
                        break;                        
                    default:
                        alert("The field name is: " + document.reviewForm.elements[i].name +
                        " and it’s value is: " + document.reviewForm.elements[i].value );
                        
                }
                
            }          
        };
     
     
     
   };
    
   // -------------------------------------------------------------------------
   // Other
    
    

   
});

