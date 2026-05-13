<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Official Barangay Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --brand: #14532d;
            --brand-2: #0f766e;
            --ink: #172026;
            --muted: #667085;
            --soft: #f3f7f4;
            --line: #d7e2dc;
        }

        body {
            color: var(--ink);
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .top-strip {
            background: var(--brand);
            color: #fff;
            font-size: .9rem;
        }

        .navbar {
            border-bottom: 1px solid var(--line);
        }

        .brand-logo {
            height: 46px;
            width: auto;
        }

        .hero {
            min-height: 78vh;
            display: flex;
            align-items: center;
            background:
                linear-gradient(rgba(10, 35, 28, .78), rgba(10, 35, 28, .78)),
                url('{{ asset("Barangay Information System logo design.png") }}') center right 12% / contain no-repeat,
                linear-gradient(135deg, #14532d, #0f766e);
            color: #fff;
            padding: 130px 0 70px;
        }

        .hero h1 {
            font-size: clamp(2.2rem, 5vw, 4.5rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: 0;
        }

        .hero p {
            max-width: 720px;
            color: rgba(255,255,255,.88);
        }

        .section {
            padding: 72px 0;
        }

        .section-soft {
            background: var(--soft);
        }

        .stat {
            border-left: 4px solid var(--brand-2);
            padding: 18px 20px;
            background: #fff;
            height: 100%;
        }

        .stat strong {
            display: block;
            font-size: 2rem;
            line-height: 1;
            color: var(--brand);
        }

        .service-card {
            border: 1px solid var(--line);
            border-radius: 8px;
            height: 100%;
            background: #fff;
        }

        .portal-entry {
            border: 1px solid rgba(255,255,255,.28);
            background: rgba(255,255,255,.1);
            border-radius: 8px;
            padding: 16px;
            max-width: 720px;
        }

        .service-icon {
            height: 44px;
            width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #e6f4ef;
            color: var(--brand);
        }

        .btn-brand {
            background: var(--brand);
            color: #fff;
            border: 1px solid var(--brand);
        }

        .btn-brand:hover {
            background: #0b3b20;
            color: #fff;
        }

        .btn-outline-brand {
            border-color: var(--brand);
            color: var(--brand);
        }

        .btn-outline-brand:hover {
            background: var(--brand);
            color: #fff;
        }

        .event-row {
            border-bottom: 1px solid var(--line);
            padding: 16px 0;
        }

        .event-row:last-child {
            border-bottom: 0;
        }

        footer {
            background: #10231c;
            color: rgba(255,255,255,.82);
        }
    </style>
</head>
<body>
    <div class="top-strip py-2">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span><i class="fas fa-location-dot me-2"></i>Barangay Hall, Purok 1, Barangay Poblacion</span>
            <span><i class="fas fa-clock me-2"></i>Office Hours: Monday to Friday, 8:00 AM - 5:00 PM</span>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="#home">
                <img src="{{ asset('Barangay Information System logo design.png') }}" alt="Barangay seal" class="brand-logo">
                <span>{{ config('app.name') }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#activities">Activities</a></li>
                    <li class="nav-item"><a class="nav-link" href="#officials">Officials</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item ms-lg-3"><a class="btn btn-outline-brand" href="{{ route('admin.login') }}">Admin Login</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-brand" href="{{ route('resident.login') }}">Resident Portal</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="home" class="hero">
        <div class="container">
            <div class="col-lg-8">
                <p class="text-uppercase fw-bold mb-3">Republic of the Philippines Barangay Portal</p>
                <h1>Barangay Information System</h1>
                <p class="lead mt-4 mb-4">
                    A digital records and services platform for resident profiling, household monitoring,
                    document requests, payments, health visits, events, blotter cases, and official reports.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.login') }}" class="btn btn-light btn-lg">Admin / Staff Login</a>
                    <a href="{{ route('resident.login') }}" class="btn btn-outline-light btn-lg">Resident Portal</a>
                    <a href="#services" class="btn btn-outline-light btn-lg">View Services</a>
                </div>
                <div class="portal-entry mt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-user-shield me-2"></i>Barangay Operations</strong>
                            <div class="small text-white-50">For officials and staff managing residents, records, payments, blotters, reports, and services.</div>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-id-card me-2"></i>Resident Self-Service</strong>
                            <div class="small text-white-50">For residents checking profiles, submitting document requests, and joining barangay activities.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-soft">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <div class="stat">
                        <strong>{{ number_format($stats['residents']) }}</strong>
                        <span>Registered Residents</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat">
                        <strong>{{ number_format($stats['households']) }}</strong>
                        <span>Households</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat">
                        <strong>{{ number_format($stats['puroks']) }}</strong>
                        <span>Puroks</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat">
                        <strong>PHP {{ number_format($stats['collections'], 2) }}</strong>
                        <span>Recorded Collections</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="section">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <h2 class="fw-bold mb-2">Barangay Services</h2>
                    <p class="text-muted mb-0">Common certificates and frontline services managed through the system.</p>
                </div>
                <a href="{{ route('resident.login') }}" class="btn btn-outline-brand">Request Through Portal</a>
            </div>

            <div class="row g-3">
                @forelse($documentTypes as $type)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card p-4">
                            <span class="service-icon mb-3"><i class="fas fa-file-signature"></i></span>
                            <h5 class="fw-bold">{{ $type->name }}</h5>
                            <p class="text-muted">{{ $type->description }}</p>
                            <div class="d-flex justify-content-between small">
                                <span>Fee: PHP {{ number_format($type->fee, 2) }}</span>
                                <span>{{ $type->processing_days }} day{{ $type->processing_days == 1 ? '' : 's' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info mb-0">No document types have been encoded yet.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="activities" class="section section-soft">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <h2 class="fw-bold mb-3">Upcoming Barangay Activities</h2>
                    <div class="bg-white p-4 border rounded-2">
                        @forelse($upcomingEvents as $event)
                            <div class="event-row">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <h5 class="mb-1">{{ $event->title }}</h5>
                                        <p class="text-muted mb-1">{{ $event->location }}</p>
                                        <small>{{ $event->event_date->format('F d, Y') }} | {{ date('g:i A', strtotime($event->start_time)) }}</small>
                                    </div>
                                    <span class="badge text-bg-success align-self-start">{{ $event->status }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No upcoming activities have been posted yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="col-lg-5">
                    <h2 class="fw-bold mb-3">System Coverage</h2>
                    <div class="bg-white p-4 border rounded-2">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>Document Types</span>
                            <strong>{{ number_format($stats['documents']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>Released Documents</span>
                            <strong>{{ number_format($stats['released_documents']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>Upcoming/Ongoing Events</span>
                            <strong>{{ number_format($stats['upcoming_events']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>Active Blotter Cases</span>
                            <strong>{{ number_format($stats['active_blotters']) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="officials" class="section">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <img src="{{ asset('Barangay Information System logo design.png') }}" alt="Barangay seal" class="img-fluid" style="max-height: 360px;">
                </div>
                <div class="col-lg-7">
                    <h2 class="fw-bold mb-3">Barangay Administration</h2>
                    <p class="text-muted">
                        The system supports the Punong Barangay, Sangguniang Barangay, Barangay Secretary,
                        Barangay Treasurer, SK, Tanod, Health Center staff, and authorized personnel in daily operations.
                    </p>
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Punong Barangay</strong><br><span class="text-muted">Hon. Juan Dela Cruz</span></div>
                        <div class="col-md-6"><strong>Barangay Secretary</strong><br><span class="text-muted">Ana Reyes</span></div>
                        <div class="col-md-6"><strong>Barangay Treasurer</strong><br><span class="text-muted">Pedro Ramos</span></div>
                        <div class="col-md-6"><strong>Barangay Tanod Desk</strong><br><span class="text-muted">Peace and Order Response</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="section section-soft">
        <div class="container">
            <h2 class="fw-bold mb-4">Contact and Office Information</h2>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="service-card p-4">
                        <span class="service-icon mb-3"><i class="fas fa-map-marker-alt"></i></span>
                        <h5>Barangay Hall</h5>
                        <p class="text-muted mb-0">Purok 1, Main Street, Barangay Poblacion</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card p-4">
                        <span class="service-icon mb-3"><i class="fas fa-phone"></i></span>
                        <h5>Hotline</h5>
                        <p class="text-muted mb-0">0917 123 4567<br>Emergency and tanod response desk</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card p-4">
                        <span class="service-icon mb-3"><i class="fas fa-envelope"></i></span>
                        <h5>Email</h5>
                        <p class="text-muted mb-0">info@barangaybis.gov.ph<br>Response within office hours</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
            <span>Official Barangay Records and Services Portal</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
