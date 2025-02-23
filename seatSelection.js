document.addEventListener("DOMContentLoaded", function () {
    const seatMap = document.getElementById("seat-map");
    const totalPriceElem = document.getElementById("total-price");
    let selectedSeats = [];
    let seatPrice = 200; // Default price per seat

    function createSeats(rows, cols) {
        seatMap.innerHTML = "";
        for (let i = 0; i < rows; i++) {
            let rowDiv = document.createElement("div");
            rowDiv.classList.add("seat-row");

            for (let j = 0; j < cols; j++) {
                let seatId = `R${i + 1}-C${j + 1}`;
                let seatDiv = document.createElement("div");
                seatDiv.classList.add("seat");
                seatDiv.dataset.seatId = seatId;
                seatDiv.innerText = seatId;

                // Check if seat is already booked
                if (Math.random() < 0.2) {  // Simulating booked seats (20% of seats)
                    seatDiv.classList.add("booked");
                    seatDiv.style.backgroundColor = "green";
                } else {
                    seatDiv.addEventListener("click", function () {
                        if (!this.classList.contains("booked")) {
                            toggleSeatSelection(this);
                        }
                    });
                }
                
                rowDiv.appendChild(seatDiv);
            }
            seatMap.appendChild(rowDiv);
        }
    }

    function toggleSeatSelection(seat) {
        const seatId = seat.dataset.seatId;
        if (selectedSeats.includes(seatId)) {
            selectedSeats = selectedSeats.filter(s => s !== seatId);
            seat.style.backgroundColor = "";
        } else {
            selectedSeats.push(seatId);
            seat.style.backgroundColor = "blue";
        }
        totalPriceElem.innerText = selectedSeats.length * seatPrice;
    }

    createSeats(10, 25);

    document.getElementById("pay-now").addEventListener("click", function () {
        if (selectedSeats.length === 0) {
            alert("Please select at least one seat.");
            return;
        }
        alert(`You have booked: ${selectedSeats.join(", ")} for ₹${selectedSeats.length * seatPrice}`);
        // Integrate Razorpay here for payment
    });
});
