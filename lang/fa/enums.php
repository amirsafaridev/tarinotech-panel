<?php

use App\Enums\Database\Exam\ExamType;
use App\Enums\Database\WordReport\EWordReportReason;

return [
    ExamType::class => [
        ExamType::Normal => 'عادی',
        ExamType::Timer => 'تایمر',
    ],
    EWordReportReason::class => [
        EWordReportReason::WrongWord => 'لغت اشتباه است',
        EWordReportReason::WrongTranslate => 'ترجمه صحیح نیست',
        EWordReportReason::Useless => 'بدون استفاده',
        EWordReportReason::WrongPronunciation => 'تلفظ صحیح نیست',
    ],
];
