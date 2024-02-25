window.addEventListener('load', ()=>{
    setByAuthorListenner();
    setByTitleAuthorListenner();
    setCartListenner();
    listenDeleteCart();
});

function setByAuthorListenner() {
    var inputId = document.getElementById("search_author");
    inputId.addEventListener('keyup', ()=>{
        if ( inputId.value != "" ) {
            XMLRequest('byAuthor', inputId.value);
        }
    });
}

function setCartListenner() {
    var cartButton = document.getElementById("cartButton");
    cartButton.addEventListener('click', ()=>{
        var request = new XMLHttpRequest();
        request.open("POST", "handleRequest.php", true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.onreadystatechange = () => {
            if ( request.readyState == XMLHttpRequest.DONE && request.status == 200 ) {
                generateCart(JSON.parse(request.responseText) );
                listenQuitButton();
                listenCommandButton();
            }
        };
        request.send("action=getCart");
    });

}


function setByTitleAuthorListenner() {
    var inputId = document.getElementById("search_title");
    inputId.addEventListener('keyup', ()=>{
        if ( inputId.value != "" ) {
            XMLRequest('byTitle', inputId.value);
        }
    }) 
}

function XMLRequest( action, name ) {
    var request = new XMLHttpRequest();
    request.open("POST", "handleRequest.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = () => {
        if ( request.readyState == XMLHttpRequest.DONE && request.status == 200 ) {
            createTableByAction( action, JSON.parse(request.responseText) );
        }
            
    };
    request.send("action="+action+"&arg="+name);
}

function authorItem(data, i) {
    var root = document.createElement("li");
    root.innerHTML = data[i].nom + " " + data[i].prenom;
    var ul = document.createElement("ul");
    data[i].ouvrages.forEach( elt => {
        console.log(elt);
        var li = document.createElement("li");
        li.innerHTML = elt.nom;
        ul.appendChild(li);
    })
    root.appendChild(ul);
    return root;
}

function createBtn(codeCopy) {
    var btn = document.createElement("button");
    btn.innerHTML = "Ajouter au panier";
    btn.value = codeCopy;
    btn.classList.add("btn-style1");
    btn.addEventListener('click', ()=>{
        onAddToCart(btn.value);
    });
    return btn;
}

function onAddToCart(codeCopy) {
    var request = new XMLHttpRequest();
    console.log(codeCopy);  
    request.open("POST", "./handleRequest.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = () => {
        if ( request.readyState == XMLHttpRequest.DONE && request.status == 200 ) {
        }
    };
    request.send("action=addToCart&codeExemplaire="+codeCopy);
}

function titleItem(data, i) {
    var root = document.createElement("li");
    root.innerHTML = data[i].nom;
    var ul = document.createElement("ul");
    data[i].exemplaire.forEach( elt => {
        var li = document.createElement("li");
        var btn = createBtn(elt.code);
        $temp = elt.code;
        console.log($temp);
        var p = document.createElement("p");
        if ( elt.prix != null ) {
            p.innerHTML = elt.nom + ", " + elt.prix + " euros";
        } else {
            p.innerHTML = elt.nom;
        }
        li.append(p);
        li.appendChild(btn);
        ul.appendChild(li);
    });
    root.appendChild(ul);
    return root;
}

function createTableByAction( action, data ) {
    var div;
    var createItem;
    if ( action == "byAuthor" ) {
        div = document.getElementById("list1");
        createItem = authorItem;
    } else if ( action == "byTitle" ) {
        div = document.getElementById("list2");
        createItem = titleItem;
    }
    removeAllChild(div)
    var list = document.createElement("ol");
    for (var i = 0; i < data.length; i++) {
        list.appendChild(createItem(data, i))
    }
    div.appendChild(list);
}

function removeAllChild(node) {
    var child = node.firstChild
    while ( child ) {
        node.removeChild(child)
        child = node.firstChild
    }
}


function generateCart( data ) {
    deletCart();
    var dialog = document.getElementById("dialogCart");
    var table = document.getElementById("tableCart");
    var total = 0;
    data.forEach(element => {
        console.log(element);
        command = document.createElement('tr');

        title = document.createElement('td');
        title.innerHTML = element.titre;

        editor = document.createElement('td');
        editor.innerHTML = element.editeur;

        quantity = document.createElement('td');
        quantity.innerHTML = element.quantite;

        cost = document.createElement('td');
        if ( element.prix == null ) {
            cost.innerHTML = "0€";
        } else {
            cost.innerHTML = element.prix +     "€";
        }

        if ( element.prix != null ) {
            total += parseFloat(element.prix) * parseFloat(element.quantite);
        } 
        
        command.appendChild(title);
        command.appendChild(editor);
        command.appendChild(quantity);
        command.appendChild(cost);

        
        table.appendChild(command);

    });
    var totalPrice = document.getElementById("totalPrice");
    totalPrice.innerHTML = "Total : " + total.toFixed(2) + "€";
    dialog.showModal();

}

function listenQuitButton() {
    var quitButton = document.getElementById("toQuit");
    quitButton.addEventListener('click', ()=>{
        var dialog = document.getElementById("dialogCart");
        dialog.close();
    });
}

function listenCommandButton() {
    var commandButton = document.getElementById("toCommand");
    commandButton.addEventListener('click', ()=>{
        sendSimpleActionRequest("toCommand");
    });
}

function listenDeleteCart() {
    console.log("here");
    var clearCartButton = document.getElementById("emptyCart");
    clearCartButton.addEventListener('click', ()=>{
        sendSimpleActionRequest("EmptyCart");
    });
}


function sendSimpleActionRequest( action ) {
    var request = new XMLHttpRequest();
    request.open("POST", "handleRequest.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = () => {
        if ( request.readyState == XMLHttpRequest.DONE && request.status == 200 ) {
            deletCart();
        }
    };
    request.send("action="+action);
}

function deletCart() {
    var dialog = document.getElementById("dialogCart");
    var table = document.getElementById("tableCart");
    while ( table.firstChild ) {
        table.removeChild( table.firstChild );
    }
    var totalPrice = document.getElementById("totalPrice");
    totalPrice.innerHTML = "Total : 0€";
}