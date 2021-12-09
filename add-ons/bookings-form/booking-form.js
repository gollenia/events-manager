import React from "react";
import ReactDOM from "react-dom";
import Booking from './Booking.jsx';
import { openBookingModal } from "./Booking.jsx";
const root = document.getElementById('booking_app');

document.addEventListener('click', (event) => {
  if(!event.target.classList.contains("open--booking")) return;
  openBookingModal();
})

if(root) {
document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Booking />,
     root
    );
  })
}