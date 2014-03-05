//var base_url = "http://www.granted-solutions.com/gsnews";
var base_url = "";

var app = angular.module('ccssmonitor',['ngResource']);


var api = function($resource) { 
    
    this.settingsLogin     = $resource(base_url + "/api/settings/login");
}

app.service("Api", api);