// Get the modal
var modal = document.getElementById('myModal');

var actionModal = document.getElementById('actionModal');

var addModal = document.getElementById('addModal');

var btn = document.getElementById("myBtn");

var actionBtn = document.getElementById("actionBtn");

btn.onclick = function() {
    modal.style.display = "block";
}


window.onclick = function(event) {

    if (event.target == modal) {

        modal.style.display = "none";
    }

    if (event.target == actionModal) {

        actionModal.style.display = "none";
    }

    if (event.target == addModal) {

        addModal.style.display = "none";
    }

}

actionBtn.onclick = function() {
    actionModal.style.display = "block";
}


function openMarketAtId(id) {
    window.location = "../pages/market.php?marketId=" + id;
}

function calculateTotal(){
    var price = document.getElementById("defaultPrice").value;
    var weight = document.getElementById("weight").value;
    document.getElementById("total").value = price * weight;

}

function changedStateOfProductAtId(id) {
    var elementId = "" + id;
    var action = 0;

    var element = document.getElementById(elementId);

    if(element.style.backgroundColor == "") {
        element.style.backgroundColor = "#CCE7F4";
        action = 1;
    } else {
        element.style.backgroundColor = "";
    }

    var ulChildren = document.getElementById('products');

    var idArray = [];
    var childrenLength = ulChildren.length;

    for(var i = 0; i < childrenLength; i++){
        idArray.push(ulChildren[i].id);
    }

    console.log(ulChildren);
}