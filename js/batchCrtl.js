
angular.module('myApp').controller('batchCtrl',['$scope','$log','$http','$filter', function($scope,$log,$http,$filter){


    $http.get('/data/getbatches.php')
            .success(function(result){
                $scope.batches = result; // items set to GET result here
                //$log.log($scope.batches);
            })
            .error(function(result, status){
                $scope.batches = status;
                $log.log("ERROR : ", status);

            });



    // column sorting
    $scope.orderByField = 'batch_name';
    $scope.reverseSort = false;


// end of controller
}]);