document.getElementById("add-car-form").addEventListener("submit", function (e) {
    e.preventDefault();
    
    const vehicleModel = document.getElementById("vehicle-model").value;
    const vehicleNumber = document.getElementById("vehicle-number").value;
    const seatingCapacity = document.getElementById("seating-capacity").value;
    const rentPerDay = document.getElementById("rent-per-day").value;
    const errorMessage = document.getElementById("error-message");

    
    errorMessage.textContent = '';

    const formData = new FormData();
    formData.append("vehicleModel", vehicleModel);
    formData.append("vehicleNumber", vehicleNumber);
    formData.append("seatingCapacity", seatingCapacity);
    formData.append("rentPerDay", rentPerDay);

    
    fetch("add_car.php", {
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
        console.error("Add car error:", error);
        errorMessage.textContent = "An error occurred while adding the car. Please try again later.";
    });
});
