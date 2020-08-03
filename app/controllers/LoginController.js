$postModule = angular.module('loginApp', []);
var base_path = document.getElementById('base_path').value;

$postModule.controller('LoginController',function($scope, $http){
	$scope.post = {};
	$scope.post.users = [];
	$scope.tempUser = {};
	$scope.editMode = false;
	$scope.index = '';
        var url = base_path+'login/doLogin';
	
	$scope.loginUser = function(){
		$http({
			method: 'post',
			url: url,
                        data: $.param({'username' : $scope.tempUser.username, 'password' : $scope.tempUser.password, 'type' : 'login' }),
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function (response){
			var data = response.data;
			if(data.success){
	    		$scope.messageSuccess(data.message);
	    		$scope.loginForm.$setPristine();
	    		$scope.tempUser = {};
                        var redirectto = base_path+'#items';
                        window.location.href = redirectto;
	    		
	    	}else{
	    		$scope.messageFailure(data.message);
	    	}
		},function (error){
			console.log(error);
		});
		
	    jQuery('.btn-save').button('reset');
	}
	
	$scope.login = function(){
		jQuery('.btn-save').button('loading');
		$scope.loginUser();
		$scope.editMode = false;
		$scope.index = '';
	}
		
	$scope.messageFailure = function (msg){
		jQuery('.alert-failure-div > p').html(msg);
		jQuery('.alert-failure-div').show('slow');
		jQuery('.alert-failure-div').delay(5000).slideUp(function(){
			jQuery('.alert-failure-div > p').html('');
		});
	}
	
	$scope.messageSuccess = function (msg){
		jQuery('.alert-success-div > p').html(msg);
		jQuery('.alert-success-div').show('slow');
		jQuery('.alert-success-div').delay(5000).slideUp(function(){
			jQuery('.alert-success-div > p').html('');
		});
	}		
});