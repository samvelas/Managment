// Get the modal
var modal = document.getElementById('myModal');

var productModal = document.getElementById('productModal');

var actionModal = document.getElementById('actionModal');

var addModal = document.getElementById('addModal');

var btn = document.getElementById("myBtn");

var actionBtn = document.getElementById("actionBtn");

btn.onclick = function() {
    modal.style.display = "block";
}

actionBtn.onclick = function() {
    actionModal.style.display = "block";
}


window.onclick = function(event) {

    if (event.target == productModal) {

        productModal.style.display = "none";
    }

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


function openMarketAtId(id) {
    window.location = "../pages/market.php?marketId=" + id;
}

function calculateTotal(){
    var price = document.getElementById("defaultPrice").value;
    var weight = document.getElementById("weight").value;
    document.getElementById("total").value = price * weight;

}