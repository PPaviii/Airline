function darker(id, color) {
    if(color !== "red") {
        document.getElementById(id).style.border = "1px solid yellow";
    }
}

function normal(id) {
        document.getElementById(id).style.border = "1px solid black";
}
