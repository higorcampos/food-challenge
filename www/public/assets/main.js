function init() {
    Swal.fire({
        title: 'Pense em um Prato?',
        showCancelButton: true,
        confirmButtonText: `OK`,
        cancelButtonText: `Cancelar`,
    }).then((result) => {
        if (result.isConfirmed) {
            get_food()
        }
    })
}

function get_food() {
    Swal.fire({
        title: 'Estou Pensando aguarde...',
        showConfirmButton: false
    })
    fetch('/api/food', {
        method: 'GET',
    }).then(function(response) {
        return response.json();
    }).then(function(data) {
        question(data.data)
    });
}


function question($food) {
    Swal.fire({
        title: 'O prato que você pensou e ' + $food + '?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: `Sim`,
        denyButtonText: `Não`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            Swal.fire('Acertei Obaa!', '', 'success')
        } else if (result.isDenied) {
            add_food();
        }
    })
}

function add_food() {
    Swal.fire({
        title: 'Oque você pensou?',
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        showLoaderOnConfirm: true,
        preConfirm: (food) => {

            var formData = new FormData;
            formData.append('food', food);

            return fetch('/api/food', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText)
                }
                return response.json()
            }).catch(error => {
                Swal.showValidationMessage(
                    `Request failed: ${error}`
                )
            })

        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        console.log(result);
        if (result.isConfirmed) {
            Swal.fire({
                title: `${result.value.message}`,
                confirmButtonText: 'Tente de novo',
            })
        }
    })
}