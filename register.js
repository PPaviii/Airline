function check(){

    var formParam = document.getElementById("register");

    var mail = formParam.elements[0].value;
    var password = formParam.elements[1].value;

    resultEmail = validateEmail(mail);
    resultPassword = validatePassword(password);

}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function validatePassword(password) {

    for(var i = 0; i < strlen(password); i++){
        if(password[i] === password[i].toLowerCase()){

        }
    }

}
