document.addEventListener('DOMContentLoaded', function () {
    const addProduct = document.getElementById('add-product-form');
    const editProduct = document.querySelectorAll('.edit-product-form');
    const deletePackage = document.querySelectorAll('.delete-product-form');

    function handleFormSubmission(event) 
    {
        const form = event.target;
        event.preventDefault();

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })

            .then((response) => response.json())
            .then((data) => {
                if (data.status == true) {
                    location.reload();
                }
                else
                {
                    alert(data.message)
                    console.log(data.error)
                }
            })
    }

    addProduct.addEventListener('submit', handleFormSubmission);

    editProduct.forEach((form) => {
        form.addEventListener('submit', handleFormSubmission);
    });

    deletePackage.forEach((form) => {
        form.addEventListener('submit', handleFormSubmission);
    });
});





