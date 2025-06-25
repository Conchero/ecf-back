




document.addEventListener('DOMContentLoaded', function () {
    let currentErrorMessage = null;
    const inputs = document.querySelectorAll(".reservation");
    const errorMessage = document.querySelectorAll(".error-message");

    const errorMessageArray = [];
    
    const ErrorMessageToDOM = ((el,remove) => {
        if (errorMessageArray.length> 0 && errorMessageArray.find(el) === remove)
        {
          remove ? errorMessageArray.remove(el) : errorMessageArray.push(el);
        }
    })

    const compareToExistingReservation = (stringToCompare => {

        errorMessage.forEach(el => {
            console.log(el);
            const dateToCompare = el.querySelectorAll(".reservation-date");


            let reservationArray = {
                start: "",
                end: ""
            }


            dateToCompare.forEach(date => {
                reservationArray.start = date.getAttribute("data-reservation-start").split("-").join("")
                reservationArray.end = date.getAttribute("data-reservation-end").split("-").join("")
            })

            console.log(reservationArray);

            const inputToCompare = parseInt(stringToCompare.split("-").join(""));
            const start = parseInt(reservationArray.start);
            const end = parseInt(reservationArray.end);

        })
    })



    inputs.forEach(el => {
        el.addEventListener("change", (e) => {
            compareToExistingReservation(e.target.value);
        })
    })

});