//var myApp = angular.module('myApp',['ngRoute',"xeditable"]);

var myApp = angular.module('myApp',[]);

myApp = angular.module('myApp',['ngRoute','ui.bootstrap','xeditable']);

// xeditable options
myApp.run(function(editableOptions,editableThemes) {
  editableOptions.theme = 'default'; // bootstrap3 theme. Can be also 'bs2', 'default'
  //editableThemes.bs3.inputClass = 'input-sm';
  //editableThemes.bs3.buttonsClass = 'btn-sm';
});


myApp.config(function($routeProvider){
    
    $routeProvider
    
    .when('/',{
        templateUrl:'/recipes.html',
        controller:'recipeCtrl'
    })

    .when ('/inventory',{
        templateUrl:'/listitems.html',
        controller:'inventoryItemCtrl'
    })
           .when('/recipes',{
        templateUrl:'/recipes.html',
        controller:'recipeCtrl'
    })
           .when('/batches',{
        templateUrl:'/batches.html',
        controller:'batchCtrl'
    })
});

// items need to be on both items and recipes pages
myApp.service('itemService', ['$log','$http', function($log,$http){
    this.items={};
    
    // get list of all items
    $http.get('/data/items.php')
            .success(function(result){
                this.items = result; // items set to GET result here
                //$log.info("in itemService here");
                //$log.log(result);
            })
            .error(function(result, status){
                this.items = status;
                $log.log(status);
            
            });
    return this.items;
}]);



