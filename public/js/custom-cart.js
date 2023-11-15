document.addEventListener('DOMContentLoaded', function () {

    const updateCartForm = document.querySelectorAll('.update-cart-form');
    const removeFromCart = document.querySelectorAll('.remove-from-cart');

    function handleFormSubmission(event) 
    {
        const form = event.target;
        event.preventDefault();

        var removeBtn = $(this).find('.remove-btn');
        removeBtn.addClass('hidden');

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
                    alert(data.message);
                    location.reload();
                }
                else
                {
                    alert(data.message)
                    console.log(data.error)
                }
            })
    }

    updateCartForm.forEach((form) => {
        form.addEventListener('submit', handleFormSubmission);
    });

    removeFromCart.forEach((form) => {
        form.addEventListener('submit', handleFormSubmission);
    })
});





