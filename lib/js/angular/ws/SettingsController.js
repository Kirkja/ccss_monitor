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
    //var currentBlockID= "";
    




app.controller("SettingsController", function($scope, Api, $http, $window, $q) {
    
    $scope.currentBlockID = "";
    $scope.currentSampleID = "";
    
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
            url: "/api/work/getwork2",
            method: "JSON",
            data: {"id":user.activeID}
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
            url: "/api/work/getwork3",
            method: "JSON",
            data: {"id":user.activeID}
        })
        .success(function(response, status, headers, config) {            
            $scope.closedworkTree = response.data;            
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });
     };  
     
     
     
     $scope.getReview = function(userID, blockID, sampleID) {
        $http({
            url: "/api/work/getreview",
            method: "JSON",
            data: { "userID":userID, "blockID":blockID, "sampleID": sampleID }
        })
        .success(function(response, status, headers, config) {
            //alert(response.data.template.toString());
            $scope.formTemplate = response.data.template;
            
            $scope.fields = response.data.form;
            
            //$scope.apply();
            
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
                "blockID":blockID, 
                "sampleID": sampleID, 
                "activeID":activeID 
            }
        })
        .success(function(response, status, headers, config) {            
            //$scope.closedworkTree = data.data; 
            $scope.fields = response.data;
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

