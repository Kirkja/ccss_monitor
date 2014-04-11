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
    $scope.currentBlockName = "";
    $scope.currentSampleName = "";    
    $scope.loadingMessage = "";
    $scope.searchMessage = "";
    $scope.currentNote = "";
    $scope.currentNoteID = "";
    $scope.currentSampleAlphacode = "";
    $scope.currentBlockAlphacode = "";
    $scope.currentGroupingID = "";
    $scope.noteStamp = "";
    $scope.angle = 0;
    
    $scope.dokArray = ['DOK-?','DOK-1','DOK-2','DOK-3','DOK-4' ];
    $scope.blmArray = ['BLM-?','BLM-1','BLM-2','BLM-3','BLM-4','BLM-5','BLM-6']; 
    
    $scope.catalogs;
    $scope.rdf;
    $scope.rdf2;
    
    $scope.searchable = {};
    
    

    if (userInit) {
        Api.getUser().then(function(result) {       
           $scope.getWork('open');           
           $scope.getWork('closed');
           $scope.user = result;
        });
        userInit = false;
    }
    
    
    
    //
    //--- WATCH VARIABLES ----------------------------------------------------
    //
    
    
    // Just makes sure that currentCatalogID is set
    // for some reason the setCID method fails to do this
    // Note: the HTML needed a $parent.catalodID on the model
    // so there is some form of scope issue
    $scope.$watch('catalogID', function(val){
        if (val) {            
            $scope.currentCatalogID = val;            
        }
    });
     
          
    /**
     * 
     */  
    $scope.$watch('currentBlockID', function(val) {
        if (val) {              
            $http({
                url: "/api/standards/getCatalogs",
                method: "JSON",
                data: { 
                    "blockID": val
                }
            })
            .success(function(response, status, headers, config) {                
                $scope.catalogs = response.data;                
            })
            .error(function(response, status, headers, config) {
                $scope.status = "errors";
            }); 
        }
     });
     
                
    /**
     * 
     */        
    $scope.$watch('currentImageID', function(newVal, oldVal) { 
        if (flgSelectedChanged) 
        {    
            $scope.currentGroupingID = "";
            $scope.getReviewData2($scope.currentBlockID, $scope.currentSampleID, $scope.currentImageID);                                     
            flgSelectedChanged = false;
        }    
    });

    // ------------------------------------------------------------------------
    //
    //
    
    $scope.exit = function() {
        alert("Logout");
    };
    
    

    $scope.refreshRD = function() {
        
        if ($scope.currentImageID === "") {
            alert("Please select a sample before trying to refresh the review items.");
            return;
        }
                
        $scope.currentGroupingID    = "";
        $scope.currentNoteID        = "";
        $scope.currentNote          = "";
          
        $scope.getReviewData2($scope.currentBlockID, $scope.currentSampleID, $scope.currentImageID);        
    };

    //
    //--- Standard Catalogs  --------------------------------------------------
    //


     /**
      * 
      * @param {type} value
      * @returns {undefined}
      */
    $scope.setCID = function(value) {
        $scope.loadingMessage = " Loading Standards ...";
        $scope.catalogEntries = {};
        
        $http({
            url: "/api/standards/getEntries",
            method: "JSON",
            data: { 
                "cid": value
            }
        })
        .success(function(response, status, headers, config) { 
            $scope.loadingMessage = "";
            if (response.data.length > 0) {
                $scope.catalogEntries = response.data; 
            }
            else {
                $scope.loadingMessage = " No Standards Available ";
                $scope.catalogEntries = {};
            }                       
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });       
    };
   


    //
    //--- Work Assignment folders ---------------------------------------------
    //

     
     /**
      * 
      * @param {type} user
      * @returns {undefined}
      */
     $scope.getWork = function(mode) {
         
        $scope.purge();
         
        $http({
            url: "/api/work/getassignments",
            method: "JSON",
            data: {
                "mode": mode
            }
        })
        .success(function(response, status, headers, config) {            
            if (mode === 'closed') { $scope.closedworkTree = response.data; }
            if (mode === 'open') { $scope.openworkTree = response.data; }
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });
     };  
     
     
     $scope.purge = function() {
        $scope.selected = {};
        $scope.currentBlockID = "";
        $scope.currentSampleID = "";
        $scope.currentImageID = "";
        $scope.rdf2 = {};         
        $scope.currentBlockName = "";
        $scope.currentSampleName = "";
        $scope.currentSampleAlphacode = "";         
     
        $scope.$parent.selected = {};
        $scope.$parent.currentBlockID = "";
        $scope.$parent.currentSampleID = "";
        $scope.$parent.currentImageID = "";
        $scope.$parent.rdf2 = {};         
        $scope.$parent.currentBlockName = "";
        $scope.$parent.currentSampleName = "";
        $scope.$parent.currentSampleAlphacode = "";    
     
     };

     
    //
    //-- Tree View -----------------------------------------------------------
    //

    $scope.closedworkTree = [];
    $scope.openworkTree = [];
    
    $scope.showSelected = function(sel) {
        //flgSelectedChanged = true;
        $scope.selected = sel;
        
        if ($scope.selected.children.length === 0) {
            flgSelectedChanged          = true;
            $scope.currentBlockID       = $scope.selected.blockID;
            $scope.currentSampleID      = $scope.selected.id;
            $scope.currentImageID       = $scope.selected.imageID;
            $scope.currentSampleName    = $scope.selected.label;
            $scope.currentBlockName     = $scope.selected.blockName;  
            //$scope.currentBlockAlphacode = "";//$scope.selected.alphaCode;
            $scope.currentSampleAlphacode = $scope.selected.alphaCode;
        }
        else {
            $scope.currentBlockName     = $scope.selected.label;
            $scope.currentBlockID       = $scope.selected.id;
            $scope.currentSampleID      = "";
            $scope.currentImageID       = "";
            $scope.currentSampleName    = "";  
            $scope.currentSampleAlphacode = "";
            $scope.currentBlockAlphacode = $scope.selected.alphaCode;
            $scope.rdf2 = [];
        }
    };


    //
    //--- Search Standards ----------------------------------------------------
    //
    
    /**
     * 
     * @param {type} searchTerms
     * @returns {undefined}
     */
    $scope.searchNow = function(searchTerms) {
       
        var inputs = $window.document.querySelectorAll("input[type='checkbox'].rdSearch");
        var checked = [];
        var listed = "";
        
        if ($scope.$parent.currentBlockID === "") {
            alert("Please select a work folder first.");
            return;
        }  

        for(var i = 0; i < inputs.length; i++) {
            if (inputs[i].checked) {
                 checked.push(inputs[i]);
                 listed += inputs[i].name;
                 listed += ",";
            }   
        }
              
        if (checked.length === 0) {
            alert("Please check the standards to search.");
            return;
        }         
              
        if (searchTerms) {
            $http({
                url: "/api/standards/searchEntries",
                method: "JSON",
                data: { 
                    "cidList": listed,
                    "terms": searchTerms
                }
            })
            .success(function(response, status, headers, config) {            
                if (response.data.length > 0) {
                   $scope.searchMessage = "";
                   $scope.searchEntries = response.data; 
                } 
                else {
                    $scope.searchMessage = "No Results: Try expanding your search.";
                }
            })
            .error(function(response, status, headers, config) {
                $scope.status = "errors";
            }); 
        }
        else {
            alert("Please enter some search terms or parameters.");
            return;
        }
   };
  
   
   //
   //--- Sample Review --------------------------------------------------------
   //
   

    $scope.getReviewData2 = function(blockID, sampleID, imageID) {
       
        $http({
            url: "/api/review/getReviewData",
            method: "JSON",
            data: { 
                "blockID": blockID, 
                "sampleID": sampleID,
                "imageID": imageID
            }
        })
        .success(function(response, status, headers, config) {            
            $scope.rdf2 = response.data;
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });            
    };    
           
   
    $scope.updateSCR = function() {
        alert("Update called");
    };
    
    
    $scope.addSCR = function() {
          
        if ($scope.currentBlockID === "" || $scope.$parent.currentBlockID === "") {
            alert("Please select a work folder and then a sample before adding a review.")
            return;
        }

        if ($scope.currentImageID === "" || $scope.$parent.currentImageID === "") {
            alert("Please select a sample before trying to add a review.")
            return;
        }   
                
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
            $scope.rdf2 = response.data;
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });         
    };


    $scope.addStd = function(value) {
          
        if ($scope.$parent.currentBlockID === "" ) {
            alert("Please select a work folder and then a sample before adding a review.")
            return;
        }

        if ($scope.$parent.currentImageID === "") {
            alert("Please select a sample before trying to add a review.")
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
            $scope.$parent.rdf2 = response.data;
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });  
        
    };


    $scope.delSCR = function() {

        var inputs = $window.document.querySelectorAll("input[type='checkbox']");
        var checked = [];
        var listed = "";

        if ($scope.currentBlockID === "" ) {
            alert("Please select a work folder and then a sample.")
            return;
        }

        if ($scope.currentImageID === "" ) {
            alert("Please select a sample before trying to delete.")
            return;
        }


        for(var i = 0; i < inputs.length; i++) {
            if (inputs[i].checked) {
                 checked.push(inputs[i]);
                 listed += inputs[i].name;
                 listed += ",";
            }   
        }
        
        if (checked.length === 0) {
            alert("Please make some selections before trying to delete.");
            return;
        }

        if (checked.length > 0) {
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
                $scope.rdf2 = response.data;
                $scope.$apply();
            })
            .error(function(response, status, headers, config) {
                $scope.status = "errors";
            });            
        } 
        
    }
    
    
    //--- Note System ---------------------------------------------------------
    //
    
    
    $scope.getNote = function(key) {

        $scope.currentGroupingID = parseInt(key);
        
        $http({
            url: "/api/review/getNote",
            method: "JSON",
            data: { 
                "blockID": $scope.currentBlockID, 
                "sampleID": $scope.currentSampleID,
                "imageID": $scope.currentImageID,
                "groupingID": parseInt(key)
            }
        })
        .success(function(response, status, headers, config) {  
            if (response.data.note) {
                $scope.currentNote   = response.data.note;
                $scope.currentNoteID = response.data.id;                
                $scope.noteStamp = response.data.stamp;
            }
            else {
                $scope.currentNote   = "";
                $scope.currentNoteID = -1;
                $scope.noteStamp = "";
                //$scope.currentGroupingID = "";
                
            }
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        }); 
    };
    
    
    $scope.saveNote = function() {
        /*
        alert("Save Note" 
              + "\nBID:" + $scope.currentBlockID
              + "\nSID:" + $scope.currentSampleID
              + "\nIID:" + $scope.currentImageID 
              + "\nGID:" + $scope.currentGroupingID
              + "\nNID:" + $scope.currentNoteID
              + "\nText:"+ $scope.currentNote                
         );
         */

         if ($scope.currentNoteID === "") {
             alert("Please select a note to save.");
             return;
         }


        $http({
            url: "/api/review/saveNote",
            method: "JSON",
            data: { 
                "blockID": $scope.currentBlockID, 
                "sampleID": $scope.currentSampleID,
                "imageID": $scope.currentImageID,
                "groupingID": $scope.currentGroupingID,
                "noteID": $scope.currentNoteID,
                "noteText": $scope.currentNote                
            }
        })
        .success(function(response, status, headers, config) {  
            //alert("Saved!");
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });          
    };
    
        
    $scope.delNote = function() {
        
        /*
        alert("Save Note" 
              + "\nBID:" + $scope.currentBlockID
              + "\nSID:" + $scope.currentSampleID
              + "\nIID:" + $scope.currentImageID 
              + "\nGID:" + $scope.currentGroupingID
              + "\nNID:" + $scope.currentNoteID                
         );
         */
       
        $scope.currentNote = "";
         
        if ($scope.currentNoteID < 0) { return; }

        $http({
            url: "/api/review/delNote",
            method: "JSON",
            data: { 
                "noteID": $scope.currentNoteID                               
            }
        })
        .success(function(response, status, headers, config) {  
            //alert("Deleted!");
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        }); 
    };
    

    $scope.rotate = function(angle) {        
        $scope.angle += angle;
    }
    
    
    $scope.blank = function() {
      //alert("Set as blank: " + $scope.$parent.currentImageID); 
      
        if ($scope.$parent.currentImageID === "") { return; }
      
        $http({
            url: "/api/review/setBlank",
            method: "JSON",
            data: { 
                "imageID": $scope.$parent.currentImageID                               
            }
        })
        .success(function(response, status, headers, config) {  
            //alert("Deleted!");
            if (response.data === "true") {
                $scope.$parent.getWork('open');
                $scope.$parent.getWork('closed');
            }
        })
        .error(function(response, status, headers, config) {
            $scope.status = "errors";
        });       
    };
    
    
    $scope.detachImage = function(imageID) {
        //$window.alert("ImageID = " + $scope.$parent.currentImageID);
        
        $window.open("/viewer/detach/" + $scope.$parent.currentImageID);
    };
    
});

