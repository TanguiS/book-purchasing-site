function handlerRegister(page) {
    var firstName = document.getElementById("first-name").value;
    var lastName = document.getElementById("last-name").value;
    var addr = document.getElementById("addr").value;
    var zipCode = document.getElementById("cp").value;
    var city = document.getElementById("city").value;
    var country = document.getElementById("country").value;

    XMLRequest('register', firstName, lastName, addr, zipCode, city, country);
}


function XMLRequest( action, firstName, lastName, addr, zipCode, city, country ) {
    var request = new XMLHttpRequest();
    request.open("POST", "../handleRequest.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = () => {
        if ( request.readyState == XMLHttpRequest.DONE && request.status == 200 ) {
            console.log(request.responseText);
            if (request.responseText != 0 ) {
                window.location.replace("../bookSaler.php");
            } else {
                location.reload(true);
            }
        }
            
    };
    request.send("action="+action+"&first-name="+firstName+"&last-name="+lastName+"&addr="+addr+"&cp="+zipCode+"&city="+city+"&country="+country);
}