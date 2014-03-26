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
    $scope.currentImageID = "";
    //$scope.reviewFormData = {};
    
    $scope.dokArray = ['DOK-?','DOK-1','DOK-2','DOK-3','DOK-4' ];
    $scope.blmArray = ['BLM-?','BLM-1','BLM-2','BLM-3','BLM-4','BLM-5','BLM-6']; 
    
    
    
    $scope.rdf;// = {};
    
    if (userInit) {
        Api.getUser().then(function(result) {       
           $scope.getOpenWork(result.activeID);           
           $scope.getClosedWork(result.activeID);
           $scope.user = result;
        });
        userInit = false;
    }
    
    
    $scope.$watch('currentImageID', function(newVal, oldVal) { 
        if (flgSelectedChanged) 
        {
            //$scope.getReview($scope.currentBlockID, $scope.currentSampleID, $scope.user.activeID);  
            $scope.getReviewData($scope.currentBlockID, $scope.currentSampleID, $scope.currentImageID); 
            
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
            $scope.currentImageID  = $scope.selected.imageID;
        }
        else {
            $scope.currentBlockID = $scope.selected.id;
            $scope.currentSampleID = "";
            $scope.currentImageID  = "";
            $scope.rdf = [];
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
        
   };
   
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
   
    
    

   $scope.getReviewData = function(blockID, sampleID, imageID) {

        $http({
            url: "/api/review/getReviewdata",
            method: "JSON",
            data: { 
                "blockID": blockID, 
                "sampleID": sampleID,
                "imageID": imageID
            }
        })
        .success(function(response, status, headers, config) {            
            $scope.rdf = response.data;
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });            
   };    
    
    
    
   // -------------------------------------------------------------------------
   // Other
    
    

   
});

