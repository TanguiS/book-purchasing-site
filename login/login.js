function handlerLogin( page ) {
    var nameInput = document.getElementById("name");
    var lastNameInput = document.getElementById("lastName");
    XMLRequest('login', nameInput.value, lastNameInput.value);
}


function XMLRequest( action, name, lastName ) {
    var request = new XMLHttpRequest();
    request.open("POST", "../handleRequest.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = () => {
        if ( request.readyState == XMLHttpRequest.DONE && request.status == 200 ) {
            console.log(request.responseText);
            if (request.responseText == 1) {
                window.location.replace("../bookSaler.php");
            } else {
                location.reload(true);
            }
        }
            
    };
    request.send("action="+action+"&firstName="+name+"&lastName="+lastName);
}