const editButtons = document.querySelectorAll('.edit');
const guardarButtons = document.querySelectorAll('.guardar');
const volverButtons = document.querySelectorAll('.volver');
const inputs = document.querySelectorAll('.form-control');
const eliminarButtons = document.querySelectorAll('.eliminar');

editButtons.forEach(button => {
    button.addEventListener('click', function (event) {
        const buttonId = Number(event.target.id);

        editButtons.forEach(but => {
            but.classList.toggle('d-none', Number(but.id) === buttonId);
        });

        inputs.forEach(input => {
            input.toggleAttribute('disabled', Number(input.id) !== buttonId);
        });

        guardarButtons.forEach(guardar => {
            guardar.classList.toggle('d-none', Number(guardar.id) !== buttonId);
        });

        volverButtons.forEach(volver => {
            volver.classList.toggle('d-none', Number(volver.id) !== buttonId);
        });

        eliminarButtons.forEach(eliminar => {
            eliminar.classList.toggle('d-none', Number(eliminar.id) === buttonId);
        });
    });
});

volverButtons.forEach(button => {
    button.addEventListener('click', function () {
        editButtons.forEach(but => but.classList.remove('d-none'));

        inputs.forEach(input => input.setAttribute('disabled', 'true'));

        guardarButtons.forEach(guardar => guardar.classList.add('d-none'));

        volverButtons.forEach(volver => volver.classList.add('d-none'));

        eliminarButtons.forEach(eliminar => eliminar.classList.remove('d-none'));
    });
});


function validarAuxiliar(input, max) {
    let errorMsg = document.getElementById("error_" + input.id);

    if (parseInt(input.value) > max) {
        input.value = max; // Ajusta al valor máximo
        errorMsg.classList.remove("d-none"); // Muestra el mensaje de error
    } else {
        errorMsg.classList.add("d-none"); // Oculta el mensaje si el valor es válido
    }
}
