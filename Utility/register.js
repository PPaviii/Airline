function check(){

    let formParam = document.getElementById("register");

    let mail = formParam.elements[0].value;
    let password1 = formParam.elements[1].value;
    let password2 = formParam.elements[2].value;

    if(password1 !== password2){
        window.alert("The two passwords you inserted are different. Try again");
        document.getElementById("pass1").value = "";
        document.getElementById("pass1").style.borderColor = 'red';
        document.getElementById("pass1").style.borderRadius = '4px';
        document.getElementById("pass2").value = "";
        document.getElementById("pass2").style.borderColor = 'red';
        document.getElementById("pass2").style.borderRadius = '4px';
        return false;
    }

    let resultEmail = validateEmail(mail);
    let resultPassword = validatePassword(password1);

    if(resultEmail === false || resultPassword === false){
        window.alert("Your username or password do not respect the registration policies. Try again");
        document.getElementById("user").value = "";
        document.getElementById("pass1").value = "";
        document.getElementById("pass2").value = "";
        return false;
    }else{
        return true;
    }
}

function validateEmail(email) {
    let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return Boolean(re.test(String(email).toLowerCase()));
}

function validatePassword(password) {
    let re = /^(([a-z]*)+([A-Z]|\d)+[a-z]+)|(([a-z]+)+([A-Z]|\d)+([a-z]*))$/; /* (*) = 0 or more, (+) = 1 or more */
    return Boolean(re.test(String(password)));
}
