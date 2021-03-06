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

namespace Rhubarb\Crown\ResponseFilters;

/**
 * The base class for all response filters.
 *
 * Response filters take a response generated by the normal rendering pipeline and
 * applies a filter to it to modify the response in some way.
 */
abstract class ResponseFilter
{
    /**
     * Filter the response and return the modified version.
     *
     * @param $response
     * @return mixed
     */
    public function processResponse($response)
    {
        return $response;
    }
}
