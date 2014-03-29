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
    var flgCatalogChanged = false;




app.controller("SettingsController", function($scope, Api, $http, $window, $q, $compile) {
    
    $scope.currentBlockID = "";
    $scope.currentSampleID = "";
    $scope.currentImageID = "";
    $scope.currentCatalogID = "";
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
    
    
    // Just makes sure that currentCatalogID is set
    // for some reason the setCID method fails to do this
    // Note: the HTML needed a $parent.catalodID on the model
    // so there is some form of scope issue
    $scope.$watch('catalogID', function(val){
        if (val) {            
            $scope.currentCatalogID = val;            
        }
    });
     
      

     
     
    $scope.setCID = function(value) {
        $http({
            url: "/api/standards/getEntries",
            method: "JSON",
            data: { 
                "cid": value
            }
        })
        .success(function(response, status, headers, config) {            
            $scope.catalogEntries = response.data;            
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });       
        
        //alert("Setting CID = " + value);
    }
            
            
            
            
    $scope.$watch('currentImageID', function(newVal, oldVal) { 
        if (flgSelectedChanged) 
        {            
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
   
   
    $scope.updateSCR = function() {
        alert("Update called");
    };
    
    
    $scope.addSCR = function() {
          
        if ($scope.currentImageID === "") return;
                
        $http({
            url: "/api/review/addSCR",
            method: "JSON",
            data: { 
                "blockID": $scope.currentBlockID, 
                "sampleID": $scope.currentSampleID,
                "imageID": $scope.currentImageID
            }
        })
        .success(function(response, status, headers, config) {            
            $scope.rdf = response.data;
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });         
    };


    $scope.addStd = function(value) {
          
        if ($scope.$parent.currentImageID === "") {
            alert("Please select a sample before adding a review.")
            return;
        }
        
        var bid = $scope.$parent.currentBlockID;
        var sid = $scope.$parent.currentSampleID;
        var iid = $scope.$parent.currentImageID;
        
        $http({
            url: "/api/review/addFilledSCR",
            method: "JSON",
            data: { 
                "blockID": bid, 
                "sampleID": sid,
                "imageID": iid,
                "stdKey": value
            }
        })
        .success(function(response, status, headers, config) {            
            $scope.$parent.rdf = response.data;
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });  
        
    };


    $scope.delSCR = function() {

        var inputs = $window.document.querySelectorAll("input[type='checkbox']");
        var checked = [];
        var listed = "";

        for(var i = 0; i < inputs.length; i++) {
            if (inputs[i].checked) {
                 checked.push(inputs[i]);
                 listed += inputs[i].name;
                 listed += ",";
            }   
        }

        if (listed.length > 0) {
            $http({
                url: "/api/review/delSCR",
                method: "JSON",
                data: { 
                    "blockID": $scope.currentBlockID, 
                    "sampleID": $scope.currentSampleID,
                    "imageID": $scope.currentImageID,
                    "groups": listed
                }
            })
            .success(function(response, status, headers, config) {            
                $scope.rdf = response.data;
                $scope.$apply();
            })
            .error(function(response, status, headers, config) {
                $scope.status = "errors";
            });            
        }            
    }
    
    
    $scope.goStandard = function(scrID) {
      alert("Go standard for SCR: "+ scrID);  
    };

});

