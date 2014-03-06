//var base_url = "http://www.granted-solutions.com/gsnews";
var base_url = "";

var app = angular.module('ccssmonitor',['ngResource']);


var api = function($resource) { 

    this.user = $resource(base_url + "/api/settings/user");
};


app.service("Api", api);