window.onload = function(e){ 
   render();
}

function render() {
   
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
    recaptchaVerifier.render();
}


function phoneAuth(phone)
{
  
    var phone_number=document.getElementById("phone_number").value;
    firebase.auth().signInWithPhoneNumber(phone_number, window.recaptchaVerifier).then(function(confirmationResult) {
        //s is in lowercase
        window.confirmationResult = confirmationResult;
        coderesult = confirmationResult;
        console.log(coderesult);
         document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "block";
        document.getElementById("sent_success").innerHTML = "OTP send successfully";
       // document.getElementById("some_error").innerHTML  = res['message'];
       // alert("Message sent");
    }).catch(function(error) {
       // alert(error.message);
        document.getElementById("some_error").innerHTML = error.message;
    });
}
function codeverify() {
    const verifyButton = document.getElementById('login');
    var code = document.getElementById('otp').value;
   
    document.getElementById("sent_success").innerHTML = "We Will Redirect You Soon";
    verifyButton.disabled = true;
    coderesult.confirm(code).then(function (result) {
       // alert("Successfully registered");
        window.location.href = "dashboard";

    }).catch(function (error) {
        alert(error.message);
        verifyButton.disabled = false;
    });
}
