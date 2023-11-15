document.addEventListener('DOMContentLoaded', function () {
    const addToCart = document.querySelectorAll('.add-to-cart');

    function cartMessage(this_)
    {
        console.log('works')
    }

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
                    // window.location.href = '/my-cart';
                    console.log(data.message)
                }
                else
                {
                    console.log(data.error)
                    location.reload();
                }
            })

            .catch((error) => {
                alert("An error occured, please try again later.");
                console.log("Error: ", error)
            })
    }

    addToCart.forEach((form) => {
        form.addEventListener('submit', handleFormSubmission);
    });

    window.cartMessage = cartMessage
});