/*
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

if (!window.rhubarb) {
    window.rhubarb = {};
}

if (!window.rhubarb.validation) {
    window.rhubarb.validation = {};
    window.rhubarb.validation.Scrolled = false;
}

window.rhubarb.validation.findValidationPlaceHolder = function (container, name) {
    var children = container.children;

    for (var i = 0; i < children.length; i++) {
        var node = children[i];

        if (node.getAttribute("name") == ( "ValidationPlaceHolder-" + name )) {
            return node;
        }

        var childResult = window.rhubarb.validation.findValidationPlaceHolder(node, name);

        if (childResult != false) {
            return childResult;
        }
    }

    return false;
};

window.rhubarb.validation.ValidationError = function (name, error) {
    this.name = name;
    this.error = error;
    this.subErrors = [];

    this.applyToPlaceholders = function (validationHostContainer) {
        var placeHolder = window.rhubarb.validation.findValidationPlaceHolder(validationHostContainer, this.name);

        if (placeHolder != false) {
            placeHolder.innerHTML = this.error;
            placeHolder.className = 'validation-placeholder validation-error';
            if (!window.rhubarb.validation.Scrolled) {
                placeHolder.scrollIntoView();
                window.rhubarb.validation.Scrolled = true;
            }
        }

        for (var i in this.subErrors) {
            this.subErrors[i].applyToPlaceholders(validationHostContainer);
        }
    }
};

window.rhubarb.validation.BaseValidation = function (name, settings) {
    this.name = name;
    this.settings = settings;
    this.failedMessage = "";

    this.validate = function (value) {

    };
};

window.rhubarb.validation.Validator = function (name) {
    window.rhubarb.validation.BaseValidation.apply(this, arguments);

    this.validations = [];

    /**
     * Set to true to validate that all validations are correct. Set to false to validate that at least one
     * validation is correct.
     *
     * @type {boolean}
     */
    this.validateAll = true;

    this.validate = function (model) {
        var error = new window.rhubarb.validation.ValidationError(this.name, "The following errors occurred:");

        var oneValid = false;
        var allValid = true;

        for (var v in this.validations) {
            var validation = this.validations[v];

            try {
                validation.validate(model[validation.name], model);

                oneValid = true;
            }
            catch (errorException) {
                error.subErrors[error.subErrors.length] = errorException;
                allValid = false;
            }
        }

        if (!allValid && this.validateAll) {
            throw error;
        }

        if (!oneValid && !this.validateAll) {
            throw error;
        }

        return true;
    }
};

window.rhubarb.validation.Validator.prototype = new window.rhubarb.validation.BaseValidation();
window.rhubarb.validation.Validator.prototype.constructor = window.rhubarb.validation.Validator;

window.rhubarb.validation.Validator.fromJson = function (json) {
    var validator = new window.rhubarb.validation.Validator();

    validator.name = json.name;
    validator.validateAll = json.settings.validateAll;

    for (var i in json.settings.validations) {
        var validationJson = json.settings.validations[i];
        var type = validationJson.type;
        var name = validationJson.name;
        var failedMessage = validationJson.failedMessage;
        var settings = validationJson.settings;

        var validationObject;

        if (type == "validator") {
            validationObject = window.rhubarb.validation.Validator.fromJson(settings);
            validationObject.name = name;
        } else {
            validationObject = new window.rhubarb.validation[type](name, settings);
        }

        validationObject.failedMessage = failedMessage;
        validator.validations[validator.validations.length] = validationObject;
    }

    return validator;
};

window.rhubarb.validation.EqualTo = function (name, settings) {
    window.rhubarb.validation.BaseValidation.apply(this, arguments);

    this.equalTo = settings.equalTo;

    this.validate = function (value) {
        if (value != this.equalTo) {
            throw new window.rhubarb.validation.ValidationError(this.name, this.failedMessage)
        }

        return true;
    }
};

window.rhubarb.validation.EqualTo.prototype = new window.rhubarb.validation.BaseValidation();
window.rhubarb.validation.EqualTo.prototype.constructor = window.rhubarb.validation.EqualTo;

window.rhubarb.validation.EqualToModelProperty = function (name, settings) {
    window.rhubarb.validation.BaseValidation.apply(this, arguments);

    this.propertyName = settings.propertyName;

    this.validate = function (value, model) {
        if (value != model[this.propertyName]) {
            throw new window.rhubarb.validation.ValidationError(this.name, this.failedMessage)
        }

        return true;
    }
};

window.rhubarb.validation.EqualToModelProperty.prototype = new window.rhubarb.validation.BaseValidation();
window.rhubarb.validation.EqualToModelProperty.prototype.constructor = window.rhubarb.validation.EqualToModelProperty;

window.rhubarb.validation.ExactLength = function (name, settings) {
    window.rhubarb.validation.BaseValidation.apply(this, arguments);

    this.exactLength = settings.exactLength;

    this.validate = function (value) {
        if (value.length != this.exactLength) {
            throw new window.rhubarb.validation.ValidationError(this.name, this.failedMessage)
        }

        return true;
    }
};

window.rhubarb.validation.ExactLength.prototype = new window.rhubarb.validation.BaseValidation();
window.rhubarb.validation.ExactLength.prototype.constructor = window.rhubarb.validation.ExactLength;

window.rhubarb.validation.HasValue = function (name, settings) {
    window.rhubarb.validation.BaseValidation.apply(this, arguments);

    this.validate = function (value) {
        if (value === null || value == "" || value == 0) {
            throw new window.rhubarb.validation.ValidationError(this.name, this.failedMessage)
        }

        return true;
    }
};

window.rhubarb.validation.EqualTo.prototype = new window.rhubarb.validation.BaseValidation();
window.rhubarb.validation.EqualTo.prototype.constructor = window.rhubarb.validation.EqualTo;

window.rhubarb.validation.MatchesRegEx = function (name, settings) {
    window.rhubarb.validation.BaseValidation.apply(this, arguments);

    this.regEx = new RegExp(settings.regEx);

    this.validate = function (value) {
        if (value.match(this.regEx)) {
            return true;
        } else {
            throw new window.rhubarb.validation.ValidationError(this.name, this.failedMessage)
        }
    }
};

window.rhubarb.validation.MatchesRegEx.prototype = new window.rhubarb.validation.BaseValidation();
window.rhubarb.validation.MatchesRegEx.prototype.constructor = window.rhubarb.validation.MatchesRegEx;

window.rhubarb.validation.GreaterThan = function (name, settings) {
    window.rhubarb.validation.BaseValidation.apply(this, arguments);

    this.validate = function (value) {
        if ((settings.equalTo && value >= settings.greaterThan) || value > settings.greaterThan) {
            return true;
        } else {
            throw new window.rhubarb.validation.ValidationError(this.name, this.failedMessage)
        }
    }
};

window.rhubarb.validation.GreaterThan.prototype = new window.rhubarb.validation.BaseValidation();
window.rhubarb.validation.GreaterThan.prototype.constructor = window.rhubarb.validation.GreaterThan;

window.rhubarb.validation.LessThan = function (name, settings) {
    window.rhubarb.validation.BaseValidation.apply(this, arguments);

    this.validate = function (value) {
        if ((settings.equalTo && value <= settings.lessThan) || value < settings.lessThan) {
            return true;
        } else {
            throw new window.rhubarb.validation.ValidationError(this.name, this.failedMessage)
        }
    }
};

window.rhubarb.validation.LessThan.prototype = new window.rhubarb.validation.BaseValidation();
window.rhubarb.validation.LessThan.prototype.constructor = window.rhubarb.validation.LessThan;