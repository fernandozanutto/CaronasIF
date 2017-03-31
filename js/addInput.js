var counter = 1;

var limit = 3;

function addInput(divName) {

    if (counter == limit) {

        alert("Atingiu o quantidade máxima de números de telefone: " + counter);

    } else {
        counter++;

        var newdiv = document.createElement('div');

        newdiv.innerHTML = "<br><input type='text' class='form-control' name='telefones[]' placeholder='Telefone " + counter + "'>";

        document.getElementById(divName).appendChild(newdiv);


    }

}
