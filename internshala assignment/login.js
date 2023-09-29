document.getElementById("login-form").addEventListener("submit", function (e) {
    e.preventDefault();
    
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const errorMessage = document.getElementById("error-message");

    
    errorMessage.textContent = '';
   
    const formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);

    
    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = "dashboard.html";
        } else {
            errorMessage.textContent = data.message;
        }
    })
    .catch(error => {
        console.error("Login error:", error);
        errorMessage.textContent = "An error occurred during login. Please try again later.";
    });
});
