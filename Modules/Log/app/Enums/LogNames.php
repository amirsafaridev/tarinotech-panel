<?php

namespace Modules\Log\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class LogNames extends Enum implements LocalizedEnum
{
    const BLOG = 'blog';

    const BLOG_CATEGORY = 'blog_category';

    const ADDRESS = 'address';

    const KNOW_LEDGE_WAY = 'know_ledge_way';

    const COMMUNICATION = 'communication';

    const USER_COMMUNICATION = 'user_communication';

    const ADMIN = 'admin';

    const COMPANY = 'company';

    const FACILITY = 'facility';

    const FACTOR = 'factor';

    const FACTOR_META = 'factor_meta';

    const FACTOR_ITEM = 'factor_item';

    const FREE_DAY = 'free_day';

    const LOGIN = 'login';

    const OTP_CODE = 'otp_code';

    const PACKAGE = 'package';

    const PACKAGE_PRICE = 'package_price';

    const PROJECT = 'project';

    const PROJECT_ADS = 'project_ads';

    const PROJECT_BASE = 'project_base';

    const PROJECT_FACILITY = 'project_facility';

    const PROJECT_SEO = 'project_seo';

    const PROJECT_STATUS = 'project_status';

    const PROJECT_TYPE = 'project_type';

    const PROJECT_WEB = 'project_web';

    const BUSINESS_DOMAIN = 'business_domain';

    const SALE_GOAL = 'sale_goal';

    const SAMPLE_MESSAGE = 'sample_message';

    const SETTING = 'setting';

    const TRANSACTION_CATEGORY = 'transaction_category';

    const USER = 'user';

    const DEFAULT = 'default';
}
