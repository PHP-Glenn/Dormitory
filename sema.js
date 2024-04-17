const form = document.querySelector('#messageform');

function sendmessage(event) {
    event.preventDefault();

    const apikey = document.querySelector('#apikey').value;
    const number = document.querySelector('#number').value;
    const message = document.querySelector('#message').value;

    const parameters = {
        apikey,
        number,
        message,
    };

    fetch('https://api.semaphore.co/api/v4/messages', {
        method: 'POST',
        headers: {
            'Content-type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(parameters),
    })
        .then(response => response.text())
        .then(output => {
            console.log(output);
        })
        .catch(error => {
            console.error(error);
        });

    form.reset();
}

form.addEventListener('submit', sendmessage);
