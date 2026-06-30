<?php

return [
    'attendance' => [
        'success' => [
            'title' => 'Attendance success!',
            'description' => 'Attendance for :name at :checkin_time was successful!'
        ],
        'expired' => [
            'title' => 'Attendance expired!',
            'description' => 'Attendance expired, plase login again!'
        ],
        'error' => [
            'title' => 'Attendance error!',
            'description' => 'Attendance error, please try again later!'
        ],
        'invalid' => [
            'title' => 'Attendance invalid!',
            'description' => 'Attendance invalid, you have been detected outside the branch. Please get closer!'
        ]
    ]
];
