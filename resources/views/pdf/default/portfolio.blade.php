<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portfolio - {{ $mahasiswa->nama_lengkap }}</title>
    <style>
        /* PORTFOLIO PDF - PRINT-FIRST PROFESSIONAL REDESIGN */

        :root {
            --color-primary: #198754;
            --color-text-dark: #2c3e50;
            --color-text-medium: #495057;
            --color-text-light: #6c757d;
            --color-border: #e9ecef;
            --color-bg-light: #f8f9fa;
            --color-white: #ffffff;
            --spacing-xs: 3px;
            --spacing-sm: 6px;
            --spacing-md: 12px;
            --spacing-lg: 15px;
            --spacing-xl: 20px;
        }

        @page {
            margin: 1.5cm 1.5cm 2.0cm 1.5cm;
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: var(--color-text-dark);
            margin: 0;
            padding: 0;
        }

        footer {
            position: fixed;
            bottom: -1.2cm;
            left: 0;
            right: 0;
            height: 1.0cm;
            text-align: center;
            font-size: 8px;
            color: var(--color-text-light);
            border-top: 1px solid var(--color-border);
            padding-top: 5px;
            width: 100%;
            background-color: var(--color-white);
        }

        .page-number:before {
            content: "Halaman " counter(page);
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
            font-weight: bold;
            color: var(--color-text-dark);
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: var(--color-text-dark);
            border-bottom: 3px solid var(--color-primary);
            padding-bottom: 8px;
            margin: 20px 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            page-break-after: avoid;
        }

        .main-layout {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .left-column {
            width: 33%;
            padding-right: 20px;
            border-right: 1px solid var(--color-border);
            vertical-align: top;
        }

        .right-column {
            width: 67%;
            padding-left: 20px;
            vertical-align: top;
        }

        /* PROJECT CARD REDESIGN - Professional Case Study Style */
        .project-card {
            page-break-inside: avoid;
            margin-bottom: 20px;
            border: none;
            border-left: 4px solid var(--color-primary);
            padding-left: 15px;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .project-title {
            font-size: 12px;
            font-weight: bold;
            color: var(--color-text-dark);
            margin: 0 0 6px 0;
        }

        .project-category {
            font-size: 8px;
            color: var(--color-primary);
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 8px 0;
        }

        .project-description {
            font-size: 10px;
            color: var(--color-text-medium);
            line-height: 1.5;
            margin: 0 0 10px 0;
        }

        .project-tech {
            font-size: 9px;
            color: var(--color-text-light);
            margin: 8px 0;
            font-style: italic;
        }

        .project-links {
            font-size: 8px;
            margin-top: 8px;
            color: var(--color-text-medium);
        }

        .project-links a {
            color: var(--color-primary);
            text-decoration: none;
            margin-right: 12px;
        }

        a {
            color: var(--color-primary);
            text-decoration: none;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            background-color: var(--color-bg-light);
            border: 1px solid var(--color-border);
            color: var(--color-text-medium);
            border-radius: 3px;
            margin-right: 3px;
            margin-bottom: 3px;
            font-size: 8px;
        }

        .badge-success {
            background-color: #e8f5e9;
            border-color: #c8e6c9;
            color: #2e7d32;
        }

        p {
            margin: 0 0 12px 0;
            line-height: 1.4;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        /* HEADER REDESIGN */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--color-primary);
            padding-bottom: 15px;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
            color: var(--color-text-dark);
            margin: 0;
            line-height: 1.1;
        }

        .student-meta {
            font-size: 9px;
            color: var(--color-text-light);
            margin: 4px 0 0 0;
        }

        .score-badge {
            border: 1px solid var(--color-border);
            border-radius: 4px;
            background-color: var(--color-bg-light);
            padding: 8px 10px;
            text-align: center;
            min-width: 90px;
        }

        .score-label {
            font-size: 6px;
            text-transform: uppercase;
            color: var(--color-text-light);
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }

        .score-value {
            font-size: 18px;
            font-weight: bold;
            color: var(--color-primary);
            display: block;
        }

        .score-text {
            font-size: 6px;
            color: var(--color-text-medium);
            display: block;
            margin-top: 2px;
        }

        /* SECTION STYLING */
        .section-content {
            margin-bottom: 15px;
        }

        .about-text {
            font-size: 10px;
            color: var(--color-text-medium);
            text-align: justify;
            line-height: 1.5;
            margin: 0 0 12px 0;
        }

        .contact-item {
            margin-bottom: 10px;
            font-size: 9px;
        }

        .contact-label {
            font-weight: bold;
            color: var(--color-text-dark);
            display: block;
            margin-bottom: 2px;
        }

        .contact-value {
            color: var(--color-text-medium);
        }

        .skills-list {
            margin-top: 8px;
        }

        .stats-table {
            font-size: 9px;
            width: 100%;
        }

        .stats-table tr {
            border-bottom: 1px solid var(--color-border);
        }

        .stats-table td {
            padding: 6px 0;
            color: var(--color-text-medium);
        }

        .stats-label {
            font-weight: bold;
            color: var(--color-text-dark);
        }

        .stats-value {
            text-align: right;
            font-weight: bold;
            color: var(--color-primary);
        }

        /* CERTIFICATES & ACHIEVEMENTS */
        .cert-table {
            font-size: 8px;
            width: 100%;
        }

        .cert-table thead tr {
            background-color: var(--color-bg-light);
            border-bottom: 1px solid var(--color-border);
        }

        .cert-table th {
            padding: 6px;
            text-align: left;
            color: var(--color-text-medium);
            font-weight: bold;
        }

        .cert-table td {
            padding: 5px 6px;
            border-bottom: 1px solid #f1f3f5;
            color: var(--color-text-medium);
        }

        /* QR SECTION */
        .qr-section {
            page-break-inside: avoid;
            margin-top: 20px;
            text-align: center;
            padding: 15px;
            border: 1px solid var(--color-border);
            border-radius: 4px;
            background-color: var(--color-bg-light);
        }

        .qr-title {
            font-size: 10px;
            color: var(--color-text-medium);
            margin: 0 0 10px 0;
            font-style: italic;
        }

        .qr-url {
            font-size: 7px;
            color: var(--color-text-light);
            margin-top: 8px;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .mb-3 { margin-bottom: 15px; }
        .mb-4 { margin-bottom: 20px; }

    </style>
</head>
<body>

    <!-- Professional Header with Large Student Name -->
    @include('pdf.default.partials.header')

    <!-- Main Two-Column Layout -->
    <table class="main-layout">
        <tr>
            <!-- Left Column -->
            <td class="left-column" valign="top">
                @include('pdf.default.partials.about')
                @include('pdf.default.partials.skills')
                @include('pdf.default.partials.statistics')
            </td>

            <!-- Right Column -->
            <td class="right-column" valign="top">
                @include('pdf.default.partials.projects')
                @include('pdf.default.partials.certificates')
                @include('pdf.default.partials.achievements')
                @include('pdf.default.partials.qr')
            </td>
        </tr>
    </table>

    @include('pdf.default.partials.footer')

</body>
</html>
