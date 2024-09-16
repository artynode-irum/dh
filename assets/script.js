document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('submit_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('formResponse').innerText = data;
        this.reset();
    })
    .catch(error => console.error('Error:', error));
});



function fetchMessages(certificateId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_chat_messages.php?id=' + certificateId, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.querySelector('.chat-messages').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

// Fetch messages every 5 seconds
setInterval(function() {
    fetchMessages('<?php echo $certificate_id; ?>');
}, 5000);
