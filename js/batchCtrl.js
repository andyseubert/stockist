
angular.module('myApp').controller('batchCtrl',['$scope','$log','$http','$filter', function($scope,$log,$http,$filter){

$scope.batchItems=[];

    $http.get('/data/getbatches.php')
            .success(function(result){
                $scope.batches = result; // items set to GET result here
                $scope.calculateBatchCost();
                $scope.calculateBatchAmounts();
                //$log.log($scope.batches);
            })
            .error(function(result, status){
                $scope.batches = status;
                $log.log("ERROR : ", status);

            });

 // caculate cost of item in batch
    // calculate total batch cost
    $scope.calculateBatchCost=function(){
        //$log.log("calculating cost");
        var total = 0;
        for(var i = 0; i < $scope.batchItems.length; i++){
            var product = $scope.batchItems[i];
            total += (product.cost * product.amount);
        };
		$scope.batchTotalCost = total;
        
        return total;
    };

    // column sorting
    $scope.orderByField = 'batch_name';
    $scope.reverseSort = false;

    //display chosen batch
    $scope.selectBatch =function(data){
        // reset the list of items
        $scope.batchItems=[];

        // should sent the batch_id as json { 'batch_id':## }
        $log.log("attempting to select batch by id here");
        //$log.log("data : " , data);
        //$log.log('data.batch_id received : ' + data.batch_id);
        $http.post('/data/listbatchitems.php',{'batch_id':data.batch_id})
            .success(function(result){
                if (angular.isObject(result)) {  // crazy ?
                    $scope.batchItems=result;
                    $log.log("batchItems from data: " , $scope.batchItems );
                    $log.log("result: " , result);

                }else{ // error or maybe brand new batch
                    $log.log("problem from data: " + result);
                }
            });
    };
// calculate batch amount produced by unit (containers separate from oz,g,lb, etc)
    $scope.calculateBatchAmounts=function(){
        // convert all units to grams
        // add up the grams
		total=0;
		$scope.containerCount=0;

        for(var i = 0; i < $scope.batchItems.length; i++){
            var product = $scope.batchItems[i];
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

		$scope.batch_units = "grams" ;
		$scope.batchCostPerUnit = parseFloat($scope.batchTotalCost / $scope.containerCount).toFixed(2)

    $scope.batchTotalAmount = parseFloat(total).toFixed(3);

        return $scope.batchTotalAmount;

    }
// UPDATE BATCH NOTES
    $scope.updateBatchNotes=function(){

        return $http.post('/data/updatebatchnotes.php',{ 'batch_id':$scope.batch.batch_id,'notes':$scope.batch.batch_notes })

        .success(function(result){
            $log.log(result);
        });
    };


// end of controller
}]);