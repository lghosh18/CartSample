app.controller('AdminController', function($scope){
 
  $scope.pools = [];
   
});

app.controller('ItemController', function(dataFactory,$scope){

  $scope.data = [];
  $scope.pageNumber = 1;
  $scope.libraryTemp = {};
  $scope.totalItemsTemp = {};

  $scope.totalItems = 0;
  $scope.pageChanged = function(newPage) {
    getResultsPage(newPage);
  };

  getResultsPage(1);
  function getResultsPage(pageNumber) {
      if(! $.isEmptyObject($scope.libraryTemp)){
          dataFactory.httpRequest('items?search='+$scope.searchText+'&page='+pageNumber).then(function(data) {                        
            $scope.data = data.data;
            $scope.totalItems = data.total;
            $scope.pageNumber = pageNumber;
            $scope.totalCartValue = data.total_cart_value;            
          });
      }else{
          dataFactory.httpRequest('items?page='+pageNumber).then(function(data) {
          $scope.data = data.data;
          $scope.totalItems = data.total;
          $scope.pageNumber = pageNumber;
          $scope.totalCartValue = data.total_cart_value;
        });
      }
  }

  $scope.searchDB = function(){
      if($scope.searchText.length >= 3){
          if($.isEmptyObject($scope.libraryTemp)){
              $scope.libraryTemp = $scope.data;
              $scope.totalItemsTemp = $scope.totalItems;
              $scope.data = {};
          }
          getResultsPage(1);
      }else{
          if(! $.isEmptyObject($scope.libraryTemp)){
              $scope.data = $scope.libraryTemp ;
              $scope.totalItems = $scope.totalItemsTemp;
              $scope.libraryTemp = {};
          }
      }
  }

  $scope.addToCart = function(id, index){
    dataFactory.httpRequest('items/itemAddToCart/'+id).then(function(data) {
    	console.log(data);   
        $scope.totalCartValue = data.total_cart_value;
        $scope.data[index].present_in_cart = 1;        
    });
  }
  
  $scope.removeFromCart = function(id, index){
    dataFactory.httpRequest('items/itemRemoveFromCart/'+id).then(function(data) {
    	console.log(data);     
        $scope.totalCartValue = data.total_cart_value;
        $scope.data[index].present_in_cart = 0;
    });
  }    
   
  $scope.redirect = function(){
    window.location = "#/cart";    
  }
});

app.controller('CartController', function(dataFactory,$scope){
  $scope.data = [];
  $scope.pageNumber = 1;
  $scope.libraryTemp = {};
  $scope.totalItemsTemp = {};

  getResultsPage();
  function getResultsPage() {
    dataFactory.httpRequest('cart').then(function(data) {
        $scope.data = data.data;    
        $scope.totalCartValue = data.total_cart_value;
    });      
  }
  
  $scope.placeOrder = function(){
    dataFactory.httpRequest('orders/placeOrder/').then(function(data) {
    	console.log(data.order_id);           
        window.location.href = "#/orders/"+data.order_id;
    });
  }
});  
  
app.controller('OrderController', function(dataFactory,$scope,$routeParams){
  $scope.data = [];
  $scope.pageNumber = 1;
  $scope.libraryTemp = {};
  $scope.totalItemsTemp = {};
  getResultsPage();
  function getResultsPage() {
    dataFactory.httpRequest('orders/get/'+$routeParams.orderId).then(function(data) {
        $scope.data = data.data;    
        $scope.orderId = data.order_id;
        $scope.totalOrderAmount = data.total_amount;
        console.log(data);
    });      
  }      

});
