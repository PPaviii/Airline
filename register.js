function check(){

    let formParam = document.getElementById("register");

    let mail = formParam.elements[0].value;
    let password = formParam.elements[1].value;

    let resultEmail = validateEmail(mail);
    let resultPassword = validatePassword(password);

    if(resultEmail === false || resultPassword === false){
        window.alert("Your username or password do not respect the registration policies. Try again");
        document.getElementById("name").value = "";
        document.getElementById("pass").value = "";
        return false;
    }else{
        return true;
    }
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return Boolean(re.test(String(email).toLowerCase()));
}

function validatePassword(password) {

    if(password.length < 2){
        return false;
    }

    for(let i = 0; i < password.length; i++){
        if((password[i] >= 'a' && password[i] <= 'z') && isNaN(parseInt(password[i], 10))){
            break;
        }

        if(i === password.length - 1){
            return false;
        }
    }

    for(let j = 0; j < password.length; j++){
        if((password[j] >= 'A' && password[j] <= 'Z') || !isNaN(parseInt(password[j], 10))){
            break;
        }

        if(j === password.length - 1){
            return false;
        }
    }

    return true;
}
