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

namespace Rhubarb\Crown\Exceptions;

/**
 * Exception thrown when an expected setting cannot be found
 */
class SettingMissingException extends RhubarbException
{
    /**
     * @param string $class
     * @param string $settingName
     */
    public function __construct($class, $settingName)
    {
        parent::__construct("The setting $class.$settingName has not been set.");
    }
}
