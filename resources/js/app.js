import popperJs from "https://cdn.jsdelivr.net/npm/popper.js@1.16.1/+esm";
const popoverTriggerList = document.querySelectorAll(
    '[data-bs-toggle="popover"]'
);
const popoverList = [...popoverTriggerList].map(
    (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
);
import * as bootstrap from "bootstrap";
import "./bootstrap";
