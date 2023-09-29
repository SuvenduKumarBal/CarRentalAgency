document.getElementById("registration-form").addEventListener("submit", function (e) {
    e.preventDefault();
    
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const errorMessage = document.getElementById("error-message");

    errorMessage.textContent = '';

    const formData = new FormData();
    formData.append("name", name);
    formData.append("email", email);
    formData.append("password", password);

    fetch("register.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
         window.location.href = "login.html";
        } else {
           errorMessage.textContent = data.message;
        }
    })
    .catch(error => {
        console.error("Registration error:", error);
        errorMessage.textContent = "An error occurred during registration. Please try again later.";
    });
});
