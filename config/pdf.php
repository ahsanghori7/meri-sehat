<?php

return [
    'mode'                     => '',
    'format'                   => 'A4',
    'default_font_size'        => '12',
    'default_font'             => 'sans-serif',
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 10,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'P',
    'title'                    => 'Laravel mPDF',
    'subject'                  => '',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => false,
    'show_watermark_image'     => false,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => '',
    'watermark_image_alpha'    => 0.2,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'custom_font_dir'  => base_path('resources/fonts/'), // don't forget the trailing slash!
    'custom_font_data' => [
        'circularstd_black' => [ // must be lowercase and snake_case
            'R'  => 'circularstd_black.ttf',    // regular font
            'B'  => 'circularstd_black.ttf',       // optional: bold font
            'I'  => 'circularstd_black.ttf',     // optional: italic font
            'BI' => 'circularstd_black.ttf' // optional: bold-italic font
        ],
        'circularstd_bold' => [ // must be lowercase and snake_case
            'R'  => 'circularstd_bold.ttf',    // regular font
            'B'  => 'circularstd_bold.ttf',       // optional: bold font
            'I'  => 'circularstd_bold.ttf',     // optional: italic font
            'BI' => 'circularstd_bold.ttf' // optional: bold-italic font
        ],
        'circularstd_book' => [ // must be lowercase and snake_case
            'R'  => 'circularstd_book.ttf',    // regular font
            'B'  => 'circularstd_book.ttf',       // optional: bold font
            'I'  => 'circularstd_book.ttf',     // optional: italic font
            'BI' => 'circularstd_book.ttf' // optional: bold-italic font
        ],
        'circularstd_medium' => [ // must be lowercase and snake_case
            'R'  => 'circularstd_medium.ttf',    // regular font
            'B'  => 'circularstd_medium.ttf',       // optional: bold font
            'I'  => 'circularstd_medium.ttf',     // optional: italic font
            'BI' => 'circularstd_medium.ttf' // optional: bold-italic font
        ],
        'circularstd_light' => [ // must be lowercase and snake_case
            'R'  => 'circularstd_light.ttf',    // regular font
            'B'  => 'circularstd_light.ttf',       // optional: bold font
            'I'  => 'circularstd_light.ttf',     // optional: italic font
            'BI' => 'circularstd_light.ttf' // optional: bold-italic font
        ],
        // ...add as many as you want.
    ],
    'auto_language_detection'  => false,
    'temp_dir'                 => storage_path('app'),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
];
