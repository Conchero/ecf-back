// // commonjs
// const flatpickr = require("flatpickr");

// es modules are recommended, if available, especially for typescript
import flatpickr from "flatpickr";


const reservationStart = document.querySelector(".reservation-date.start");

// If using flatpickr in a framework, its recommended to pass the element directly
flatpickr(reservationStart, {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
});


console.log(reservationStart);