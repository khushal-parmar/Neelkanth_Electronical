<?php 
require_once 'config/db.php';
include 'includes/header.php'; 
?>

<div class="bg-light py-5 text-center mb-5 border-bottom">
    <div class="container">
        <h1 class="fw-bold">Our Professional Services</h1>
        <p class="lead text-muted">Reliable & Safe Electrical Services for Home and Business</p>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://picsum.photos/400/250?random=1" class="card-img-top" alt="Wiring">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Complete House Wiring</h5>
                    <p class="card-text">Professional conceal and surface electrical wiring for new buildings, houses, and offices.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://picsum.photos/400/250?random=2" class="card-img-top" alt="Maintenance">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Repair & Maintenance</h5>
                    <p class="card-text">Quick short-circuit troubleshooting, fuse fixes, MCB replacements, and switch repair services.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://picsum.photos/400/250?random=3" class="card-img-top" alt="Appliance Care">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Appliance Installation</h5>
                    <p class="card-text">Installation and setup for Geysers, Ceiling Fans, Water Pumps, and Inverter systems.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-primary text-white p-5 rounded mt-5 text-center shadow">
        <h3>Need an Electrician Right Now?</h3>
        <p class="lead">Contact us today to get a quote or book an expert technician.</p>
        <a href="contact.php" class="btn btn-warning btn-lg fw-bold">Get In Touch</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>