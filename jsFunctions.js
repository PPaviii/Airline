function darker(x) {
    var color = document.getElementById(x).style.backgroundColor;
    if(color !== "red") {
        document.getElementById(x).style.border = "3px solid yellow";
    }
}

function normal(x) {
    document.getElementById(x).style.border = "1px solid black";
}
