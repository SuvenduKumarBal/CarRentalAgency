document.getElementById("rent-car-form").addEventListener("submit", function (e) {
    e.preventDefault();
    
    const selectedCar = document.getElementById("car-dropdown").value;
    const startDate = document.getElementById("start-date").value;
    const numberOfDays = document.getElementById("number-of-days").value;
    const errorMessage = document.getElementById("error-message");


    errorMessage.textContent = '';

    const formData = new FormData();
    formData.append("selectedCar", selectedCar);
    formData.append("startDate", startDate);
    formData.append("numberOfDays", numberOfDays);

    
    fetch("rent_car.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            
            window.location.href = "booking_confirmation.html";
        } else {
            
            errorMessage.textContent = data.message;
        }
    })
    .catch(error => {
        console.error("Rent car error:", error);
        errorMessage.textContent = "An error occurred while processing your request. Please try again later.";
    });
});
