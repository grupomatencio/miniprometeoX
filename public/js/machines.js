const editButtons = document.querySelectorAll('.edit');
const guardarButtons = document.querySelectorAll('.guardar');
const volverButtons = document.querySelectorAll('.volver');
const inputs = document.querySelectorAll('.form-control');
const eliminarButtons = document.querySelectorAll('.eliminar');

editButtons.forEach(button => {
    button.addEventListener('click', function () {
        const buttonId = Number(event.target.id);

        console.log('check', buttonId);
        editButtons.forEach(but => {
            if (Number(but.id) == buttonId) {
                but.classList.add('d-none');
                console.log('yyy');
            } else {
                but.classList.remove('d-none');
            }
        })
        inputs.forEach(input => {
            if (Number(input.id) == buttonId) {
                input.removeAttribute('disabled');
            } else {
                input.setAttribute('disabled', 'true');
            }
        })
        guardarButtons.forEach(guardar => {
            if (Number(guardar.id) == buttonId) {
                guardar.classList.remove('d-none');
            } else {
                guardar.classList.add('d-none');
            }
        })

        volverButtons.forEach(volver => {
            if (Number(volver.id) == buttonId) {
                volver.classList.remove('d-none');
            } else {
                volver.classList.add('d-none');
            }
        })

        eliminarButtons.forEach(eliminar => {
            if (Number(eliminar.id) == buttonId) {
                eliminar.classList.add('d-none');
            } else {
                eliminar.classList.remove('d-none');
            }
        })

    })
})

volverButtons.forEach(button => {
    button.addEventListener('click', function () {

        editButtons.forEach(but => {
            but.classList.remove('d-none');
        })
        inputs.forEach(input => {
            input.setAttribute('disabled', 'true');
        })
        guardarButtons.forEach(guardar => {
            guardar.classList.add('d-none');
        })

        volverButtons.forEach(volver => {
            volver.classList.add('d-none');
        })

        eliminarButtons.forEach(eliminar => {
            eliminar.classList.remove('d-none');
        })
    })
})
