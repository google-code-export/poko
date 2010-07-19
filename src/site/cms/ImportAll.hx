/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tony Polinelli <tonyp@touchmypixel.com> 
 * Contributers: Tarwin Stroh-Spijer 
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *   - Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   - Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE TOUCH MY PIXEL & CONTRIBUTERS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE TOUCH MY PIXEL & CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 */

package site.cms;

#if php

/* import  all CMS pages */

import site.cms.Index;
import site.cms.Home;

import site.cms.modules.base.Datasets;
import site.cms.modules.base.DatasetsLink;
import site.cms.modules.base.Dataset;
import site.cms.modules.base.DatasetItem;
import site.cms.modules.base.Definitions;
import site.cms.modules.base.Definition;
import site.cms.modules.base.DefinitionElement;
import site.cms.modules.base.Pages;
import site.cms.modules.base.Users;
import site.cms.modules.base.User;
import site.cms.modules.base.Users_Groups;
import site.cms.modules.base.Users_Group;
import site.cms.modules.base.SiteView;

import site.cms.modules.help.Help;

import site.cms.services.Image;
import site.cms.services.Csv;
import site.cms.services.CmsCss;

import site.cms.modules.media.Index;
import site.cms.modules.media.Gallery;
import site.cms.modules.media.Galleries;
import site.cms.modules.media.MediaSelector;

import site.cms.modules.base.Settings;
import site.cms.modules.base.SettingsTheme;

// procedures
import site.cms.common.procedures.CategoryProcedure;

#elseif js

import site.cms.js.JsCommon;
import site.cms.modules.base.js.JsKeyValueInput;
import site.cms.modules.base.js.JsFileUpload;
import site.cms.modules.base.js.JsDefinitionElement;
import site.cms.modules.base.js.JsDatasetItem;
import site.cms.modules.base.js.JsDataset;
import site.cms.modules.base.js.JsDefinition;
import site.cms.modules.base.js.JsSiteView;

import site.cms.modules.media.js.JsGallery;
import site.cms.modules.media.js.JsMediaSelector;

import site.cms.js.JsTest;

#end

class ImportAll { }