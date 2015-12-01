
angular.module('myApp').controller('recipeCtrl',['$scope','$log','$http','$filter', function($scope,$log,$http,$filter){



    // manage recipes with this controller
// also calculate and save a batch. a batch is a recipe multiplied by 1 or more times (or even by a fraction)
    // need to know about the items

    // get list of all items
    $http.get('/data/items.php')
            .success(function(result){
                $scope.items = result; // items set to GET result here
                //$log.info("in itemService here");
                //$log.log(result);
            })
            .error(function(result, status){
                $scope.items = status;
                $log.log(status);

            });

        //$log.info ($scope.items);

    // get all recipes
    //$scope.recipes={};
    $scope.recipeItems=[];

    $http.get('/data/getrecipes.php')
            .success(function(result){
                $scope.recipes = result; // items set to GET result here
                //$log.log($scope.recipes);
            })
            .error(function(result, status){
                $scope.recipes = status;
                $log.log(status);

            });



    // column sorting
    $scope.orderByField = 'name';
    $scope.reverseSort = false;


    //display chosen recipe
    $scope.selectRecipe =function(data){
        // reset the list of items
        $scope.recipeItems=[];
        //reset the batch
        $scope.batch.multiplier=1;

        // should only be sending me the recipe_id as { 'recipe_id':## }
        $log.log("attempting to select recipe by id here");
        $log.log('data.recipe_id received : ' + data.recipe_id);
        $http.post('/data/listrecipeitems.php',{'recipe_id':data.recipe_id})
            .success(function(result){
                if (angular.isObject(result)) {  // crazy ?
                    $scope.recipeItems=result;
                    $log.log("result from choosing: '" + $scope.recipeItems + "'");
                    $log.log("length of result: " + $scope.recipeItems.length);
                    $scope.calculateStockQty();
                }else{ // brand new recipe



                }
            });
    };


    // add recipe  ADD RECIPE NOT WORKING CORRECTLY ******* <<-----------
    $scope.addRecipe = function(){
        $scope.addone=false;
        if ($scope.recipe_name !=='') {
        // add recipe - receive recipe object
        $http.post('/data/addrecipe.php',{ 'recipe_name':$scope.recipe_name })
        .success(function(result){
            $log.log('result from addrecipe.php:' + result);
            $scope.recipe = {
    			'recipe_id':result,
    			'name':$scope.recipe_name,
    			'description':'',
    			'recipe_cost':'',
    			'recipe_size':'',
    			'recipe_units':''
			};
            //$scope.recipe=result;
            $scope.recipes.push($scope.recipe);
            $log.info("scope.recipe is : " + $scope.recipe);
            //$log.info('scope.recipe.name is '+ $scope.recipe_name);
            $scope.selectRecipe( { 'recipe_id':result } );

        })

        .error(function(result, status){
            $log.log(result + status);
            // duplicate name needs feedback
        });


    }else{
        //maybe say something about entering empty recipe name
    }
    };

    // add item to recipe
    $scope.addItemToRecipe = function(additem_id){
        $log.log('looking to add id: '+ additem_id.item_id);
            //don't allow duplicates to be added
            // php will check for duplicates in db
            $http.post('/data/additemtorecipe.php',{'item_id':additem_id.item_id,'recipe_id':$scope.recipe.recipe_id})
            .success(function(result){
                if ( angular.isObject(result) ) { // empty means there was a duplicate
                    // find the item object from the items we have and push it onto the recipeItems
                    $log.log("add item to recipe result from php: " + result);
                    var matchedItem = $filter('filter')($scope.items, function(value, index) {return value.item_id == additem_id.item_id;})[0];
                    if (matchedItem) {

                        $scope.recipeItems.push(matchedItem);
                        $scope.calculateStockQty(); //calculate instock quantity
                        $log.log(matchedItem);

                    }else{
                        $log.log ("item not matched..");
                    }
                }else{
                    $log.log("no object returned add item to recipe result from php: " + result);
                    // alert the user here that they tried to add a duplicate
                }
            })
            .error(function(result,status){
                // probably should handle some error here
                $log.log("error adding item to recipe result from php: " + result);
                });
            // reset select box
            $scope.additem_id={};

        };



    // delete item from recipe
    $scope.removeRecipeItem=function(removethis){
        // {'recipe_id':recipe_id,'item_id':recipeitem.item_id})
        $log.log("incoming values:"+removethis.toString());

        $log.log("removethis recipe_id : " + removethis.recipe_id);
		$log.log("current page $scope.recipe.recipe_id: " + $scope.recipe.recipe_id);
        $log.log("removethis item_id : " + removethis.item_id);

        $http.post('/data/removerecipeitem.php',{'recipe_id':$scope.recipe.recipe_id,'item_id':removethis.item_id})
			.success(function(result){
			   // remove from recipeItems
			   $log.log("returned from php: " + result);
			   $scope.recipeItems = $filter('filter')($scope.recipeItems, function(value, index) {return value.item_id !== removethis.item_id;});
			 })
			.error(function(result,status){
			   $log.log("error result:" + result);
			})

    };

    // edit amount of item in recipe
    $scope.updateRecipeItem=function(data){

        return $http.post('/data/updaterecipeitem.php',data)
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

    calculateRecipeCost();
        };

// UPDATE RECIPE NOTES
    $scope.updateRecipeNotes=function(){

        return $http.post('/data/updaterecipenotes.php',{ 'recipe_id':$scope.recipe.recipe_id })

        .success(function(result){
            $log.log(result);
        });



    };

    // caculate cost of item in recipe
    // calculate total recipe cost
    $scope.calculateRecipeCost=function(){
        //$log.log("calculating cost");
        var total = 0;
        for(var i = 0; i < $scope.recipeItems.length; i++){
            var product = $scope.recipeItems[i];
            total += (product.cost * product.amount);
        };
		$scope.recipeTotalCost = total;
        
        return total;
    };

    // GATHER THE QUANTITY FOR EACH recipeItem that is  "in stock"
    $scope.calculateStockQty=function(){
        for(var i = 0; i < $scope.recipeItems.length; i++){
            $log.log ("trying to calculate in stock qty");
            var product = $scope.recipeItems[i];
            var matchedItem = $filter('filter')($scope.items, function(value, index) {return value.item_id == product.item_id;})[0];
            if (matchedItem) {
                product.stockqty = parseInt(matchedItem.quantity,10 );
                //$log.log(matchedItem.name + " qty: " + matchedItem.quantity);
                //$log.log(product.name + " qty:" + product.stockqty);
            }
        }
    }


    // calculate recipe amount produced by unit (containers separate from oz,g,lb, etc)
    $scope.calculateRecipeAmounts=function(){
        // convert all units to grams
        // add up the grams
		total=0;
		$scope.containerCount=0;

        for(var i = 0; i < $scope.recipeItems.length; i++){
            var product = $scope.recipeItems[i];
			// get the unit of the item from the data service.
			// if the unit is grams then add to total
			if (product.unit == "g" ){
				total = parseFloat(total) + parseFloat(product.amount);
			}else if (product.unit == "oz"){
				// How many grams in 1 US fluid ounce? The answer is 29.5735296875.
				// oz * 29.5735296875 = g
				total = (parseFloat(total) + (parseFloat(product.amount) * 29.57)).toFixed(3);
			}else if (product.unit == "lb"){
        // 1 lb = 453.592 grams
        total = (parseFloat(total) + (parseFloat(product.amount) * 453.592)).toFixed(2);
      }

      // for containers take the number of containers to calculate per container cost
			if (product.type=="container"){
				$scope.containerCount = parseFloat(product.amount);
			}
        };

		$scope.recipe_units = "grams" ;
		$scope.recipeCostPerUnit = parseFloat($scope.recipeTotalCost / $scope.containerCount).toFixed(2)

    $scope.recipeTotalAmount = parseFloat(total).toFixed(3);

        return $scope.recipeTotalAmount;

    }
        // ADD/UPDATE NOTES TO RECIPE
        // updaterecipenotes.php


        // change recipe name

        // duplicate recipe


// BATCH FUNCTIONS BELOW HERE
// map batch objects to recipe objects
$scope.batch={};
$scope.batch.recipe = $scope.recipe;
$scope.batch.items  = [];
$scope.batch.items  = $scope.recipeItems;
$scope.batch.multiplier=1;


$scope.calculateBatch=function(){
  $scope.batch.amount=(parseFloat($scope.batch.multiplier) * parseFloat($scope.recipeTotalAmount));
  $scope.batch.cost = (parseFloat($scope.batch.multiplier) * parseFloat($scope.recipeTotalCost));
  $log.log("batch.multiplier is " + $scope.batch.multiplier);
  $scope.batch.units = $scope.recipe_units;
  /*
  $log.log ("calculated batch amount is " + $scope.batch.amount);
  $log.log("calculated batch cost is "+ $scope.batch.cost);
  $log.log("batch units are : " + $scope.batch.units);
  */
  return  ;

}

// SAVE BATCH TO DATABASE
 // create the batch
 //  createbatch.php
 //  add the items to the batch
 // just loop over the recipe items and for each one add it and set the amount to the amount * batch.multiplier




// end of controller
}]);
