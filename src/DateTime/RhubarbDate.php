<?php

/**
 * Copyright (c) 2016 RhubarbPHP.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Rhubarb\Crown\DateTime;

use DateTimeZone;

class RhubarbDate extends RhubarbDateTime
{
    public function __construct($dateValue = '', DateTimeZone $timezone = null)
    {
        // Use the parent constructor to parse all accepted date formats
        parent::__construct($dateValue, $timezone);

        $this->setTimezone(new DateTimeZone(date_default_timezone_get()));

        // Use the parent constructor again with the parsed date, dropping the time element
        parent::__construct($this->format('Y-m-d 00:00:00'), $timezone);
    }

    public function setTime($hour, $minute, $second = 0)
    {
        return parent::setTime(0, 0, 0);
    }

    public static function createFromFormat($format, $time, $timezone = null)
    {
        $date = parent::createFromFormat($format, $time, $timezone);
        return new RhubarbDate($date);
    }
}
