  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyDnDn5QaoXta15pWQIcva29P56O15VrPmo",
    authDomain: "fashionapps-d9526.firebaseapp.com",
    projectId: "fashionapps-d9526",
    databaseURL: "https://fashionapps-d9526-default-rtdb.firebaseio.com",
    storageBucket: "fashionapps-d9526.appspot.com",
    messagingSenderId: "188802685100",
    appId: "1:188802685100:web:5709ae75eaaadc54c0e709",
    measurementId: "G-Q4G93CTVV8"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

    firebase.auth.Auth.Persistence.LOCAL; 

    $("#btn-login").click(function(){
        
        var email = $("#email").val();
        var password = $("#password").val(); 

        var result = firebase.auth().signInWithEmailAndPassword(email, password);
    
        result.catch(function(error){
            var errorCode = error.code; 
            var errorMessage = error.message; 

            console.log(errorCode);
            console.log(errorMessage);
        });

    });

    $("#btn-logout").click(function(){
        firebase.auth().signOut();
    });

    function switchView(view){
        $.get({
            url:view,
            cache: false,  
        }).then(function(data){
            $("#container").html(data);
        });
    }