/**
 * ...
 * @author Tony Polinelli
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

import site.cms.modules.help.Help;

import site.cms.services.Image;


class ImportAll { }

#elseif js

import site.cms.js.JsCommon;
import site.cms.modules.base.js.JsKeyValueInput;
import site.cms.modules.base.js.JsDefinitionElement;
import site.cms.modules.base.js.JsDatasetItem;
import site.cms.modules.base.js.JsDataset;
import site.cms.modules.base.js.JsDefinition;
import site.cms.js.JsTest;

#end