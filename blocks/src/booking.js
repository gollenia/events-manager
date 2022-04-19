import React from "react";
import ReactDOM from "react-dom";
import Booking from './booking/frontend/index.js';
import { openBookingModal } from "./booking/frontend/index.js";
const root = document.getElementById('booking_app');

document.addEventListener('click', (event) => {
  if(!event.target.classList.contains("open--booking")) return;
  openBookingModal();
})

if(root) {
console.log(root)
document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Booking />,
     root
    );
  })
}

