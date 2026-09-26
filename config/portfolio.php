<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Guest PDF Download Authorization
    |--------------------------------------------------------------------------
    |
    | When set to true, public visitors are allowed to download or preview the
    | student's portfolio PDF. When false, only the student owner and admin
    | users can access the PDF endpoints.
    |
    */
    'allow_guest_pdf_download' => env('PORTFOLIO_ALLOW_GUEST_PDF', true),

    /*
    |--------------------------------------------------------------------------
    | Default PDF Theme
    |--------------------------------------------------------------------------
    |
    | Defines the default stylesheet and layout theme template folder to be used
    | when generating PDFs (under resources/views/pdf/{theme_name}).
    |
    */
    'default_pdf_theme' => env('PORTFOLIO_DEFAULT_PDF_THEME', 'default'),
];
