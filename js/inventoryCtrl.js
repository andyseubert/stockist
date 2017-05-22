
angular.module('myApp').controller('inventoryItemCtrl',['$scope','$log','$http','itemService','$filter', function($scope,$log,$http,itemService,$filter){


    // put inventory item data URLs here as variables
    
    //$log.info("in inventoryItemController here");
    // manage inventory items 
    // need to know about the items
    $scope.items=[];
    //$log.log($scope.items);
    $http.get('/data/items.php')
    .success(function(result){
                $scope.items = result; // items set to GET result here
                $log.log(result);
            })
    .error(function(result, status){
        $scope.items = status;
        $log.log(status);
        
    });
    
    //calculate inventory total cost
    $scope.calculateTotalCost=function(){
        //$log.log("calculating cost");
        var total = 0;
        for(var i = 0; i < $scope.items.length; i++){
            var product = $scope.items[i];
            total += (product.cost * product.quantity);
        };
        return total;
    };
    
    
    // add item to inventory
    $scope.newitem={
        'name':'',
        'unit':'g',
        'quantity':0,
        'vendor':'Liberty Natural',
        'vendorpartno':'',
        'cost':'',
        'size':'',
        'type':'ESSENTIAL OIL',
        'origin':'',
        'description':''
    };

    $scope.addItem = function() {
	// 
	
	$http.post('/data/additem.php', $scope.newitem )
   .success(function(result){
      
      $log.log("result.item_id is " +result);
      $scope.newitem.id=result;
      $scope.items.splice(0,0,$scope.newitem);
      $scope.newitem={
        'name':'',
        'unit':'g',
        'quantity':0,
        'vendor':'Liberty Natural',
        'vendorpartno':'',
        'cost':'',
        'size':'',
        'type':'ESSENTIAL OIL',
        'origin':'',
        'description':''
    };
})
   .error(function(result,status){
      $log.log(status+"	"+result);
  })
   
};


    // edit item parameters
    $scope.updateItem=function(data){
        return $http.post('/data/updateitem.php',data)
        .error(function(err) {
            if(err.field && err.msg) {
              // err like {field: "name", msg: "Server-side error for this !"} 
              $log.log(err.field, err.msg);
          } else { 
              // unknown error
              $log.log('name', 'Unknown error!');
          }
      })
        
        .success(function(result){
            //$log.log(result);
        });
        
        calculateTotalCost();
    };
    
    // delete Item from inventory
    $scope.deleteItem=function(removethis){
        // {'item_id':item.item_id})
        $http.post('data/deleteitem.php',removethis)
        .success(function(result){
         $log.log ("deleted item id : " + removethis.item_id);
			// remove from $scope.items too			
          $log.log("returned from php: " + result);
          $scope.items = $filter('filter')($scope.items, function(value, index) {return value.item_id !== removethis.item_id;});
          
      })
        
        
    };
    
}]);

