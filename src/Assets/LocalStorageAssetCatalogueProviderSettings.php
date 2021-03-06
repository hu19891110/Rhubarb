<?php

/**
 * Copyright (c) 2017 RhubarbPHP.
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

namespace Rhubarb\Crown\Assets;

use Rhubarb\Crown\Settings;

/**
 * Settings relevant to the LocalStorageAssetCatalogueProvider
 *
 * @see LocalStorageAssetCatalogueProvider
 */
class LocalStorageAssetCatalogueProviderSettings extends Settings
{
    /**
     * The path to the storage root folder.
     * 
     * @var string
     */
    public $storageRootPath;

    /**
     * To provide public URLs for assets you should give the URL that points to the storage root folder.
     *
     * Use with caution - public URLs should not be enabled if the catalogue ccntains uploads you would rather
     * keep private.
     * 
     * @var string
     */
    public $rootUrl;
}